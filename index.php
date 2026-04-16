<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>متجر همم | الرئيسية</title>
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
        <li><a href="index.php" class="active"><i class="fas fa-home"></i> الرئيسية</a></li>
        <li><a href="products.php"><i class="fas fa-th-large"></i> المنتجات</a></li>
        <li>
            <a href="cart.php" class="cart-link">
                <i class="fas fa-shopping-cart"></i> السلة
                <?php
                $cart_count = 0;
                if (isset($_SESSION['cart'])) {
                    $cart_count = count($_SESSION['cart']);
                }
                if ($cart_count > 0) echo "<span class='cart-badge'>$cart_count</span>";
                ?>
            </a>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><span class="nav-user"><span class="user-name">مرحباً، <?php echo $_SESSION['username']; ?></span></span></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a></li>
            <?php endif; ?>
            <li><a href="?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> دخول</a></li>
            <li><a href="register.php" class="btn btn-accent btn-sm"><i class="fas fa-user-plus"></i> تسجيل</a></li>
        <?php endif; ?>
    </ul>
</nav>

<?php
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<section class="hero">
    <div class="hero-content">
        <h1>مرحباً بك في <span>متجر همم</span></h1>
        <p>اكتشف أفضل المنتجات بأفضل الأسعار - تسوق الآن واستمتع بتجربة فريدة</p>
        <div class="hero-btns">
            <a href="cart.php" class="btn btn-accent btn-lg">
                <i class="fas fa-shopping-bag"></i> تسوق الآن
            </a>
            <a href="#categories" class="btn btn-outline btn-lg" style="border-color: white; color: white;">
                <i class="fas fa-th-large"></i> تصفح الأقسام
            </a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="number">+500</div>
                <div class="label">منتج متنوع</div>
            </div>
            <div class="hero-stat">
                <div class="number">+1000</div>
                <div class="label">عميل سعيد</div>
            </div>
            <div class="hero-stat">
                <div class="number">4.9★</div>
                <div class="label">تقييم العملاء</div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="categories">
    <div class="section-header">
        <h2>تسوق حسب القسم</h2>
        <p>اختر القسم المناسب لك واكتشف أحدث المنتجات</p>
        <div class="line"></div>
    </div>
    <div class="categories-grid">
        <a href="products.php?cat=electronics" class="category-card cat-electronics">
            <div class="icon"><i class="fas fa-laptop"></i></div>
            <h3>إلكترونيات</h3>
            <p>أحدث الأجهزة الإلكترونية والذكية</p>
        </a>
        <a href="products.php?cat=clothes" class="category-card cat-clothes">
            <div class="icon"><i class="fas fa-tshirt"></i></div>
            <h3>ملابس</h3>
            <p>أزياء عصرية للرجال والنساء</p>
        </a>
        <a href="products.php?cat=perfumes" class="category-card cat-perfumes">
            <div class="icon"><i class="fas fa-spray-can"></i></div>
            <h3>عطور</h3>
            <p>عطور فاخرة وماركات عالمية</p>
        </a>
        <a href="products.php?cat=accessories" class="category-card cat-accessories">
            <div class="icon"><i class="fas fa-gem"></i></div>
            <h3>إكسسوارات</h3>
            <p>إكسسوارات راقية ومميزة</p>
        </a>
    </div>
</section>

<section class="section">
    <div class="section-header">
        <h2>أحدث المنتجات</h2>
        <p>تشكيلة مميزة من أحدث المنتجات لدينا</p>
        <div class="line"></div>
    </div>
    <div class="products-grid">
        <?php
        $result = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 8");
        while ($row = $result->fetch_assoc()):
        ?>
        <div class="product-card">
            <div class="product-img-wrap">
                <span class="badge badge-new">جديد</span>
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="product-img">
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
                <h3><?php echo $row['name']; ?></h3>
                <p class="product-desc"><?php echo $row['description']; ?></p>
                <div class="price-row">
                    <div class="price"><?php echo $row['price']; ?> <small>ر.س</small></div>
                    <div class="stock <?php echo $row['stock'] < 10 ? 'low' : ''; ?>">
                        <?php echo $row['stock'] < 10 ? 'كمية محدودة' : 'متوفر'; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <div style="text-align: center; margin-top: 40px;">
        <a href="products.php" class="btn btn-primary btn-lg">
            <i class="fas fa-th-large"></i> عرض جميع المنتجات
        </a>
    </div>
</section>

