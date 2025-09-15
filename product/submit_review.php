<?php
session_start();
header('Content-Type: application/json');

require_once '../admin/config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để gửi đánh giá.']);
    exit;
}

// Lấy dữ liệu từ yêu cầu AJAX
$data = json_decode(file_get_contents('php://input'), true);
$rating = isset($data['rating']) ? (int)$data['rating'] : 0;
$content = isset($data['content']) ? mysqli_real_escape_string($connect, $data['content']) : '';
$username = mysqli_real_escape_string($connect, $_SESSION['user']);
$product_id = isset($data['product_id']) ? (int)$data['product_id'] : 1;

// Kiểm tra dữ liệu hợp lệ
if ($rating < 1 || $rating > 5 || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

// Lưu vào cơ sở dữ liệu
$query = "INSERT INTO reviews (username, rating, content, review_date, product_id) 
          VALUES ('$username', $rating, '$content', NOW(), $product_id)";
if (mysqli_query($connect, $query)) {
    $review_id = mysqli_insert_id($connect); // Lấy ID của bản ghi vừa thêm
    echo json_encode(['success' => true, 'message' => 'Đánh giá đã được gửi', 'review_id' => $review_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . mysqli_error($connect)]);
}

mysqli_close($connect);
?>