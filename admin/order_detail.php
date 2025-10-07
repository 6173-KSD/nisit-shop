<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../db.php';
$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id=?");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
  echo "<p class='text-danger'>ไม่พบข้อมูลออเดอร์นี้</p>";
  exit;
}

$items = $pdo->prepare("
  SELECT p.name, oi.qty, oi.unit_price
  FROM order_items oi
  JOIN products p ON p.id = oi.product_id
  WHERE oi.order_id=?
");
$items->execute([$id]);
?>

<div class="mb-3">
  <h5 class="fw-bold text-dark">รหัสออเดอร์: <?= htmlspecialchars($order['order_code']) ?></h5>
  <p class="mb-1">ลูกค้า: <?= htmlspecialchars($order['user_name']) ?></p>
  <p class="mb-1">วันที่สั่ง: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
  <p class="mb-2">สถานะ:
    <span class="badge bg-secondary"><?= htmlspecialchars($order['status']) ?></span>
  </p>
</div>

<table class="table table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>ชื่อสินค้า</th>
      <th class="text-center">จำนวน</th>
      <th class="text-end">ราคาต่อหน่วย (฿)</th>
      <th class="text-end">ราคารวม (฿)</th>
    </tr>
  </thead>
  <tbody>
    <?php $total = 0; foreach ($items as $it): $sum = $it['qty'] * $it['unit_price']; $total += $sum; ?>
      <tr>
        <td><?= htmlspecialchars($it['name']) ?></td>
        <td class="text-center"><?= $it['qty'] ?></td>
        <td class="text-end"><?= number_format($it['unit_price'], 2) ?></td>
        <td class="text-end"><?= number_format($sum, 2) ?></td>
      </tr>
    <?php endforeach; ?>
    <tr class="fw-bold">
      <td colspan="3" class="text-end">ยอดรวมทั้งหมด</td>
      <td class="text-end text-success"><?= number_format($total, 2) ?></td>
    </tr>
  </tbody>
</table>

<?php if (!empty($order['note'])): ?>
  <p class="mt-3"><strong>หมายเหตุ:</strong> <?= htmlspecialchars($order['note']) ?></p>
<?php endif; ?>
