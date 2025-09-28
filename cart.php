<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/partials/header.php';

$cart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) die('Invalid CSRF');

    if (isset($_POST['update'])) {
        foreach ($_POST['qty'] as $id => $q) {
            $q = max(0, (int)$q);
            if ($q === 0) {
                unset($_SESSION['cart'][$id]);
            } else {
                $_SESSION['cart'][$id]['qty'] = $q;
            }
        }
        header('Location: ' . base_url('cart.php'));
        exit;
    }
}

$cart = $_SESSION['cart'] ?? [];
?>

<h2>ตะกร้าสินค้า</h2>
<form method="post">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>สินค้า</th>
          <th class="text-center">จำนวน</th>
          <th class="text-end">ราคา/ชิ้น</th>
          <th class="text-end">รวม</th>
        </tr>
      </thead>
      <tbody>
        <?php $sum = 0; foreach ($cart as $item): $line = $item['price'] * $item['qty']; $sum += $line; ?>
        <tr>
          <td>
            <div class="d-flex align-items-center gap-3">
              <img src="<?= $item['image'] ? base_url('uploads/'.$item['image']) : base_url('assets/no-image.png') ?>"
                   width="64" class="rounded">
              <div>
                <div class="fw-semibold"><?= htmlspecialchars($item['name']) ?></div>
                <div class="text-muted small">ID: <?= (int)$item['id'] ?></div>
              </div>
            </div>
          </td>
          <td class="text-center" style="max-width:120px">
            <input class="form-control text-center" type="number"
                   name="qty[<?= (int)$item['id'] ?>]" min="0"
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

  <div class="d-flex gap-2 justify-content-end">
    <button class="btn btn-outline-secondary" name="update" value="1">อัปเดตจำนวน</button>
    <a class="btn btn-primary"
       href="<?= base_url('checkout.php') ?>"
       <?= empty($cart) ? 'aria-disabled="true" class="btn btn-primary disabled"' : '' ?>>
       ดำเนินการสั่งซื้อ
    </a>
  </div>
</form>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
