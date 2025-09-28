<?php if (!isset($pdo)) require_once __DIR__ . '/db.php';
if (!isset($in_page_embed)) { $standalone = true; require_once __DIR__ . '/partials/header.php'; }
$kw = isset($_GET['q']) ? trim($_GET['q']) : '';
$sql = "SELECT * FROM products WHERE is_active=1 AND (name LIKE :kw OR sku LIKE :kw) ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':kw' => "%$kw%"]);
$rows = $stmt->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
<h2 class="mb-0">สินค้า</h2>
<form class="d-flex" method="get">
<input class="form-control me-2" type="search" name="q" placeholder="ค้นหาชื่อ/รหัสสินค้า" value="<?= htmlspecialchars($kw) ?>">
<button class="btn btn-outline-primary" type="submit">ค้นหา</button>
</form>
</div>
<div class="row g-3">
<?php foreach ($rows as $p): ?>
<div class="col-12 col-sm-6 col-md-4 col-lg-3">
<div class="card h-100 shadow-sm">
<img src="<?= $p['image'] ? base_url('uploads/'.$p['image']) : base_url('assets/no-image.png') ?>" class="card-img-top" alt="<?= htmlspecialchars($p['name']) ?>">
<div class="card-body d-flex flex-column">
<h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
<p class="card-text small text-muted flex-grow-1">รหัส: <?= htmlspecialchars($p['sku']) ?></p>
<div class="d-flex justify-content-between align-items-center">
<span class="fw-bold">฿<?= money($p['price']) ?></span>
<?php if ((int)$p['stock'] > 0): ?>
<span class="badge bg-success">มีของ: <?= (int)$p['stock'] ?></span>
<?php else: ?>
<span class="badge bg-secondary">ของหมด<?= $p['expected_restock_date'] ? ' (คาดว่า '.htmlspecialchars($p['expected_restock_date']).')' : '' ?></span>
<?php endif; ?>
</div>
<a href="product.php?id=<?= $p['id'] ?>" class="btn btn-primary mt-3 w-100">ดูรายละเอียด</a>
</div>
</div>
</div>
<?php endforeach; ?>
<?php if (!empty($standalone)) require_once __DIR__ . '/partials/footer.php'; ?>