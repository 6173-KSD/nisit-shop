<?php if (!isset($pdo)) require_once __DIR__ . '/db.php';
if (!isset($in_page_embed)) { $standalone = true; require_once __DIR__ . '/partials/header.php'; }
$kw = isset($_GET['q']) ? trim($_GET['q']) : '';
$sql = "SELECT * FROM products WHERE is_active=1 AND (name LIKE :kw OR sku LIKE :kw) ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':kw' => "%$kw%"]);
$rows = $stmt->fetchAll();
?>
<!-- เพิ่มสไตล์สำหรับรูปภาพใน Card เพื่อให้มีความสูงคงที่และครอบคลุมพื้นที่ -->
<style>
.card-img-top-fixed {
    width: 100%;
    height: 250px; /* กำหนดความสูงคงที่สำหรับรูปภาพ */
    object-fit: cover; /* ทำให้รูปภาพครอบคลุมพื้นที่โดยรักษาสัดส่วน */
    border-radius: 0.5rem 0.5rem 0 0 !important;
}
.card-body-content {
    min-height: 80px; /* กำหนดความสูงขั้นต่ำสำหรับส่วนเนื้อหา (ชื่อ/รหัส) */
}
.price-text {
    color: #FFD700; /* สีเหลืองทอง มมส. */
    font-size: 1.25rem; /* ขนาดตัวอักษรใหญ่ขึ้น */
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h2 class="mb-0 text-dark fw-bold">สินค้าทั้งหมด</h2>
    <form class="d-flex" method="get">
        <input class="form-control me-2" type="search" name="q" placeholder="ค้นหาชื่อ/รหัสสินค้า" value="<?= htmlspecialchars($kw) ?>">
        <button class="btn btn-outline-warning fw-bold" type="submit">ค้นหา</button> <!-- ใช้สีเหลืองทอง/ดำ -->
    </form>
</div>
<div class="row g-4">
<?php 
// ถ้าค้นหาแล้วไม่พบสินค้า
if (empty($rows) && $kw) {
    echo '<div class="col-12"><div class="alert alert-info">ไม่พบสินค้าที่ตรงกับคำว่า "'.htmlspecialchars($kw).'"</div></div>';
}
// วนลูปแสดงสินค้า
foreach ($rows as $p): ?>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card h-100 shadow-lg border-0 rounded-3 overflow-hidden">
            <!-- ใช้คลาสใหม่สำหรับรูปภาพ -->
            <img src="<?= $p['image'] ? base_url('uploads/'.$p['image']) : base_url('assets/no-image.png') ?>" 
                class="card-img-top-fixed" 
                alt="<?= htmlspecialchars($p['name']) ?>" 
                onerror="this.onerror=null;this.src='<?= base_url('assets/no-image.png') ?>';"
            >
            <div class="card-body d-flex flex-column">
                <div class="card-body-content">
                    <h5 class="card-title fw-bold text-truncate" title="<?= htmlspecialchars($p['name']) ?>"><?= htmlspecialchars($p['name']) ?></h5>
                    <p class="card-text small text-muted">รหัส: <?= htmlspecialchars($p['sku']) ?></p>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                    <!-- ใช้สีเหลืองทองสำหรับราคา -->
                    <span class="fw-bold price-text">฿<?= money($p['price']) ?></span>
                    
                    <?php if ((int)$p['stock'] > 0): ?>
                        <span class="badge bg-success py-2 px-3 fw-normal">มีของ: <?= (int)$p['stock'] ?></span>
                    <?php else: ?>
                        <span class="badge bg-secondary py-2 px-3 fw-normal">ของหมด
                            <?= $p['expected_restock_date'] ? ' (คาดเข้า '.htmlspecialchars($p['expected_restock_date']).')' : '' ?>
                        </span>
                    <?php endif; ?>
                </div>
                <!-- ปุ่มดูรายละเอียด ใช้สีหลักของ Bootstrap (Primary) ให้ดูเด่น -->
                <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-primary mt-3 w-100 fw-bold">
                    <i class="fa-solid fa-eye me-1"></i> ดูรายละเอียด
                </a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php if (empty($rows) && !$kw): ?>
    <div class="alert alert-info text-center mt-5">
        <h4>ยังไม่มีสินค้าในระบบ</h4>
        <p class="mb-0">กรุณาเพิ่มสินค้าผ่านระบบจัดการ</p>
    </div>
<?php endif; ?>

<?php if (!empty($standalone)) require_once __DIR__ . '/partials/footer.php'; ?>
