<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$order_code = $_GET['order'] ?? '';
$st = $pdo->prepare("SELECT * FROM orders WHERE order_code=?");
$st->execute([$order_code]);
$order = $st->fetch();

if (!$order) {
    die("ไม่พบคำสั่งซื้อ");
}

// คำนวณยอดรวมจาก order_items
$st = $pdo->prepare("SELECT SUM(qty*unit_price) FROM order_items WHERE order_id=?");
$st->execute([$order['id']]);
$total = (float)$st->fetchColumn();
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>
<div class="container mt-4">
  <h2>ชำระเงินสำหรับคำสั่งซื้อ #<?=htmlspecialchars($order_code)?></h2>
  <p>ยอดรวม: <b><?=money($total)?> บาท</b></p>

  <form method="post" action="upload_slip.php" enctype="multipart/form-data" class="card card-body shadow-sm">
    <input type="hidden" name="csrf" value="<?=csrf_token()?>">
    <input type="hidden" name="order_id" value="<?=$order['id']?>">

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
        <img src="1.PNG" alt="PromptPay QR" style="max-width:220px;">
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

    <button type="submit" class="btn btn-primary">ยืนยันการชำระเงิน</button>
  </form>
</div>

<script>
function togglePaymentInfo(val) {
    document.getElementById('boxPromptPay').style.display = (val === 'promptpay') ? 'block' : 'none';
    document.getElementById('boxTrueMoney').style.display = (val === 'truemoney') ? 'block' : 'none';
}
</script>
<?php require_once __DIR__ . '/partials/footer.php'; ?>
