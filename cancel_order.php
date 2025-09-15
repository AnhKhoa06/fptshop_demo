<?php
session_start();
require_once 'admin/config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
    exit();
}

$user_id = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);
$order_code = $input['order_code'] ?? '';

if (empty($order_code)) {
    echo json_encode(['success' => false, 'message' => 'Mã đơn hàng không hợp lệ']);
    exit();
}

$query = "UPDATE orders SET status = 'Hủy đơn' WHERE order_code = ? AND user_id = ? AND status = 'Chờ xác nhận'";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "si", $order_code, $user_id);
$success = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

echo json_encode([
    'success' => $success,
    'message' => $success ? 'Hủy đơn hàng thành công' : 'Không thể hủy đơn hàng'
]);
?>