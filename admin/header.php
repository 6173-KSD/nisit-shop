<?php require_once __DIR__ . '/../functions.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Panel - Ni-sit Shop</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url('assets/styles.css') ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= base_url('admin/index.php') ?>">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/products.php') ?>">จัดการสินค้า</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/orders.php') ?>">จัดการออเดอร์</a></li>
      </ul>

      <div class="d-flex align-items-center gap-2">
        <a class="btn btn-outline-light" href="<?= base_url('admin/logout.php') ?>">ออกจากระบบ</a>
      </div>
    </div>
  </div>
</nav>
<main class="py-4">
<div class="container">
