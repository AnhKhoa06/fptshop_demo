<?php
session_start();
require_once 'admin/config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thực hiện thao tác này']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy dữ liệu từ yêu cầu AJAX
$data = json_decode(file_get_contents('php://input'), true);
$order_code = isset($data['order_code']) ? $data['order_code'] : '';

if (empty($order_code)) {
    echo json_encode(['success' => false, 'message' => 'Mã đơn hàng không hợp lệ']);
    exit();
}

// Cập nhật trạng thái is_deleted
$query = "UPDATE notifications SET is_deleted = 1 WHERE user_id = ? AND order_code = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "is", $user_id, $order_code);
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa thông báo']);
}
mysqli_stmt_close($stmt);
mysqli_close($connect);
?>