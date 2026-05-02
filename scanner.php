<?php
session_start();
if (!isset($_SESSION['user_name'])) { header("Location: index.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>AI Scanner | تصوير أو اختيار صورة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { margin: 0; background: #0b0f19; font-family: 'Cairo', sans-serif; color: white; overflow: hidden; }
        .scanner-container { position: relative; width: 100vw; height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        
        /* منطقة العرض */
        #display-container { width: 90%; height: 60%; border: 2px dashed #ef4444; border-radius: 20px; overflow: hidden; position: relative; background: #000; }
        #video, #image-preview { width: 100%; height: 100%; object-fit: cover; }
        #image-preview { display: none; }

        /* أزرار التحكم */
        .controls { display: flex; gap: 20px; margin-top: 30px; }
        .btn-action { background: #1e293b; color: white; border: 2px solid #ef4444; padding: 15px 25px; border-radius: 15px; cursor: pointer; font-size: 18px; transition: 0.3s; }
        .btn-action:hover { background: #ef4444; }
        .btn-main { background: #ef4444; border: none; padding: 15px 50px; border-radius: 50px; font-weight: bold; font-size: 20px; }

        /* إخفاء input الـ file الأصلي */
        #file-input { display: none; }
        
        .back-btn { position: absolute; top: 20px; right: 20px; font-size: 30px; color: white; text-decoration: none; }
    </style>
</head>
<body>

<div class="scanner-container">
    <a href="dashboard.php" class="back-btn"><i class="fa-solid fa-circle-xmark"></i></a>

    <div id="display-container">
        <video id="video" autoplay playsinline></video>
        <img id="image-preview" src="" alt="Preview">
        <div style="position: absolute; width: 100%; height: 4px; background: #ef4444; top: 0; animation: scan 2s linear infinite; box-shadow: 0 0 15px #ef4444;" id="scan-line"></div>
    </div>

    <div class="controls">
        <button class="btn-action" onclick="document.getElementById('file-input').click()">
            <i class="fa-solid fa-images"></i> Galerie
        </button>
        <input type="file" id="file-input" accept="image/*" onchange="previewFile()">

        <button class="btn-action btn-main" onclick="startAIDiagnosis()">
            <i class="fa-solid fa-microscope"></i> حلل التصويرة
        </button>

        <button class="btn-action" onclick="restartCamera()">
            <i class="fa-solid fa-camera"></i> Camera
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const video = document.getElementById('video');
    const imagePreview = document.getElementById('image-preview');
    const fileInput = document.getElementById('file-input');

    // تشغيل الكاميرا عند البداية
    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then(stream => { video.srcObject = stream; })
            .catch(err => console.log("Camera error: ", err));
    }
    startCamera();

    // اختيار تصويرة من الـ Galerie
    function previewFile() {
        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                video.style.display = 'none';
                // إيقاف الكاميرا لتوفير الطاقة
                if (video.srcObject) {
                    video.srcObject.getTracks().forEach(track => track.stop());
                }
            }
            reader.readAsDataURL(file);
        }
    }

    // رجوع للكاميرا
    function restartCamera() {
        imagePreview.style.display = 'none';
        video.style.display = 'block';
        startCamera();
    }

    function startAIDiagnosis() {
        Swal.fire({
            title: 'قاعد نفحص في الورقة...',
            text: 'لحظة يا مالك تو يقلك الـ AI شنية المشكلة',
            timer: 3000,
            timerProgressBar: true,
            didOpen: () => { Swal.showLoading(); }
        }).then(() => {
            Swal.fire({
                title: 'التشخيص بالتونسي',
                text: 'يا مالك، التصويرة تقول  مريڨل، أما أعمل طلة على العروق لا تبدأ ناقصة كلسيوم.',
                icon: 'success',
                confirmButtonText: 'واضح، يرحم والديك'
            });
        });
    }
</script>

<style>
    @keyframes scan { 0% { top: 0; } 100% { top: 100%; } }
</style>

</body>
</html>