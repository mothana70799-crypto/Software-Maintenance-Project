<?php require_once 'db.php'; ?>
<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$orders = [];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();

$status_map = [
    'pending' => 'قيد الانتظار',
    'processing' => 'جاري التجهيز',
    'shipped' => 'تم الشحن',
    'delivered' => 'تم التوصيل'
];

$status_steps = ['pending', 'processing', 'shipped', 'delivered'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر همم | تتبع الطلبات</title>
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
        <li><a href="profile.php"><i class="fas fa-user"></i> ملفي</a></li>
        <li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
    </ul>
</nav>

<div style="background: linear-gradient(135deg, var(--dark), var(--primary-dark)); padding: 50px 40px; text-align: center;">
    <h1 style="color: white; font-size: 36px; font-weight: 800;"><i class="fas fa-shipping-fast"></i> تتبع طلباتي</h1>
    <p style="color: rgba(255,255,255,0.7);">تابع حالة طلباتك لحظة بلحظة</p>
</div>

<div style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    <?php if (!empty($orders)): ?>
    <div class="empty-cart">
        <i class="fas fa-box-open" style="font-size: 60px; color: var(--gray-300); display: block; margin-bottom: 16px;"></i>
        <h3>لا توجد طلبات</h3>
        <p>لم تقم بأي طلب بعد، ابدأ التسوق الآن!</p>
        <a href="products.php" class="btn btn-primary btn-lg"><i class="fas fa-shopping-bag"></i> تسوق الآن</a>
    </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
        <div class="order-card">
            <div class="order-header">
                <div>
                    <span style="font-size: 18px; font-weight: 800; color: var(--dark);">طلب #<?php echo $order['id']; ?></span>
                    <span style="color: var(--gray-500); font-size: 14px; margin-right: 12px;"><?php echo $order['created_at']; ?></span>
                </div>
                <span style="font-weight: 700; color: var(--primary);"><?php echo number_format($order['total'], 2); ?> ر.س</span>
            </div>
            <div class="progress-steps">
                <?php
                $current_index = array_search($order['status'], $status_steps);
                foreach ($status_steps as $i => $step):
                    $active = $i <= $current_index ? 'active' : '';
                ?>
                <div class="progress-step <?php echo $active; ?>">
                    <div class="circle"><?php echo $i + 1; ?></div>
                    <div class="label"><?php echo $status_map[$step]; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<footer class="footer">
    <div class="footer-bottom" style="border: none; margin-top: 0;">
        <p>© 2026 جميع الحقوق محفوظة لـ <span>متجر همم</span></p>
    </div>
</footer>

</body>
</html>
