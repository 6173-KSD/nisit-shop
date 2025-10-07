<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../functions.php';

// ✅ ตรวจสอบสิทธิ์ผู้ใช้
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: login.php");
  exit;
}

require_once __DIR__ . '/header.php';

// ================= KPI Summary =================
$totalSales = (float)$pdo->query("
  SELECT COALESCE(SUM(od.qty * od.unit_price), 0)
  FROM orders o
  JOIN order_items od ON o.id = od.order_id
  WHERE o.status <> 'cancelled'
")->fetchColumn();

$totalOrders = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pendingOrders = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status IN ('pending','preparing')")->fetchColumn();
$totalProducts = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

// ================= Monthly Sales Chart =================
$rows = $pdo->query("
  SELECT DATE_FORMAT(o.created_at, '%b %Y') AS month,
         SUM(od.qty * od.unit_price) AS total
  FROM orders o
  JOIN order_items od ON o.id = od.order_id
  WHERE o.status <> 'cancelled'
  GROUP BY YEAR(o.created_at), MONTH(o.created_at)
  ORDER BY YEAR(o.created_at), MONTH(o.created_at)
")->fetchAll(PDO::FETCH_ASSOC);

$months = array_column($rows, 'month');
$sales = array_map(fn($r) => (float)$r['total'], $rows);

// ================= Top 5 Products =================
$top = $pdo->query("
  SELECT p.name, SUM(od.qty) AS sold_qty
  FROM order_items od
  JOIN products p ON p.id = od.product_id
  GROUP BY od.product_id
  ORDER BY sold_qty DESC
  LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$topNames = array_column($top, 'name');
$topValues = array_map(fn($r) => (int)$r['sold_qty'], $top);

// ================= Order Status =================
$status = $pdo->query("SELECT status, COUNT(*) AS c FROM orders GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);
$statusLabels = ['pending','confirmed','preparing','ready_for_pickup','picked_up','cancelled'];
$statusValues = array_map(fn($s) => (int)($status[$s] ?? 0), $statusLabels);

// ================= Latest & Pending Orders =================
$recent = $pdo->query("
  SELECT o.order_code, o.user_name, SUM(od.qty * od.unit_price) AS total_price, o.status, o.created_at
  FROM orders o
  JOIN order_items od ON o.id = od.order_id
  GROUP BY o.id
  ORDER BY o.created_at DESC
  LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$pending = $pdo->query("
  SELECT o.order_code, o.user_name, SUM(od.qty * od.unit_price) AS total_price, o.status, o.created_at
  FROM orders o
  JOIN order_items od ON o.id = od.order_id
  WHERE o.status IN ('pending','preparing')
  GROUP BY o.id
  ORDER BY o.created_at DESC
  LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ✅ HTML เริ่มตรงนี้ -->
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">แดชบอร์ดภาพรวมระบบ</h2>
    <div class="fw-medium text-secondary">👤 Admin: <?= htmlspecialchars($_SESSION['user']['email'] ?? 'อาม') ?></div>
  </div>

  <!-- KPI Cards -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-warning-subtle">
        <div class="card-body">
          <div class="text-muted small">ยอดขายรวมทั้งหมด</div>
          <div class="h4 fw-bold mb-0 text-dark">฿<?= number_format($totalSales, 2) ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-info-subtle">
        <div class="card-body">
          <div class="text-muted small">จำนวนออเดอร์ทั้งหมด</div>
          <div class="h4 fw-bold mb-0 text-dark"><?= $totalOrders ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-danger-subtle">
        <div class="card-body">
          <div class="text-muted small">ออเดอร์ที่ยังไม่จัดส่ง</div>
          <div class="h4 fw-bold mb-0 text-dark"><?= $pendingOrders ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 bg-success-subtle">
        <div class="card-body">
          <div class="text-muted small">จำนวนสินค้า</div>
          <div class="h4 fw-bold mb-0 text-dark"><?= $totalProducts ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts -->
  <div class="row g-3 mb-4">
    <div class="col-lg-7">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">ยอดขายรายเดือน</div>
        <div class="card-body"><canvas id="salesChart"></canvas></div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white">5 อันดับสินค้าขายดี</div>
        <div class="card-body"><canvas id="topProductsChart"></canvas></div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-5">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white">สถานะออเดอร์ทั้งหมด</div>
        <div class="card-body"><canvas id="orderStatusChart"></canvas></div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-header bg-warning text-dark fw-bold">ออเดอร์ค้างส่ง</div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr><th>รหัสออเดอร์</th><th>ลูกค้า</th><th>ยอดรวม (฿)</th><th>สถานะ</th></tr>
            </thead>
            <tbody>
              <?php foreach($pending as $p): ?>
                <tr>
                  <td><?= htmlspecialchars($p['order_code']) ?></td>
                  <td><?= htmlspecialchars($p['user_name']) ?></td>
                  <td><?= number_format($p['total_price'],2) ?></td>
                  <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($p['status']) ?></span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card shadow-sm border-0">
        <div class="card-header bg-light fw-bold">ออเดอร์ล่าสุด</div>
        <div class="card-body p-0">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr><th>รหัสออเดอร์</th><th>ลูกค้า</th><th>ยอดรวม (฿)</th><th>สถานะ</th></tr>
            </thead>
            <tbody>
              <?php foreach($recent as $r): ?>
                <tr>
                  <td><?= htmlspecialchars($r['order_code']) ?></td>
                  <td><?= htmlspecialchars($r['user_name']) ?></td>
                  <td><?= number_format($r['total_price'],2) ?></td>
                  <td>
                    <span class="badge
                      <?php
                        $map = [
                          'pending'=>'bg-warning text-dark',
                          'confirmed'=>'bg-primary',
                          'preparing'=>'bg-info text-dark',
                          'ready_for_pickup'=>'bg-secondary',
                          'picked_up'=>'bg-success',
                          'cancelled'=>'bg-danger'
                        ];
                        echo $map[$r['status']] ?? 'bg-light text-dark';
                      ?>">
                      <?= htmlspecialchars($r['status']) ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <p class="text-muted small mt-4">Ni-sit Shop Admin Dashboard • PHP + Chart.js + Bootstrap 5</p>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const months = <?= json_encode($months, JSON_UNESCAPED_UNICODE) ?>;
const sales = <?= json_encode($sales) ?>;
const topNames = <?= json_encode($topNames, JSON_UNESCAPED_UNICODE) ?>;
const topValues = <?= json_encode($topValues) ?>;
const statusLabels = <?= json_encode($statusLabels, JSON_UNESCAPED_UNICODE) ?>;
const statusValues = <?= json_encode($statusValues) ?>;

// กราฟยอดขายรายเดือน
new Chart(document.getElementById('salesChart'), {
  type: 'bar',
  data: { labels: months, datasets: [{ label: 'ยอดขาย (บาท)', data: sales, backgroundColor: '#4e73df' }] },
  options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

// กราฟสินค้าขายดี
new Chart(document.getElementById('topProductsChart'), {
  type: 'bar',
  data: { labels: topNames, datasets: [{ label: 'จำนวนที่ขาย', data: topValues, backgroundColor: '#36b9cc' }] },
  options: { indexAxis: 'y', responsive: true, scales: { x: { beginAtZero: true } } }
});

// กราฟสถานะออเดอร์
new Chart(document.getElementById('orderStatusChart'), {
  type: 'doughnut',
  data: {
    labels: statusLabels,
    datasets: [{
      data: statusValues,
      backgroundColor: ['#f6c23e','#4e73df','#36b9cc','#858796','#1cc88a','#e74a3b']
    }]
  },
  options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
