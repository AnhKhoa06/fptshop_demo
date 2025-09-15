<?php
require_once 'config/db.php';

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $query = "SELECT DISTINCT rom FROM product_colors WHERE product_id = ? ORDER BY rom ASC";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    echo '<option value="">Chọn dung lượng</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . htmlspecialchars($row['rom']) . '">' . htmlspecialchars($row['rom']) . '</option>';
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($connect);
?>