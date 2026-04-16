<?php require_once 'db.php'; ?>
<?php
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['contact_name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(trim($_POST['contact_email'] ?? ''), ENT_QUOTES, 'UTF-8');
    $subject = htmlspecialchars(trim($_POST['contact_subject'] ?? ''), ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars(trim($_POST['contact_message'] ?? ''), ENT_QUOTES, 'UTF-8');

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        if ($stmt->execute()) {
            $success = 'تم إرسال رسالتك بنجاح! سنرد عليك في أقرب وقت';
        } else {
            $error = 'حدث خطأ أثناء إرسال الرسالة';
        }
        $stmt->close();
    } else {
        $error = 'يرجى تعبئة جميع الحقول المطلوبة';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر همم | تواصل معنا</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="logo"><i class="fas fa-store"></i><span>متجر همم</span></a>
    <ul class="nav-links">
        <li><a href="index.php"><i class="fas fa-home"></i> الرئيسية</a></li>
        <li><a href="products.php"><i class="fas fa-th-large"></i> المنتجات</a></li>
        <li><a href="offers.php"><i class="fas fa-tags"></i> العروض</a></li>
        <li><a href="contact.php" class="active"><i class="fas fa-headset"></i> تواصل معنا</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="profile.php"><i class="fas fa-user"></i> ملفي</a></li>
            <li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> دخول</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div style="background: linear-gradient(135deg, var(--dark), var(--primary-dark)); padding: 50px 40px; text-align: center;">
    <h1 style="color: white; font-size: 36px; font-weight: 800;"><i class="fas fa-headset"></i> تواصل معنا</h1>
    <p style="color: rgba(255,255,255,0.7);">نحن هنا لمساعدتك! لا تتردد في التواصل معنا</p>
</div>

<div style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <div>
            <?php if ($success): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
            <?php endif; ?>

            <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow);">
                <h3 style="margin-bottom: 24px; color: var(--dark);"><i class="fas fa-envelope" style="color: var(--primary);"></i> أرسل لنا رسالة</h3>
                <form method="POST">
                    <div class="form-group">
                        <label>الاسم الكامل *</label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" name="contact_name" placeholder="أدخل اسمك" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>البريد الإلكتروني *</label>
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="contact_email" placeholder="أدخل بريدك الإلكتروني" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>الموضوع</label>
                        <div class="input-icon">
                            <i class="fas fa-tag"></i>
                            <input type="text" name="contact_subject" placeholder="موضوع الرسالة">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>الرسالة *</label>
                        <textarea name="contact_msg" rows="5" placeholder="اكتب رسالتك هنا..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                        <i class="fas fa-paper-plane"></i> إرسال الرسالة
                    </button>
                </form>
            </div>
        </div>

        <div>
            <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow); margin-bottom: 20px;">
                <h3 style="margin-bottom: 24px; color: var(--dark);"><i class="fas fa-info-circle" style="color: var(--primary);"></i> معلومات التواصل</h3>
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: #EDE9FE; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h4 style="color: var(--dark); margin-bottom: 4px;">العنوان</h4>
                            <p style="color: var(--gray-500); font-size: 14px;">الرياض، المملكة العربية السعودية</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: #D1FAE5; color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h4 style="color: var(--dark); margin-bottom: 4px;">الهاتف</h4>
                            <p style="color: var(--gray-500); font-size: 14px;">+966 50 000 0000</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: #FEF3C7; color: var(--warning); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h4 style="color: var(--dark); margin-bottom: 4px;">البريد الإلكتروني</h4>
                            <p style="color: var(--gray-500); font-size: 14px;">info@himam.com</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: #FCE7F3; color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h4 style="color: var(--dark); margin-bottom: 4px;">ساعات العمل</h4>
                            <p style="color: var(--gray-500); font-size: 14px;">السبت - الخميس: 9 صباحاً - 10 مساءً</p>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow);">
                <h3 style="margin-bottom: 16px; color: var(--dark);"><i class="fas fa-question-circle" style="color: var(--secondary);"></i> أسئلة شائعة</h3>
                <div style="margin-bottom: 16px;">
                    <h4 style="color: var(--dark); font-size: 15px; margin-bottom: 4px;">كم مدة التوصيل؟</h4>
                    <p style="color: var(--gray-500); font-size: 14px;">التوصيل خلال 24-48 ساعة داخل المملكة</p>
                </div>
                <div style="margin-bottom: 16px;">
                    <h4 style="color: var(--dark); font-size: 15px; margin-bottom: 4px;">هل يوجد سياسة إرجاع؟</h4>
                    <p style="color: var(--gray-500); font-size: 14px;">نعم، يمكنك الإرجاع خلال 30 يوم</p>
                </div>
                <div>
                    <h4 style="color: var(--dark); font-size: 15px; margin-bottom: 4px;">ما طرق الدفع المتاحة؟</h4>
                    <p style="color: var(--gray-500); font-size: 14px;">مدى، فيزا، ماستركارد، والدفع عند الاستلام</p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="footer-bottom" style="border: none; margin-top: 0;">
        <p>© 2026 جميع الحقوق محفوظة لـ <span>متجر همم</span></p>
    </div>
</footer>

</body>
</html>
