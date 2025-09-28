<?php
require_once __DIR__ . '/functions.php';

// ลบ session user
unset($_SESSION['user']);

// กลับไปที่หน้าแรก
header('Location: ' . base_url('index.php'));
exit;
