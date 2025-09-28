<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) die('Invalid CSRF');

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name === '' || $email === '' || $password === '') {
        $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    } else {
        // เช็กว่ามีอีเมลนี้แล้วหรือยัง
        $st = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $st->execute([$email]);
        if ($st->fetch()) {
            $error = 'อีเมลนี้มีอยู่แล้ว';
        } else {
            // บันทึกข้อมูลใหม่
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $st = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $st->execute([$name, $email, $hash]);

            // ✅ set success flag
            $success = true;
        }
    }
}
?>

<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="container" style="max-width:500px">
  <div class="card shadow-sm mt-5">
    <div class="card-body">
      <h3 class="mb-3">สมัครสมาชิก</h3>
      <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <div class="mb-3">
          <label class="form-label">ชื่อ - นามสกุล</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">อีเมล</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">รหัสผ่าน</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-success w-100">สมัครสมาชิก</button>
      </form>
      <p class="mt-3 small">มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
    </div>
  </div>
</div>

<?php if ($success): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: 'success',
    title: 'สมัครสมาชิกสำเร็จ!',
    text: 'กรุณาเข้าสู่ระบบเพื่อดำเนินการต่อ',
    confirmButtonText: 'OK'
}).then(() => {
    window.location.href = "<?= base_url('login.php') ?>";
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
