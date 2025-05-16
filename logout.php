<?php
# Initialize the session
session_start();

# ประกาศฟังก์ชัน redirect (เพิ่มส่วนนี้เข้าไปด้านบนหรือตรงนี้)
function redirect($url) {
    header("Location: $url");
    exit;
}

# Unset all session variables
$_SESSION = array();

# Destroy the session
session_destroy();

# Redirect to login page
redirect("./login.php");
?>
