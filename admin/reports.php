<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../functions.php';
session_start();

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../login/login.php");
  exit;
}

require_once __DIR__ . '/header.php';
?>

<div class="container-fluid my-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">รายงานยอดขายและสถิติ</h2>
    <div class="fw-medium text-secondary">📊 สรุปข้อมูลการขาย Ni-sit Shop</div>
  </div>

  <?php
  // ================= ยอดขายรายเดือน =================
$monthly = $pdo->query("
  SELECT DATE_FORMAT(o.created_at, '%b %Y') AS m,
         SUM(oi.qty * oi.unit_price) AS total
  FROM orders o
  JOIN order_items oi ON o.id = oi.order_id
  WHERE o.status <> 'cancelled'
  GROUP BY YEAR(o.created_at), MONTH(o.created_at)
  ORDER BY YEAR(o.created_at), MONTH(o.created_at)
")->fetchAll(PDO::FETCH_ASSOC);

  $monthLabels = array_column($monthly, 'm');
  $monthValues = array_map(fn($r) => (float)$r['total'], $monthly);

  // ================= สินค้าขายดี =================
  $top = $pdo->query("
    SELECT p.name, SUM(oi.qty) AS sold_qty
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    GROUP BY oi.product_id
    ORDER BY sold_qty DESC
    LIMIT 5
  ")->fetchAll(PDO::FETCH_ASSOC);
  $topNames = array_column($top, 'name');
  $topValues = array_column($top, 'sold_qty');

  // ================= ออเดอร์รายวันเดือนปัจจุบัน =================
  $daily = $pdo->query("
    SELECT DATE(created_at) AS d, COUNT(*) AS c
    FROM orders
    WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at)=YEAR(CURDATE())
    GROUP BY DATE(created_at)
  ")->fetchAll(PDO::FETCH_ASSOC);
  $dailyLabels = array_column($daily, 'd');
  $dailyValues = array_column($daily, 'c');
  ?>

  <div class="row g-3">
    <!-- กราฟยอดขายรายเดือน -->
    <div class="col-lg-6">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-warning fw-bold">
          ยอดขายรายเดือน (บาท)
        </div>
        <div class="card-body">
          <canvas id="monthlyChart" height="140"></canvas>
        </div>
      </div>
    </div>

    <!-- กราฟสินค้าขายดี -->
    <div class="col-lg-6">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-info fw-bold text-white">
          5 อันดับสินค้าขายดีที่สุด
        </div>
        <div class="card-body">
          <canvas id="topChart" height="140"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- กราฟออเดอร์รายวัน -->
  <div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-success fw-bold text-white">
      จำนวนออเดอร์รายวัน (เดือนปัจจุบัน)
    </div>
    <div class="card-body">
      <canvas id="dailyChart" height="120"></canvas>
    </div>
  </div>

  <p class="text-muted small mt-4 text-center">
    © <?= date('Y') ?> MSU Ni-sit Shop • รายงานสถิติและยอดขาย
  </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const monthLabels = <?= json_encode($monthLabels, JSON_UNESCAPED_UNICODE) ?>;
const monthValues = <?= json_encode($monthValues) ?>;
const topNames = <?= json_encode($topNames, JSON_UNESCAPED_UNICODE) ?>;
const topValues = <?= json_encode($topValues) ?>;
const dailyLabels = <?= json_encode($dailyLabels, JSON_UNESCAPED_UNICODE) ?>;
const dailyValues = <?= json_encode($dailyValues) ?>;

// 🟡 กราฟยอดขายรายเดือน
new Chart(document.getElementById('monthlyChart'), {
  type: 'bar',
  data: {
    labels: monthLabels,
    datasets: [{
      label: 'ยอดขาย (บาท)',
      data: monthValues,
      backgroundColor: '#f6c23e'
    }]
  },
  options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

// 🔵 สินค้าขายดี
new Chart(document.getElementById('topChart'), {
  type: 'bar',
  data: {
    labels: topNames,
    datasets: [{
      label: 'จำนวนที่ขาย',
      data: topValues,
      backgroundColor: '#36b9cc'
    }]
  },
  options: { indexAxis: 'y', responsive: true }
});

// 🟢 ออเดอร์รายวัน
new Chart(document.getElementById('dailyChart'), {
  type: 'line',
  data: {
    labels: dailyLabels,
    datasets: [{
      label: 'จำนวนออเดอร์',
      data: dailyValues,
      borderColor: '#1cc88a',
      backgroundColor: 'rgba(28,200,138,0.2)',
      tension: 0.3,
      fill: true
    }]
  },
  options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
