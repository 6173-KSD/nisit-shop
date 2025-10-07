<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../db.php';
session_start();

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../login/login.php");
  exit;
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $price = (float)$_POST['price'];
  $stock = (int)$_POST['stock'];
  $image = null;

  if (!empty($_FILES['image']['name'])) {
    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    $image = time() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image);
  }

  $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, image, created_at) VALUES (?,?,?,?,NOW())");
  $stmt->execute([$name, $price, $stock, $image]);

  $msg = "เพิ่มสินค้าเรียบร้อยแล้ว!";
}

require_once __DIR__ . '/header.php';
?>

<div class="container my-4">
  <h2 class="fw-bold mb-4">เพิ่มสินค้าใหม่</h2>

  <?php if ($msg): ?>
    <div class="alert alert-success"><?= $msg ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="card shadow-sm p-4">
    <div class="mb-3">
      <label class="form-label">ชื่อสินค้า</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">ราคา (บาท)</label>
      <input type="number" name="price" step="0.01" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">จำนวนในสต็อก</label>
      <input type="number" name="stock" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">รูปภาพสินค้า</label>
      <input type="file" name="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> บันทึกสินค้า</button>
    <a href="products.php" class="btn btn-secondary">กลับ</a>
  </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
