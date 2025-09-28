<?php
// ตั้งค่าเบื้องต้นของระบบ
define('BASE_URL', '/nisit-shop/'); // แก้ตามโฟลเดอร์จริงบน XAMPP

// ข้อมูลผู้ดูแลระบบ (สำหรับเดโม) – ควรเปลี่ยนก่อนใช้งานจริง
define('ADMIN_EMAIL', 'admin@nisit.test');
define('ADMIN_PASS', 'admin123');
    
// การเชื่อมต่อฐานข้อมูล (XAMPP ค่าเริ่มต้น)
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'nisit_shop');
define('DB_USER', 'root');
define('DB_PASS', 'KSD6173');

// ตั้งค่า timezone
date_default_timezone_set('Asia/Bangkok');
