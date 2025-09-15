<?php
$sql = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($connect, $sql);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="padding-top: 25px; padding-bottom: 25px;">
            <h2 class="mb-0">Danh sách Người dùng</h2>
            <a class="btn btn-primary" href="index.php?page_layout=them_nguoi_dung">Thêm Người dùng</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name'] ?: $row['username']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['role']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <a class="btn btn-sm btn-warning" href="index.php?page_layout=sua_nguoi_dung&id=<?php echo $row['id']; ?>">Sửa</a>
                                <a class="btn btn-sm btn-danger" href="index.php?page_layout=xoa_nguoi_dung&id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>