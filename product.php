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

    // ✅ 1. รับค่า Size และ Quantity จากฟอร์ม
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    // ถ้าสินค้าไม่มี Size (เช่น เนคไท) ให้ $size เป็น 'N/A'
    $size = trim($_POST['size'] ?? 'N/A'); 

    // ตรวจสอบว่ามีการเลือกไซส์หรือไม่ (เฉพาะกรณีที่สินค้าควรมีไซส์)
    $sku_prefix = substr($p['sku'], 0, 2);
    $requires_size = ($sku_prefix === 'TS' || $sku_prefix === 'SH' || $sku_prefix === 'PT' || $sku_prefix === 'SK');
    
    if ($requires_size && $size === 'N/A') {
        echo '<div class="alert alert-warning">กรุณาเลือกขนาด (Size) ของสินค้า</div>';
    } elseif ($qty > (int)$p['stock']) {
        echo '<div class="alert alert-warning">สต๊อกไม่พอ</div>';
    } else {
        // สร้าง key สำหรับสินค้าในตะกร้าที่รวม ID และ Size เข้าด้วยกัน
        // เช่น '5-L' หรือ '12-N/A'
        $cart_key = $id . '-' . $size;

        // ✅ 2. บันทึกข้อมูลสินค้าในตะกร้า พร้อมเพิ่ม 'size'
        if (isset($_SESSION['cart'][$cart_key])) {
            $_SESSION['cart'][$cart_key]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$cart_key] = [
                'id'    => $id,
                'name'  => $p['name'],
                'price' => (float)$p['price'],
                'qty'   => $qty,
                'image' => $p['image'],
                'size'  => $size, // <--- เพิ่ม size เข้าไปใน Session Cart
            ];
        }

        // ✅ กลับไปหน้าตะกร้า
        header('Location: ' . base_url('cart.php'));
        exit;
    }
}
?>

