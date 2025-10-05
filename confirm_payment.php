<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !check_csrf($_POST['csrf'] ?? '')) die('Bad request');

$user = $_SESSION['pending_user'] ?? null;
$cart = $_SESSION['pending_cart'] ?? [];
if (!$user || empty($cart)) die('ไม่พบข้อมูลการสั่งซื้อ');

$method = $_POST['method'] ?? '';
if ($method === '') die('กรุณาเลือกวิธีชำระเงิน');

// ✅ เก็บสลิป (ถ้ามี)
$slip = null;
if (!empty($_FILES['slip']['name'])) {
  $upload_dir = __DIR__ . '/uploads/';
  if (!is_dir($upload_dir)) mkdir($upload_dir);
  $fname = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $_FILES['slip']['name']);
  if (move_uploaded_file($_FILES['slip']['tmp_name'], $upload_dir . $fname)) {
    $slip = $fname;
  }
}

$pdo->beginTransaction();
try {
  // ✅ ตรวจสต๊อกก่อน
  $ids = array_column($cart, 'id');
  $in  = implode(',', array_fill(0, count($ids), '?'));

  $stmt = $pdo->prepare("SELECT id, stock FROM products WHERE id IN ($in) FOR UPDATE");
  $stmt->execute($ids);
  $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $map = [];
  foreach ($stocks as $s) $map[$s['id']] = (int)$s['stock'];

  foreach ($cart as $it) {
    $pid = (int)$it['id'];
    if (($map[$pid] ?? 0) < (int)$it['qty']) {
      $pdo->rollBack();
      die('สต๊อกไม่พอสำหรับบางรายการ');
    }
  }

  // ✅ บันทึกคำสั่งซื้อ (ตอนนี้ค่อย INSERT)
  $code = order_code();
$ins = $pdo->prepare("
  INSERT INTO orders(order_code, user_name, user_phone, user_email, pickup_option, pickup_date, payment_method, status)
  VALUES(?,?,?,?,?,?,?, 'pending')
");
$ins->execute([
  $code,
  $user['name'],
  $user['phone'],
  $user['email'],
  $user['pickup_option'],
  $user['pickup_date'],
  $method
]);

  $order_id = (int)$pdo->lastInsertId();

  // ✅ รายการสินค้า + ตัดสต๊อก + movement
  $oi  = $pdo->prepare("INSERT INTO order_items(order_id, product_id, size, qty, unit_price) VALUES(?,?,?,?,?)");
  $dec = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
  $mov = $pdo->prepare("INSERT INTO stock_movements(product_id, type, qty, note) VALUES(?, 'order', ?, CONCAT('order ', ?))");

  foreach ($cart as $it) {
  $pid = (int)$it['id'];
  $qty = (int)$it['qty'];
  $price = (float)$it['price'];

  $oi->execute([$order_id, $pid, $it['size'] ?? 'N/A', $qty, $price]); // ✅ เพิ่ม size
  $dec->execute([$qty, $pid]);
  $mov->execute([$pid, $qty, $code]);
}


  $pdo->commit();

  // ✅ เคลียร์ Session
  unset($_SESSION['cart'], $_SESSION['pending_cart'], $_SESSION['pending_user']);

  // ✅ แสดงผลลัพธ์สวย ๆ
  require_once __DIR__ . '/partials/header.php';
  echo '<div class="container my-5 text-center">';
  echo '  <h2 class="text-success">✅ สั่งซื้อสำเร็จ!</h2>';
  echo '  <p>รหัสติดตามคำสั่งซื้อของคุณคือ</p>';
  echo '  <div style="font-size:36px;font-weight:700;letter-spacing:2px;">' . htmlspecialchars($code) . '</div>';
  echo '  <a href="index.php" class="btn btn-primary mt-3">เลือกซื้อสินค้าต่อ</a>';
  echo '</div>';
  require_once __DIR__ . '/partials/footer.php';

} catch (Exception $e) {
  $pdo->rollBack();
  die('Error: ' . $e->getMessage());
}
?>
