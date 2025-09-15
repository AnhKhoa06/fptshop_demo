<?php
// Bật hiển thị lỗi để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/db.php';

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    // Truy vấn danh sách màu sắc của sản phẩm
    $query = "SELECT DISTINCT color FROM product_colors WHERE product_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    if (!$stmt) {
        error_log("Lỗi chuẩn bị truy vấn: " . mysqli_error($connect));
        echo '<option value="">Lỗi truy vấn</option>';
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Tạo HTML cho dropdown màu sắc
    $options = '<option value="">Chọn màu sắc</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        $color = htmlspecialchars($row['color']);
        $options .= "<option value='$color'>$color</option>";
    }

    echo $options;
    mysqli_stmt_close($stmt);
} else {
    echo '<option value="">Chọn màu sắc</option>';
}
?>