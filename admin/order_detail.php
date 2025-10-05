<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../functions.php';
require_admin();
require_once __DIR__ . '/header.php';

$order_id = (int)($_GET['id'] ?? 0);
if ($order_id <= 0) die('ไม่พบคำสั่งซื้อ');

// ✅ ดึงข้อมูลออเดอร์
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) die('ไม่พบข้อมูลคำสั่งซื้อ');

// ✅ ดึงรายการสินค้าในออเดอร์นี้
$stmt = $pdo->prepare("
  SELECT oi.*, p.name AS product_name 
  FROM order_items oi
  JOIN products p ON p.id = oi.product_id
  WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>รายละเอียดคำสั่งซื้อ</h2>
  <a href="orders.php" class="btn btn-outline-secondary">← กลับหน้าจัดการออเดอร์</a>
</div>

<div class="card mb-4">
  <div class="card-body">
    <p><strong>รหัสคำสั่งซื้อ:</strong> <?= htmlspecialchars($order['order_code']) ?></p>
    <p><strong>ชื่อลูกค้า:</strong> <?= htmlspecialchars($order['user_name']) ?></p>
    <p><strong>เบอร์โทร:</strong> <?= htmlspecialchars($order['user_phone']) ?></p>
    <p><strong>อีเมล:</strong> <?= htmlspecialchars($order['user_email']) ?></p>
    <p><strong>รับสินค้า:</strong> <?= htmlspecialchars($order['pickup_option']) ?> (<?= htmlspecialchars($order['pickup_date']) ?>)</p>
    <p><strong>วิธีชำระเงิน:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
    <p><strong>สถานะ:</strong> <span class="badge bg-info text-dark"><?= htmlspecialchars($order['status']) ?></span></p>
    <p><strong>วันที่สั่ง:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
  </div>
</div>

<h5>รายการสินค้า</h5>
<table class="table table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>ชื่อสินค้า</th>
      <th>ขนาด</th>
      <th>จำนวน</th>
      <th>ราคาต่อชิ้น</th>
      <th>รวม</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $i = 1; $total = 0;
    foreach ($items as $it):
      $sum = $it['qty'] * $it['unit_price'];
      $total += $sum;
    ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars($it['product_name']) ?></td>
      <td><?= htmlspecialchars($it['size']) ?></td>
      <td><?= (int)$it['qty'] ?></td>
      <td><?= number_format($it['unit_price'], 2) ?> ฿</td>
      <td><?= number_format($sum, 2) ?> ฿</td>
    </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr class="table-light fw-bold">
      <td colspan="5" class="text-end">ยอดรวมทั้งหมด</td>
      <td><?= number_format($total, 2) ?> ฿</td>
    </tr>
  </tfoot>
</table>

<?php require_once __DIR__ . '/footer.php'; ?>
