<?php
session_start();
require_once 'admin/config/db.php';
header('Content-Type: application/json');

$response = ['success' => false];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Bạn cần đăng nhập để thực hiện thao tác này.';
    echo json_encode($response);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['user_id'];
$product_id = $input['product_id'];
$color = $input['color'];
$rom = $input['rom'];

$query = "INSERT INTO favorites (user_id, product_id, color, rom) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "iiss", $user_id, $product_id, $color, $rom);

if (mysqli_stmt_execute($stmt)) {
    $response['success'] = true;
} else {
    $response['message'] = "Lỗi khi thêm sản phẩm yêu thích: " . mysqli_error($connect);
}

mysqli_stmt_close($stmt);
echo json_encode($response);
?>