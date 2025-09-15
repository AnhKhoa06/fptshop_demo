<?php
session_start();
require_once 'admin/config/db.php';
header('Content-Type: application/json');

$response = ['is_favorite' => false];

$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['user_id'];
$product_id = $input['product_id'];
$color = $input['color'];
$rom = $input['rom'];

$query = "SELECT COUNT(*) as count FROM favorites WHERE user_id = ? AND product_id = ? AND color = ? AND rom = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "iiss", $user_id, $product_id, $color, $rom);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($row['count'] > 0) {
    $response['is_favorite'] = true;
}

mysqli_stmt_close($stmt);
echo json_encode($response);
?>