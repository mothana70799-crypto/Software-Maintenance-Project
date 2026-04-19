<?php require_once 'db.php'; ?>
<?php
$success = '';
$error = '';

$product_id = intval($_GET['product_id'] ?? 0);
$product = null;

if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $pid = intval($_POST['product_id'] ?? 0);
    $rating = intval($_POST['rating'] ?? 5);
    $comment = htmlspecialchars(trim($_POST['comment'] ?? ''), ENT_QUOTES, 'UTF-8');

    if ($pid > 0 && !empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $_SESSION['user_id'], $pid, $rating, $comment);
        if ($stmt->execute()) {
            $success = 'تم إضافة تقييمك بنجاح!';
        } else {
            $error = 'حدث خطأ أثناء إضافة التقييم';
        }
        $stmt->close();
    }
}

$reviews = [];
$avg_rating = 0;
$total_reviews = 0;

if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT r.*, u.username FROM reviews r LEFT JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_rating = 0;
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
        $total_rating += $row['rating'];
        $total_reviews++;
    }
    $stmt->close();

    if ($total_reviews > 0) {
        $avg_rating = $total_rating / $total_reviews;
    }
} else {
    $latest_reviews = $conn->query("SELECT r.*, u.username, p.name as product_name FROM reviews r LEFT JOIN users u ON r.user_id = u.id LEFT JOIN products p ON r.product_id = p.id ORDER BY r.created_at DESC LIMIT 20");
    if ($latest_reviews) {
        while ($row = $latest_reviews->fetch_assoc()) {
            $reviews[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر همم | التقييمات</title>
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
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="profile.php"><i class="fas fa-user"></i> ملفي</a></li>
            <li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> دخول</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div style="background: linear-gradient(135deg, var(--dark), var(--primary-dark)); padding: 50px 40px; text-align: center;">
    <h1 style="color: white; font-size: 36px; font-weight: 800;"><i class="fas fa-star"></i> تقييمات العملاء</h1>
    <p style="color: rgba(255,255,255,0.7);">
        <?php echo $product ? 'تقييمات: ' . htmlspecialchars($product['name']) : 'آراء عملائنا حول منتجاتنا'; ?>
    </p>
</div>

<div style="max-width: 800px; margin: 40px auto; padding: 0 20px;">

    <?php if ($product && $total_reviews > 0): ?>
    <div style="background: white; padding: 30px; border-radius: var(--radius-lg); box-shadow: var(--shadow); text-align: center; margin-bottom: 30px;">
        <div style="font-size: 48px; font-weight: 900; color: var(--primary);"><?php echo number_format($avg_rating, 1); ?></div>
        <div class="review-stars" style="font-size: 24px; margin: 8px 0;">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <i class="fas fa-star" style="color: <?php echo $i <= round($avg_rating) ? 'var(--secondary)' : 'var(--gray-200)'; ?>;"></i>
            <?php endfor; ?>
        </div>
        <p style="color: var(--gray-500);">بناءً على <?php echo $total_reviews; ?> تقييم</p>
    </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($product && isset($_SESSION['user_id'])): ?>
    <div style="background: white; padding: 30px; border-radius: var(--radius-lg); box-shadow: var(--shadow); margin-bottom: 30px;">
        <h3 style="margin-bottom: 16px; color: var(--dark);"><i class="fas fa-edit" style="color: var(--primary);"></i> أضف تقييمك</h3>
        <form method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <div class="form-group">
                <label>التقييم</label>
                <select name="rating" style="padding: 10px; border-radius: 8px; border: 2px solid var(--gray-200); font-family: 'Tajawal'; width: 100%;">
                    <option value="5">★★★★★ ممتاز</option>
                    <option value="4">★★★★ جيد جداً</option>
                    <option value="3">★★★ جيد</option>
                    <option value="2">★★ مقبول</option>
                    <option value="1">★ ضعيف</option>
                </select>
            </div>
            <div class="form-group">
                <label>تعليقك</label>
                <textarea name="comment" rows="3" placeholder="شاركنا رأيك عن المنتج..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-paper-plane"></i> إرسال التقييم
            </button>
        </form>
    </div>
    <?php endif; ?>

    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
        <div class="review-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <div>
                    <strong style="color: var(--dark);"><?php echo htmlspecialchars($review['username'] ?? 'مجهول'); ?></strong>
                    <?php if (isset($review['product_name'])): ?>
                        <span style="color: var(--gray-400);"> — <?php echo htmlspecialchars($review['product_name']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="review-stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star" style="color: <?php echo $i <= $review['rating'] ? 'var(--secondary)' : 'var(--gray-200)'; ?>; font-size: 14px;"></i>
                    <?php endfor; ?>
                </div>
            </div>
            <p style="color: var(--gray-600); line-height: 1.8;"><?php echo htmlspecialchars($review['comment']); ?></p>
            <small style="color: var(--gray-400); margin-top: 8px; display: block;"><?php echo $review['created_at']; ?></small>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
    <div class="empty-cart">
        <i class="fas fa-comment-slash" style="font-size: 60px; color: var(--gray-300); display: block; margin-bottom: 16px;"></i>
        <h3>لا توجد تقييمات بعد</h3>
        <p>كن أول من يقيّم هذا المنتج</p>
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
