<?php
// File: update_cart.php - Cập nhật giỏ hàng
session_start();
require_once 'admin/config/db.php';
header('Content-Type: application/json');

// Log để debug
error_log("Request received: " . print_r($_POST, true));

// Kiểm tra phương thức yêu cầu
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Lấy thông tin hành động từ request
$action = isset($_POST['action']) ? $_POST['action'] : '';
$item_id = isset($_POST['item_id']) ? $_POST['item_id'] : '';

error_log("Action: $action, Item ID: $item_id");

// Xử lý theo loại hành động
switch ($action) {
    case 'update':
        // Cập nhật số lượng sản phẩm
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        updateCartItemQuantity($item_id, $quantity);
        break;
        
    case 'remove':
        // Xóa sản phẩm khỏi giỏ hàng
        removeCartItem($item_id);
        break;
        
    default:
        error_log("Invalid action: $action");
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
}

// Hàm cập nhật số lượng sản phẩm trong giỏ hàng
function updateCartItemQuantity($item_id, $quantity) {
    global $connect;
    
    error_log("Updating quantity for item $item_id to $quantity");
    
    // Kiểm tra số lượng hợp lệ
    if ($quantity < 1) {
        echo json_encode(['success' => false, 'message' => 'Số lượng không hợp lệ']);
        exit;
    }
    
    if (isset($_SESSION['user_id'])) {
        // Người dùng đã đăng nhập, cập nhật trong database
        $user_id = $_SESSION['user_id'];
        error_log("User logged in, ID: $user_id");
        
        // Cập nhật số lượng trong database
        $query = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "iii", $quantity, $item_id, $user_id);
        
        error_log("Executing query: $query with params: $quantity, $item_id, $user_id");
        
        if (mysqli_stmt_execute($stmt)) {
            error_log("Cart updated successfully");
            echo json_encode(['success' => true, 'message' => 'Giỏ hàng đã được cập nhật']);
        } else {
            $error = mysqli_error($connect);
            error_log("Database error: $error");
            echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật giỏ hàng: ' . $error]);
        }
    } else {
        // Người dùng chưa đăng nhập, cập nhật trong session
        error_log("User not logged in, updating session cart");
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            $updated = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $item_id) {
                    $item['quantity'] = $quantity;
                    $updated = true;
                    break;
                }
            }
            
            if ($updated) {
                error_log("Session cart updated successfully");
                echo json_encode(['success' => true, 'message' => 'Giỏ hàng đã được cập nhật']);
            } else {
                error_log("Item not found in session cart");
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng']);
            }
        } else {
            error_log("Cart not found in session");
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy giỏ hàng']);
        }
    }
}

// Hàm xóa sản phẩm khỏi giỏ hàng
function removeCartItem($item_id) {
    global $connect;
    
    error_log("Removing item $item_id from cart");
    
    if (isset($_SESSION['user_id'])) {
        // Người dùng đã đăng nhập, xóa từ database
        $user_id = $_SESSION['user_id'];
        error_log("User logged in, ID: $user_id");
        
        // Xóa sản phẩm khỏi database
        $query = "DELETE FROM cart WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "ii", $item_id, $user_id);
        
        error_log("Executing query: $query with params: $item_id, $user_id");
        
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_affected_rows($connect);
            error_log("Rows affected: $affected_rows");
            if ($affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng']);
            }
        } else {
            $error = mysqli_error($connect);
            error_log("Database error: $error");
            echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa sản phẩm: ' . $error]);
        }
    } else {
        // Người dùng chưa đăng nhập, xóa từ session
        error_log("User not logged in, updating session cart");
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            $found = false;
            foreach ($_SESSION['cart'] as $index => $item) {
                if ($item['id'] == $item_id) {
                    unset($_SESSION['cart'][$index]);
                    $found = true;
                    // Cập nhật lại indices
                    $_SESSION['cart'] = array_values($_SESSION['cart']);
                    break;
                }
            }
            
            if ($found) {
                error_log("Item removed from session cart");
                echo json_encode(['success' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
            } else {
                error_log("Item not found in session cart");
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng']);
            }
        } else {
            error_log("Cart not found in session");
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy giỏ hàng']);
        }
    }
}