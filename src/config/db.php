<?php
// Thông tin kết nối XAMPP mặc định
$host = 'localhost';
$dbname = 'technova';
$username = 'root';
$password = ''; // Mặc định XAMPP không có mật khẩu

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Bật chế độ báo lỗi exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>