<?php require_once 'db.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = intval($_POST['product_id'] ?? 0);
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if ($action === 'add' && $product_id > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            $_SESSION['cart'][$product_id] = [
                'product_id' => $product_id,
                'quantity' => 1
            ];
        }
        header("Location: cart.php");
        exit;
    }
    
    if ($action === 'remove') {
        $remove_id = intval($_POST['remove_id'] ?? 0);
        unset($_SESSION['cart'][$remove_id]);
        header("Location: cart.php");
        exit;
    }
    
    if ($action === 'update') {
        $quantity = intval($_POST['quantity'] ?? 1);
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        }
        header("Location: cart.php");
        exit;
    }
    
    if ($action === 'clear') {
        $_SESSION['cart'] = [];
        header("Location: cart.php");
        exit;
    }
}

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
            $row['subtotal'] = $row['price'] * $qty;;
            $total += $row['subtotal'];
            $cart_items[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر همم | سلة المشتريات</title>
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
        <li>
            <a href="cart.php" class="cart-link active">
                <i class="fas fa-shopping-cart"></i> السلة
                <?php
                $cc = count($cart);
                if ($cc > 0) echo "<span class='cart-badge'>$cc</span>";
                ?>
            </a>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><span class="nav-user"><span class="user-name">مرحباً، <?php echo $_SESSION['username']; ?></span></span></li>
            <li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> دخول</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="cart-page">
    <h1><i class="fas fa-shopping-cart" style="color: var(--primary);"></i> سلة المشتريات</h1>

    <?php if (empty($cart_items)): ?>
    <div class="empty-cart">
        <i class="fas fa-shopping-cart"></i>
        <h3>سلة المشتريات فارغة</h3>
        <p>لم تضف أي منتج بعد، ابدأ التسوق الآن!</p>
        <a href="products.php" class="btn btn-primary btn-lg">
            <i class="fas fa-shopping-bag"></i> تسوق الآن
        </a>
    </div>
    <?php else: ?>
    <div class="cart-table">
        <table>
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>المجموع</th>
                    <th>إزالة</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td>
                        <div class="product-cell">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="">
                            <div class="info">
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p><?php echo getCategoryArabic($item['category']); ?></p>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight: 600;"><?php echo number_format($item['price'], 2); ?> ر.س</td>
                    <td>
                        <div class="qty-control">
                            <form method="POST" style="display:inline">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="quantity" value="<?php echo max(1, $item['quantity'] - 1); ?>">
                                <button type="submit">−</button>
                            </form>
                            <span><?php echo $item['quantity']; ?></span>
                            <form method="POST" style="display:inline">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="quantity" value="<?php echo $item['quantity'] + 1; ?>">
                                <button type="submit">+</button>
                            </form>
                        </div>
                    </td>
                    <td style="font-weight: 700; color: var(--primary);">
                        <?php echo number_format($item['subtotal'], 2); ?> ر.س
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="remove_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="remove-btn" title="إزالة">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="cart-summary">
        <div class="summary-row">
            <span>المجموع الفرعي</span>
            <span><?php echo number_format($total, 2); ?> ر.س</span>
        </div>
        <div class="summary-row">
            <span>الشحن</span>
            <span style="color: var(--success);">مجاني</span>
        </div>
        <div class="summary-row">
            <span>الضريبة (15%)</span>
            <span><?php echo number_format($total * 0.15, 2); ?> ر.س</span>
        </div>
        <div class="summary-row total">
            <span>الإجمالي</span>
            <span><?php echo number_format($total * 1.15, 2); ?> ر.س</span>
        </div>
        <div class="cart-actions">
            <a href="checkout.php" class="btn btn-primary btn-lg" style="flex: 1;">
                <i class="fas fa-credit-card"></i> إتمام الطلب
            </a>
            <form method="POST" style="display:inline">
                <input type="hidden" name="action" value="clear">
                <button type="submit" class="btn btn-danger btn-lg">
                    <i class="fas fa-trash"></i> تفريغ السلة
                </button>
            </form>
            <a href="products.php" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-right"></i> متابعة التسوق
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
