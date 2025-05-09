<?php
    // Lấy danh sách thương hiệu từ database
    $sql = "SELECT * FROM brands ORDER BY brand_id ASC";
    $query = mysqli_query($connect, $sql);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="padding-top: 25px; padding-bottom: 25px;">
            <h2 class="mb-0">Danh sách danh mục (Thương hiệu)</h2>
            <a class="btn btn-primary" href="index.php?page_layout=them_danhmuc">Thêm danh mục</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên thương hiệu</th>
                        <th>Hình ảnh</th>
                        <th>Sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 1;
                        while($row = mysqli_fetch_assoc($query)){ ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($row['brand_name']); ?></td>
                                <td>
                                    <img src="img1/<?php echo $row['image1']; ?>" width="60" height="60" alt="<?php echo htmlspecialchars($row['brand_name']); ?>">
                                </td>

                                <td>
                                    <a class="btn btn-sm btn-warning" href="index.php?page_layout=sua_danhmuc&id=<?php echo $row['brand_id']; ?>">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-danger" onclick="return Del('<?php echo $row['brand_name']; ?>')" href="index.php?page_layout=xoa_danhmuc&id=<?php echo $row['brand_id']; ?>">
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
        return confirm("Bạn có chắc chắn muốn xóa danh mục: " + name + "?");
    }
</script>
