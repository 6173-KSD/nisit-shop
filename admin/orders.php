<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../functions.php';
require_admin();
require_once __DIR__ . '/header.php';   // ใช้ header ของ admin

if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_csrf($_POST['csrf'] ?? '')) {
    $id = (int)$_POST['id'];
    $status = $_POST['status'] ?? 'pending';
    $pdo->prepare('UPDATE orders SET status=? WHERE id=?')->execute([$status, $id]);
}
$rows = $pdo->query('SELECT * FROM orders ORDER BY id DESC')->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>จัดการออเดอร์</h2>
  <a class="btn btn-outline-secondary" href="<?= base_url('admin/index.php') ?>">← กลับแผงควบคุม</a>
</div>
<div class="table-responsive">
  <table class="table align-middle">
    <thead><tr><th>รหัส</th><th>ลูกค้า</th><th>ติดต่อ</th><th>รับสินค้า</th><th>สถานะ</th><th>วันที่</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($rows as $o): ?>
    <tr>
      <td class="fw-bold"><?= htmlspecialchars($o['order_code']) ?></td>
      <td><?= htmlspecialchars($o['user_name']) ?></td>
      <td class="small text-muted">☎ <?= htmlspecialchars($o['user_phone']) ?><br><?= htmlspecialchars($o['user_email']) ?></td>
      <td><?= htmlspecialchars($o['pickup_option']) ?><?= $o['pickup_date'] ? '<br><span class="small text-muted">'.htmlspecialchars($o['pickup_date']).'</span>':'' ?></td>
      <td><span class="badge bg-info text-dark"><?= htmlspecialchars($o['status']) ?></span></td>
      <td class="small text-muted"><?= htmlspecialchars($o['created_at']) ?></td>
      <td class="text-end">
        <form method="post" class="d-flex gap-2 align-items-center justify-content-end">
          <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
          <input type="hidden" name="id" value="<?= (int)$o['id'] ?>">
          <select name="status" class="form-select form-select-sm" style="max-width:220px">
            <?php foreach (['pending','confirmed','preparing','ready_for_pickup','picked_up','cancelled'] as $s): ?>
              <option value="<?= $s ?>" <?= $o['status']===$s?'selected':'' ?>><?= $s ?></option>
            <?php endforeach; ?>
          </select>
          <button class="btn btn-sm btn-primary">บันทึก</button>
          <a href="order_detail.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-info">รายละเอียด</a>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/footer.php';   // footer ของ admin ?>
