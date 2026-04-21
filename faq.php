<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر همم | الأسئلة الشائعة</title>
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
        <li><a href="faq.php" class="active"><i class="fas fa-question-circle"></i> الأسئلة الشائعة</a></li>
        <li><a href="contact.php"><i class="fas fa-headset"></i> تواصل معنا</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="profile.php"><i class="fas fa-user"></i> ملفي</a></li>
            <li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> خروج</a></li>
        <?php else: ?>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> دخول</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div style="background: linear-gradient(135deg, var(--dark), var(--primary-dark)); padding: 50px 40px; text-align: center;">
    <h1 style="color: white; font-size: 36px; font-weight: 800;"><i class="fas fa-question-circle"></i> الأسئلة الشائعة</h1>
    <p style="color: rgba(255,255,255,0.7);">إجابات على أكثر الأسئلة شيوعاً حول متجر همم</p>
</div>

<div style="max-width: 800px; margin: 40px auto; padding: 0 20px;">

    <div style="display: grid; gap: 16px;">
        <?php
        $faqs = [
            ['q' => 'كيف أسجل حساب في متجر همم؟', 'a' => 'اضغط على زر "تسجيل" في أعلى الصفحة، ثم أدخل اسمك وبريدك الإلكتروني وكلمة المرور. سيتم إنشاء حسابك فوراً ويمكنك البدء بالتسوق.', 'icon' => 'fa-user-plus'],
            ['q' => 'ما هي طرق الدفع المتاحة؟', 'a' => 'نوفر عدة طرق للدفع تشمل: الدفع عند الاستلام، التحويل البنكي، وبطاقات الائتمان (فيزا/ماستركارد). جميع عمليات الدفع الإلكتروني مشفرة وآمنة.', 'icon' => 'fa-credit-card'],
            ['q' => 'كم تستغرق عملية التوصيل؟', 'a' => 'يتم التوصيل خلال 2-5 أيام عمل داخل المدن الرئيسية (الرياض، جدة، الدمام). للمناطق الأخرى قد يستغرق 5-7 أيام عمل. يمكنك تتبع طلبك من صفحة "تتبع الطلبات".', 'icon' => 'fa-shipping-fast'],
            ['q' => 'هل يمكنني استرجاع أو استبدال المنتج؟', 'a' => 'نعم، يمكنك استرجاع أو استبدال المنتج خلال 14 يوماً من تاريخ الاستلام بشرط أن يكون المنتج بحالته الأصلية وفي تغليفه الأصلي. تواصل معنا عبر صفحة "تواصل معنا".', 'icon' => 'fa-undo-alt'],
            ['q' => 'كيف أستخدم كوبون الخصم؟', 'a' => 'عند إتمام الطلب، أدخل رمز الكوبون في حقل "كوبون الخصم" واضغط "تطبيق". سيتم خصم القيمة تلقائياً من إجمالي طلبك. يمكنك الاطلاع على الكوبونات المتاحة من صفحة "الكوبونات".', 'icon' => 'fa-ticket-alt'],
            ['q' => 'هل الأسعار تشمل الضريبة؟', 'a' => 'الأسعار المعروضة لا تشمل ضريبة القيمة المضافة (15%). يتم إضافة الضريبة تلقائياً عند إتمام الطلب في صفحة الدفع.', 'icon' => 'fa-receipt'],
            ['q' => 'كيف أسجل كبائع في المنصة؟', 'a' => 'يمكنك التسجيل كبائع من خلال صفحة "سجّل كبائع". أدخل بياناتك ومعلومات متجرك وسيتم مراجعة طلبك والرد عليك خلال 48 ساعة.', 'icon' => 'fa-store-alt'],
            ['q' => 'هل التوصيل مجاني؟', 'a' => 'نعم! نوفر شحن مجاني على جميع الطلبات داخل المملكة العربية السعودية بدون حد أدنى للطلب.', 'icon' => 'fa-truck'],
            ['q' => 'كيف أتتبع طلبي؟', 'a' => 'بعد تأكيد طلبك، يمكنك متابعة حالته من صفحة "تتبع الطلبات" في حسابك. ستتلقى أيضاً إشعارات عبر البريد الإلكتروني عند كل تحديث.', 'icon' => 'fa-map-marker-alt'],
            ['q' => 'هل يمكنني تعديل طلبي بعد تأكيده؟', 'a' => 'يمكنك تعديل أو إلغاء طلبك خلال ساعة واحدة من تأكيده إذا لم يبدأ التجهيز بعد. تواصل معنا فوراً عبر صفحة "تواصل معنا" أو اتصل بنا.', 'icon' => 'fa-edit'],
        ];

        foreach ($faqs as $index => $faq):
        ?>
        <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow); overflow: hidden;">
            <div onclick="this.parentElement.classList.toggle('faq-open')" style="padding: 20px 24px; display: flex; align-items: center; gap: 16px; cursor: pointer; transition: background 0.2s;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #EDE9FE; color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas <?php echo $faq['icon']; ?>"></i>
                </div>
                <h4 style="flex: 1; color: var(--dark); font-size: 15px;"><?php echo $faq['q']; ?></h4>
                <i class="fas fa-chevron-down" style="color: var(--gray-400); transition: transform 0.3s;"></i>
            </div>
            <div style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                <div style="padding: 0 24px 20px; color: var(--gray-600); line-height: 1.8; font-size: 14px; border-top: 1px solid var(--gray-100); padding-top: 16px;">
                    <?php echo $faq['a']; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div style="background: linear-gradient(135deg, var(--primary), var(--accent)); border-radius: var(--radius-lg); padding: 40px; margin-top: 40px; text-align: center; color: white;">
        <h3 style="margin-bottom: 10px;"><i class="fas fa-headset"></i> لم تجد إجابة لسؤالك؟</h3>
        <p style="opacity: 0.9; margin-bottom: 20px;">تواصل معنا وسنرد عليك في أقرب وقت ممكن</p>
        <a href="contact.php" class="btn" style="background: white; color: var(--primary); font-weight: 700; padding: 12px 30px;">
            <i class="fas fa-envelope"></i> تواصل معنا
        </a>
    </div>
</div>

<style>
.faq-open .fa-chevron-down {
    transform: rotate(180deg) !important;
}
.faq-open > div:last-child {
    max-height: 200px !important;
}
</style>

<footer class="footer">
    <div class="footer-bottom" style="border: none; margin-top: 0;">
        <p>© 2026 جميع الحقوق محفوظة لـ <span>متجر همم</span></p>
    </div>
</footer>

</body>
</html>
