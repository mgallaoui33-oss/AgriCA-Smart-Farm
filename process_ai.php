<?php
session_start();
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['captured_image'])) {
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir);
    
    $filename = time() . ".jpg";
    $target_file = $upload_dir . $filename;

    if (move_uploaded_file($_FILES['captured_image']['tmp_name'], $target_file)) {
        // استدعاء البايثون (تأكد من كتابة python أو python3 حسب جهازك)
        $command = "python ai_brain.py " . escapeshellarg($target_file);
        $output = shell_exec($command);
        
        // إرجاع النتيجة لـ AJAX
        echo $output;
    } else {
        echo "مشكل في تحميل التصويرة.";
    }
}
?>