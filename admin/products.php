<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../functions.php';
require_admin();
require_once __DIR__ . '/header.php';   // ใช้ header ของ admin

$success = '';
$error   = '';

// จัดการการเพิ่ม/แก้ไข/ลบ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_csrf($_POST['csrf'] ?? '')) {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $sku   = trim($_POST['sku']);
        $name  = trim($_POST['name']);
        $price = (float)$_POST['price'];
        $stock = (int)$_POST['stock'];
        $expected = $_POST['expected_restock_date'] ?: null;
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        $image = null;
        if (!empty($_FILES['image']['name'])) {
            $image = time() . '_' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $image);
        }

        $pdo->prepare("INSERT INTO products (sku,name,price,stock,expected_restock_date,image,is_active) 
                       VALUES (?,?,?,?,?,?,?)")
            ->execute([$sku,$name,$price,$stock,$expected,$image,$is_active]);
        $success = 'เพิ่มสินค้าเรียบร้อยแล้ว';
    }

    if ($action === 'update') {
        $id    = (int)$_POST['id'];
        $sku   = trim($_POST['sku']);
        $name  = trim($_POST['name']);
        $price = (float)$_POST['price'];
        $stock = (int)$_POST['stock'];
        $expected = $_POST['expected_restock_date'] ?: null;
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        $image = null;
        if (!empty($_FILES['image']['name'])) {
            $image = time() . '_' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $image);
        }

        if ($image) {
            $pdo->prepare("UPDATE products 
                           SET sku=?,name=?,price=?,stock=?,expected_restock_date=?,image=?,is_active=? 
                           WHERE id=?")
                ->execute([$sku,$name,$price,$stock,$expected,$image,$is_active,$id]);
        } else {
            $pdo->prepare("UPDATE products 
                           SET sku=?,name=?,price=?,stock=?,expected_restock_date=?,is_active=? 
                           WHERE id=?")
                ->execute([$sku,$name,$price,$stock,$expected,$is_active,$id]);
        }
        $success = 'แก้ไขสินค้าเรียบร้อยแล้ว';
    }

    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        $pdo->prepare("DELETE FROM products WHERE id=?")->execute([$id]);
        $success = 'ลบสินค้าเรียบร้อยแล้ว';
    }
}

// ดึงข้อมูลสินค้า
$rows = $pdo->query("
    SELECT * FROM products
    ORDER BY id DESC
")->fetchAll();
?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>จัดการสินค้า</h2>
    <a class="btn btn-outline-secondary" href="<?= base_url('admin/index.php') ?>">← กลับแผงควบคุม</a>
  </div>

  <!-- แสดงข้อความแจ้งเตือน -->
  <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($success) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- ฟอร์มเพิ่มสินค้า -->
  <div class="card card-body mb-4">
    <h5 class="mb-3">เพิ่มสินค้าใหม่</h5>
    <form method="post" enctype="multipart/form-data" class="row g-2">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
      <input type="hidden" name="action" value="add">

      <div class="col-md-2"><input class="form-control" name="sku" placeholder="SKU" required></div>
      <div class="col-md-3"><input class="form-control" name="name" placeholder="ชื่อสินค้า" required></div>
      <div class="col-md-2"><input class="form-control" type="number" step="0.01" name="price" placeholder="ราคา" required></div>
      <div class="col-md-2"><input class="form-control" type="number" name="stock" placeholder="สต๊อก" required></div>
      <div class="col-md-3"><input class="form-control" type="date" name="expected_restock_date"></div>
      <div class="col-md-3"><input class="form-control" type="file" name="image" accept="image/*"></div>
      <div class="col-md-2 form-check ms-2">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
        <label class="form-check-label" for="is_active">แสดงหน้าเว็บ</label>
      </div>
      <div class="col-12"><button class="btn btn-primary">บันทึกสินค้า</button></div>
    </form>
  </div>

  <!-- ตารางสินค้า -->
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>รูป</th><th>SKU</th><th>ชื่อ</th>
          <th class="text-end">ราคา</th><th class="text-center">สต๊อก</th>
          <th>คาดเข้า</th><th>สถานะ</th><th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $p): ?>
        <tr>
          <td><img src="<?= $p['image'] ? base_url('uploads/'.$p['image']) : base_url('assets/no-image.png') ?>" width="48" class="rounded"></td>
          <td><?= htmlspecialchars($p['sku']) ?></td>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td class="text-end">฿<?= money($p['price']) ?></td>
          <td class="text-center"><?= (int)$p['stock'] ?></td>
          <td><?= htmlspecialchars($p['expected_restock_date'] ?? '') ?></td>
          <td><?= $p['is_active'] ? '<span class="badge bg-success">แสดง</span>' : '<span class="badge bg-secondary">ซ่อน</span>' ?></td>
          <td class="text-end">
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#edit<?= $p['id'] ?>">แก้ไข</button>
            <form method="post" class="d-inline" onsubmit="return confirm('ยืนยันการลบสินค้านี้?')">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger">ลบ</button>
            </form>
          </td>
        </tr>

        <!-- ฟอร์มแก้ไข -->
        <tr class="collapse" id="edit<?= $p['id'] ?>">
          <td colspan="8">
            <form method="post" enctype="multipart/form-data" class="row g-2 p-2 bg-light rounded">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">

              <div class="col-md-2"><input class="form-control" name="sku" value="<?= htmlspecialchars($p['sku']) ?>" required></div>
              <div class="col-md-3"><input class="form-control" name="name" value="<?= htmlspecialchars($p['name']) ?>" required></div>
              <div class="col-md-2"><input class="form-control" type="number" step="0.01" name="price" value="<?= $p['price'] ?>" required></div>
              <div class="col-md-2"><input class="form-control" type="number" name="stock" value="<?= (int)$p['stock'] ?>" required></div>
              <div class="col-md-3"><input class="form-control" type="date" name="expected_restock_date" value="<?= htmlspecialchars($p['expected_restock_date']) ?>"></div>
              <div class="col-md-3"><input class="form-control" type="file" name="image" accept="image/*"></div>
              <div class="col-md-2 form-check ms-2">
                <input class="form-check-input" type="checkbox" name="is_active" <?= $p['is_active']? 'checked':'' ?>>
                <label class="form-check-label">แสดงหน้าเว็บ</label>
              </div>
              <div class="col-12"><button class="btn btn-primary">บันทึกการแก้ไข</button></div>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php';   // footer ของ admin ?>
