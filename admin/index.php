<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../functions.php';
require_admin();
require_once __DIR__ . '/header.php';

// นับจำนวนสินค้า
$pc = $pdo->query('SELECT COUNT(*) c FROM products')->fetch()['c'];

// นับจำนวนออเดอร์
$oc = $pdo->query('SELECT COUNT(*) c FROM orders')->fetch()['c'];
?>

<h2>แผงควบคุมผู้ดูแลระบบ</h2>

<div class="row g-3">
  <div class="col-md-6">
    <div class="card card-body shadow-sm">
      <div class="text-muted">จำนวนสินค้า</div>
      <div class="display-6 fw-bold"><?= (int)$pc ?></div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card card-body shadow-sm">
      <div class="text-muted">จำนวนออเดอร์</div>
      <div class="display-6 fw-bold"><?= (int)$oc ?></div>
    </div>
  </div>
</div>

<div class="mt-4 d-flex gap-2">
  <a class="btn btn-primary" href="<?= base_url('admin/products.php') ?>">จัดการสินค้า</a>
  <a class="btn btn-outline-primary" href="<?= base_url('admin/orders.php') ?>">จัดการออเดอร์</a>
  <a class="btn btn-outline-danger ms-auto" href="<?= base_url('admin/logout.php') ?>">ออกจากระบบ</a>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
