<?php require_once 'db.php'; ?>
<?php
$result = $conn->query("SELECT * FROM products ORDER BY price DESC LIMIT 12");
$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر همم | العروض والتخفيضات</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="logo"><i class="fas fa-store"></i><span>متجر همم</span></a>
    <ul class="nav-links">
        <li><a href="index.php"><i class="fas fa-home"></i> الرئيسية</a></li>
        <li><a href="products.php"><i class="fas fa-th-large"></i> المنتجات</a></li>
        <li><a href="offers.php" class="active"><i class="fas fa-tags"></i> العروض</a></li>
        <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> السلة</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="profile.php"><i class="fas fa-user"></i> ملفي</a></li>
            <li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> دخول</a></li>
            <li><a href="register.php" class="btn btn-accent btn-sm"><i class="fas fa-user-plus"></i> تسجيل</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div style="background: linear-gradient(135deg, #DC2626, #991B1B); padding: 50px 40px; text-align: center;">
    <h1 style="color: white; font-size: 36px; font-weight: 800;"><i class="fas fa-fire"></i> عروض حصرية!</h1>
    <p style="color: rgba(255,255,255,0.8); font-size: 18px;">خصم 25% على منتجات مختارة - العرض لفترة محدودة</p>
    <div style="display: inline-flex; gap: 30px; margin-top: 20px;">
        <div style="text-align: center;">
            <div style="font-size: 28px; font-weight: 900; color: white;">3</div>
            <div style="color: rgba(255,255,255,0.7); font-size: 12px;">أيام</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 28px; font-weight: 900; color: white;">12</div>
            <div style="color: rgba(255,255,255,0.7); font-size: 12px;">ساعة</div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 28px; font-weight: 900; color: white;">45</div>
            <div style="color: rgba(255,255,255,0.7); font-size: 12px;">دقيقة</div>
        </div>
    </div>
</div>

<section class="section">
    <div class="products-grid">
        <?php foreach ($products as $row):
            $discount = 0.25;
            $discounted_price = $row['price'] - ($row['price'] * $discount);
        ?>
        <div class="product-card">
            <div class="product-img-wrap">
                <span style="position: absolute; top: 12px; left: 12px; background: var(--danger); color: white; padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 700; z-index: 2;">-25%</span>
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-img" loading="lazy">
                <div class="quick-actions">
                    <form method="POST" action="cart.php" style="display:inline">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="action" value="add">
                        <button type="submit" class="action-btn" title="أضف للسلة">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="product-info">
                <div class="product-category"><?php echo getCategoryArabic($row['category']); ?></div>
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <div class="price-row">
                    <div>
                        <span class="price" style="color: var(--danger);"><?php echo number_format($discounted_price, 2); ?> <small>ر.س</small></span>
                        <span style="text-decoration: line-through; color: var(--gray-400); font-size: 14px; margin-right: 8px;"><?php echo number_format($row['price'], 2); ?></span>
                    </div>
                </div>
                <form method="POST" action="cart.php" style="margin-top: 14px;">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="action" value="add">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-cart-plus"></i> أضف للسلة
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<footer class="footer">
    <div class="footer-bottom" style="border: none; margin-top: 0;">
        <p>© 2026 جميع الحقوق محفوظة لـ <span>متجر همم</span></p>
    </div>
</footer>

</body>
</html>
