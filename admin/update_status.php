<?php
require_once 'config/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$order_code = $data['order_code'] ?? '';
$new_status = $data['status'] ?? '';

if (empty($order_code) || empty($new_status)) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit();
}

$query = "UPDATE orders SET status = ? WHERE order_code = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "ss", $new_status, $order_code);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật trạng thái']);
}

mysqli_stmt_close($stmt);
?>