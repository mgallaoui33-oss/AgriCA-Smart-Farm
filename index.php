<?php
include 'db_config.php';
session_start();

$error = "";

// 1. منطق تسجيل الدخول (Login)
if (isset($_POST['login'])) {
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE phone = '$phone'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // التحقق من كلمة السر
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['user_name'] = $user['fullname'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "كلمة السر خاطئة!";
        }
    } else {
        $error = "هذا الرقم غير مسجل!";
    }
}

// 2. منطق إنشاء حساب جديد (Register)
if (isset($_POST['register'])) {
    $name = $_POST['fullname'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT id FROM users WHERE phone = '$phone'");
    if ($check->num_rows > 0) {
        $error = "هذا الرقم موجود مسبقاً!";
    } else {
        $sql = "INSERT INTO users (fullname, phone, farm_location, password) VALUES ('$name', '$phone', '$location', '$pass')";
        if ($conn->query($sql)) {
            $_SESSION['user_phone'] = $phone;
            $_SESSION['user_name'] = $name;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "حدث خطأ أثناء التسجيل.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الفلاحين | مشروع الثورة</title>
    <link rel="manifest" href="manifest.json">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#d32f2f">
    <style>
        :root { --primary: #2ecc71; --dark: #1a1a1a; }
        body { background: var(--dark); color: white; font-family: 'Arial', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .auth-container { background: #2c3e50; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); width: 350px; }
        h2 { text-align: center; color: var(--primary); }
        input { width: 100%; padding: 12px; margin: 10px 0; border-radius: 5px; border: none; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: var(--primary); border: none; color: white; font-weight: bold; cursor: pointer; border-radius: 5px; }
        .error-msg { color: #e74c3c; font-size: 14px; text-align: center; }
        .switch-btn { text-align: center; margin-top: 15px; font-size: 13px; cursor: pointer; color: #bdc3c7; }
    </style>
</head>
<body>

<div class="auth-container">
    <h2 id="form-title">إنشاء حساب فلاح</h2>
    <p class="error-msg"><?php echo $error; ?></p>
    
    <form id="auth-form" method="POST" onsubmit="return validateForm()">
        <div id="extra-fields">
            <input type="text" name="fullname" id="fullname" placeholder="الاسم واللقب">
            <input type="text" name="location" id="location" placeholder="مكان السانية (مثلاً: مجاز الباب)">
        </div>
        
        <input type="text" name="phone" id="phone" placeholder="رقم الهاتف (8 أرقام)">
        <input type="password" name="password" id="password" placeholder="كلمة السر (5 رموز فأكثر)">
        
        <button type="submit" name="register" id="submit-btn">تسجيل الحساب</button>
    </form>

    <div class="switch-btn" onclick="toggleForm()">لديك حساب؟ سجل الدخول من هنا</div>
</div>

<script>
function validateForm() {
    const phone = document.getElementById('phone').value;
    const pass = document.getElementById('password').value;
    const firstChar = phone.charAt(0);

    // شرط رقم الهاتف: 8 أرقام ويبدأ بـ 2، 4، 5، أو 9
    const validStart = ['2', '4', '5', '9'].includes(firstChar);
    if (phone.length !== 8 || isNaN(phone) || !validStart) {
        alert("خطأ: رقم الهاتف يجب أن يتكون من 8 أرقام ويبدأ بـ 2 أو 4 أو 5 أو 9");
        return false;
    }

    // شرط كلمة السر: 5 رموز على الأقل
    if (pass.length < 5) {
        alert("خطأ: كلمة السر يجب أن تكون 5 رموز على الأقل");
        return false;
    }
    return true;
}

function toggleForm() {
    const title = document.getElementById('form-title');
    const extra = document.getElementById('extra-fields');
    const btn = document.getElementById('submit-btn');
    const switchBtn = document.querySelector('.switch-btn');

    if (btn.name === "register") {
        title.innerText = "تسجيل الدخول";
        extra.style.display = "none";
        btn.name = "login";
        btn.innerText = "دخول";
        switchBtn.innerText = "ليس لديك حساب؟ أنشئ حساباً جديداً";
    } else {
        title.innerText = "إنشاء حساب فلاح";
        extra.style.display = "block";
        btn.name = "register";
        btn.innerText = "تسجيل الحساب";
        switchBtn.innerText = "لديك حساب؟ سجل الدخول من هنا";
    }
}
</script>

</body>
</html>
