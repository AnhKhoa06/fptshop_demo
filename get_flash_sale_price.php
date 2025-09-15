<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
header('Content-Type: application/json');
require_once 'admin/config/db.php';

$response = ['success' => false];

if (isset($_GET['product_id']) && isset($_GET['color']) && isset($_GET['rom'])) {
    $product_id = $_GET['product_id'];
    $color = urldecode($_GET['color']);
    $rom = urldecode($_GET['rom']);
    $current_time = date('Y-m-d H:i:s', time());
    
    $query = "SELECT discount, price_discount FROM flash_sales 
              WHERE product_id = ? AND color = ? AND rom = ? 
              AND start_time <= ? AND end_time >= ?";
    $stmt = mysqli_prepare($connect, $query);
    if (!$stmt) {
        $response['error'] = "Lỗi chuẩn bị truy vấn: " . mysqli_error($connect);
        echo json_encode($response);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "issss", $product_id, $color, $rom, $current_time, $current_time);
    if (!mysqli_stmt_execute($stmt)) {
        $response['error'] = "Lỗi thực thi truy vấn: " . mysqli_stmt_error($stmt);
        echo json_encode($response);
        exit;
    }
    $result = mysqli_stmt_get_result($stmt);
    $flash_sale = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($flash_sale) {
        $response['success'] = true;
        $response['price_discount'] = (int)$flash_sale['price_discount'];
        $response['discount'] = (int)$flash_sale['discount'];
    } else {
        $response['message'] = "Không tìm thấy Flash Sale cho product_id = $product_id, color = $color, rom = $rom";
    }
} else {
    $response['message'] = "Thiếu tham số product_id, color hoặc rom";
}

echo json_encode($response);
?>