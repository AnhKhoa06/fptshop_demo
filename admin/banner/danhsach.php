<?php
$sql = "SELECT * FROM banners ORDER BY created_at DESC";
$query = mysqli_query($connect, $sql);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="padding-top: 25px; padding-bottom: 25px;">
            <h2 class="mb-0">Danh sách Banner</h2>
            <a class="btn btn-primary" href="index.php?page_layout=them_banner">Thêm Banner</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>Ảnh</th>
                        <th>Thời gian tạo</th>
                        <th>Sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><img src="./img/<?php echo htmlspecialchars($row['image']); ?>" alt="Banner" style="width: 280px; height: 45px; object-fit: cover;"></td>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a class="btn btn-sm btn-warning" style="width:60px" href="index.php?page_layout=sua_banner&id=<?php echo $row['id']; ?>">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-danger" style="width:60px" onclick="return Del('<?php echo htmlspecialchars($row['image']); ?>')" href="index.php?page_layout=xoa_banner&id=<?php echo $row['id']; ?>">
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
        return confirm("Bạn có chắc chắn muốn xóa banner: " + name + "?");
    }
</script>