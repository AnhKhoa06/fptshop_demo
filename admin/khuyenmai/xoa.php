<?php
if (!isset($_GET['id'])) {
    header('Location: index.php?page_layout=khuyenmai');
    exit();
}

$id = $_GET['id'];
$sql = "DELETE FROM promotions WHERE promo_id = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>window.location.href='index.php?page_layout=khuyenmai';</script>";
} else {
    echo "<script>alert('Lỗi khi xóa khuyến mãi');</script>";
}
mysqli_stmt_close($stmt);
?>