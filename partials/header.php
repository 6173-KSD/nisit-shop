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
<title>MSU Ni-sit Shop</title> <!-- เปลี่ยนชื่อ Title ให้มี MSU -->

<!-- Bootstrap 5.3.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;500;700&display=swap" rel="stylesheet">

<!-- Font Awesome สำหรับไอคอนเพิ่มเติม -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Custom CSS -->
<link href="<?= base_url('assets/styles.css') ?>" rel="stylesheet">

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="font-family: 'Prompt', sans-serif;">
<!-- 
    Navbar:
    - bg-secondary: ใช้สีเทาเข้มของ Bootstrap เพื่อสื่อถึงสีเทาของ มมส.
    - navbar-dark: ใช้ข้อความสีขาวเพื่อให้ตัดกับพื้นหลังสีเทาเข้ม 
-->
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary shadow-sm sticky-top">
    <div class="container">
        <!-- Brand/Logo: ใช้สีเหลืองทอง (#FFD700) เพื่อเป็นสีเน้นหลัก -->
        <a class="navbar-brand fw-bold fs-4" href="<?= base_url('index.php') ?>" style="color: #FFD700;">
            <i class="fa-solid fa-graduation-cap me-2"></i> MSU Ni-sit Shop
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample">
            <!-- เมนูหลัก (ข้อความสีขาวตาม default ของ navbar-dark) -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('products.php') ?>">สินค้าทั้งหมด</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('track.php') ?>">ติดตามคำสั่งซื้อ</a></li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <!-- 🛒 ปุ่มตะกร้า: ใช้สีเทาอ่อน (light) -->
                <a class="btn btn-light position-relative" href="<?= base_url('cart.php') ?>">
                    ตะกร้า
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= cart_count() ?>
                    </span>
                </a>

                <?php if (!empty($_SESSION['admin'])): ?>
                    <!-- ปุ่ม Admin: ใช้สีแดง (danger) เพื่อเน้นความสำคัญ -->
                    <a class="btn btn-danger" href="<?= base_url('admin/index.php') ?>">แผงควบคุม</a>
                    <a class="btn btn-outline-light" href="<?= base_url('admin/logout.php') ?>">ออกจากระบบ</a>
                <?php elseif (!empty($_SESSION['user'])): ?>
                    <span class="text-white">👋 <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                    <a class="btn btn-outline-light" href="<?= base_url('logout.php') ?>">ออกจากระบบ</a>
                <?php else: ?>
                    <!-- ปุ่มเข้าสู่ระบบ: ใช้สีเทาอ่อน (outline-light) -->
                    <a class="btn btn-outline-light" href="<?= base_url('login.php') ?>">เข้าสู่ระบบ</a>
                    <!-- ปุ่มสมัครสมาชิก: ใช้สีเหลืองทอง (warning) เพื่อเน้น -->
                    <a class="btn btn-warning fw-bold" href="<?= base_url('register.php') ?>">สมัครสมาชิก</a>
                    <!-- ปุ่ม Admin Login: ใช้สีเทาอ่อน (outline-light) -->
                    <a class="btn btn-outline-light" href="<?= base_url('admin/login.php') ?>">เข้าสู่ระบบ Admin</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<main class="py-4">
<div class="container">
