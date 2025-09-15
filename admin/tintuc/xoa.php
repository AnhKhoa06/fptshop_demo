<?php
if (!isset($_GET['id'])) {
    header('Location: index.php?page_layout=tintuc');
    exit();
}

$news_id = intval($_GET['id']);
$query = "DELETE FROM news WHERE news_id = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $news_id);
if (mysqli_stmt_execute($stmt)) {
    header('Location: index.php?page_layout=tintuc');
    exit();
} else {
    echo "<div class='alert alert-danger'>Lỗi khi xóa tin tức: " . mysqli_stmt_error($stmt) . "</div>";
}
mysqli_stmt_close($stmt);
?>