<?php
session_start();
include_once 'header.php';
require_once 'admin/config/db.php';
?>

<title>Smart Phone | Tin Tức - Sự Kiện</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/news1.css">

<div class="breadcrumb">
    <i class="fas fa-home" style="color: red;"></i><a href="index.php">Trang chủ</a>
    <i class="fas fa-angle-right"></i>
    <span>Tin Tức - Sự Kiện</span>
</div>

<?php
// Lấy nội dung tin tức cụ thể nếu có ID
$news_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($news_id) {
    $query = "SELECT content FROM news WHERE news_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $news_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $news = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$news) {
        echo "<div class='alert alert-danger'>Tin tức không tồn tại.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-warning'>Vui lòng chọn một tin tức để xem nội dung.</div>";
    exit();
}
?>

<div class="news-container">
    <div class="news-content">
        <?php echo $news['content']; ?>
    </div>
</div>



<?php
include_once 'footer.php';
?>