<?php
// File get_cart_count.php - Lấy số lượng sản phẩm riêng biệt trong giỏ hàng


session_start();
require_once 'admin/config/db.php';

header('Content-Type: application/json');

// Tính số lượng sản phẩm riêng biệt trong giỏ hàng
$count = 0;

if (isset($_SESSION['user_id'])) {
    // Người dùng đã đăng nhập, lấy từ database
    $user_id = $_SESSION['user_id'];
    $query = "SELECT COUNT(*) AS total FROM cart WHERE user_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $count = (int)$row['total'] ?: 0;
    }
} else {
    // Người dùng chưa đăng nhập, lấy từ session
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $count = count($_SESSION['cart']);
    }
}

echo json_encode(['success' => true, 'count' => $count]);
exit;
?>