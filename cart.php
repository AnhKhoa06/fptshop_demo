<?php
session_start();
include_once 'header.php';
require_once 'admin/config/db.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="css/cartbuy3.css">
<title>Smart Phone | Giỏ hàng</title>
<div class="breadcrumb">
    <i class="fas fa-home" style="color: red;"></i><a href="index.php">Trang chủ</a>
    <i class="fas fa-angle-right"></i>
    <span>Giỏ Hàng</span>
</div>

<div class="cart-container">
    <div class="cart-header">
        <h2>Giỏ Hàng <span class="product-count">(<span class="product-count-number">0</span> Sản Phẩm)</span></h2>
    </div>

    <div class="cart-selection-bar">
        <div class="select-all-container">
            <input type="checkbox" id="select-all" class="select-all-checkbox">
            <label for="select-all">Chọn tất cả (0)</label>
        </div>
        <div class="bulk-actions">
            <button class="bulk-delete-btn"><i class="fas fa-trash-alt"></i></button>
        </div>
        <div class="order-summary">
            <i class="fas fa-shopping-cart"></i> Tóm tắt đơn hàng
        </div>
    </div>
    
    <div class="cart-content">
        <div class="cart-items">
            <?php
            // Lấy dữ liệu giỏ hàng
            $cartItems = [];
            
            if (isset($_SESSION['user_id'])) {
                // Người dùng đã đăng nhập, lấy từ database
                $user_id = $_SESSION['user_id'];
                $query = "SELECT * FROM cart WHERE user_id = ?";
                $stmt = mysqli_prepare($connect, $query);
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                while ($row = mysqli_fetch_assoc($result)) {
                    $cartItems[] = $row;
                }
            } else {
                // Người dùng chưa đăng nhập, lấy từ session
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    $cartItems = $_SESSION['cart'];
                }
            }
            
            // Hiển thị các sản phẩm trong giỏ hàng
            if (count($cartItems) > 0) {
                foreach ($cartItems as $item) {
                    // Kiểm tra xem product_name đã chứa rom hay chưa
                    $display_name = htmlspecialchars($item['product_name']);
                    if (stripos($item['product_name'], $item['rom']) === false) {
                        $display_name = htmlspecialchars(trim($item['product_name'] . ' ' . $item['rom']));
                    }
                    ?>
                    <div class="cart-item" data-id="<?= $item['id'] ?>">
                        <div class="product-checkbox">
                            <input type="checkbox" class="item-checkbox">
                        </div>
                        <div class="product-image">
                            <img src="<?= strpos($item['image'], 'http') === 0 ? $item['image'] : 'admin/img/' . htmlspecialchars(basename($item['image'])) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                        </div>
                        <div class="product-details">
                            <h3><?= $display_name ?> - <?= htmlspecialchars($item['color']) ?></h3>
                            <div class="price-container">
                                <span class="current-price"><?= number_format($item['price_discount'], 0, ',', '.') ?></span><span class="currency-symbol">₫</span>
                                <?php if ($item['price'] > $item['price_discount']): ?>
                                    <span class="original-price"><?= number_format($item['price'], 0, ',', '.') ?></span><span class="currency-symbol1">₫</span>
                                <?php endif; ?>
                            </div>
                            <div class="quantity-controls">
                                <button class="quantity-btn decrease">−</button>
                                <input type="text" class="quantity-input" value="<?= $item['quantity'] ?>" data-price="<?= $item['price_discount'] ?>" readonly>
                                <button class="quantity-btn increase">+</button>
                            </div>
                        </div>
                        <div class="remove-action">
                            <button class="remove-btn" data-id="<?= $item['id'] ?>"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // Hiển thị thông báo khi giỏ hàng trống
                echo '<div class="empty-cart-message">Giỏ hàng của bạn hiện đang trống</div>';
            }
            ?>
        </div>
        
        <div class="cart-summary">
            <div class="summary-row total-provisional">
                <span>Tạm Tính</span>
                <div class="price-wrapper">
                    <span class="price">0</span><span class="currency-symbol">₫</span>
                </div>
            </div>
            <div class="summary-row total-final">
                <span>Thành Tiền</span>
                <div class="price-wrapper">
                    <span class="price">0</span><span class="currency-symbol">₫</span>
                </div>
            </div>
            <div class="checkout-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form id="checkout-form" action="checkout.php" method="POST">
                        <input type="hidden" name="selected_items" id="selected-items">
                        <input type="hidden" name="total_price" id="total-price">
                        <button type="submit" class="checkout-btn" data-logged-in="true">Thanh Toán Ngay</button>
                    </form>
                <?php else: ?>
                    <button class="checkout-btn" data-logged-in="false" onclick="alert('Bạn hãy đăng nhập để mua hàng'); event.stopImmediatePropagation(); window.location.href='login1.html';">Thanh Toán Ngay</button>
                <?php endif; ?>
                <a href="index.php"><button class="continue-shopping-btn">Tiếp Tục Mua Hàng</button></a>
            </div>
        </div>
    </div>
</div>

<script src="cart.js"></script>
<?php
include_once 'footer.php';
?>