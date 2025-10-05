<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
session_start();

// ✅ ตรวจสอบคำร้อง (Request ต้องเป็น POST เท่านั้น)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !check_csrf($_POST['csrf'] ?? '')) {
    die('Bad request');
}

// ✅ ตรวจสอบว่ามีสินค้าในตะกร้าไหม
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    die('Cart empty');
}

// ✅ รับข้อมูลจากฟอร์ม checkout.php
$user_name     = trim($_POST['user_name'] ?? '');
$user_phone    = trim($_POST['user_phone'] ?? '');
$user_email    = trim($_POST['user_email'] ?? '');
$pickup_option = $_POST['pickup_option'] ?? 'pickup';
$pickup_date   = $_POST['pickup_date'] ?? null;

if ($user_name === '' || $user_phone === '' || empty($pickup_date)) {
    die('ข้อมูลไม่ครบ');
}

try {
    // เริ่มต้น Transaction
    $pdo->beginTransaction();

    // ✅ 1. เพิ่มข้อมูลคำสั่งซื้อในตาราง orders
    $order_code = strtoupper(bin2hex(random_bytes(4))); // เช่น 9F3C7A2B

    $stmt = $pdo->prepare("
        INSERT INTO orders 
        (order_code, user_name, user_phone, user_email, pickup_option, pickup_date, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");
    // 👇 ต้องใส่ 6 ตัว ตามเครื่องหมาย ? ทั้งหมด
    $stmt->execute([
        $order_code,
        $user_name,
        $user_phone,
        $user_email,
        $pickup_option,
        $pickup_date
    ]);

    // ✅ เอา id ของ order ล่าสุดไปใช้กับ order_items
    $order_id = $pdo->lastInsertId();

    // ✅ 2. เพิ่มสินค้าแต่ละชิ้นลงใน order_items (พร้อม size)
    $stmtItem = $pdo->prepare("
        INSERT INTO order_items (order_id, product_id, size, qty, unit_price)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($cart as $it) {
        $stmtItem->execute([
            $order_id,
            $it['id'],
            $it['size'] ?? 'N/A', // ✅ ดึงจาก session cart
            $it['qty'],
            $it['price']
        ]);

        // ✅ 3. อัปเดต stock สินค้า
        $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?")
            ->execute([$it['qty'], $it['id']]);
    }

    // ✅ ยืนยันการบันทึกข้อมูลทั้งหมด
    $pdo->commit();

    // ✅ ล้าง session ตะกร้าสินค้า
    unset($_SESSION['cart']);

    // ✅ แจ้งเตือนสำเร็จ + กลับไปหน้าแรก
    echo "<script>
        alert('✅ สั่งซื้อสำเร็จแล้ว!\\nรหัสคำสั่งซื้อ: {$order_code}');
        window.location.href = 'index.php';
    </script>";

} catch (Exception $e) {
    $pdo->rollBack();
    die('Error: ' . $e->getMessage());
}
?>
