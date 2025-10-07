<?php
require_once __DIR__ . '/../db/connect.php';
session_start();

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../login/login.php");
  exit;
}

$id = $_GET['id'] ?? 0;
$pdo->prepare("DELETE FROM products WHERE id=?")->execute([$id]);

header("Location: products.php");
exit;
