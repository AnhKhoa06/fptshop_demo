<?php
session_start();
include_once 'header.php';
require_once 'admin/config/db.php';

// Kiểm tra người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn hãy đăng nhập để mua hàng'); window.location.href='login1.html';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$selected_items = [];
$cart_id = isset($_GET['cart_id']) ? intval($_GET['cart_id']) : (isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0);
$total_price = 0;
$total_quantity = 0;
$show_modal = false;

// Xử lý đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $payment_method = $_POST['payment_method'];
    $cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
    $selected_items = !empty($_POST['selected_items']) ? json_decode($_POST['selected_items'], true) : [];

    // Lấy tổng giá từ form (đã giảm nếu có mã áp dụng)
    $total_price = floatval($_POST['total_price']);

    // Tạo mã đơn hàng
    $query_last_order = "SELECT MAX(id) as last_id FROM orders";
    $result_last_order = mysqli_query($connect, $query_last_order);
    if (!$result_last_order) {
        error_log("Lỗi truy vấn last_id: " . mysqli_error($connect));
        echo "<script>alert('Lỗi hệ thống, vui lòng thử lại sau');</script>";
    } else {
        $last_id = mysqli_fetch_assoc($result_last_order)['last_id'] ?? 0;
        $order_code = 'DH' . str_pad($last_id + 1, 5, '0', STR_PAD_LEFT);

        // Lấy thông tin sản phẩm (dùng để lưu order_details, không tính lại total_price)
        $order_items = [];
        if ($cart_id > 0) {
            $query = "SELECT * FROM cart WHERE user_id = ? AND id = ?";
            $stmt = mysqli_prepare($connect, $query);
            mysqli_stmt_bind_param($stmt, "ii", $user_id, $cart_id);
        } elseif (!empty($selected_items)) {
            $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
            $query = "SELECT * FROM cart WHERE user_id = ? AND id IN ($placeholders)";
            $stmt = mysqli_prepare($connect, $query);
            $params = array_merge([$user_id], $selected_items);
            $types = str_repeat('i', count($params));
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        } else {
            echo "<script>alert('Không có sản phẩm để thanh toán'); window.location.href='cart.php';</script>";
            exit();
        }

        if (!mysqli_stmt_execute($stmt)) {
            error_log("Lỗi truy vấn cart: " . mysqli_stmt_error($stmt));
            echo "<script>alert('Lỗi hệ thống, vui lòng thử lại sau');</script>";
        } else {
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $order_items[] = $row;
                $total_quantity += $row['quantity'];
            }
            mysqli_stmt_close($stmt);

            if (empty($order_items)) {
                echo "<script>alert('Không tìm thấy sản phẩm để thanh toán'); window.location.href='cart.php';</script>";
                exit();
            }

            // Lấy và lưu địa chỉ, tên từ localStorage vào bảng users
            $username = $_SESSION['user'] ?? '';
            if ($username) {
                // Lưu địa chỉ
                $address = $_POST['address'] ?? '';
                if (!empty($address)) {
                    $query_update_address = "UPDATE users SET address = ? WHERE id = ?";
                    $stmt_address = mysqli_prepare($connect, $query_update_address);
                    mysqli_stmt_bind_param($stmt_address, "si", $address, $user_id);
                    if (!mysqli_stmt_execute($stmt_address)) {
                        error_log("Lỗi lưu địa chỉ vào users: " . mysqli_stmt_error($stmt_address));
                    }
                    mysqli_stmt_close($stmt_address);
                }

                // Lưu tên
                $full_name = $_POST['full_name'] ?? '';
                if (!empty($full_name)) {
                    $query_update_name = "UPDATE users SET name = ? WHERE id = ?";
                    $stmt_name = mysqli_prepare($connect, $query_update_name);
                    mysqli_stmt_bind_param($stmt_name, "si", $full_name, $user_id);
                    if (!mysqli_stmt_execute($stmt_name)) {
                        error_log("Lỗi lưu tên vào users: " . mysqli_stmt_error($stmt_name));
                    }
                    mysqli_stmt_close($stmt_name);
                }
            }

            // Lưu vào bảng orders với tổng giá đã giảm và số tiền giảm
            $query_order = "INSERT INTO orders (user_id, order_code, created_at, payment_method, status, total_amount, discount_amount) VALUES (?, ?, NOW(), ?, 'Chờ xác nhận', ?, ?)";
            $stmt_order = mysqli_prepare($connect, $query_order);
            $discount_amount = floatval($_POST['discount_amount'] ?? 0); // Lấy số tiền giảm từ form
            mysqli_stmt_bind_param($stmt_order, "issdd", $user_id, $order_code, $payment_method, $total_price, $discount_amount);
            if (!mysqli_stmt_execute($stmt_order)) {
                error_log("Lỗi lưu order: " . mysqli_stmt_error($stmt_order));
                echo "<script>alert('Lỗi hệ thống, vui lòng thử lại sau');</script>";
            } else {
                $order_id = mysqli_insert_id($connect);
                mysqli_stmt_close($stmt_order);

                // Lưu chi tiết sản phẩm vào bảng order_details
                $success = true;
                foreach ($order_items as $item) {
                    $query_product = "SELECT product_code FROM products WHERE prd_id = ?";
                    $stmt_product = mysqli_prepare($connect, $query_product);
                    mysqli_stmt_bind_param($stmt_product, "i", $item['product_id']);
                    if (!mysqli_stmt_execute($stmt_product)) {
                        error_log("Lỗi truy vấn product_code: " . mysqli_stmt_error($stmt_product));
                        $success = false;
                        break;
                    }
                    $result_product = mysqli_stmt_get_result($stmt_product);
                    $product = mysqli_fetch_assoc($result_product);
                    $product_code = $product['product_code'] ?? 'UNKNOWN';
                    mysqli_stmt_close($stmt_product);

                    $query_detail = "INSERT INTO order_details (order_id, product_id, product_code, product_name, color, quantity, unit_price) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt_detail = mysqli_prepare($connect, $query_detail);
                    $product_name = $item['product_name'];
                    mysqli_stmt_bind_param($stmt_detail, "iisssid", $order_id, $item['product_id'], $product_code, $product_name, $item['color'], $item['quantity'], $item['price_discount']);
                    if (!mysqli_stmt_execute($stmt_detail)) {
                        error_log("Lỗi lưu order_details: " . mysqli_stmt_error($stmt_detail));
                        $success = false;
                        break;
                    }
                    mysqli_stmt_close($stmt_detail);

                    // Kiểm tra nếu sản phẩm thuộc Flash Sale, cập nhật cột sold
                    $flash_sale_query = "SELECT id, sold FROM flash_sales WHERE product_id = ? AND color = ? AND rom = ? AND start_time <= NOW() AND end_time >= NOW() LIMIT 1";
                    $flash_sale_stmt = mysqli_prepare($connect, $flash_sale_query);
                    mysqli_stmt_bind_param($flash_sale_stmt, "iss", $item['product_id'], $item['color'], $item['rom']);
                    mysqli_stmt_execute($flash_sale_stmt);
                    $flash_sale_result = mysqli_stmt_get_result($flash_sale_stmt);
                    if ($flash_sale_row = mysqli_fetch_assoc($flash_sale_result)) {
                        $new_sold = $flash_sale_row['sold'] + $item['quantity'];
                        $update_sold_query = "UPDATE flash_sales SET sold = ? WHERE id = ?";
                        $update_sold_stmt = mysqli_prepare($connect, $update_sold_query);
                        mysqli_stmt_bind_param($update_sold_stmt, "ii", $new_sold, $flash_sale_row['id']);
                        if (!mysqli_stmt_execute($update_sold_stmt)) {
                            error_log("Lỗi cập nhật sold trong flash_sales: " . mysqli_stmt_error($update_sold_stmt));
                        }
                        mysqli_stmt_close($update_sold_stmt);
                    }
                    mysqli_stmt_close($flash_sale_stmt);

                    // Cập nhật cột sold trong bảng products cho tất cả sản phẩm (bao gồm cả không thuộc flash sale)
                    $current_sold_query = "SELECT sold FROM products WHERE prd_id = ?";
                    $current_sold_stmt = mysqli_prepare($connect, $current_sold_query);
                    mysqli_stmt_bind_param($current_sold_stmt, "i", $item['product_id']);
                    mysqli_stmt_execute($current_sold_stmt);
                    $current_sold_result = mysqli_stmt_get_result($current_sold_stmt);
                    $current_sold_row = mysqli_fetch_assoc($current_sold_result);
                    $current_sold = $current_sold_row['sold'] ?? 0;
                    $new_sold = $current_sold + $item['quantity'];
                    mysqli_stmt_close($current_sold_stmt);

                    $update_product_sold_query = "UPDATE products SET sold = ? WHERE prd_id = ?";
                    $update_product_sold_stmt = mysqli_prepare($connect, $update_product_sold_query);
                    mysqli_stmt_bind_param($update_product_sold_stmt, "ii", $new_sold, $item['product_id']);
                    if (!mysqli_stmt_execute($update_product_sold_stmt)) {
                        error_log("Lỗi cập nhật sold trong products: " . mysqli_stmt_error($update_product_sold_stmt));
                    }
                    mysqli_stmt_close($update_product_sold_stmt);
                }

                if ($success) {
                    // Xóa sản phẩm khỏi bảng cart
                    if ($cart_id > 0) {
                        $query_delete = "DELETE FROM cart WHERE user_id = ? AND id = ?";
                        $stmt_delete = mysqli_prepare($connect, $query_delete);
                        mysqli_stmt_bind_param($stmt_delete, "ii", $user_id, $cart_id);
                        if (!mysqli_stmt_execute($stmt_delete)) {
                            error_log("Lỗi xóa cart: " . mysqli_stmt_error($stmt_delete));
                        }
                        mysqli_stmt_close($stmt_delete);
                    } elseif (!empty($selected_items)) {
                        $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
                        $query_delete = "DELETE FROM cart WHERE user_id = ? AND id IN ($placeholders)";
                        $stmt_delete = mysqli_prepare($connect, $query_delete);
                        $params = array_merge([$user_id], $selected_items);
                        $types = str_repeat('i', count($params));
                        mysqli_stmt_bind_param($stmt_delete, $types, ...$params);
                        if (!mysqli_stmt_execute($stmt_delete)) {
                            error_log("Lỗi xóa cart: " . mysqli_stmt_error($stmt_delete));
                        }
                        mysqli_stmt_close($stmt_delete);
                    }

                    // Thêm thông báo vào bảng notifications
                    $message = "Đơn hàng $order_code của bạn đã được gửi thành công. Cảm ơn bạn đã tin tưởng và lựa chọn chúng tôi, chúng tôi sẽ nỗ lực mang đến trải nghiệm mua sắm tuyệt vời nhất cho bạn!";
                    $query_notification = "INSERT INTO notifications (user_id, order_code, message, created_at, is_deleted) VALUES (?, ?, ?, NOW(), 0)";
                    $stmt_notification = mysqli_prepare($connect, $query_notification);
                    mysqli_stmt_bind_param($stmt_notification, "iss", $user_id, $order_code, $message);
                    if (!mysqli_stmt_execute($stmt_notification)) {
                        error_log("Lỗi lưu thông báo: " . mysqli_stmt_error($stmt_notification));
                    }
                    mysqli_stmt_close($stmt_notification);

                    // Đặt biến để hiển thị modal
                    $show_modal = true;
                } else {
                    echo "<script>alert('Lỗi khi lưu chi tiết đơn hàng, vui lòng thử lại');</script>";
                }
            }
        }
    }
}

