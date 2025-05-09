<?php
    $id = $_GET['id'];

    // Xóa tất cả sản phẩm có thương hiệu này trước
    $sql_delete_products = "DELETE FROM products WHERE brand_id = $id";
    mysqli_query($connect, $sql_delete_products);

    // Sau đó xóa thương hiệu
    $sql_delete_brand = "DELETE FROM brands WHERE brand_id = $id";
    mysqli_query($connect, $sql_delete_brand);

    header('Location: index.php?page_layout=danhmuc');
?>
