<?php require_once 'db.php'; ?>
<?php
$search = trim($_GET['q'] ?? '');
$results = [];

if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
    $search_param = "%" . $search . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر همم | البحث</title>
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

<div style="background: linear-gradient(135deg, var(--dark), var(--primary-dark)); padding: 50px 40px; text-align: center;">
    <h1 style="color: white; font-size: 36px; font-weight: 800;"><i class="fas fa-search"></i> البحث المتقدم</h1>
    <p style="color: rgba(255,255,255,0.7);">ابحث عن أي منتج تريده بسهولة</p>

    <form method="GET" style="max-width: 600px; margin: 30px auto 0;">
        <div style="display: flex; gap: 8px;">
            <div style="flex: 1; position: relative;">
<input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>"                    placeholder="اكتب اسم المنتج..."
                    style="width: 100%; padding: 14px 20px 14px 50px; border-radius: 50px; border: none; font-family: 'Tajawal'; font-size: 16px; box-shadow: var(--shadow-lg);">
                <i class="fas fa-search" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: var(--gray-400);"></i>
            </div>
            <button type="submit" class="btn btn-accent btn-lg" style="border-radius: 50px; padding: 14px 30px;">
                بحث
            </button>
        </div>
    </form>
</div>

<section class="section">
    <?php if (!empty($search)): ?>
        <div style="margin-bottom: 24px;">
            <h2 style="color: var(--dark); font-size: 22px;">
                نتائج البحث عن: <span style="color: var(--primary);">"<?php echo htmlspecialchars($search); ?>"</span>
                <span style="color: var(--gray-400); font-size: 16px;">(<?php echo count($results); ?> نتيجة)</span>
            </h2>
        </div>
    <?php endif; ?>

    <?php if (!empty($search) && empty($results)): ?>
    <div class="empty-cart">
        <i class="fas fa-search" style="font-size: 60px; color: var(--gray-300); display: block; margin-bottom: 16px;"></i>
        <h3>لا توجد نتائج</h3>
        <p>لم نجد منتجات مطابقة لبحثك، حاول البحث بكلمات مختلفة</p>
        <a href="products.php" class="btn btn-primary btn-lg" style="margin-top: 16px;"><i class="fas fa-th-large"></i> تصفح جميع المنتجات</a>
    </div>
    <?php elseif (!empty($results)): ?>
    <div class="products-grid">
        <?php foreach ($results as $row): ?>
        <div class="product-card">
            <div class="product-img-wrap">
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
                <p class="product-desc"><?php echo htmlspecialchars($row['description']); ?></p>
                <div class="price-row">
                    <div class="price"><?php echo number_format($row['price'], 2); ?> <small>ر.س</small></div>
                    <div class="stock <?php echo $row['stock'] < 10 ? 'low' : ''; ?>">
                        <?php echo $row['stock'] < 10 ? 'كمية محدودة' : 'متوفر'; ?>
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
    <?php elseif (empty($search)): ?>
    <div style="text-align: center; padding: 60px 0;">
        <i class="fas fa-search" style="font-size: 80px; color: var(--gray-200); margin-bottom: 20px; display: block;"></i>
        <h3 style="color: var(--gray-600); margin-bottom: 8px;">ابدأ البحث</h3>
        <p style="color: var(--gray-400);">اكتب اسم المنتج في شريط البحث أعلاه</p>
    </div>
    <?php endif; ?>
</section>

<footer class="footer">
    <div class="footer-bottom" style="border: none; margin-top: 0;">
        <p>© 2026 جميع الحقوق محفوظة لـ <span>متجر همم</span></p>
    </div>
</footer>

</body>
</html>
