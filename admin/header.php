<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../functions.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MSU Ni-sit Shop Admin</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;500;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Custom CSS -->
  <style>
    body {
      font-family: 'Prompt', sans-serif;
      background-color: #f8f9fa;
    }
    .sidebar {
      width: 250px;
      background-color: #444c54;
      min-height: 100vh;
      position: fixed;
      top: 0; left: 0;
      color: white;
      padding-top: 1rem;
    }
    .sidebar .brand {
      font-weight: 700;
      font-size: 1.2rem;
      color: #FFD43B;
      padding-left: 1.25rem;
    }
    .sidebar .nav-link {
      color: #ddd;
      padding: 0.75rem 1.25rem;
      border-radius: 0;
      display: block;
    }
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: #FFD43B;
      color: #222;
      font-weight: 600;
    }
    .main-content {
      margin-left: 250px;
      min-height: 100vh;
      background-color: #f9f9f9;
      padding: 1.5rem;
    }
    .navbar-custom {
      background-color: #FFD43B;
      color: #222;
    }
    .navbar-custom .btn-logout {
      background: #444c54;
      color: #fff;
      border: none;
    }
    .navbar-custom .btn-logout:hover {
      background: #2e3338;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column">
    <div class="brand mb-4 ps-2">
      <i class="fa-solid fa-graduation-cap me-2"></i>MSU Ni-sit Shop Admin
    </div>
    <nav class="nav flex-column">
      <a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
        <i class="fa-solid fa-chart-line me-2"></i>Dashboard
      </a>
      <a href="products.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : '' ?>">
        <i class="fa-solid fa-boxes-stacked me-2"></i>จัดการสินค้า
      </a>
      <a href="product_add.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'product_add.php' ? 'active' : '' ?>">
        <i class="fa-solid fa-plus-circle me-2"></i>เพิ่มสินค้า
      </a>
      <a href="orders.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'active' : '' ?>">
        <i class="fa-solid fa-receipt me-2"></i>จัดการออเดอร์
      </a>
      <a href="reports.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'reports.php' ? 'active' : '' ?>">
        <i class="fa-solid fa-chart-pie me-2"></i>รายงาน
      </a>
      <a href="logout.php" class="nav-link mt-auto">
        <i class="fa-solid fa-right-from-bracket me-2"></i>ออกจากระบบ
      </a>
    </nav>
  </div>

  <!-- Top Navbar -->
  <nav class="navbar navbar-custom fixed-top" style="margin-left: 250px;">
    <div class="container-fluid">
      <span class="fw-bold">🟡 แผงควบคุมผู้ดูแลระบบ</span>
      <button class="btn btn-logout btn-sm" onclick="window.location='logout.php'">
        <i class="fa-solid fa-sign-out-alt me-1"></i> ออกจากระบบ
      </button>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="main-content" style="padding-top: 4rem;">
