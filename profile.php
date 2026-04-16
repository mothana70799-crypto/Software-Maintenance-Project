<?php require_once 'db.php'; ?>
<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';
$user = null;

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(trim($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8');

    if (!empty($username) && !empty($email)) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $email, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $success = 'تم تحديث البيانات بنجاح';
            $user['username'] = $username;
            $user['email'] = $email;
        } else {
            $error = 'حدث خطأ أثناء التحديث';
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
    <title>متجر همم | ملفي الشخصي</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="logo"><i class="fas fa-store"></i><span>متجر همم</span></a>
    <ul class="nav-links">
        <li><a href="index.php"><i class="fas fa-home"></i> الرئيسية</a></li>
        <li><a href="products.php"><i class="fas fa-th-large"></i> المنتجات</a></li>
        <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> السلة</a></li>
        <li><a href="profile.php" class="active"><i class="fas fa-user"></i> ملفي</a></li>
        <li><a href="wishlist.php"><i class="fas fa-heart"></i> المفضلة</a></li>
        <li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
    </ul>
</nav>

<div style="background: linear-gradient(135deg, var(--dark), var(--primary-dark)); padding: 50px 40px; text-align: center;">
    <h1 style="color: white; font-size: 36px; font-weight: 800;"><i class="fas fa-user-circle"></i> ملفي الشخصي</h1>
    <p style="color: rgba(255,255,255,0.7);">عرض وتعديل بياناتك الشخصية</p>
</div>

<div style="max-width: 700px; margin: 40px auto; padding: 0 20px;">
    <?php if ($success): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($user): ?>
    <div style="background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow);">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--accent)); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 32px; color: white;">
                <i class="fas fa-user"></i>
            </div>
            <h2 style="color: var(--dark);"><?php echo htmlspecialchars($user['username']); ?></h2>
            <p style="color: var(--gray-500);"><?php echo $user['role'] === 'admin' ? 'مدير النظام' : 'مشتري'; ?></p>
        </div>

        <form method="GET">
            <div class="form-group">
                <label>اسم المستخدم</label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>نوع الحساب</label>
                <div class="input-icon">
                    <i class="fas fa-shield-alt"></i>
                    <input type="text" value="<?php echo $user['role'] === 'admin' ? 'مدير' : 'مشتري'; ?>" disabled>
                </div>
            </div>
            <div class="form-group">
                <label>تاريخ التسجيل</label>
                <div class="input-icon">
                    <i class="fas fa-calendar"></i>
                    <input type="text" value="<?php echo $user['created_at']; ?>" disabled>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 10px;">
                <i class="fas fa-save"></i> حفظ التعديلات
            </button>
        </form>
    </div>

    <div style="display: flex; gap: 12px; margin-top: 20px;">
        <a href="track_order.php" class="btn btn-secondary btn-lg" style="flex: 1; text-align: center;">
            <i class="fas fa-box"></i> طلباتي
        </a>
        <a href="wishlist.php" class="btn btn-secondary btn-lg" style="flex: 1; text-align: center;">
            <i class="fas fa-heart"></i> المفضلة
        </a>
    </div>
    <?php endif; ?>
</div>

<footer class="footer">
    <div class="footer-bottom" style="border: none; margin-top: 0;">
        <p>© 2026 جميع الحقوق محفوظة لـ <span>متجر همم</span></p>
    </div>
</footer>

</body>
</html>
