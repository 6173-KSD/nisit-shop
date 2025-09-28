<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

// ✅ ถ้าไม่ได้ล็อกอิน บังคับให้ไป login ก่อน
if (empty($_SESSION['user'])) {
    header('Location: ' . base_url('login.php?redirect=checkout.php'));
    exit;
}

require_once __DIR__ . '/partials/header.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) { 
    echo '<div class="alert alert-warning">ตะกร้าว่าง</div>'; 
    require_once __DIR__ . '/partials/footer.php'; 
    exit; 
}

$sum = array_reduce($cart, fn($c,$i)=>$c+($i['price']*$i['qty']), 0);
?>
<h2>ยืนยันการสั่งซื้อ</h2>
<div class="row g-4">
<div class="col-md-7">
<form class="card card-body shadow-sm" method="post" action="place_order.php">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<div class="mb-3">
<label class="form-label">ชื่อ-นามสกุล</label>
<input type="text" name="user_name" class="form-control" 
       value="<?= htmlspecialchars($_SESSION['user']['name']) ?>" required>
</div>
<div class="mb-3">
<label class="form-label">เบอร์โทร</label>
<input type="tel" name="user_phone" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">อีเมล</label>
<input type="email" name="user_email" class="form-control"
       value="<?= htmlspecialchars($_SESSION['user']['email']) ?>">
</div>
<div class="row g-3">
<div class="col-md-6">
<label class="form-label">วิธีรับสินค้า</label>
<select name="pickup_option" class="form-select" required>
<option value="pickup">รับที่ร้าน</option>
<!-- <option value="delivery">จัดส่ง</option> -->
</select>
</div>
<div class="col-md-6">
<label class="form-label">วันรับสินค้า (โดยประมาณ)</label>
<input type="date" name="pickup_date" class="form-control" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
</div>
</div>
<button class="btn btn-primary mt-3" type="submit">ยืนยันสั่งซื้อ</button>
</form>
</div>
<div class="col-md-5">
<div class="card card-body shadow-sm">
<h5 class="mb-3">สรุปรายการ</h5>
<?php foreach ($cart as $it): ?>
<div class="d-flex justify-content-between">
    <span><?= htmlspecialchars($it['name']) ?> × <?= (int)$it['qty'] ?></span>
    <span>฿<?= money($it['price']*$it['qty']) ?></span>
</div>
<?php endforeach; ?>
<hr>
<div class="d-flex justify-content-between fw-bold"><span>ยอดรวม</span><span>฿<?= money($sum) ?></span></div>
</div>
</div>
</div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>