<div class="row g-4">
    <div class="col-md-6">
        <!-- ปรับปรุงให้ภาพดูสวยงามขึ้นตามธีม มมส. -->
        <img class="img-fluid rounded-4 shadow-lg border border-2 border-gray"
            src="<?= $p['image'] ? base_url('uploads/'.$p['image']) : base_url('assets/no-image.png') ?>"
            alt="<?= htmlspecialchars($p['name']) ?>">
    </div>
    <div class="col-md-6">
        <!-- ปรับปรุงสีหัวข้อให้เป็นสีเหลืองทองตามธีม -->
        <h2 style="color: #FFD700;"><?= htmlspecialchars($p['name']) ?></h2>
        <p class="text-muted">รหัส: <?= htmlspecialchars($p['sku']) ?></p>
        <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>
        <h3 class="text-secondary fw-bold">฿<?= money($p['price']) ?></h3> <!-- ใช้สีเทาเข้มเป็นสีราคาหลัก -->

        <p>
            <?php if ($p['stock'] > 0): ?>
                <span class="badge bg-success">สต๊อก: <?= (int)$p['stock'] ?></span>
            <?php else: ?>
                <span class="badge bg-secondary">
                    ของหมด<?= $p['expected_restock_date'] ? ' · คาดเข้า '.htmlspecialchars($p['expected_restock_date']) : '' ?>
                </span>
            <?php endif; ?>
        </p>

        <!-- 🎉 ส่วนรายละเอียดเพิ่มเติมของชุด (เพิ่มเงื่อนไข) -->
        <div class="card border-0 shadow-sm mb-4 bg-light rounded-3">
            <div class="card-body">
                <h6 class="card-title text-secondary fw-bold mb-3">
                    <i class="fa-solid fa-circle-info me-2"></i> รายละเอียดสินค้า
                </h6>
                
                <?php
                // ✅ ใช้รหัส SKU เป็นตัวกำหนดรายละเอียดชุด
                $sku_prefix = substr($p['sku'], 0, 2); 
                ?>

                <!-- รายละเอียดสำหรับเสื้อ (SKU ขึ้นต้นด้วย TS หรือ SH) -->
                <?php if ($sku_prefix === 'TS' || $sku_prefix === 'SH'): ?>
                    <!-- คุณสมบัติหลัก 3-4 ข้อ ใช้ Badge เพื่อเน้น -->
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-dark-subtle text-dark-emphasis p-2">
                            <i class="fa-solid fa-shirt me-1"></i> ผ้าคอตตอน 100%
                        </span>
                        <span class="badge bg-dark-subtle text-dark-emphasis p-2">
                            <i class="fa-solid fa-sun me-1"></i> ระบายอากาศดีเยี่ยม
                        </span>
                        <span class="badge bg-dark-subtle text-dark-emphasis p-2">
                            <i class="fa-solid fa-paintbrush me-1"></i> สีไม่ตก ทนทาน
                        </span>
                    </div>
                    <!-- รายละเอียดปลีกย่อย -->
                    <p class="card-text small text-muted mb-0">
                        ผลิตจากเส้นใยคุณภาพสูง มาตรฐานมหาวิทยาลัยมหาสารคาม ตัดเย็บปราณีต ทรงเสื้อเข้ารูปเล็กน้อยเพื่อความทันสมัย
                        เหมาะสำหรับนักศึกษาและบุคลากรทุกท่าน
                    </p>
                
                <!-- รายละเอียดสำหรับกางเกง/กระโปรง (SKU ขึ้นต้นด้วย PT หรือ SK) -->
                <?php elseif ($sku_prefix === 'PT' || $sku_prefix === 'SK'): ?>
                    <!-- คุณสมบัติหลัก 3-4 ข้อ ใช้ Badge เพื่อเน้น -->
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-dark-subtle text-dark-emphasis p-2">
                            <i class="fa-solid fa-person-military-pointing me-1"></i> ผ้า Spendex ผสมโพลีเอสเตอร์
                        </span>
                        <span class="badge bg-dark-subtle text-dark-emphasis p-2">
                            <i class="fa-solid fa-water me-1"></i> แห้งไว ไม่ต้องรีด
                        </span>
                        <span class="badge bg-dark-subtle text-dark-emphasis p-2">
                            <i class="fa-solid fa-scissors me-1"></i> ทรงสวย ยืดหยุ่นสูง
                        </span>
                    </div>
                    <!-- รายละเอียดปลีกย่อย -->
                    <p class="card-text small text-muted mb-0">
                        ตัดเย็บพิเศษสำหรับกิจกรรมที่ต้องเคลื่อนไหวตัวมาก เนื้อผ้าเบา ทนทาน ไม่ยับง่าย 
                        มีกระเป๋าข้างลึกสำหรับเก็บของสำคัญได้
                    </p>

                <!-- รายละเอียดเริ่มต้น (Default) -->
                <?php else: ?>
                    <p class="card-text small text-muted mb-0">
                        ผลิตจากวัสดุคุณภาพดีตามมาตรฐาน ม.มหาสารคาม กรุณาดูรายละเอียดสินค้าจากรูปภาพประกอบ
                    </p>
                <?php endif; ?>

            </div>
        </div>

        <?php
        // กำหนด SKU Prefix ที่ต้องการให้มี Size
        $sku_prefix = substr($p['sku'], 0, 2);
        $has_size = ($sku_prefix === 'TS' || $sku_prefix === 'SH' || $sku_prefix === 'PT' || $sku_prefix === 'SK');
        ?>

        <!-- 3. Dropdown เลือกไซส์และจัดรูปแบบฟอร์ม (เฉพาะสินค้าที่มี Size) -->
        <?php if ($has_size): ?>
            <form method="post">
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                
                <div class="row g-3 align-items-end">
                    <!-- Dropdown เลือกไซส์ -->
                    <div class="col-12 col-md-5">
                        <label for="productSize" class="form-label fw-bold">เลือกขนาด:</label>
                        <select class="form-select" id="productSize" name="size" required>
                            <option value="N/A" disabled selected>-- กรุณาเลือกไซส์ --</option>
                            <!-- ไซส์ Hardcode สำหรับเสื้อ: ในโลกจริงควรดึงจาก DB -->
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                    </div>

                    <!-- ช่องจำนวน (Quantity) -->
                    <div class="col-6 col-md-3">
                        <label for="qty" class="form-label fw-bold">จำนวน:</label>
                        <input type="number" min="1" max="<?= max(1,(int)$p['stock']) ?>"
                            id="qty" name="qty" value="1" class="form-control" required>
                    </div>

                    <!-- ปุ่มเพิ่มลงตะกร้า -->
                    <div class="col-6 col-md-4">
                        <!-- ใช้สีเหลืองทอง (btn-warning) ตามธีม มมส. -->
                        <button class="btn btn-warning fw-bold w-100"<?= $p['stock'] <= 0 ? ' disabled' : '' ?>>
                            <i class="fa-solid fa-cart-plus me-1"></i> เพิ่มลงตะกร้า
                        </button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <!-- 3. ฟอร์มสำหรับสินค้าที่ไม่มี Size (เช่น เนคไท, เข็มขัด) -->
            <form method="post">
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                <!-- ไม่ต้องมี Dropdown Size, ใช้ input hidden เพื่อส่ง size='N/A' ไปแทน -->
                <input type="hidden" name="size" value="N/A">
                
                <div class="row g-3 align-items-end">
                    <!-- ช่องจำนวน (Quantity) -->
                    <div class="col-6 col-md-3">
                        <label for="qty" class="form-label fw-bold">จำนวน:</label>
                        <input type="number" min="1" max="<?= max(1,(int)$p['stock']) ?>"
                            id="qty" name="qty" value="1" class="form-control" required>
                    </div>

                    <!-- ปุ่มเพิ่มลงตะกร้า -->
                    <div class="col-6 col-md-4">
                        <button class="btn btn-warning fw-bold w-100"<?= $p['stock'] <= 0 ? ' disabled' : '' ?>>
                            <i class="fa-solid fa-cart-plus me-1"></i> เพิ่มลงตะกร้า
                        </button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
        
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
