<?php
require_once __DIR__ . '/../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf'] ?? '')) die('Invalid CSRF');

    $email = trim($_POST['email'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    if ($email === ADMIN_EMAIL && $pass === ADMIN_PASS) {
        // ✅ ตั้ง session ให้ตรงกับ index.php
        $_SESSION['user'] = [
          'email' => $email,
          'role'  => 'admin'
        ];

        header('Location: ' . base_url('admin/index.php'));
        exit;
    } else {
        $error = 'อีเมลหรือรหัสผ่านไม่ถูกต้อง';
    }
}
?>

<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="mb-3">Admin Login</h4>
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="post">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input class="form-control" type="email" name="email" required>
              </div>
              <div class="mb-3">
                <label class="form-label">รหัสผ่าน</label>
                <input class="form-control" type="password" name="password" required>
              </div>
              <button class="btn btn-primary w-100">เข้าสู่ระบบ</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
