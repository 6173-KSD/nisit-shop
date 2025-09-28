<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/partials/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ? AND is_active=1');
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
    echo '<div class="alert alert-danger">ไม่พบสินค้า</div>';
    require_once __DIR__ . '/partials/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) die('Invalid CSRF');

    $qty = max(1, (int)($_POST['qty'] ?? 1));

    if ($qty > (int)$p['stock']) {
        echo '<div class="alert alert-warning">สต๊อกไม่พอ</div>';
    } else {
        // ✅ ถ้ามีสินค้าอยู่แล้วในตะกร้าให้บวกเพิ่ม
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$id] = [
                'id'    => $id,
                'name'  => $p['name'],
                'price' => (float)$p['price'],
                'qty'   => $qty,
                'image' => $p['image']
            ];
        }

        // ✅ กลับไปหน้าสินค้า
        header('Location: ' . base_url('products.php'));
        exit;
    }
}
?>

<div class="row g-4">
  <div class="col-md-6">
    <img class="img-fluid rounded shadow-sm"
         src="<?= $p['image'] ? base_url('uploads/'.$p['image']) : base_url('assets/no-image.png') ?>"
         alt="<?= htmlspecialchars($p['name']) ?>">
  </div>
  <div class="col-md-6">
    <h2><?= htmlspecialchars($p['name']) ?></h2>
    <p class="text-muted">รหัส: <?= htmlspecialchars($p['sku']) ?></p>
    <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>
    <h3 class="text-primary">฿<?= money($p['price']) ?></h3>

    <p>
      <?php if ($p['stock'] > 0): ?>
        <span class="badge bg-success">สต๊อก: <?= (int)$p['stock'] ?></span>
      <?php else: ?>
        <span class="badge bg-secondary">
          ของหมด<?= $p['expected_restock_date'] ? ' · คาดเข้า '.htmlspecialchars($p['expected_restock_date']) : '' ?>
        </span>
      <?php endif; ?>
    </p>

    <form method="post" class="row row-cols-lg-auto g-2 align-items-center">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
      <div class="col-12">
        <input type="number" min="1" max="<?= max(1,(int)$p['stock']) ?>"
               name="qty" value="1" class="form-control">
      </div>
      <div class="col-12">
        <button class="btn btn-primary"<?= $p['stock'] <= 0 ? ' disabled' : '' ?>>
          เพิ่มลงตะกร้า
        </button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
