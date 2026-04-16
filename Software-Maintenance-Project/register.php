<?php require_once 'db.php'; ?>
<?php
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    if ($password === $confirm) {
        $error = 'كلمات المرور غير متطابقة';
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $username, $email, $password);
        
        if ($stmt->execute()) {
            $success = 'تم إنشاء الحساب بنجاح! يمكنك الآن تسجيل الدخول';
        } else {
            $error = 'حدث خطأ أثناء إنشاء الحساب';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر همم | إنشاء حساب</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <div class="logo-text">متجر همم</div>
            <p>أنشئ حسابك الآن وابدأ التسوق</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>الاسم الكامل</label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="أدخل اسمك الكامل" required>
                </div>
            </div>

            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="أدخل بريدك الإلكتروني" required>
                </div>
            </div>

            <div class="form-group">
                <label>كلمة المرور</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="أدخل كلمة المرور" required>
                </div>
            </div>

            <div class="form-group">
                <label>تأكيد كلمة المرور</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm_password" placeholder="أعد إدخال كلمة المرور" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> إنشاء الحساب
            </button>
        </form>

        <div class="auth-footer">
            لديك حساب بالفعل؟ <a href="login.php">سجّل دخولك</a>
        </div>
        <div class="auth-footer" style="margin-top: 10px;">
            <a href="index.php"><i class="fas fa-home"></i> العودة للرئيسية</a>
        </div>
    </div>
</div>

</body>
</html>
