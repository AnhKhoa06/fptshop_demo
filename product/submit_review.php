<?php
session_start();
header('Content-Type: application/json');

require_once '../admin/config/db.php';

// Lấy dữ liệu từ yêu cầu AJAX
$data = json_decode(file_get_contents('php://input'), true);
$rating = isset($data['rating']) ? (int)$data['rating'] : 0;
$content = isset($data['content']) ? mysqli_real_escape_string($connect, $data['content']) : '';
$username = isset($_SESSION['user']) ? mysqli_real_escape_string($connect, $_SESSION['user']) : 'Người dùng';
$product_id = 1; // Thay bằng ID sản phẩm thực tế (có thể gửi từ client)

// Kiểm tra dữ liệu hợp lệ
if ($rating < 1 || $rating > 5 || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

// Lưu vào cơ sở dữ liệu
$query = "INSERT INTO reviews (username, rating, content, review_date, product_id) 
          VALUES ('$username', $rating, '$content', NOW(), $product_id)";
if (mysqli_query($connect, $query)) {
    echo json_encode(['success' => true, 'message' => 'Đánh giá đã được gửi']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . mysqli_error($connect)]);
}

mysqli_close($connect);
?>