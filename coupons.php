<?php require_once 'db.php'; ?>
<?php
$coupon_msg = '';
$coupon_type = '';
$discount_amount = 0;

$coupons_list = [
    'HIMAM10' => ['discount' => 10, 'type' => 'percent', 'min_order' => 100],
    'HIMAM25' => ['discount' => 25, 'type' => 'percent', 'min_order' => 500],
    'FREE50' => ['discount' => 50, 'type' => 'fixed', 'min_order' => 200],
    'WELCOME' => ['discount' => 15, 'type' => 'percent', 'min_order' => 0],
    'VIP100' => ['discount' => 100, 'type' => 'fixed', 'min_order' => 1000],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(trim($_POST['coupon_code'] ?? ''));

    if (empty($code)) {
        $coupon_msg = 'يرجى إدخال رمز الكوبون';
        $coupon_type = 'danger';
    } elseif (isset($coupons_list[$code])) {
        $coupon = $coupons_list[$code];
        if ($coupon['type'] === 'percent') {
            $coupon_msg = "كوبون صالح! خصم {$coupon['discount']}% على طلبك";
            $discount_amount = $coupon['discount'];
        } else {
            $coupon_msg = "كوبون صالح! خصم {$coupon['discount']} ر.س من طلبك";
            $discount_amount = $coupon['discount'];
        }
        $coupon_type = 'success';
    } else {
        $coupon_msg = 'رمز الكوبون غير صحيح أو منتهي الصلاحية';
        $coupon_type = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر همم | كوبونات الخصم</title>
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
        <li><a href="coupons.php" class="active"><i class="fas fa-ticket-alt"></i> الكوبونات</a></li>
        <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> السلة</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="profile.php"><i class="fas fa-user"></i> ملفي</a></li>
            <li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> دخول</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div style="background: linear-gradient(135deg, #059669, #047857); padding: 50px 40px; text-align: center;">
    <h1 style="color: white; font-size: 36px; font-weight: 800;"><i class="fas fa-ticket-alt"></i> كوبونات الخصم</h1>
    <p style="color: rgba(255,255,255,0.8); font-size: 18px;">استخدم كوبونات الخصم واحصل على أفضل الأسعار</p>
</div>

<div style="max-width: 800px; margin: 40px auto; padding: 0 20px;">

    <div style="background: white; padding: 30px; border-radius: var(--radius-lg); box-shadow: var(--shadow); margin-bottom: 30px;">
        <h3 style="margin-bottom: 20px; color: var(--dark);"><i class="fas fa-check-circle" style="color: var(--success);"></i> تحقق من كوبونك</h3>
        <?php if ($coupon_msg): ?>
            <div class="alert alert-<?php echo $coupon_type; ?>" style="margin-bottom: 16px;">
                <i class="fas fa-<?php echo $coupon_type === 'success' ? 'check-circle' : 'times-circle'; ?>"></i>
                <?php echo $coupon_msg; ?>
            </div>
        <?php endif; ?>
        <form method="POST" style="display: flex; gap: 12px;">
            <div class="input-icon" style="flex: 1;">
                <i class="fas fa-tag"></i>
                <input type="text" name="coupon_code" placeholder="أدخل رمز الكوبون..." style="text-transform: uppercase;">
            </div>
            <button type="submit" class="btn btn-primary" style="white-space: nowrap;">
                <i class="fas fa-check"></i> تحقق
            </button>
        </form>
    </div>

    <h3 style="margin-bottom: 20px; color: var(--dark);"><i class="fas fa-gift" style="color: var(--accent);"></i> الكوبونات المتاحة</h3>

    <div style="display: grid; gap: 16px;">
        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow); padding: 24px; display: flex; align-items: center; gap: 20px; border-right: 4px solid var(--primary);">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: #EDE9FE; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 900;">10%</div>
            <div style="flex: 1;">
                <h4 style="color: var(--dark); margin-bottom: 4px;">HIMAM10</h4>
                <p style="color: var(--gray-500); font-size: 14px;">خصم 10% على الطلبات فوق 100 ر.س</p>
            </div>
            <span style="background: var(--gray-50); padding: 6px 14px; border-radius: 50px; font-size: 12px; color: var(--success); font-weight: 700;">متاح</span>
        </div>

        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow); padding: 24px; display: flex; align-items: center; gap: 20px; border-right: 4px solid var(--accent);">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: #FCE7F3; color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 900;">25%</div>
            <div style="flex: 1;">
                <h4 style="color: var(--dark); margin-bottom: 4px;">HIMAM25</h4>
                <p style="color: var(--gray-500); font-size: 14px;">خصم 25% على الطلبات فوق 500 ر.س</p>
            </div>
            <span style="background: var(--gray-50); padding: 6px 14px; border-radius: 50px; font-size: 12px; color: var(--success); font-weight: 700;">متاح</span>
        </div>

        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow); padding: 24px; display: flex; align-items: center; gap: 20px; border-right: 4px solid var(--success);">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: #D1FAE5; color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 900;">50<small>ر.س</small></div>
            <div style="flex: 1;">
                <h4 style="color: var(--dark); margin-bottom: 4px;">FREE50</h4>
                <p style="color: var(--gray-500); font-size: 14px;">خصم 50 ر.س على الطلبات فوق 200 ر.س</p>
            </div>
            <span style="background: var(--gray-50); padding: 6px 14px; border-radius: 50px; font-size: 12px; color: var(--success); font-weight: 700;">متاح</span>
        </div>

        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow); padding: 24px; display: flex; align-items: center; gap: 20px; border-right: 4px solid var(--secondary);">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: #FEF3C7; color: var(--secondary); display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 900;">15%</div>
            <div style="flex: 1;">
                <h4 style="color: var(--dark); margin-bottom: 4px;">WELCOME</h4>
                <p style="color: var(--gray-500); font-size: 14px;">خصم ترحيبي 15% بدون حد أدنى للطلب</p>
            </div>
            <span style="background: var(--gray-50); padding: 6px 14px; border-radius: 50px; font-size: 12px; color: var(--success); font-weight: 700;">متاح</span>
        </div>

        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow); padding: 24px; display: flex; align-items: center; gap: 20px; border-right: 4px solid var(--danger);">
            <div style="width: 60px; height: 60px; border-radius: 12px; background: #FEE2E2; color: var(--danger); display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 900;">100<small>ر.س</small></div>
            <div style="flex: 1;">
                <h4 style="color: var(--dark); margin-bottom: 4px;">VIP100</h4>
                <p style="color: var(--gray-500); font-size: 14px;">خصم 100 ر.س للعملاء المميزين - طلبات فوق 1000 ر.س</p>
            </div>
            <span style="background: var(--gray-50); padding: 6px 14px; border-radius: 50px; font-size: 12px; color: var(--warning); font-weight: 700;">VIP</span>
        </div>
    </div>

    <div style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: var(--radius-lg); padding: 30px; margin-top: 30px; text-align: center; color: white;">
        <h3 style="margin-bottom: 10px;"><i class="fas fa-info-circle"></i> كيفية استخدام الكوبون</h3>
        <p style="opacity: 0.9;">انسخ رمز الكوبون وأدخله عند إتمام الطلب في صفحة الدفع للحصول على الخصم</p>
        <a href="products.php" class="btn btn-accent btn-lg" style="margin-top: 16px;">
            <i class="fas fa-shopping-bag"></i> تسوق الآن
        </a>
    </div>
</div>

<footer class="footer">
    <div class="footer-bottom" style="border: none; margin-top: 0;">
        <p>© 2026 جميع الحقوق محفوظة لـ <span>متجر همم</span></p>
    </div>
</footer>

</body>
</html>
