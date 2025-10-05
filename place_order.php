<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
session_start();

// ✅ ตรวจสอบคำร้อง
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !check_csrf($_POST['csrf'] ?? '')) {
    die('Bad request');
}

// ✅ ตรวจสอบตะกร้า
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    die('Cart empty');
}

// ✅ รับข้อมูลผู้ใช้จากฟอร์ม
$user_name     = trim($_POST['user_name'] ?? '');
$user_phone    = trim($_POST['user_phone'] ?? '');
$user_email    = trim($_POST['user_email'] ?? '');
$pickup_option = $_POST['pickup_option'] ?? 'pickup';
$pickup_date   = $_POST['pickup_date'] ?? null;

if ($user_name === '' || $user_phone === '' || empty($pickup_date)) {
    die('ข้อมูลไม่ครบ');
}

// ✅ เก็บข้อมูลไว้ใน Session แทน (ยังไม่ insert DB)
$_SESSION['pending_user'] = [
    'name'          => $user_name,
    'phone'         => $user_phone,
    'email'         => $user_email,
    'pickup_option' => $pickup_option,
    'pickup_date'   => $pickup_date
];

$_SESSION['pending_cart'] = $cart;

// ✅ ไปหน้าชำระเงิน (ยังไม่บันทึก DB)
header('Location: payment.php');
exit;
?>
