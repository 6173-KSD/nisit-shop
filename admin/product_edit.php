<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../db.php';
session_start();

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../login/login.php");
  exit;
}

$id = $_GET['id'] ?? 0;
$product = $pdo->prepare("SELECT * FROM products WHERE id=?");
$product->execute([$id]);
$data = $product->fetch();

if (!$data) die("ไม่พบสินค้านี้");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $price = (float)$_POST['price'];
  $stock = (int)$_POST['stock'];
  $image = $data['image'];

  if (!empty($_FILES['image']['name'])) {
    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    $image = time() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image);
  }

  $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, stock=?, image=? WHERE id=?");
  $stmt->execute([$name, $price, $stock, $image, $id]);

  header("Location: products.php");
  exit;
}

require_once __DIR__ . '/header.php';
?>

<div class="container my-4">
  <h2 class="fw-bold mb-4">แก้ไขสินค้า</h2>

  <form method="post" enctype="multipart/form-data" class="card shadow-sm p-4">
    <div class="mb-3">
      <label class="form-label">ชื่อสินค้า</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data['name']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">ราคา (บาท)</label>
      <input type="number" name="price" step="0.01" class="form-control" value="<?= $data['price'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">จำนวนในสต็อก</label>
      <input type="number" name="stock" class="form-control" value="<?= $data['stock'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">รูปภาพสินค้า (ถ้ามี)</label><br>
      <?php if ($data['image']): ?>
        <img src="../uploads/<?= htmlspecialchars($data['image']) ?>" width="100" class="mb-2 rounded">
      <?php endif; ?>
      <input type="file" name="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> บันทึกการแก้ไข</button>
    <a href="products.php" class="btn btn-secondary">กลับ</a>
  </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
