<?php
$sql = "SELECT p.*, b.brand_name 
        FROM promotions p 
        LEFT JOIN brands b ON p.brand_id = b.brand_id 
        ORDER BY p.promo_id DESC";
$query = mysqli_query($connect, $sql);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="padding-top: 25px; padding-bottom: 25px;">
            <h2 class="mb-0">Danh sách khuyến mãi</h2>
            <a class="btn btn-primary" href="index.php?page_layout=them_khuyenmai">Thêm khuyến mãi</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Mã KM</th>
                        <th>Giảm Giá</th>
                        <th>Kiểu Giảm</th>
                        <th>Ngày Hết Hạn</th>
                        <th>Danh Mục Áp Dụng</th>
                        <th>Thời Gian Tạo</th>
                        <th>Sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><?php echo $row['promo_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['promo_code']); ?></td>
                            <td><?php echo number_format($row['discount'], 2, ',', '.'); ?><?php echo $row['discount_type'] == 'percent' ? '%' : 'đ'; ?></td>
                            <td><?php echo $row['discount_type'] == 'percent' ? 'Phần trăm' : 'Tiền mặt'; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['expiry_date'])); ?></td>
                            <td><?php echo is_null($row['brand_id']) ? 'Tất cả' : htmlspecialchars($row['brand_name']); ?></td>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a class="btn btn-sm btn-warning" style="width:60px" href="index.php?page_layout=sua_khuyenmai&id=<?php echo $row['promo_id']; ?>">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-danger" style="width:60px" onclick="return Del('<?php echo $row['promo_code']; ?>')" href="index.php?page_layout=xoa_khuyenmai&id=<?php echo $row['promo_id']; ?>">
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
    function Del(name) {
        return confirm("Bạn có chắc chắn muốn xóa khuyến mãi: " + name + "?");
    }
</script>