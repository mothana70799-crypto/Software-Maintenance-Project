<?php require_once 'db.php'; ?>
<?php

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['product_name'];
    $desc = $_POST['product_desc'];
    $price = floatval($_POST['product_price']);
    $category = $_POST['product_category'];
    $image = $_POST['product_image'];
    $stock = intval($_POST['product_stock']);
    
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, category, image, stock) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdssi", $name, $desc, $price, $category, $image, $stock);
    
    if ($stmt->execute()) {
        $message = 'تم إضافة المنتج بنجاح!';
        $msg_type = 'success';
    } else {
        $message = 'حدث خطأ أثناء إضافة المنتج';
        $msg_type = 'danger';
    }
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    $message = 'تم حذف المنتج';
    $msg_type = 'warning';
}

$products = $conn->query("SELECT * FROM products ORDER BY created_at DESC");

$total_products = $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'];
$total_users = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$total_orders = 0;
$orders_result = $conn->query("SELECT COUNT(*) as c FROM orders");
if ($orders_result) $total_orders = $orders_result->fetch_assoc()['c'];

$total_revenue = 0;
$rev_result = $conn->query("SELECT COALESCE(SUM(total), 0) as total FROM orders");
if ($rev_result) $total_revenue = $rev_result->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر همم | لوحة التحكم</title>
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
        <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> دخول</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="dashboard-page">
    <div class="dashboard-header">
        <h1><i class="fas fa-tachometer-alt" style="color: var(--primary);"></i> لوحة التحكم</h1>
        <span style="color: var(--gray-500);">مرحباً بك في لوحة تحكم متجر همم</span>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $msg_type; ?>" style="margin-bottom: 24px;">
            <i class="fas fa-info-circle"></i> <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #EDE9FE; color: var(--primary);">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-value"><?php echo $total_products; ?></div>
            <div class="stat-label">إجمالي المنتجات</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #D1FAE5; color: var(--success);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value"><?php echo $total_users; ?></div>
            <div class="stat-label">المستخدمين</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #FEF3C7; color: var(--warning);">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-value"><?php echo $total_orders; ?></div>
            <div class="stat-label">الطلبات</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #FCE7F3; color: var(--accent);">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-value"><?php echo $total_revenue; ?> ر.س</div>
            <div class="stat-label">إجمالي المبيعات</div>
        </div>
    </div>

    <div class="dashboard-section">
        <h2><i class="fas fa-plus-circle" style="color: var(--success);"></i> إضافة منتج جديد</h2>
        <form method="POST" class="add-product-form">
            <div class="form-group">
                <label>اسم المنتج *</label>
                <input type="text" name="product_name" placeholder="مثال: سماعات بلوتوث" required>
            </div>
            <div class="form-group">
                <label>السعر (ر.س) *</label>
                <input type="number" name="product_price" step="0.01" placeholder="299.99" required>
            </div>
            <div class="form-group">
                <label>القسم *</label>
                <select name="product_category" required>
                    <option value="">اختر القسم</option>
                    <option value="electronics">إلكترونيات</option>
                    <option value="clothes">ملابس</option>
                    <option value="perfumes">عطور</option>
                    <option value="accessories">إكسسوارات</option>
                </select>
            </div>
            <div class="form-group">
                <label>الكمية المتوفرة *</label>
                <input type="number" name="product_stock" placeholder="50" required>
            </div>
            <div class="form-group full-width">
                <label>وصف المنتج</label>
                <textarea name="product_desc" rows="3" placeholder="وصف مختصر للمنتج..."></textarea>
            </div>
            <div class="form-group full-width">
                <label>رابط الصورة</label>
                <input type="url" name="product_image" placeholder="https://example.com/image.jpg" value="https://via.placeholder.com/400x400?text=Product">
            </div>
            <div class="full-width">
                <button type="submit" name="add_product" class="btn btn-success btn-lg">
                    <i class="fas fa-plus"></i> إضافة المنتج
                </button>
            </div>
        </form>
    </div>

    <div class="dashboard-section">
        <h2><i class="fas fa-list" style="color: var(--primary);"></i> إدارة المنتجات</h2>
        <div style="overflow-x: auto;">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الصورة</th>
                        <th>اسم المنتج</th>
                        <th>القسم</th>
                        <th>السعر</th>
                        <th>المخزون</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($products && $products->num_rows > 0):
                        while ($p = $products->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td>
                            <img src="<?php echo htmlspecialchars($p['image']); ?>" class="product-thumb" alt="">
                        </td>
                        <td style="font-weight: 600;"><?php echo htmlspecialchars($p['name']); ?></td>
                        <td><?php echo getCategoryArabic($p['category']); ?></td>
                        <td style="font-weight: 700; color: var(--primary);"><?php echo number_format($p['price'], 2); ?> ر.س</td>
                        <td>
                            <span style="padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 700;
                                background: <?php echo $p['stock'] < 10 ? '#FEE2E2' : '#D1FAE5'; ?>;
                                color: <?php echo $p['stock'] < 10 ? '#991B1B' : '#065F46'; ?>;">
                                <?php echo $p['stock']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="dashboard.php?delete=<?php echo $p['id']; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                <i class="fas fa-trash"></i> حذف
                            </a>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--gray-500);">
                            لا توجد منتجات حالياً
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="footer-bottom" style="border: none; margin-top: 0;">
        <p>© 2026 جميع الحقوق محفوظة لـ <span>متجر همم</span> | لوحة التحكم</p>
    </div>
</footer>

</body>
</html>
