<?php
session_start();
require_once 'admin/config/db.php'; // Kết nối database

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); 

    // Kiểm tra trong bảng admin
    $sqlAdmin = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $resultAdmin = mysqli_query($connect, $sqlAdmin);

    if (mysqli_num_rows($resultAdmin) == 1) {
        $_SESSION['admin'] = $username;
        header('Location: admin/index.php'); // Admin đăng nhập thành công
        exit();
    }

    // Kiểm tra trong bảng users
    $sqlUser = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $resultUser = mysqli_query($connect, $sqlUser);

    if (mysqli_num_rows($resultUser) == 1) {
        $user = mysqli_fetch_assoc($resultUser); // Lấy dữ liệu người dùng
        $_SESSION['user'] = $user['username'];
    
        echo "<script>
            localStorage.setItem('username', '{$user['username']}');
            localStorage.setItem('{$user['username']}_phone', '{$user['phone']}');
            localStorage.setItem('{$user['username']}_email', '{$user['email']}');
            window.location.href = 'index.php';
        </script>";
        exit();
    }
    

    // Nếu không tìm thấy trong cả admin và users
    echo "<script>alert('Sai tài khoản hoặc mật khẩu!'); window.location.href='login1.html';</script>";
}
?>
