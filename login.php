<?php
session_start();
require_once 'admin/config/db.php'; 

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); 

    // Kiểm tra trong bảng users
    $sqlUser = "SELECT * FROM users WHERE username='$username' AND password='$password' AND status='active'";
    $resultUser = mysqli_query($connect, $sqlUser);

    if (mysqli_num_rows($resultUser) == 1) {
        $user = mysqli_fetch_assoc($resultUser); // Lấy dữ liệu người dùng

        // Xóa toàn bộ session liên quan đến người dùng thông thường trước khi xử lý (trừ cart nếu cần chuyển)
        unset($_SESSION['user']);
        unset($_SESSION['user_id']);

        // Chuyển hướng dựa trên vai trò
        if ($user['role'] == 'admin') {
            $_SESSION['admin'] = $user['username']; // Chỉ đặt session admin
            unset($_SESSION['cart']); // Xóa giỏ hàng session khi là Admin
            header('Location: admin/index.php');
        } else {
            $_SESSION['user'] = $user['username']; // Đặt session user cho người dùng thông thường
            $_SESSION['user_id'] = $user['id'];
            // Chuyển sản phẩm từ session sang database
            if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                $user_id = $_SESSION['user_id'];
                foreach ($_SESSION['cart'] as $item) {
                    // Debug: Kiểm tra dữ liệu item
                    // error_log("Cart item: " . print_r($item, true));
                    $checkQuery = "SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND color = ? AND rom = ?";
                    $stmt = mysqli_prepare($connect, $checkQuery);
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "iiss", $user_id, $item['product_id'], $item['color'], $item['rom']);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        if (mysqli_num_rows($result) > 0) {
                            $cartItem = mysqli_fetch_assoc($result);
                            $newQuantity = $cartItem['quantity'] + $item['quantity'];
                            $updateQuery = "UPDATE cart SET quantity = ? WHERE id = ?";
                            $stmt = mysqli_prepare($connect, $updateQuery);
                            if ($stmt) {
                                mysqli_stmt_bind_param($stmt, "ii", $newQuantity, $cartItem['id']);
                                mysqli_stmt_execute($stmt);
                            } else {
                                // error_log("Error preparing update query: " . mysqli_error($connect));
                            }
                        } else {
                            $insertQuery = "INSERT INTO cart (user_id, product_id, product_name, color, rom, price, price_discount, quantity, image) 
                                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt = mysqli_prepare($connect, $insertQuery);
                            if ($stmt) {
                                mysqli_stmt_bind_param($stmt, "iisssiiis", $user_id, $item['product_id'], $item['product_name'], $item['color'], $item['rom'], $item['price'], $item['price_discount'], $item['quantity'], $item['image']);
                                mysqli_stmt_execute($stmt);
                            } else {
                                // error_log("Error preparing insert query: " . mysqli_error($connect));
                            }
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        // error_log("Error preparing check query: " . mysqli_error($connect));
                    }
                }
                // Xóa session cart sau khi chuyển sang database
                unset($_SESSION['cart']);
            } else {
                // Debug: Kiểm tra nếu không có cart
                // error_log("No cart session or empty: " . print_r($_SESSION['cart'], true));
            }
            
            echo "<script>
                localStorage.setItem('username', '{$user['username']}');
                localStorage.setItem('{$user['username']}_phone', '{$user['phone']}');
                localStorage.setItem('{$user['username']}_email', '{$user['email']}');
                window.location.href = 'index.php';
            </script>";
        }
        exit();
    }

    // Nếu không tìm thấy
    echo "<script>alert('Sai tài khoản hoặc mật khẩu!'); window.location.href='login1.html';</script>";
}
?>