<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/partials/header.php';

$cart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) die('Invalid CSRF');

    // ✅ ปุ่มล้างตะกร้า
    if (isset($_POST['clear_cart'])) {
        unset($_SESSION['cart']);
        header('Location: cart.php');
        exit;
    }

    // ✅ ปุ่มอัปเดตจำนวน
    if (isset($_POST['update']) && isset($_POST['qty'])) {
        foreach ($_POST['qty'] as $id => $q) {
            $id = (int)$id;
            $q = max(0, (int)$q);

            foreach ($_SESSION['cart'] as $index => $item) {
                if ((int)$item['id'] === $id) {
                    if ($q === 0) {
                        unset($_SESSION['cart'][$index]);
                    } else {
                        $_SESSION['cart'][$index]['qty'] = $q;
                    }
                }
            }
        }
        $_SESSION['cart'] = array_values($_SESSION['cart']); // รีเซ็ต key
        header('Location: cart.php');
        exit;
    }
}

$cart = $_SESSION['cart'] ?? [];
?>

<h2 class="mb-4">ตะกร้าสินค้า</h2>

<form method="post">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

  <div class="table-responsive">
    <table class="table align-middle">
      <thead class="table-light">
        <tr>
          <th>สินค้า</th>
          <th class="text-center">จำนวน</th>
          <th class="text-end">ราคา/ชิ้น</th>
          <th class="text-end">รวม</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $sum = 0; 
        foreach ($cart as $item): 
          if (empty($item['id'])) continue; 
          $line = $item['price'] * $item['qty']; 
          $sum += $line; 
        ?>
        <tr>
          <td>
            <div class="d-flex align-items-center gap-3">
              <img src="<?= $item['image'] ? base_url('uploads/'.$item['image']) : base_url('assets/no-image.png') ?>"
                   width="64" height="64" style="object-fit:cover;" class="rounded">
              <div>
                 <div class="fw-semibold"><?= htmlspecialchars($item['name']) ?></div>
                 <div class="text-muted small">
                      ID: <?= (int)$item['id'] ?>
                  <?php if (!empty($item['size']) && $item['size'] !== 'N/A'): ?>
               | Size: <?= htmlspecialchars($item['size']) ?>
           <?php endif; ?>
            </div>
              </div>

            </div>
          </td>
          <td class="text-center" style="max-width:120px">
            <input class="form-control text-center" 
                   type="number"
                   name="qty[<?= (int)$item['id'] ?>]" 
                   min="0"
                   value="<?= (int)$item['qty'] ?>">
          </td>
          <td class="text-end">฿<?= money($item['price']) ?></td>
          <td class="text-end">฿<?= money($line) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3" class="text-end">รวมทั้งสิ้น</th>
          <th class="text-end">฿<?= money($sum) ?></th>
        </tr>
      </tfoot>
    </table>
  </div>

  <!-- ✅ แบ่ง 2 ฝั่ง: ซ้าย (ล้างตะกร้า) / ขวา (อัปเดต + สั่งซื้อ) -->
  <div class="d-flex justify-content-between align-items-center mt-3">
    <!-- ฝั่งซ้าย -->
    <button type="submit" name="clear_cart" value="1" 
            class="btn btn-outline-danger"
            onclick="return confirm('แน่ใจหรือไม่ว่าต้องการล้างสินค้าทั้งหมดในตะกร้า?')">
        🧹 ล้างตะกร้า
    </button>

    <!-- ฝั่งขวา -->
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary" name="update" value="1">อัปเดตจำนวน</button>
      <a class="btn btn-primary <?= empty($cart) ? 'disabled' : '' ?>" 
         href="<?= base_url('checkout.php') ?>" 
         <?= empty($cart) ? 'aria-disabled="true"' : '' ?>>
         ดำเนินการสั่งซื้อ
      </a>
    </div>
  </div>
</form>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
