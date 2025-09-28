<?php
require_once __DIR__ . '/db.php'; require_once __DIR__ . '/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !check_csrf($_POST['csrf'] ?? '')) die('Bad request');
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) die('Cart empty');


$user_name = trim($_POST['user_name'] ?? '');
$user_phone = trim($_POST['user_phone'] ?? '');
$user_email = trim($_POST['user_email'] ?? '');
$pickup_option = $_POST['pickup_option'] ?? 'pickup';
$pickup_date = $_POST['pickup_date'] ?? null;
if ($user_name==='' || $user_phone==='' || empty($pickup_date)) die('ข้อมูลไม่ครบ');


// ตรวจสอบสต๊อกก่อน
$ids = array_map('intval', array_keys($cart));
if (empty($ids)) die('Cart error');
$in = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT id, stock FROM products WHERE id IN ($in) FOR UPDATE");
$pdo->beginTransaction();
$stmt->execute($ids);
$stocks = $stmt->fetchAll();
$map = [];
foreach ($stocks as $s) $map[$s['id']] = (int)$s['stock'];
foreach ($cart as $pid => $it) {
if (($map[$pid] ?? 0) < (int)$it['qty']) {
$pdo->rollBack();
die('สต๊อกไม่พอสำหรับบางรายการ');
}
}


// บันทึกออเดอร์
$code = order_code();
$stmt = $pdo->prepare("INSERT INTO orders(order_code, user_name, user_phone, user_email, pickup_option, pickup_date, status) VALUES(?,?,?,?,?,?, 'pending')");
$stmt->execute([$code, $user_name, $user_phone, $user_email, $pickup_option, $pickup_date]);
$order_id = (int)$pdo->lastInsertId();


// บันทึกสินค้าและตัดสต๊อก
$oi = $pdo->prepare("INSERT INTO order_items(order_id, product_id, qty, unit_price) VALUES(?,?,?,?)");
$dec = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
$mov = $pdo->prepare("INSERT INTO stock_movements(product_id, type, qty, note) VALUES(?, 'order', ?, CONCAT('order ', ?))");
foreach ($cart as $pid => $it) {
$oi->execute([$order_id, $pid, (int)$it['qty'], (float)$it['price']]);
$dec->execute([(int)$it['qty'], $pid]);
$mov->execute([$pid, (int)$it['qty'], $code]);
}
$pdo->commit();


// เคลียร์ตะกร้า
unset($_SESSION['cart']);


// แสดงผลลัพธ์
require_once __DIR__ . '/partials/header.php';
?>
<div class="text-center py-5">
<h2 class="text-success">สั่งซื้อสำเร็จ!</h2>
<p class="lead">รหัสติดตามคำสั่งซื้อของคุณคือ</p>
<div class="display-6 fw-bold"><?= htmlspecialchars($code) ?></div>
<p class="mt-3">สามารถติดตามสถานะได้ที่หน้า <a href="<?= base_url('track.php') ?>">ติดตามคำสั่งซื้อ</a></p>
<a class="btn btn-primary mt-3" href="<?= base_url('products.php') ?>">เลือกซื้อสินค้าต่อ</a>
</div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>