// Linked to Jira Task: KAN-2
<?php require_once 'db.php'; ?>
<?php
$product1 = null;
$product2 = null;

$id1 = intval($_GET['p1'] ?? 0);
$id2 = intval($_GET['p2'] ?? 0);

if ($id1 > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id1);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) $product1 = $result->fetch_assoc();
    $stmt->close();
}

if ($id2 > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id2);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) $product2 = $result->fetch_assoc();
    $stmt->close();
}

$all_products = [];
$result = $conn->query("SELECT id, name FROM products ORDER BY name ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $all_products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر همم | مقارنة المنتجات</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="logo"><i class="fas fa-store"></i><span>متجر همم</span></a>
    <ul class="nav-links">
        <li><a href="index.php"><i class="fas fa-home"></i> الرئيسية</a></li>
        <li><a href="products.php"><i class="fas fa-th-large"></i> المنتجات</a></li>
        <li><a href="compare.php" class="active"><i class="fas fa-balance-scale"></i> المقارنة</a></li>
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
    <h1 style="color: white; font-size: 36px; font-weight: 800;"><i class="fas fa-balance-scale"></i> مقارنة المنتجات</h1>
    <p style="color: rgba(255,255,255,0.7);">قارن بين منتجين جنباً إلى جنب واختر الأنسب لك</p>
</div>

<div style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    <div style="background: white; padding: 30px; border-radius: var(--radius-lg); box-shadow: var(--shadow); margin-bottom: 30px;">
        <h3 style="margin-bottom: 20px; color: var(--dark);"><i class="fas fa-search" style="color: var(--primary);"></i> اختر المنتجات للمقارنة</h3>
        <form method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                <label>المنتج الأول</label>
                <select name="p1" style="padding: 10px; border-radius: 8px; border: 2px solid var(--gray-200); font-family: 'Tajawal'; width: 100%;">
                    <option value="">اختر المنتج</option>
                    <?php foreach ($all_products as $p): ?>
                        <option value="<?php echo $p['id']; ?>" <?php echo $id1 == $p['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                <label>المنتج الثاني</label>
                <select name="p2" style="padding: 10px; border-radius: 8px; border: 2px solid var(--gray-200); font-family: 'Tajawal'; width: 100%;">
                    <option value="">اختر المنتج</option>
                    <?php foreach ($all_products as $p): ?>
                        <option value="<?php echo $p['id']; ?>" <?php echo $id2 == $p['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height: 46px;">
                <i class="fas fa-balance-scale"></i> قارن
            </button>
        </form>
    </div>

    <?php if ($product1 && $product2): ?>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow); overflow: hidden;">
            <img src="<?php echo htmlspecialchars($product1['image']); ?>" alt="<?php echo htmlspecialchars($product1['name']); ?>" style="width: 100%; height: 250px; object-fit: cover;">
            <div style="padding: 24px;">
                <div style="color: var(--primary); font-size: 13px; font-weight: 600; margin-bottom: 6px;"><?php echo getCategoryArabic($product1['category']); ?></div>
                <h3 style="color: var(--dark); margin-bottom: 10px;"><?php echo htmlspecialchars($product1['name']); ?></h3>
                <p style="color: var(--gray-500); font-size: 14px; margin-bottom: 16px;"><?php echo htmlspecialchars($product1['description']); ?></p>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid var(--gray-100);">
                    <span style="font-size: 22px; font-weight: 800; color: var(--primary);"><?php echo number_format($product1['price'], 2); ?> <small>ر.س</small></span>
                    <span style="color: <?php echo $product1['stock'] > 0 ? 'var(--success)' : 'var(--danger)'; ?>; font-weight: 600;">
                        <?php echo $product1['stock'] > 0 ? 'متوفر (' . $product1['stock'] . ')' : 'نفذ'; ?>
                    </span>
                </div>
                <form method="POST" action="cart.php" style="margin-top: 14px;">
                    <input type="hidden" name="product_id" value="<?php echo $product1['id']; ?>">
                    <input type="hidden" name="action" value="add">
                    <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fas fa-cart-plus"></i> أضف للسلة</button>
                </form>
            </div>
        </div>

        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow); overflow: hidden;">
            <img src="<?php echo htmlspecialchars($product2['image']); ?>" alt="<?php echo htmlspecialchars($product2['name']); ?>" style="width: 100%; height: 250px; object-fit: cover;">
            <div style="padding: 24px;">
                <div style="color: var(--primary); font-size: 13px; font-weight: 600; margin-bottom: 6px;"><?php echo getCategoryArabic($product2['category']); ?></div>
                <h3 style="color: var(--dark); margin-bottom: 10px;"><?php echo htmlspecialchars($product2['name']); ?></h3>
                <p style="color: var(--gray-500); font-size: 14px; margin-bottom: 16px;"><?php echo htmlspecialchars($product2['description']); ?></p>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid var(--gray-100);">
                    <span style="font-size: 22px; font-weight: 800; color: var(--primary);"><?php echo number_format($product2['price'], 2); ?> <small>ر.س</small></span>
                    <span style="color: <?php echo $product2['stock'] > 0 ? 'var(--success)' : 'var(--danger)'; ?>; font-weight: 600;">
                        <?php echo $product2['stock'] > 0 ? 'متوفر (' . $product2['stock'] . ')' : 'نفذ'; ?>
                    </span>
                </div>
                <form method="POST" action="cart.php" style="margin-top: 14px;">
                    <input type="hidden" name="product_id" value="<?php echo $product2['id']; ?>">
                    <input type="hidden" name="action" value="add">
                    <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fas fa-cart-plus"></i> أضف للسلة</button>
                </form>
            </div>
        </div>
    </div>

    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow); margin-top: 24px; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: var(--gray-50);">
                    <th style="padding: 16px; text-align: right; font-weight: 700; color: var(--dark);">المعيار</th>
                    <th style="padding: 16px; text-align: center; font-weight: 700; color: var(--primary);"><?php echo htmlspecialchars($product1['name']); ?></th>
                    <th style="padding: 16px; text-align: center; font-weight: 700; color: var(--primary);"><?php echo htmlspecialchars($product2['name']); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid var(--gray-100);">
                    <td style="padding: 14px; font-weight: 600;">السعر</td>
                    <td style="padding: 14px; text-align: center; font-weight: 700; color: <?php echo $product1['price'] <= $product2['price'] ? 'var(--success)' : 'var(--danger)'; ?>;"><?php echo number_format($product1['price'], 2); ?> ر.س</td>
                    <td style="padding: 14px; text-align: center; font-weight: 700; color: <?php echo $product2['price'] <= $product1['price'] ? 'var(--success)' : 'var(--danger)'; ?>;"><?php echo number_format($product2['price'], 2); ?> ر.س</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--gray-100);">
                    <td style="padding: 14px; font-weight: 600;">القسم</td>
                    <td style="padding: 14px; text-align: center;"><?php echo getCategoryArabic($product1['category']); ?></td>
                    <td style="padding: 14px; text-align: center;"><?php echo getCategoryArabic($product2['category']); ?></td>
                </tr>
                <tr style="border-bottom: 1px solid var(--gray-100);">
                    <td style="padding: 14px; font-weight: 600;">المخزون</td>
                    <td style="padding: 14px; text-align: center;"><?php echo $product1['stock']; ?> قطعة</td>
                    <td style="padding: 14px; text-align: center;"><?php echo $product2['stock']; ?> قطعة</td>
                </tr>
                <tr>
                    <td style="padding: 14px; font-weight: 600;">فرق السعر</td>
                    <td colspan="2" style="padding: 14px; text-align: center; font-weight: 800; color: var(--primary); font-size: 18px;">
                        <?php echo number_format(abs($product1['price'] - $product2['price']), 2); ?> ر.س
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php elseif ($id1 > 0 || $id2 > 0): ?>
    <div class="empty-cart">
        <i class="fas fa-exclamation-triangle" style="font-size: 60px; color: var(--warning); display: block; margin-bottom: 16px;"></i>
        <h3>يرجى اختيار منتجين للمقارنة</h3>
        <p>اختر المنتج الأول والثاني من القوائم أعلاه</p>
    </div>
    <?php else: ?>
    <div class="empty-cart">
        <i class="fas fa-balance-scale" style="font-size: 60px; color: var(--gray-300); display: block; margin-bottom: 16px;"></i>
        <h3>اختر منتجين للمقارنة</h3>
        <p>استخدم القوائم أعلاه لاختيار المنتجات التي تريد مقارنتها</p>
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