// Lấy thông tin sản phẩm để hiển thị
$order_items = [];
if ($cart_id > 0) {
    $query = "SELECT * FROM cart WHERE user_id = ? AND id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $cart_id);
} elseif (!empty($_POST['selected_items'])) {
    $selected_items = json_decode($_POST['selected_items'], true) ?? [];
    if (!empty($selected_items)) {
        $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
        $query = "SELECT * FROM cart WHERE user_id = ? AND id IN ($placeholders)";
        $stmt = mysqli_prepare($connect, $query);
        $params = array_merge([$user_id], $selected_items);
        $types = str_repeat('i', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
}

if (isset($stmt) && !mysqli_stmt_execute($stmt)) {
    error_log("Lỗi truy vấn cart để hiển thị: " . mysqli_stmt_error($stmt));
    echo "<script>alert('Lỗi hệ thống, vui lòng thử lại sau');</script>";
} elseif (isset($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $order_items[] = $row;
        $total_quantity += $row['quantity'];
        $total_price += $row['price_discount'] * $row['quantity'];
    }
    mysqli_stmt_close($stmt);
}

// Đảm bảo $total_price được cập nhật từ form nếu có
if (isset($_POST['total_price'])) {
    $total_price = floatval($_POST['total_price']);
}


// Lấy danh sách brand_id từ các sản phẩm trong giỏ hàng
$brand_ids = [];
if (!empty($order_items)) {
    $product_ids = array_map(function($item) { return $item['product_id']; }, $order_items);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $query_brands = "SELECT brand_id FROM products WHERE prd_id IN ($placeholders)";
    $stmt_brands = mysqli_prepare($connect, $query_brands);
    mysqli_stmt_bind_param($stmt_brands, str_repeat('i', count($product_ids)), ...$product_ids);
    mysqli_stmt_execute($stmt_brands);
    $result_brands = mysqli_stmt_get_result($stmt_brands);
    while ($row = mysqli_fetch_assoc($result_brands)) {
        if ($row['brand_id']) {
            $brand_ids[] = $row['brand_id'];
        }
    }
    mysqli_stmt_close($stmt_brands);
    $brand_ids = array_unique($brand_ids); // Loại bỏ trùng lặp
}

// Truy vấn khuyến mãi từ bảng promotions dựa trên brand_ids
$current_date = date('Y-m-d'); // Ngày hiện tại: 2025-05-26
$promotions = [];
if (!empty($brand_ids)) {
    $in_clause = implode(',', array_fill(0, count($brand_ids), '?'));
    $sql_promotions = "SELECT promo_code, discount, discount_type, expiry_date, condition_type, condition_value, condition_message, brand_id
                       FROM promotions 
                       WHERE (brand_id IN ($in_clause) OR brand_id IS NULL) 
                       AND expiry_date >= ? 
                       ORDER BY expiry_date ASC";
    $params = array_merge($brand_ids, [$current_date]);
    $types = str_repeat('i', count($brand_ids)) . 's';
    $stmt_promotions = mysqli_prepare($connect, $sql_promotions);
    mysqli_stmt_bind_param($stmt_promotions, $types, ...$params);
} else {
    // Nếu giỏ hàng trống, chỉ lấy khuyến mãi áp dụng cho tất cả (brand_id IS NULL)
    $sql_promotions = "SELECT promo_code, discount, discount_type, expiry_date, condition_type, condition_value, condition_message, brand_id
                       FROM promotions 
                       WHERE brand_id IS NULL 
                       AND expiry_date >= ? 
                       ORDER BY expiry_date ASC";
    $stmt_promotions = mysqli_prepare($connect, $sql_promotions);
    mysqli_stmt_bind_param($stmt_promotions, "s", $current_date);
}
mysqli_stmt_execute($stmt_promotions);
$result_promotions = mysqli_stmt_get_result($stmt_promotions);
$promotions = mysqli_fetch_all($result_promotions, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_promotions);

// Chuyển đổi dữ liệu khuyến mãi thành JSON để sử dụng trong JavaScript
$promo_data = [];
foreach ($promotions as $promo) {
    $promo_data[$promo['promo_code']] = [
        'discount' => $promo['discount_type'] == 'percent' ? $promo['discount'] / 100 : $promo['discount'],
        'expiry' => $promo['expiry_date'],
        'type' => $promo['discount_type'],
        'condition' => [
            'type' => $promo['condition_type'],
            'value' => $promo['condition_value'],
            'message' => $promo['condition_message']
        ]
    ];
}
$promo_json = json_encode($promo_data);

?>
<title>Smart Phone | Thanh toán</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="css/checkout6.css">

<div class="checkout-container">
    <div class="breadcrumb">
        <i class="fas fa-home" style="color: red;"></i><a href="index.php">Trang chủ</a>
        <i class="fas fa-angle-right"></i><a href="cart.php">Giỏ hàng</a>
        <i class="fas fa-angle-right"></i>
        <span>Thanh toán</span>
    </div>
    <div class="checkout-title">
        <h2>THANH TOÁN</h2>
    </div>
    
    <form id="checkout-form" method="POST">
        <input type="hidden" name="place_order" value="1">
        <input type="hidden" name="selected_items" value='<?php echo htmlspecialchars(json_encode($selected_items)); ?>'>
        <input type="hidden" name="cart_id" value="<?php echo $cart_id; ?>">
        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
        <input type="hidden" name="payment_method" id="payment-method" value="cod">
        <input type="hidden" name="address" id="address-input">
        <input type="hidden" name="full_name" id="full-name-input"> <!-- Thêm input ẩn để lưu tên -->
        <input type="hidden" name="discount_amount" id="discount-amount-hidden">

        <div class="checkout-content">
            <div class="checkout-left">
                <div class="order-details">
                    <h3>Sản phẩm trong đơn (<?php echo $total_quantity; ?>)</h3>
                    <div class="order-items">
                        <?php if (!empty($order_items)): ?>
                            <?php foreach ($order_items as $item): ?>
                                <div class="order-item">
                                    <div class="item-img">
                                        <img src="<?php echo strpos($item['image'], 'http') === 0 ? $item['image'] : 'admin/img/' . htmlspecialchars(basename($item['image'])); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                    </div>
                                    <div class="item-info">
                                        <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                        <div class="item-color">Màu: <?php echo htmlspecialchars($item['color']); ?></div>
                                    </div>
                                    <div class="item-quantity">x<?php echo $item['quantity']; ?></div>
                                    <div class="item-price">
                                        <div class="current-price"><?php echo number_format($item['price_discount'], 0, ',', '.'); ?> <span class="currency">đ</span></div>
                                        <div class="original-price"><?php echo number_format($item['price'], 0, ',', '.'); ?> <span class="currency1">đ</span></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không có sản phẩm nào được chọn để thanh toán.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="payment-methods">
                    <h3>Vui lòng chọn phương thức thanh toán</h3>
                    <div class="payment-options">
                        <div class="payment-option active" onclick="updateDescription(this, 'cod'); document.getElementById('payment-method').value='cod';">
                            <input type="radio" id="cod" name="payment" value="cod" checked>
                            <label for="cod">Thanh toán khi nhận hàng (COD)</label>
                            <div class="payment-description">
                                Bạn chỉ phải thanh toán khi nhận hàng
                            </div>
                        </div>
                        <div class="payment-option" onclick="updateDescription(this, 'vnpay'); document.getElementById('payment-method').value='vnpay';">
                            <input type="radio" id="vnpay" name="payment" value="vnpay">
                            <label for="vnpay">Thanh toán Online (Online Payment)</label>
                            <div class="payment-description">
                                Thanh toán qua cổng thanh toán VN Pay
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="checkout-right">
                <div class="shipping-info">
                    <div class="section-header">
                        <h3>Thông tin giao hàng</h3>
                        <a href="account.php" class="change-btn">Thay đổi</a>
                    </div>
                    <div class="customer-info" id="customer-info">
                        <!-- Thông tin sẽ được cập nhật bằng JavaScript -->
                    </div>
                </div>
                
                <div class="promo-code">
                    <h3>Khuyến mãi</h3>
                    <div class="promo-input">
                        <input type="text" id="promo-code-input" placeholder="Nhập mã giảm giá ...">
                        <button type="button" class="apply-btn" onclick="applyPromoCode()">Áp dụng</button>
                    </div>
                    <div id="promo-message" class="promo-message"></div>
                </div>
                
                <div class="order-summary">
                    <div class="section-header">
                        <h3>Đơn hàng</h3>
                        <a href="cart.php" class="change-btn">Thay đổi</a>
                    </div>
                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Tạm tính</span>
                            <span class="price"><?php echo number_format($total_price, 0, ',', '.'); ?></span><span class="currency2"><u>đ</u></span>
                        </div>
                        <div class="summary-row">
                            <span>Phí vận chuyển</span>
                            <span class="price">Miễn phí</span>
                        </div>
                        <div class="summary-row">
                            <span>Giảm giá</span>
                            <span class="discount1" id="discount-amount">- 0</span><span class="currency3"><u>đ</u></span>
                        </div>
                        <div class="summary-row total">
                            <span>Tổng tiền</span>
                            <span class="total-price" id="total-price"><?php echo number_format($total_price, 0, ',', '.'); ?></span><span class="currency4"><u>đ</u></span>
                        </div>
                    </div>
                    <button type="submit" class="order-btn">Đặt hàng</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        let totalPrice = <?php echo $total_price; ?>;
        let totalQuantity = <?php echo $total_quantity; ?>;
        let appliedDiscount = 0;

        // Truyền dữ liệu khuyến mãi từ PHP sang JavaScript
        const promoList = <?php echo $promo_json; ?>;

        function applyPromoCode(event) {
            event.preventDefault();

            const promoCode = document.getElementById('promo-code-input').value.trim();
            const promoMessage = document.getElementById('promo-message');
            const discountAmount = document.getElementById('discount-amount');
            const totalPriceEl = document.getElementById('total-price');
            const totalPriceInput = document.querySelector('input[name="total_price"]');
            const discountAmountInput = document.getElementById('discount-amount-hidden');

            if (promoList[promoCode]) {
                const promo = promoList[promoCode];
                const expiryDate = new Date(promo.expiry);
                const now = new Date();
                if (now <= expiryDate) {
                    let conditionMet = true;
                    if (promo.condition.type === 'quantity' && totalQuantity < promo.condition.value) {
                        conditionMet = false;
                        promoMessage.textContent = 'Mã không áp dụng! ' + promo.condition.message;
                        promoMessage.style.color = '#dc3545';
                    } else if (promo.condition.type === 'total' && totalPrice < promo.condition.value) {
                        conditionMet = false;
                        promoMessage.textContent = 'Mã không áp dụng! ' + promo.condition.message;
                        promoMessage.style.color = '#dc3545';
                    }

                    if (conditionMet) {
                        let discountValue = 0;
                        let newTotal = totalPrice;

                        if (promo.type === 'percent') {
                            discountValue = totalPrice * promo.discount;
                            appliedDiscount = discountValue;
                            newTotal = totalPrice - appliedDiscount;
                            discountAmount.textContent = '-' + number_format(Math.round(discountValue));
                        } else {
                            discountValue = Math.min(promo.discount, totalPrice); // Giới hạn giảm giá không vượt tổng tiền
                            appliedDiscount = discountValue;
                            newTotal = totalPrice - appliedDiscount;
                            discountAmount.textContent = '-' + number_format(Math.round(discountValue));
                        }

                        totalPriceEl.textContent = number_format(Math.round(newTotal));
                        totalPriceInput.value = Math.round(newTotal);
                        discountAmountInput.value = Math.round(discountValue); // Cập nhật số tiền giảm
                        promoMessage.textContent = 'Áp dụng thành công! Giảm ' + (promo.type === 'percent' ? (promo.discount * 100) + '%' : number_format(promo.discount, 0, ',', '.') + '₫');
                        promoMessage.style.color = '#28a745';
                    } else {
                        appliedDiscount = 0;
                        discountAmount.textContent = '- 0';
                        totalPriceEl.textContent = number_format(totalPrice);
                        totalPriceInput.value = totalPrice;
                        discountAmountInput.value = 0;
                    }
                } else {
                    appliedDiscount = 0;
                    promoMessage.textContent = 'Mã khuyến mãi đã hết hạn!';
                    promoMessage.style.color = '#dc3545';
                    discountAmount.textContent = '- 0';
                    totalPriceEl.textContent = number_format(totalPrice);
                    totalPriceInput.value = totalPrice;
                    discountAmountInput.value = 0;
                }
            } else {
                appliedDiscount = 0;
                promoMessage.textContent = 'Mã khuyến mãi không hợp lệ!';
                promoMessage.style.color = '#dc3545';
                discountAmount.textContent = '- 0';
                totalPriceEl.textContent = number_format(totalPrice);
                totalPriceInput.value = totalPrice;
                discountAmountInput.value = 0;
            }
        }

        function number_format(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        document.querySelector('.apply-btn').addEventListener('click', applyPromoCode);
    </script>

    <!-- Modal xác nhận đặt hàng -->
    <div id="order-success-modal" class="modal-overlay" style="display: <?php echo $show_modal ? 'flex' : 'none'; ?>;">
        <div class="confirmation-modal">
            <div class="check-icon"><i class="fas fa-check"></i></div>
            <h3 class="modal-title">Mua hàng thành công</h3>
            <p class="modal-message">
                Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi.<br>
                Sản phẩm của bạn sẽ được chuyển đến trong thời gian sớm nhất
            </p>
            <button class="modal-btn" onclick="window.location.href='order.php'">Xem Đơn Hàng</button>
        </div>
    </div>
</div>

<script>
    function updateDescription(element, option) {
        const allOptions = document.querySelectorAll('.payment-option');
        allOptions.forEach(opt => opt.classList.remove('active'));
        element.classList.add('active');
    }
    
    // Cập nhật thông tin giao hàng
    document.addEventListener("DOMContentLoaded", function () {
        const customerInfo = document.getElementById('customer-info');
        const addressInput = document.getElementById('address-input');
        const fullNameInput = document.getElementById('full-name-input'); // Thêm input ẩn để lưu tên
        const currentUsername = localStorage.getItem("username");

        if (!currentUsername) {
            customerInfo.innerHTML = `
                <p class="customer-address">Bạn chưa đăng nhập.</p>
                <p class="customer-address"><a href="login1.html">Đăng nhập ngay</a></p>
            `;
            return;
        }

        const fullName = localStorage.getItem(currentUsername + "_name");
        const phone = localStorage.getItem(currentUsername + "_phone");
        const savedAddress = localStorage.getItem(currentUsername + "_address");

        let addressData = {};
        if (savedAddress) {
            try {
                addressData = JSON.parse(savedAddress);
            } catch (error) {
                console.error('Error parsing address from localStorage:', error);
            }
        }

        const isInfoComplete = fullName && phone && addressData && addressData.street && addressData.ward && addressData.ward.name && addressData.district && addressData.district.name && addressData.province && addressData.province.name;

        if (isInfoComplete) {
            const fullAddress = [
                addressData.street,
                addressData.ward.name,
                addressData.district.name,
                addressData.province.name
            ].filter(Boolean).join(', ');

            customerInfo.innerHTML = `
                <p class="customer-name">${fullName} | ${phone}</p>
                <p class="customer-address">${fullAddress}</p>
            `;
            // Lưu địa chỉ và tên vào input ẩn để gửi lên server
            addressInput.value = JSON.stringify(addressData);
            fullNameInput.value = fullName; // Lưu tên vào input ẩn
        } else {
            customerInfo.innerHTML = `
                <p class="customer-address">Bạn chưa có địa chỉ giao hàng?</p>
                <p class="customer-address"><a href="account.php">Thêm địa chỉ giao hàng</a></p>
            `;
            addressInput.value = '';
            fullNameInput.value = '';
        }
    });
</script>

<?php
include_once 'footer.php';
?>