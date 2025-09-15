<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];
    $status = $_POST['status'];

    $sql = "INSERT INTO users (username, email, phone, password, role, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "sssiss", $username, $email, $phone, $password, $role, $status);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?page_layout=quan_ly_nguoi_dung");
        exit();
    }
    mysqli_stmt_close($stmt);
}
?>

<div class="container mt-4">
    <h2>Thêm Người dùng</h2>
    <form action="index.php?page_layout=them_nguoi_dung" method="post">
        <div class="form-group">
            <label for="username">Tên đăng nhập</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="role">Vai trò</label>
            <select class="form-control" id="role" name="role" required>
                <option value="user">Người dùng</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select class="form-control" id="status" name="status" required>
                <option value="active">Hoạt động</option>
                <option value="locked">Bị khóa</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="index.php?page_layout=quan_ly_nguoi_dung" class="btn btn-secondary">Quay lại</a>
    </form>
</div>