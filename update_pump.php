<?php
include 'db_config.php';
session_start();

if (isset($_POST['status']) && isset($_SESSION['user_phone'])) {
    $status = $_POST['status'];
    $phone = $_SESSION['user_phone'];
    
    // تسجيل العملية في جدول sensor_logs
    $sql = "INSERT INTO sensor_logs (user_phone, pump_status) VALUES ('$phone', '$status')";
    if ($conn->query($sql)) {
        echo "Success";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>