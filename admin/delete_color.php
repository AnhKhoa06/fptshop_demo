<?php
    require_once 'config/db.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['color'])) {
        $product_id = $_POST['product_id'];
        $color = $_POST['color'];

        $sql = "DELETE FROM product_colors WHERE product_id = ? AND color = ?";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "is", $product_id, $color);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo 'success';
        } else {
            echo 'error';
        }
        mysqli_stmt_close($stmt);
    } else {
        echo 'error';
    }
    mysqli_close($connect);
?>