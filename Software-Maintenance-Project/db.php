<?php
$host = 'localhost';
$dbname = 'himam_store';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("عذراً، حدث خطأ في الاتصال. يرجى المحاولة لاحقاً.");
}
$conn->set_charset("utf8mb4");

session_start();

function getCategoryArabic($cat) {
    $map = [
        'electronics' => 'إلكترونيات',
        'clothes' => 'ملابس',
        'perfumes' => 'عطور',
        'accessories' => 'إكسسوارات'
    ];
    return $map[$cat] ?? $cat;
}
?>
