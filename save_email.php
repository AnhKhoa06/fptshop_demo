<?php
session_start();
require_once 'admin/config/db.php';

if (isset($_POST['email']) && isset($_SESSION['user'])) {
    $email = $_POST['email'];
    $username = $_SESSION['user'];

    $stmt = mysqli_prepare($connect, "UPDATE users SET email = ? WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $username);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['email_updated'] = $email;
        header("Location: account.php");
        exit();
    } else {
        echo "Lỗi khi cập nhật: " . mysqli_error($connect);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connect);
} else {
    echo "Thiếu dữ liệu hoặc chưa đăng nhập.";
}
