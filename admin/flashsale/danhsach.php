<?php
$query = "SELECT fs.*, p.prd_name 
          FROM flash_sales fs 
          JOIN products p ON fs.product_id = p.prd_id 
          ORDER BY fs.start_time DESC";
$result = mysqli_query($connect, $query);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="padding-top: 25px; padding-bottom: 25px;">
            <h2 class="mb-0">Quản lý Flash Sale</h2>
            <a class="btn btn-primary" href="index.php?page_layout=them_flashsale">Thêm Flash Sale</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Giảm Giá (%)</th>
                        <th>Đã Bán</th>
                        <th>Thời Gian Bắt Đầu</th>
                        <th>Thời Gian Kết Thúc</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['prd_name']); ?></td>
                            <td><?php echo $row['discount']; ?>%</td>
                            <td><?php echo $row['sold']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['start_time'])); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['end_time'])); ?></td>
                            <td>
                                <a class="btn btn-sm btn-warning" style="width:60px" href="index.php?page_layout=sua_flashsale&id=<?php echo $row['id']; ?>">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a class="btn btn-sm btn-danger" style="width:60px" onclick="return Del('<?php echo htmlspecialchars($row['prd_name']); ?>')" href="index.php?page_layout=xoa_flashsale&id=<?php echo $row['id']; ?>">
                                    <i class="fas fa-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function Del(name) {
        return confirm("Bạn có chắc chắn muốn xóa flash sale: " + name + "?");
    }
</script>