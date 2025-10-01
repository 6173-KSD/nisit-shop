<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !check_csrf($_POST['csrf'] ?? '')) {
    die("Invalid request");
}

$order_id = (int)$_POST['order_id'];
$method = $_POST['method'];
$slip = null;

if (!empty($_FILES['slip']['name'])) {
    $slip = time() . '_' . basename($_FILES['slip']['name']);
    move_uploaded_file($_FILES['slip']['tmp_name'], __DIR__ . '/uploads/' . $slip);
}

$st = $pdo->prepare("INSERT INTO payments (order_id, method, amount, status, slip_path) 
                     VALUES (?, ?, (SELECT SUM(qty*unit_price) FROM order_items WHERE order_id=?), 'pending', ?)");
$st->execute([$order_id, $method, $order_id, $slip]);

header("Location: track.php?order=" . $_GET['order']);
exit;
