<?php
session_start();
require_once 'admin/config/db.php'; // Ket noi database

if (isset($_POST['sign_up'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiem tra mk nhap lai
    if ($password !== $confirm_password) {
        echo "<script>alert('Mật khẩu xác nhận không khớp!'); window.location.href='login1.html';</script>";
        exit();
    }

    // ma ho mk trc khi luu
    $hashed_password = md5($password);

    // ktra username va email da ton tai ch
    $checkUser = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = mysqli_query($connect, $checkUser);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Username hoặc Email đã tồn tại!'); window.location.href='login1.html';</script>";
        exit();
    }

    // Them vao database voi role va status mac dinh cho nguoi dung
    $sql = "INSERT INTO users (username, email, phone, password, role, status) 
            VALUES ('$username', '$email', '$phone', '$hashed_password', 'user', 'active')";

    if (mysqli_query($connect, $sql)) {
        echo "<script>
                localStorage.setItem('username', '$username'); 
                localStorage.setItem('$username" . "_phone', '$phone');
                localStorage.setItem('$username" . "_email', '$email');
                alert('Đăng ký thành công! Vui lòng đăng nhập.');
                window.location.href='login1.html';
              </script>";
        exit();
    } else {
        echo "<script>alert('Đăng ký thất bại!'); window.location.href='login1.html';</script>";
        exit();
    }
}
?>