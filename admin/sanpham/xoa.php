<?php
    $id = $_GET['id'];

    // Xóa tất cả các bản ghi trong bảng product_colors liên quan đến product_id
    $sql_delete_colors = "DELETE FROM product_colors WHERE product_id = $id";
    $query_delete_colors = mysqli_query($connect, $sql_delete_colors);

    // Sau đó xóa sản phẩm trong bảng products
    $sql = "DELETE FROM products WHERE prd_id = $id";
    $query = mysqli_query($connect, $sql);

    header('location: index.php?page_layout=danhsach');
?>