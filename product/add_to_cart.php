<?php
session_start();
require_once '../admin/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : '';
    $color = isset($_POST['color']) ? trim($_POST['color']) : '';
    $rom = isset($_POST['rom']) ? trim($_POST['rom']) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $price_discount = isset($_POST['price_discount']) ? floatval($_POST['price_discount']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $image = isset($_POST['image']) ? trim($_POST['image']) : '';
    $action = isset($_POST['action']) ? trim($_POST['action']) : ''; // Phân biệt "Thêm vào giỏ" và "Mua ngay"

    // Kiểm tra dữ liệu đầu vào
    if ($product_id <= 0 || empty($product_name) || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit();
    }

    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (isset($_SESSION['user_id'])) {
        // Đã đăng nhập, lưu vào database
        $user_id = $_SESSION['user_id'];
        $cart_id = 0; // Biến để lưu ID của bản ghi trong bảng cart

        // Kiểm tra nếu sản phẩm đã có trong giỏ hàng của user
        $checkQuery = "SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND color = ? AND rom = ?";
        $stmt = mysqli_prepare($connect, $checkQuery);
        mysqli_stmt_bind_param($stmt, "iiss", $user_id, $product_id, $color, $rom);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // Sản phẩm đã tồn tại, cập nhật số lượng
            $cartItem = mysqli_fetch_assoc($result);
            $newQuantity = $cartItem['quantity'] + $quantity;
            $cart_id = $cartItem['id'];

            $updateQuery = "UPDATE cart SET quantity = ? WHERE id = ?";
            $stmt = mysqli_prepare($connect, $updateQuery);
            mysqli_stmt_bind_param($stmt, "ii", $newQuantity, $cart_id);
            $success = mysqli_stmt_execute($stmt);
        } else {
            // Sản phẩm chưa có, thêm mới
            $insertQuery = "INSERT INTO cart (user_id, product_id, product_name, color, rom, price, price_discount, quantity, image) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connect, $insertQuery);
            mysqli_stmt_bind_param($stmt, "iisssdiss", $user_id, $product_id, $product_name, $color, $rom, $price, $price_discount, $quantity, $image);
            $success = mysqli_stmt_execute($stmt);
            $cart_id = mysqli_insert_id($connect); // Lấy ID của bản ghi vừa thêm
        }

        mysqli_stmt_close($stmt);

        if ($success) {
            // Trả về kết quả cho Ajax
            $response = ['success' => true, 'message' => 'Đã thêm sản phẩm vào giỏ hàng'];
            if ($action === 'buy_now') {
                $response['cart_id'] = $cart_id; // Trả về cart_id nếu là "Mua ngay"
            }
            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi thêm sản phẩm']);
        }
    } else {
        // Chưa đăng nhập, lưu vào session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Tạo một ID tạm thời cho sản phẩm trong giỏ hàng
        $cart_item_id = $product_id . '_' . $rom . '_' . $color;

        // Kiểm tra nếu sản phẩm đã có trong giỏ
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if (
                $item['product_id'] == $product_id && 
                $item['color'] == $color && 
                $item['rom'] == $rom
            ) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            // Thêm sản phẩm mới vào giỏ hàng session
            $_SESSION['cart'][] = [
                'id' => $cart_item_id,
                'product_id' => $product_id,
                'product_name' => $product_name,
                'color' => $color,
                'rom' => $rom,
                'price' => $price,
                'price_discount' => $price_discount,
                'quantity' => $quantity,
                'image' => $image
            ];
        }

        // Trả về kết quả cho Ajax
        echo json_encode(['success' => true, 'message' => 'Đã thêm sản phẩm vào giỏ hàng', 'loginRequired' => false]);
    }

    mysqli_close($connect);
} else {
    // Method không phải POST
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

?>