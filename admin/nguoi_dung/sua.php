<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$row = null;
if ($id > 0) {
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id > 0) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'] ? md5($_POST['password']) : $row['password'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    $sql = "UPDATE users SET username = ?, email = ?, phone = ?, password = ?, role = ?, status = ? WHERE id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssi", $username, $email, $phone, $password, $role, $status, $id);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?page_layout=quan_ly_nguoi_dung");
        exit();
    }
    mysqli_stmt_close($stmt);
}
?>

<div class="container mt-4">
    <h2>Sửa Người dùng</h2>
    <?php if ($row): ?>
        <form action="index.php?page_layout=sua_nguoi_dung&id=<?php echo $id; ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $row['phone']; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu (để trống nếu không đổi)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="role">Vai trò</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="user" <?php echo $row['role'] == 'user' ? 'selected' : ''; ?>>Người dùng</option>
                    <option value="admin" <?php echo $row['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="active" <?php echo $row['status'] == 'active' ? 'selected' : ''; ?>>Hoạt động</option>
                    <option value="locked" <?php echo $row['status'] == 'locked' ? 'selected' : ''; ?>>Bị khóa</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="index.php?page_layout=quan_ly_nguoi_dung" class="btn btn-secondary">Quay lại</a>
        </form>
    <?php else: ?>
        <p style="color: red;">Không tìm thấy người dùng!</p>
    <?php endif; ?>
</div>