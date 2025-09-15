<?php
session_start();
header('Content-Type: application/json');

require_once '../admin/config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để xóa đánh giá.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$review_id = isset($data['review_id']) ? (int)$data['review_id'] : 0;
$current_user = mysqli_real_escape_string($connect, $_SESSION['user']);

// Kiểm tra quyền xóa
$query_check = "SELECT username FROM reviews WHERE id = $review_id";
$result = mysqli_query($connect, $query_check);
$review = mysqli_fetch_assoc($result);

if (!$review || $review['username'] !== $current_user) {
    echo json_encode(['success' => false, 'message' => 'Bạn không có quyền xóa đánh giá này.']);
    exit;
}

// Xóa đánh giá
$query_delete = "DELETE FROM reviews WHERE id = $review_id AND username = '$current_user'";
if (mysqli_query($connect, $query_delete)) {
    // Tính lại điểm trung bình
    $product_id = 1; // Thay bằng ID sản phẩm thực tế
    $query_avg = "SELECT AVG(rating) as avg_rating, COUNT(*) as count FROM reviews WHERE product_id = $product_id";
    $result_avg = mysqli_query($connect, $query_avg);
    $summary = mysqli_fetch_assoc($result_avg);
    $avg_rating = $summary['avg_rating'] ? round($summary['avg_rating'], 1) : 0;
    echo json_encode(['success' => true, 'message' => 'Đánh giá đã được xóa', 'avg_rating' => $avg_rating]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa đánh giá: ' . mysqli_error($connect)]);
}

mysqli_close($connect);
?>