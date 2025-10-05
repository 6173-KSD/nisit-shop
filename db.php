<?php
require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // ตั้งค่าภาษาไทยและ collation ให้แน่ใจ
    $pdo->exec("SET NAMES utf8mb4");
    $pdo->exec("SET CHARACTER SET utf8mb4");
    $pdo->exec("SET COLLATION_CONNECTION = 'utf8mb4_general_ci'");
} catch (PDOException $e) {
    die('❌ DB connection failed: ' . $e->getMessage());
}
?>
