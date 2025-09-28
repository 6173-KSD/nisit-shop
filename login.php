<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) die('Invalid CSRF');

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $st = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $st->execute([$email]);
    $user = $st->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email']
        ];

        // ✅ กลับไปหน้าที่ตั้งใจเข้า เช่น checkout.php
        $redirect = $_GET['redirect'] ?? 'index.php';
        header('Location: ' . base_url($redirect));
        exit;
    } else {
        $error = '❌ อีเมลหรือรหัสผ่านไม่ถูกต้อง';
    }
}
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="d-flex justify-content-center align-items-center" style="min-height:80vh;">
  <div class="card shadow-sm" style="width:100%; max-width:420px;">
    <div class="card-body p-4">
      <h3 class="text-center mb-4 fw-bold">🔑 เข้าสู่ระบบ</h3>

      <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" novalidate>
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

        <div class="mb-3">
          <label class="form-label">📧 อีเมล</label>
          <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
        </div>

        <div class="mb-3">
          <label class="form-label">🔒 รหัสผ่าน</label>
          <input type="password" name="password" class="form-control" placeholder="********" required>
        </div>

        <button class="btn btn-primary w-100 py-2">เข้าสู่ระบบ</button>
      </form>

      <div class="text-center mt-3">
        <p class="small mb-1">ยังไม่มีบัญชี? 
          <a href="register.php" class="fw-bold text-decoration-none">สมัครสมาชิก</a>
        </p>
        <p class="small"><a href="#">ลืมรหัสผ่าน?</a></p>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
