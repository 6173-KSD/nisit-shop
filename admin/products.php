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

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">จัดการสินค้า</h2>
    <a href="product_add.php" class="btn btn-success">
      <i class="fa-solid fa-plus me-1"></i> เพิ่มสินค้าใหม่
    </a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table id="productsTable" class="table table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>ภาพสินค้า</th>
            <th>ชื่อสินค้า</th>
            <th>ราคา (฿)</th>
            <th>คงเหลือ</th>
            <th>วันที่เพิ่ม</th>
            <th>การจัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
          foreach ($stmt as $i => $p): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td>
                <?php if (!empty($p['image'])): ?>
                  <img src="../uploads/<?= htmlspecialchars($p['image']) ?>" width="60" height="60" style="object-fit:cover;border-radius:8px;">
                <?php else: ?>
                  <span class="text-muted">ไม่มีรูป</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($p['name']) ?></td>
              <td><?= number_format($p['price'], 2) ?></td>
              <td><?= $p['stock'] ?></td>
              <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
              <td>
                <a href="product_edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $p['id'] ?>)"><i class="fa-solid fa-trash"></i></button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
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
  $('#productsTable').DataTable({
    pageLength: 10,
    language: {
      search: "ค้นหา:",
      lengthMenu: "แสดง _MENU_ รายการต่อหน้า",
      info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
      paginate: { previous: "ก่อนหน้า", next: "ถัดไป" }
    }
  });
});

function confirmDelete(id) {
  Swal.fire({
    title: 'แน่ใจหรือไม่?',
    text: "คุณต้องการลบสินค้านี้หรือไม่",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'ใช่, ลบเลย!',
    cancelButtonText: 'ยกเลิก'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location = 'delete_product.php?id=' + id;
    }
  });
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
