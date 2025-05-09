<?php
session_start();
require_once 'admin/config/db.php'; 

if (isset($_POST['phone']) && isset($_SESSION['user'])) {
    $phone = $_POST['phone'];
    $username = $_SESSION['user'];

    $stmt = mysqli_prepare($connect, "UPDATE users SET phone = ? WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "ss", $phone, $username);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['phone_success'] = "Cập nhật số điện thoại thành công!";
    } else {
        $_SESSION['phone_error'] = "Lỗi khi cập nhật số điện thoại: " . mysqli_error($connect);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connect);

    // Trở về trang cập nhật điện thoại để hiển thị thông báo
    header("Location: phone.php");
    exit();
} else {
    $_SESSION['phone_error'] = "Thiếu dữ liệu hoặc chưa đăng nhập.";
    header("Location: phone.php");
    exit();
}
