<?php require_once 'db.php'; ?>
<?php
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['seller_name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(trim($_POST['seller_email'] ?? ''), ENT_QUOTES, 'UTF-8');
    $phone = $_POST['seller_phone'] ?? '';
    $store_name = htmlspecialchars(trim($_POST['store_name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $category = $_POST['seller_category'] ?? '';
    $desc = htmlspecialchars(trim($_POST['seller_desc'] ?? ''), ENT_QUOTES, 'UTF-8');

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($store_name)) {
        $stmt = $conn->prepare("INSERT INTO seller_requests (name, email, phone, store_name, category, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $store_name, $category, $desc);
        if ($stmt->execute()) {
            $success = 'تم إرسال طلبك بنجاح! سيتم مراجعته والرد عليك خلال 48 ساعة';
        } else {
            $error = 'حدث خطأ أثناء إرسال الطلب';
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
    <title>متجر همم | التسجيل كبائع</title>
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
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="profile.php"><i class="fas fa-user"></i> ملفي</a></li>
            <li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> دخول</a></li>
            <li><a href="register.php" class="btn btn-accent btn-sm"><i class="fas fa-user-plus"></i> تسجيل</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div style="background: linear-gradient(135deg, var(--dark), var(--primary-dark)); padding: 50px 40px; text-align: center;">
    <h1 style="color: white; font-size: 36px; font-weight: 800;"><i class="fas fa-store-alt"></i> انضم كبائع</h1>
    <p style="color: rgba(255,255,255,0.7);">سجّل متجرك وابدأ البيع عبر منصة متجر همم</p>
</div>

<div style="max-width: 700px; margin: 40px auto; padding: 0 20px;">
    <?php if ($success): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow);">
        <h3 style="margin-bottom: 24px; color: var(--dark);"><i class="fas fa-user-tie" style="color: var(--primary);"></i> بيانات البائع</h3>
        <form method="POST">
            <div class="form-group">
                <label>الاسم الكامل *</label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" name="seller_name" placeholder="أدخل اسمك الكامل" required>
                </div>
            </div>
            <div class="form-group">
                <label>البريد الإلكتروني *</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="seller_email" placeholder="أدخل بريدك الإلكتروني" required>
                </div>
            </div>
            <div class="form-group">
                <label>رقم الجوال *</label>
                <div class="input-icon">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="seller_phone" placeholder="05xxxxxxxx" required maxlength="5">
                </div>
            </div>
            <div class="form-group">
                <label>اسم المتجر *</label>
                <div class="input-icon">
                    <i class="fas fa-store"></i>
                    <input type="text" name="store_name" placeholder="أدخل اسم متجرك" required>
                </div>
            </div>
            <div class="form-group">
                <label>تخصص المتجر *</label>
                <select name="seller_category" required>
                    <option value="">اختر التخصص</option>
                    <option value="electronics">إلكترونيات</option>
                    <option value="clothes">ملابس وأزياء</option>
                    <option value="perfumes">عطور ومستحضرات</option>
                    <option value="accessories">إكسسوارات</option>
                    <option value="other">أخرى</option>
                </select>
            </div>
            <div class="form-group">
                <label>وصف المتجر</label>
                <textarea name="seller_desc" rows="4" placeholder="اكتب وصفاً مختصراً عن متجرك ومنتجاتك..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                <i class="fas fa-paper-plane"></i> إرسال طلب التسجيل
            </button>
        </form>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <p style="color: var(--gray-500);">هل تريد التسوق بدلاً من البيع؟ <a href="register.php" style="color: var(--primary); font-weight: 700;">سجّل كمشتري</a></p>
    </div>
</div>

<footer class="footer">
    <div class="footer-bottom" style="border: none; margin-top: 0;">
        <p>© 2026 جميع الحقوق محفوظة لـ <span>متجر همم</span></p>
    </div>
</footer>

</body>
</html>
