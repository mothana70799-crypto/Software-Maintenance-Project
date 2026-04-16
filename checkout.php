<?php require_once 'db.php'; ?>
<?php
$error = '';
$success = '';

$cart = $_SESSION['cart'] ?? [];
$cart_items = [];
$total = 0;

if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $qty = $cart[$row['id']]['quantity'];
            $row['quantity'] = $qty;
            $row['subtotal'] = $row['price'] * $qty;
            $total += $row['subtotal'];
            $cart_items[] = $row;
        }
    }
}

$grand_total = $total + 15;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    
    if (!empty($name) && !empty($phone) && !empty($address)) {
        $success = 'تم استلام طلبك بنجاح! سيتم التواصل معك قريباً';
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
    <title>متجر همم | إتمام الطلب</title>
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
        <li><a href="products.php"><i class="fas fa-th-large"></i> المنتجات</a></li>
        <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> السلة</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> دخول</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="checkout-page">
    <h1><i class="fas fa-credit-card" style="color: var(--primary);"></i> إتمام الطلب</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>

    <?php if (empty($cart_items) && !$success): ?>
    <div class="empty-cart">
        <i class="fas fa-shopping-cart"></i>
        <h3>السلة فارغة</h3>
        <p>أضف منتجات إلى السلة أولاً</p>
        <a href="products.php" class="btn btn-primary btn-lg">تسوق الآن</a>
    </div>
    <?php else: ?>
    <div class="checkout-grid">
        <div class="checkout-form">
            <h3><i class="fas fa-truck"></i> بيانات الشحن</h3>
            <form method="POST">
                <div class="form-group">
                    <label>الاسم الكامل *</label>
                    <input type="text" name="name" placeholder="أدخل اسمك الكامل" required>
                </div>
                <div class="form-group">
                    <label>رقم الجوال *</label>
                    <input type="tel" name="phone" placeholder="05xxxxxxxx" required>
                </div>
                <div class="form-group">
                    <label>المدينة *</label>
                    <select name="city" required>
                        <option value="">اختر المدينة</option>
                        <option value="riyadh">الرياض</option>
                        <option value="jeddah">جدة</option>
                        <option value="makkah">مكة المكرمة</option>
                        <option value="madinah">المدينة المنورة</option>
                        <option value="dammam">الدمام</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>العنوان التفصيلي *</label>
                    <textarea name="address" rows="3" placeholder="الحي، الشارع، رقم المبنى..." required></textarea>
                </div>
                <div class="form-group">
                    <label>ملاحظات إضافية</label>
                    <textarea name="notes" rows="2" placeholder="أي ملاحظات خاصة بالتوصيل..."></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 10px;">
                    <i class="fas fa-check-circle"></i> تأكيد الطلب ودفع <?php echo number_format($grand_total, 2); ?> ر.س
                </button>
            </form>
        </div>

        <div class="order-summary-card">
            <h3><i class="fas fa-receipt"></i> ملخص الطلب</h3>
            
            <?php foreach ($cart_items as $item): ?>
            <div class="order-item">
                <div class="item-name">
                    <?php echo htmlspecialchars($item['name']); ?> 
                    <small style="color: var(--gray-500);">× <?php echo $item['quantity']; ?></small>
                </div>
                <div class="item-price"><?php echo number_format($item['subtotal'], 2); ?> ر.س</div>
            </div>
            <?php endforeach; ?>

            <div style="margin-top: 20px; padding-top: 16px; border-top: 2px solid var(--gray-200);">
                <div class="summary-row" style="display: flex; justify-content: space-between; padding: 6px 0;">
                    <span>المجموع الفرعي</span>
                    <span><?php echo number_format($total, 2); ?> ر.س</span>
                </div>
                <div class="summary-row" style="display: flex; justify-content: space-between; padding: 6px 0;">
                    <span>الشحن</span>
                    <span style="color: var(--success);">مجاني</span>
                </div>
                <div class="summary-row" style="display: flex; justify-content: space-between; padding: 6px 0;">
                    <span>الضريبة (15%)</span>
                    <span><?php echo number_format($total * 0.15, 2); ?> ر.س</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 14px 0 0; margin-top: 10px; border-top: 2px solid var(--gray-200); font-size: 20px; font-weight: 800; color: var(--primary);">
                    <span>الإجمالي</span>
                    <span><?php echo number_format($grand_total, 2); ?> ر.س</span>
                </div>
            </div>
            
            <a href="cart.php" class="btn btn-secondary" style="width: 100%; margin-top: 16px;">
                <i class="fas fa-arrow-right"></i> العودة للسلة
            </a>
        </div>
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
