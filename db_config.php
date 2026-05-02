<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "db_config"; // تأكد أن هذا هو نفس اسم القاعدة التي أنشأتها

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
?>