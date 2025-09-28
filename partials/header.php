<?php
require_once __DIR__ . '/../functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ni-sit Shop</title>

<!-- Bootstrap 5.3.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;500;700&display=swap" rel="stylesheet">

<!-- Custom CSS -->
<link href="<?= base_url('assets/styles.css') ?>" rel="stylesheet">

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="font-family: 'Prompt', sans-serif;">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= base_url('index.php') ?>">Ni-sit Shop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExample">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="<?= base_url('products.php') ?>">สินค้าทั้งหมด</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('track.php') ?>">ติดตามคำสั่งซื้อ</a></li>
      </ul>

      <div class="d-flex align-items-center gap-2">
        <!-- 🛒 ปุ่มตะกร้า -->
        <a class="btn btn-light position-relative" href="<?= base_url('cart.php') ?>">
          ตะกร้า
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?= cart_count() ?>
          </span>
        </a>

        <?php if (!empty($_SESSION['admin'])): ?>
          <a class="btn btn-danger" href="<?= base_url('admin/index.php') ?>">แผงควบคุม</a>
          <a class="btn btn-outline-light" href="<?= base_url('admin/logout.php') ?>">ออกจากระบบ</a>
        <?php elseif (!empty($_SESSION['user'])): ?>
          <span class="text-white">👋 <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
          <a class="btn btn-outline-light" href="<?= base_url('logout.php') ?>">ออกจากระบบ</a>
        <?php else: ?>
          <a class="btn btn-outline-light" href="<?= base_url('login.php') ?>">เข้าสู่ระบบ</a>
          <a class="btn btn-warning" href="<?= base_url('register.php') ?>">สมัครสมาชิก</a>
          <a class="btn btn-danger" href="<?= base_url('admin/login.php') ?>">เข้าสู่ระบบ Admin</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<main class="py-4">
<div class="container">
