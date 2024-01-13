<?php
// เริ่ม session
session_start();

// ทำลาย session ทั้งหมด
session_destroy();

// ทำลาย cookie ทั้งหมด
setcookie(session_name(), '', time() - 3600, '/');

// ไปที่หน้าหลักหลังจาก logout
header("Location: index.php");
exit();
?>
