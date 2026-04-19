<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر همم | المنتجات</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="logo">
        <i class="fas fa-store"></i>
        <span>متجر همم</span>
    </a>
    <ul class="nav-links">
        <li><a href="index.php"><i class="fas fa-home"></i> الرئيسية</a></li>
        <li><a href="products.php" class="active"><i class="fas fa-th-large"></i> المنتجات</a></li>
        <li>
            <a href="cart.php" class="cart-link">
                <i class="fas fa-shopping-cart"></i> السلة
                <?php
                $cart_count = 0;
                if (isset($_SESSION['cart'])) $cart_count = count($_SESSION['cart']);
                if ($cart_count > 0) echo "<span class='cart-badge'>$cart_count</span>";
                ?>
            </a>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><span class="nav-user"><span class="user-name">مرحباً، <?php echo $_SESSION['username']; ?></span></span></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a></li>
            <?php endif; ?>
            <li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> دخول</a></li>
            <li><a href="register.php" class="btn btn-accent btn-sm"><i class="fas fa-user-plus"></i> تسجيل</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div style="background: linear-gradient(135deg, var(--dark), var(--primary-dark)); padding: 50px 40px; text-align: center;">
    <h1 style="color: white; font-size: 36px; font-weight: 800; margin-bottom: 10px;">
        <?php
        $category = isset($_GET['cat']) ? $_GET['cat'] : '';
        if ($category) {
            echo 'قسم ' . getCategoryArabic($category);
        } else {
            echo 'جميع المنتجات';
        }
        ?>
    </h1>
    <p style="color: rgba(255,255,255,0.7); font-size: 16px;">اكتشف تشكيلتنا المميزة واختر ما يناسبك</p>
</div>

<section class="section">
    <div class="filters-bar">
        <a href="products.php" class="filter-btn <?php echo !$category ? 'active' : ''; ?>">الكل</a>
        <a href="products.php?cat=electronics" class="filter-btn <?php echo $category == 'electronics' ? 'active' : ''; ?>">
            <i class="fas fa-laptop"></i> إلكترونيات
        </a>
        <a href="products.php?cat=clothes" class="filter-btn <?php echo $category == 'clothes' ? 'active' : ''; ?>">
            <i class="fas fa-tshirt"></i> ملابس
        </a>
        <a href="products.php?cat=perfumes" class="filter-btn <?php echo $category == 'perfumes' ? 'active' : ''; ?>">
            <i class="fas fa-spray-can"></i> عطور
        </a>
        <a href="products.php?cat=accessories" class="filter-btn <?php echo $category == 'accessories' ? 'active' : ''; ?>">
            <i class="fas fa-gem"></i> إكسسوارات
        </a>
    </div>

    <div class="products-grid">
        <?php
        if ($category) {
            $sql = "SELECT * FROM products WHERE category = '" . $conn->real_escape_string($category) . "' ORDER BY created_at DESC";
        } else {
            $sql = "SELECT * FROM products ORDER BY created_at DESC";
        }
        
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <div class="product-card">
            <div class="product-img-wrap">
                <?php if (rand(0,2) == 0): ?>
                    <span class="badge badge-new">جديد</span>
                <?php elseif (rand(0,3) == 0): ?>
                    <span class="badge badge-sale">تخفيض</span>
                <?php endif; ?>
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
                        <?php echo $row['stock'] < 10 ? 'كمية محدودة (' . $row['stock'] . ')' : 'متوفر'; ?>
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
        <?php
            endwhile;
        else:
        ?>
        <div style="grid-column: 1/-1; text-align: center; padding: 60px;">
            <i class="fas fa-box-open" style="font-size: 60px; color: var(--gray-300); margin-bottom: 16px;"></i>
            <h3 style="color: var(--gray-600);">لا توجد منتجات في هذا القسم</h3>
            <a href="products.php" class="btn btn-primary" style="margin-top: 16px;">عرض جميع المنتجات</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<footer class="footer">
    <div class="footer-grid">
        <div class="footer-col">
            <h3>🛍️ متجر همم</h3>
            <p>متجر إلكتروني رائد يقدم أفضل المنتجات بأفضل الأسعار.</p>
        </div>
        <div class="footer-col">
            <h3>روابط سريعة</h3>
            <ul>
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="products.php">المنتجات</a></li>
                <li><a href="cart.php">سلة المشتريات</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h3>الأقسام</h3>
            <ul>
                <li><a href="products.php?cat=electronics">إلكترونيات</a></li>
                <li><a href="products.php?cat=clothes">ملابس</a></li>
                <li><a href="products.php?cat=perfumes">عطور</a></li>
                <li><a href="products.php?cat=accessories">إكسسوارات</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h3>تواصل معنا</h3>
            <ul>
                <li><i class="fas fa-envelope"></i> info@himam.com</li>
                <li><i class="fas fa-phone"></i> +966 50 000 0000</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2026 جميع الحقوق محفوظة لـ <span>متجر همم</span></p>
    </div>
</footer>

</body>
</html>
