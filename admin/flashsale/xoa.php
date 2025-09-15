<?php


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<script>alert('ID không hợp lệ!'); window.location.href='index.php?page_layout=flashsale';</script>";
    exit();
}

$query = "DELETE FROM flash_sales WHERE id = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Xóa Flash Sale thành công!'); window.location.href='index.php?page_layout=flashsale';</script>";
} else {
    echo "<script>alert('Lỗi khi xóa Flash Sale!');</script>";
}
mysqli_stmt_close($stmt);