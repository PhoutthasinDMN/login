<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล - ควรย้ายไปไฟล์ .env ในอนาคต
define("DB_SERVER", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_NAME", "registered");

// เริ่มเชื่อมต่อฐานข้อมูล
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// ตรวจสอบการเชื่อมต่อ
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// ตั้งค่า charset เป็น utf8 เพื่อรองรับภาษาไทย
mysqli_set_charset($link, "utf8");

// ฟังก์ชันความปลอดภัยสำหรับการป้องกัน XSS
function escape_html($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// ฟังก์ชันสำหรับเปลี่ยนเส้นทาง (redirect) อย่างปลอดภัย
function redirect($url) {
    // ตรวจสอบว่าได้ส่ง header ไปแล้วหรือไม่
    if (!headers_sent()) {
        header("Location: " . $url);
        exit;
    } else {
        echo "<script>window.location.href='" . $url . "';</script>";
        exit;
    }
}
?>