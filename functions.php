<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// โหลด config ก่อน
require_once __DIR__ . '/config.php';

function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function check_csrf($token) {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}
function money($n) { return number_format((float)$n, 2); }
function order_code() { return strtoupper(bin2hex(random_bytes(4))); }
function cart_count() { return isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0; }

function base_url($path = '') {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function is_admin() { return !empty($_SESSION['admin']); }
function require_admin() {
    if (!is_admin()) {
        header('Location: ' . base_url('admin/login.php'));
        exit;
    }
}
// ✅ ฟังก์ชันคำนวณยอดรวมสินค้าในตะกร้า (ใช้สำหรับ Unit Test และหน้า checkout)
function calculateCartTotal($cart) {
    $total = 0;
    if (!is_array($cart)) return 0;
    foreach ($cart as $item) {
        $price = isset($item['price']) ? (float)$item['price'] : 0;
        $qty   = isset($item['qty']) ? (int)$item['qty'] : 0;
        $total += $price * $qty;
    }
    return $total;
}