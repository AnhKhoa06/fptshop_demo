<?php
    $sql = "SELECT * FROM products inner join  brands on products.brand_id = brands.brand_id ORDER BY products.prd_id DESC";
    $query = mysqli_query($connect, $sql);

    // Tạo mảng để lưu số lượng màu cho từng sản phẩm
    $color_counts = [];
    $sql_color_count = "SELECT product_id, COUNT(*) as color_count FROM product_colors GROUP BY product_id";
    $query_color_count = mysqli_query($connect, $sql_color_count);
    while ($row = mysqli_fetch_assoc($query_color_count)) {
        $color_counts[$row['product_id']] = $row['color_count'];
    }
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="padding-top: 25px; padding-bottom: 25px;">
            <h2 class="mb-0">Danh sách sản phẩm</h2>
            <a class="btn btn-primary" href="index.php?page_layout=them">Thêm sản phẩm</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên S.Phẩm</th>
                        <th>Ảnh</th>
                        <th>Giá gốc</th>
                        <th>Giá KM</th>
                        <th>RAM</th>
                        <th>ROM</th>
                        <th>SL</th>
                        <th>SL.Màu</th>
                        <th>Brands</th>
                        <th>Sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                        while($row = mysqli_fetch_assoc($query)){?>
                            <tr>
                                <td><?php echo $row['prd_id']; ?></td>
                                <td><?php echo $row['prd_name']; ?></td>

                                <td>
                                    <img style="width: 60px; height: 60px" src="img/<?php echo $row['image']; ?>">
                                    
                                </td>

                                <td><?php echo number_format($row['price']); ?></td>
                                <td><?php echo number_format($row['price_discount']); ?></td>
                                <td><?php echo htmlspecialchars($row['ram']); ?></td>
                                <td><?php echo htmlspecialchars($row['rom']); ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo isset($color_counts[$row['prd_id']]) ? $color_counts[$row['prd_id']] : 0; ?></td>
                                <td><?php echo $row['brand_name']; ?></td>
                                <td>
                                    <a class="btn btn-sm btn-warning" style="width:60px" href="index.php?page_layout=sua&id=<?php echo $row['prd_id']; ?>">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-danger" style="width:60px" onclick="return Del('<?php echo $row['prd_name']; ?>')" href="index.php?page_layout=xoa&id=<?php echo $row['prd_id']; ?>">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                </td>
                               
                            </tr>
                        <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
<script>
    function Del(name){
        return confirm("Bạn có chắc chắn muốn xóa sản phẩm: " + name + "?");
    }
</script>
