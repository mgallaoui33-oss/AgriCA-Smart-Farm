<?php
session_start();
include 'db_config.php';

// حماية الصفحة: إذا لم يسجل الدخول يعود لـ index.php
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

$user_name = $_SESSION['user_name'];

// جلب آخر حالة للسنطاج من القاعدة (إذا كنت تريد تسجيل الحالات)
// سنفترض وجود جدول اسمه sensor_logs سجلنا فيه العمليات
$query = "SELECT * FROM sensor_logs ORDER BY log_time DESC LIMIT 5";
$logs = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم Agri-Tech | مالك</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="manifest" href="manifest.json">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#d32f2f">
    <style>
        :root { --primary: #22c55e; --bg: #020617; --card: #1e293b; --text: #f8fafc; }
        body { background: var(--bg); color: var(--text); font-family: 'Segoe UI', Tahoma, Geneva, sans-serif; margin: 0; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
        
        /* Header Area */
        header { padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; background: rgba(30, 41, 59, 0.5); backdrop-filter: blur(10px); border-bottom: 1px solid #334155; }
        .logo { font-size: 24px; font-weight: bold; color: var(--primary); }
        .user-info { display: flex; align-items: center; gap: 15px; }

        /* Main Dashboard */
        .container { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; padding: 30px; flex-grow: 1; }
        
        .main-panel { display: flex; flex-direction: column; gap: 20px; }
        .status-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        
        .card { background: var(--card); border-radius: 20px; padding: 25px; border: 1px solid #334155; position: relative; transition: 0.3s; }
        .card:hover { border-color: var(--primary); box-shadow: 0 0 20px rgba(34, 197, 94, 0.2); }

        /* Humidity Gauge */
        .humidity-box { text-align: center; }
        .gauge-container { width: 150px; height: 150px; border: 10px solid #334155; border-radius: 50%; margin: 20px auto; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: bold; position: relative; }
        .gauge-fill { position: absolute; top: -10px; left: -10px; width: 150px; height: 150px; border: 10px solid var(--primary); border-radius: 50%; clip-path: inset(50% 0 0 0); transition: 1s; }

        /* Pump Control */
        .pump-control { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 20px; }
        .pump-icon { font-size: 60px; color: #475569; transition: 0.5s; }
        .pump-active { color: var(--primary); text-shadow: 0 0 20px var(--primary); }
        
        .btn-toggle { background: var(--primary); color: white; border: none; padding: 15px 40px; border-radius: 50px; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-stop { background: #ef4444; }

        /* AI Camera Section */
        .ai-cam { grid-column: span 2; display: flex; align-items: center; gap: 20px; background: linear-gradient(90deg, #1e293b, #0f172a); }
        .cam-placeholder { width: 200px; height: 120px; background: #000; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--primary); }

        /* History Side */
        .side-panel { background: var(--card); border-radius: 20px; padding: 20px; border: 1px solid #334155; overflow-y: auto; }
        .log-item { padding: 15px; border-bottom: 1px solid #334155; display: flex; justify-content: space-between; font-size: 14px; }
    </style>
</head>
<body>

<header>
    <div style="font-size: 22px; font-weight: bold;">🌿 سانية <?php echo $user_name; ?> الذكية</div>
    <div>مرحباً بك، <strong><?php echo $user_name; ?></strong> | <a href="logout.php" style="color: #ef4444; text-decoration: none;">خروج</a></div>
</header>

<div class="container">
    <div class="main-panel">
        <div class="status-cards">
            <div class="card humidity-box">
                <h3>رطوبة التربة (هكتارين)</h3>
                <div class="gauge-container">
                    <div class="gauge-fill" id="gauge"></div>
                    <span id="hum-val">45%</span>
                </div>
                <p>الحالة: <span id="hum-status" style="color: var(--primary)">ممتازة</span></p>
            </div>

            <div class="card pump-control">
                <h3>التحكم في السنطاج</h3>
                <i class="fa-solid fa-faucet-drip pump-icon" id="p-icon"></i>
                <div id="p-label" style="font-weight: bold;">مغلق</div>
                <button class="btn-toggle" id="p-btn" onclick="togglePump()">تشغيل الري</button>
            </div>
        </div>

        <div class="card ai-cam" onclick="window.location.href='scanner.php'" style="cursor: pointer;">
    <div class="cam-placeholder"><i class="fa-solid fa-expand"></i></div>
    <div>
        <h4 style="color: var(--ca-red); margin-bottom: 5px;">تشخيص الذكاء الاصطناعي</h4>
        <p style="font-size: 16px;">اضغط هنا لفتح الكاميرا وفحص الفول</p>
    </div>
</div>
    </div>
    

    <div class="side-panel">
        <h3>سجل النشاط اليومي</h3>
        <div id="logs">
            <div class="log-item"><span>بدء الري</span> <span>10:30 AM</span></div>
            <div class="log-item"><span>رطوبة منخفضة (20%)</span> <span>09:15 AM</span></div>
            <div class="log-item"><span>فحص الكاميرا</span> <span>08:00 AM</span></div>
        </div>
    </div>
    
</div>



<script>
   let pOn = false;

function togglePump() {
    const btn = document.getElementById('p-btn');
    const stat = document.getElementById('p-status');
    const icon = document.getElementById('p-icon');
    
    // تغيير الحالة محلياً أولاً لسرعة الاستجابة
    pOn = !pOn;
    const statusText = pOn ? 'ON' : 'OFF';

    // إرسال الحالة إلى قاعدة البيانات
    fetch('update_pump.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'status=' + statusText
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "Success") {
            if(pOn) {
                btn.innerText = "إيقاف السنطاج";
                btn.classList.add('on');
                icon.classList.add('pump-active');
                stat.innerText = "الحالة: يعمل الآن...";
                Swal.fire('تم التشغيل', 'بدأ الري وتم تسجيل العملية', 'success');
            } else {
                btn.innerText = "تشغيل السنطاج";
                btn.classList.remove('on');
                icon.classList.remove('pump-active');
                stat.innerText = "الحالة: متوقف";
                Swal.fire('تم الإيقاف', 'توقف الري بنجاح', 'info');
            }
        } else {
            alert("حدث خطأ في الاتصال بالسيرفر");
            pOn = !pOn; // إعادة الحالة لما كانت عليه في حال الفشل
        }
    })
    .catch(err => console.error("Error:", err));
}

    // محاكاة تغير الرطوبة
    setInterval(() => {
        let val = Math.floor(Math.random() * (70 - 40) + 40);
        document.getElementById('hum-val').innerText = val + "%";
        document.getElementById('gauge').style.clipPath = `inset(${100 - val}% 0 0 0)`;
    }, 5000);
</script>

</body>
</html>
