<?php
$sql = "SELECT * FROM news ORDER BY created_at DESC";
$query = mysqli_query($connect, $sql);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="padding-top: 25px; padding-bottom: 25px;">
            <h2 class="mb-0">Danh sách tin tức</h2>
            <a class="btn btn-primary" href="index.php?page_layout=them_tintuc">Thêm tin tức</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>Ảnh</th>
                        <th>Chủ đề</th>
                        <th>Tiêu đề</th>
                        <th>Thời gian tạo</th>
                        <th>Sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><img src="../<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" style="width: 50px; height: 50px; object-fit: cover;"></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a class="btn btn-sm btn-warning" style="width:60px" href="index.php?page_layout=sua_tintuc&id=<?php echo $row['news_id']; ?>">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-danger" style="width:60px" onclick="return Del('<?php echo $row['title']; ?>')" href="index.php?page_layout=xoa_tintuc&id=<?php echo $row['news_id']; ?>">
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
        return confirm("Bạn có chắc chắn muốn xóa tin tức: " + name + "?");
    }
</script>