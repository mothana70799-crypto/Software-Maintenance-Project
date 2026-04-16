<?php require_once 'db.php'; ?>
<?php
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $product_id = intval($_POST['product_id'] ?? 0);
        if ($product_id > 0 && !in_array($product_id, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'][] = $product_id;
        }
        header("Location: wishlist.php");
        exit;
    }

    if ($action === 'remove') {
        $wish_id = intval($_POST['product_id'] ?? 0);
        $_SESSION['wishlist'] = array_values(array_filter($_SESSION['wishlist'], function($id) use ($wish_id) {
            return $id !== $wish_id;
        }));
        header("Location: wishlist.php");
        exit;
    }
}

$wishlist_items = [];
if (!empty($_SESSION['wishlist'])) {
    $ids = implode(',', array_map('intval', $_SESSION['wishlist']));
    $result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $wishlist_items[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر همم | المفضلة</title>
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
        <li><a href="wishlist.php" class="active"><i class="fas fa-heart"></i> المفضلة</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="profile.php"><i class="fas fa-user"></i> ملفي</a></li>
            <li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> دخول</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div style="background: linear-gradient(135deg, var(--dark), var(--primary-dark)); padding: 50px 40px; text-align: center;">
    <h1 style="color: white; font-size: 36px; font-weight: 800;"><i class="fas fa-heart"></i> قائمة المفضلة</h1>
    <p style="color: rgba(255,255,255,0.7);">المنتجات التي أعجبتك وحفظتها لوقت لاحق</p>
</div>

<section class="section">
    <?php if (empty($wishlist_items)): ?>
    <div class="empty-cart">
        <i class="fas fa-heart" style="font-size: 60px; color: var(--gray-300); display: block; margin-bottom: 16px;"></i>
        <h3>قائمة المفضلة فارغة</h3>
        <p>لم تضف أي منتج للمفضلة بعد</p>
        <a href="products.php" class="btn btn-primary btn-lg"><i class="fas fa-shopping-bag"></i> تصفح المنتجات</a>
    </div>
    <?php else: ?>
    <div class="products-grid">
        <?php foreach ($wishlist_items as $item): ?>
        <div class="product-card">
            <div class="product-img-wrap">
                <span class="badge" style="background: var(--accent); color: white; padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 700; position: absolute; top: 12px; right: 12px;">❤️ مفضل</span>
                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-img">
            </div>
            <div class="product-info">
                <div class="product-category"><?php echo getCategoryArabic($item['category']); ?></div>
                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                <p class="product-desc"><?php echo htmlspecialchars($item['description']); ?></p>
                <div class="price-row">
                    <div class="price"><?php echo number_format($item['price'], 2); ?> <small>ر.س</small></div>
                </div>
                <div style="display: flex; gap: 8px; margin-top: 14px;">
                    <form method="POST" action="cart.php" style="flex: 1;">
                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fas fa-cart-plus"></i> أضف للسلة</button>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                        <button type="submit" class="btn btn-danger"><i class="fas fa-heart-broken"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
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
