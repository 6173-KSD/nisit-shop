<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
session_start();

// ✅ ตรวจสอบข้อมูลชั่วคราว
$user = $_SESSION['pending_user'] ?? null;
$cart = $_SESSION['pending_cart'] ?? [];
if (!$user || empty($cart)) {
    die("ไม่พบข้อมูลการสั่งซื้อ");
}

// ✅ คำนวณยอดรวม
$total = 0;
foreach ($cart as $it) {
    $total += $it['price'] * $it['qty'];
}

require_once __DIR__ . '/partials/header.php';
?>
<div class="container mt-4">
  <h2 class="fw-bold">ชำระเงิน</h2>
  <p>ยอดรวมทั้งหมด: <b><?= money($total) ?> บาท</b></p>

  <form method="post" action="confirm_payment.php" enctype="multipart/form-data" class="card card-body shadow-sm mt-3">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

    <div class="mb-3">
      <label class="form-label">วิธีชำระเงิน</label>
      <select id="payMethod" name="method" class="form-select" required onchange="togglePaymentInfo(this.value)">
        <option value="">-- เลือกวิธีชำระ --</option>
        <option value="promptpay">PromptPay QR</option>
        <option value="truemoney">TrueMoney Wallet</option>
        <option value="cash">เงินสด (จ่ายที่ร้าน)</option>
      </select>
    </div>

    <!-- PromptPay -->
    <div id="boxPromptPay" class="mb-3" style="display:none;">
      <p>สแกน QR เพื่อโอน</p>
      <img src="QRCode.png" alt="PromptPay QR" style="max-width:220px;">
    </div>

    <!-- TrueMoney -->
    <div id="boxTrueMoney" class="mb-3" style="display:none;">
      <p>โอนผ่าน TrueMoney Wallet</p>
      <p class="text-muted">หมายเลข Wallet: <b>08x-xxx-xxxx</b></p>
    </div>

    <div class="mb-3">
      <label class="form-label">อัปโหลดสลิป (ถ้ามี)</label>
      <input type="file" name="slip" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary w-100">ยืนยันการชำระเงิน</button>
  </form>
</div>

<script>
function togglePaymentInfo(val) {
    document.getElementById('boxPromptPay').style.display = (val === 'promptpay') ? 'block' : 'none';
    document.getElementById('boxTrueMoney').style.display = (val === 'truemoney') ? 'block' : 'none';
}
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
