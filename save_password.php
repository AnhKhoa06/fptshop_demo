<?php
session_start();
require_once 'admin/config/db.php';

if (isset($_POST['password'], $_POST['confirm_password'], $_SESSION['user'])) {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $username = $_SESSION['user'];

    if ($password !== $confirmPassword) {
        $_SESSION['password_error'] = "Mật khẩu xác nhận không khớp.";
        header("Location: password.php");
        exit();
    }

    $hashedPassword = md5($password);
    $stmt = mysqli_prepare($connect, "UPDATE users SET password = ? WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $username);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['password_success'] = "Cập nhật mật khẩu thành công!";
    } else {
        $_SESSION['password_error'] = "Lỗi khi cập nhật: " . mysqli_error($connect);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connect);
    header("Location: password.php");
    exit();
} else {
    $_SESSION['password_error'] = "Thiếu dữ liệu hoặc chưa đăng nhập.";
    header("Location: password.php");
    exit();
}
