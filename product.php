<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/partials/header.php';
session_start();

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
    $size = trim($_POST['size'] ?? 'N/A'); 

    // ✅ เพิ่ม SKU รองเท้า (SO) และกางเกง (PL)
    $sku_prefix = substr($p['sku'], 0, 2);
    $requires_size = in_array($sku_prefix, ['TS', 'SH', 'PT', 'SK', 'PL', 'SO']);
    
    if ($requires_size && $size === 'N/A') {
        echo '<div class="alert alert-warning">กรุณาเลือกขนาด (Size) ของสินค้า</div>';
    } elseif ($qty > (int)$p['stock']) {
        echo '<div class="alert alert-warning">สต๊อกไม่พอ</div>';
    } else {
        $cart_key = $id . '-' . $size;

        if (isset($_SESSION['cart'][$cart_key])) {
            $_SESSION['cart'][$cart_key]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$cart_key] = [
                'id'    => $id,
                'name'  => $p['name'],
                'price' => (float)$p['price'],
                'qty'   => $qty,
                'image' => $p['image'],
                'size'  => $size,
            ];
        }

        echo "<script>
    Swal.fire({
        icon: 'success',
        title: 'เพิ่มลงตะกร้าสำเร็จ!',
        text: 'สินค้าของคุณถูกเพิ่มในตะกร้าแล้ว',
        confirmButtonColor: '#facc15',
        confirmButtonText: 'ตกลง'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'products.php';
        }
    });
</script>";
exit;

    }
}
?>

<div class="row g-4">
  <div class="col-md-6">
    <img class="img-fluid rounded-4 shadow-lg border border-2 border-gray"
         src="<?= $p['image'] ? base_url('uploads/'.$p['image']) : base_url('assets/no-image.png') ?>"
         alt="<?= htmlspecialchars($p['name']) ?>">
  </div>

  <div class="col-md-6">
    <h2 style="color: #FFD700;"><?= htmlspecialchars($p['name']) ?></h2>
    <p class="text-muted">รหัส: <?= htmlspecialchars($p['sku']) ?></p>
    <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>
    <h3 class="text-secondary fw-bold">฿<?= money($p['price']) ?></h3>

    <p>
      <?php if ($p['stock'] > 0): ?>
        <span class="badge bg-success">สต๊อก: <?= (int)$p['stock'] ?></span>
      <?php else: ?>
        <span class="badge bg-secondary">
          ของหมด<?= $p['expected_restock_date'] ? ' · คาดเข้า '.htmlspecialchars($p['expected_restock_date']) : '' ?>
        </span>
      <?php endif; ?>
    </p>

    <!-- ✅ ส่วนรายละเอียดสินค้า (ของอามเหมือนเดิม) -->
    <div class="card border-0 shadow-sm mb-4 bg-light rounded-3">
      <div class="card-body">
        <h6 class="card-title text-secondary fw-bold mb-3">
          <i class="fa-solid fa-circle-info me-2"></i> รายละเอียดสินค้า
        </h6>

        <?php
        $sku_prefix = substr($p['sku'], 0, 2); 
        ?>

        <?php if ($sku_prefix === 'TS' || $sku_prefix === 'SH'): ?>
          <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge bg-dark-subtle text-dark-emphasis p-2"><i class="fa-solid fa-shirt me-1"></i> ผ้าคอตตอน 100%</span>
            <span class="badge bg-dark-subtle text-dark-emphasis p-2"><i class="fa-solid fa-sun me-1"></i> ระบายอากาศดี</span>
            <span class="badge bg-dark-subtle text-dark-emphasis p-2"><i class="fa-solid fa-paintbrush me-1"></i> สีไม่ตก</span>
          </div>
          <p class="card-text small text-muted mb-0">ผลิตจากเส้นใยคุณภาพสูงตามมาตรฐาน มมส. เหมาะกับนิสิตและบุคลากรทุกท่าน</p>

        <?php elseif ($sku_prefix === 'PT' || $sku_prefix === 'SK' || $sku_prefix === 'PL'): ?>
          <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge bg-dark-subtle text-dark-emphasis p-2"><i class="fa-solid fa-scissors me-1"></i> ผ้าโพลีเอสเตอร์ยืดหยุ่น</span>
            <span class="badge bg-dark-subtle text-dark-emphasis p-2"><i class="fa-solid fa-water me-1"></i> แห้งไว ไม่ต้องรีด</span>
          </div>
          <p class="card-text small text-muted mb-0">ตัดเย็บพิเศษ เนื้อผ้าเบา ยืดหยุ่นสูง มีกระเป๋าลึก</p>

        <?php elseif ($sku_prefix === 'SO'): ?>
          <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge bg-dark-subtle text-dark-emphasis p-2"><i class="fa-solid fa-shoe-prints me-1"></i> พื้นนุ่มสบาย</span>
            <span class="badge bg-dark-subtle text-dark-emphasis p-2"><i class="fa-solid fa-shield me-1"></i> กันลื่น</span>
          </div>
          <p class="card-text small text-muted mb-0">รองเท้านิสิตคุณภาพสูง พื้นทนทาน สวมใส่สบายตามระเบียบ มมส.</p>

        <?php else: ?>
          <p class="card-text small text-muted mb-0">ผลิตจากวัสดุคุณภาพดีตามมาตรฐาน มมส.</p>
        <?php endif; ?>
      </div>
    </div>

    <?php
    // ✅ เพิ่ม PL (กางเกง) และ SO (รองเท้า)
    $sku_prefix = substr($p['sku'], 0, 2);
    $has_size = in_array($sku_prefix, ['TS', 'SH', 'PT', 'SK', 'PL', 'SO']);
    ?>

    <?php if ($has_size): ?>
      <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

        <div class="row g-3 align-items-end">
          <div class="col-12 col-md-5">
            <label for="productSize" class="form-label fw-bold">เลือกขนาด:</label>
            <select class="form-select" id="productSize" name="size" required>
              <option value="N/A" disabled selected>-- กรุณาเลือกไซส์ --</option>
              <?php
              if (in_array($sku_prefix, ['TS', 'SH'])) {
                echo '<option value="S">S</option><option value="M">M</option><option value="L">L</option><option value="XL">XL</option><option value="XXL">XXL</option>';
              } elseif (in_array($sku_prefix, ['PT', 'PL'])) {
                echo '<option value="28">28</option><option value="30">30</option><option value="32">32</option><option value="34">34</option><option value="36">36</option>';
              } elseif ($sku_prefix === 'SO') {
                echo '<option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option>';
              }
              ?>
            </select>
          </div>

          <div class="col-6 col-md-3">
            <label for="qty" class="form-label fw-bold">จำนวน:</label>
            <input type="number" min="1" max="<?= max(1,(int)$p['stock']) ?>"
                   id="qty" name="qty" value="1" class="form-control" required>
          </div>

          <div class="col-6 col-md-4">
            <button class="btn btn-warning fw-bold w-100"<?= $p['stock'] <= 0 ? ' disabled' : '' ?>>
              <i class="fa-solid fa-cart-plus me-1"></i> เพิ่มลงตะกร้า
            </button>
          </div>
        </div>
      </form>
    <?php else: ?>
      <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <input type="hidden" name="size" value="N/A">
        <div class="row g-3 align-items-end">
          <div class="col-6 col-md-3">
            <label for="qty" class="form-label fw-bold">จำนวน:</label>
            <input type="number" min="1" max="<?= max(1,(int)$p['stock']) ?>" id="qty" name="qty" value="1" class="form-control" required>
          </div>
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