<section class="section" style="background: var(--white); max-width: 100%; padding: 60px 40px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div class="section-header">
            <h2>خدماتنا</h2>
            <p>استفد من جميع مميزات متجر همم</p>
            <div class="line"></div>
        </div>
        <div class="categories-grid" style="grid-template-columns: repeat(4, 1fr);">
            <a href="seller_register.php" class="feature-card">
                <div class="icon" style="background: #EDE9FE; color: var(--primary);"><i class="fas fa-store-alt"></i></div>
                <h3>سجّل كبائع</h3>
                <p>انضم كبائع وابدأ عرض منتجاتك</p>
            </a>
            <a href="wishlist.php" class="feature-card">
                <div class="icon" style="background: #FCE7F3; color: var(--accent);"><i class="fas fa-heart"></i></div>
                <h3>قائمة المفضلة</h3>
                <p>احفظ المنتجات التي أعجبتك</p>
            </a>
            <a href="track_order.php" class="feature-card">
                <div class="icon" style="background: #FEF3C7; color: var(--warning);"><i class="fas fa-shipping-fast"></i></div>
                <h3>تتبع طلباتك</h3>
                <p>تابع حالة طلباتك لحظة بلحظة</p>
            </a>
            <a href="offers.php" class="feature-card">
                <div class="icon" style="background: #FEE2E2; color: var(--danger);"><i class="fas fa-tags"></i></div>
                <h3>العروض والتخفيضات</h3>
                <p>اكتشف أقوى العروض الحصرية</p>
            </a>
            <a href="reviews.php" class="feature-card">
                <div class="icon" style="background: #D1FAE5; color: var(--success);"><i class="fas fa-star"></i></div>
                <h3>تقييمات العملاء</h3>
                <p>شارك رأيك وقيّم المنتجات</p>
            </a>
            <a href="search.php" class="feature-card">
                <div class="icon" style="background: #DBEAFE; color: var(--info);"><i class="fas fa-search"></i></div>
                <h3>البحث المتقدم</h3>
                <p>ابحث بسهولة عن أي منتج</p>
            </a>
            <a href="contact.php" class="feature-card">
                <div class="icon" style="background: #E0E7FF; color: #4338CA;"><i class="fas fa-headset"></i></div>
                <h3>تواصل معنا</h3>
                <p>نحن هنا لمساعدتك دائماً</p>
            </a>
            <a href="profile.php" class="feature-card">
                <div class="icon" style="background: #F3E8FF; color: #7C3AED;"><i class="fas fa-user-cog"></i></div>
                <h3>إدارة حسابك</h3>
                <p>عدّل بياناتك الشخصية</p>
            </a>
            <a href="compare.php" class="feature-card">
                <div class="icon" style="background: #FEF9C3; color: #CA8A04;"><i class="fas fa-balance-scale"></i></div>
                <h3>مقارنة المنتجات</h3>
                <p>قارن بين المنتجات واختر الأنسب</p>
            </a>
            <a href="coupons.php" class="feature-card">
                <div class="icon" style="background: #DCFCE7; color: #16A34A;"><i class="fas fa-ticket-alt"></i></div>
                <h3>كوبونات الخصم</h3>
                <p>استخدم الكوبونات للحصول على خصومات</p>
            </a>
            <a href="faq.php" class="feature-card">
                <div class="icon" style="background: #E0F2FE; color: #0284C7;"><i class="fas fa-question-circle"></i></div>
                <h3>الأسئلة الشائعة</h3>
                <p>إجابات على أكثر الأسئلة شيوعاً</p>
            </a>
        </div>
    </div>
</section>

<section class="section" style="background: var(--white); max-width: 100%; padding: 60px 40px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div class="section-header">
            <h2>لماذا تختار همم؟</h2>
            <p>نوفر لك تجربة تسوق لا مثيل لها</p>
            <div class="line"></div>
        </div>
        <div class="categories-grid">
            <div class="category-card">
                <div class="icon" style="background: #EDE9FE; color: var(--primary);"><i class="fas fa-shipping-fast"></i></div>
                <h3>شحن سريع</h3>
                <p>توصيل خلال 24-48 ساعة لجميع المناطق</p>
            </div>
            <div class="category-card">
                <div class="icon" style="background: #D1FAE5; color: var(--success);"><i class="fas fa-shield-alt"></i></div>
                <h3>دفع آمن</h3>
                <p>طرق دفع آمنة ومتعددة لراحتك</p>
            </div>
            <div class="category-card">
                <div class="icon" style="background: #FEF3C7; color: var(--warning);"><i class="fas fa-undo"></i></div>
                <h3>إرجاع مجاني</h3>
                <p>سياسة إرجاع مرنة خلال 30 يوم</p>
            </div>
            <div class="category-card">
                <div class="icon" style="background: #FCE7F3; color: var(--accent);"><i class="fas fa-headset"></i></div>
                <h3>دعم متواصل</h3>
                <p>خدمة عملاء على مدار الساعة</p>
            </div>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="footer-grid">
        <div class="footer-col">
            <h3>🛍️ متجر همم</h3>
            <p>متجر إلكتروني رائد يقدم أفضل المنتجات بأفضل الأسعار. نسعى لتوفير تجربة تسوق مميزة وفريدة لعملائنا الكرام.</p>
        </div>
        <div class="footer-col">
            <h3>روابط سريعة</h3>
            <ul>
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="products.php">المنتجات</a></li>
                <li><a href="offers.php">العروض</a></li>
                <li><a href="cart.php">سلة المشتريات</a></li>
                <li><a href="search.php">البحث</a></li>
                <li><a href="login.php">تسجيل الدخول</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h3>الخدمات</h3>
            <ul>
                <li><a href="seller_register.php">سجّل كبائع</a></li>
                <li><a href="wishlist.php">المفضلة</a></li>
                <li><a href="track_order.php">تتبع الطلبات</a></li>
                <li><a href="reviews.php">التقييمات</a></li>
                <li><a href="contact.php">تواصل معنا</a></li>
                <li><a href="profile.php">ملفي الشخصي</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h3>تواصل معنا</h3>
            <ul>
                <li><i class="fas fa-envelope"></i> info@himam.com</li>
                <li><i class="fas fa-phone"></i> +966 50 000 0000</li>
                <li><i class="fas fa-map-marker-alt"></i> الرياض، السعودية</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2026 جميع الحقوق محفوظة لـ <span>متجر همم</span> | صُنع بـ ❤️</p>
    </div>
</footer>

</body>
</html>
