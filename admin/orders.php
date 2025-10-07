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
    <h2 class="fw-bold">จัดการออเดอร์</h2>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body table-responsive">
      <table id="ordersTable" class="table table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>รหัสออเดอร์</th>
            <th>ลูกค้า</th>
            <th>ยอดรวม (฿)</th>
            <th>สถานะ</th>
            <th>วันที่สั่ง</th>
            <th>ดูรายละเอียด</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
          foreach ($stmt as $i => $o): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($o['order_code']) ?></td>
              <td><?= htmlspecialchars($o['user_name']) ?></td>
              <td><?= number_format($o['total_price'], 2) ?></td>
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
                    echo $map[$o['status']] ?? 'bg-light text-dark';
                  ?>">
                  <?= htmlspecialchars($o['status']) ?>
                </span>
              </td>
              <td><?= date('d/m/Y', strtotime($o['created_at'])) ?></td>
              <td>
                <button class="btn btn-sm btn-outline-dark" onclick="viewDetail(<?= $o['id'] ?>)">
                  <i class="fa-solid fa-eye"></i> ดู
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal แสดงรายละเอียด -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title fw-bold">รายละเอียดออเดอร์</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="orderDetailContent" class="p-2">กำลังโหลด...</div>
      </div>
    </div>
  </div>
</div>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function() {
  $('#ordersTable').DataTable({
    pageLength: 10,
    order: [[5, 'desc']],
    language: {
      search: "ค้นหา:",
      lengthMenu: "แสดง _MENU_ รายการต่อหน้า",
      info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
      paginate: { previous: "ก่อนหน้า", next: "ถัดไป" }
    }
  });
});

function viewDetail(id) {
  $('#detailModal').modal('show');
  $('#orderDetailContent').html('<div class="text-center p-4">⏳ กำลังโหลด...</div>');
  fetch('order_detail.php?id=' + id)
    .then(res => res.text())
    .then(html => $('#orderDetailContent').html(html));
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
