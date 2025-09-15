<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login1.html');
    exit();
}

require_once 'admin/config/db.php';
$user_id = $_SESSION['user_id'];

// Lấy order_code từ URL
$order_code = isset($_GET['code']) ? $_GET['code'] : '';
if (empty($order_code)) {
    header('Location: order.php');
    exit();
}

// Lấy thông tin đơn hàng
$query_order = "SELECT order_code, payment_method, total_amount, created_at, discount_amount 
                FROM orders 
                WHERE order_code = ? AND user_id = ?";
$stmt_order = mysqli_prepare($connect, $query_order);
mysqli_stmt_bind_param($stmt_order, "si", $order_code, $user_id);
mysqli_stmt_execute($stmt_order);
$result_order = mysqli_stmt_get_result($stmt_order);
$order = mysqli_fetch_assoc($result_order);
mysqli_stmt_close($stmt_order);

if (!$order) {
    header('Location: order.php');
    exit();
}

// Lấy chi tiết sản phẩm
$query_details = "SELECT product_code, product_name, color, quantity, unit_price 
                  FROM order_details 
                  WHERE order_id = (SELECT id FROM orders WHERE order_code = ?)";
$stmt_details = mysqli_prepare($connect, $query_details);
mysqli_stmt_bind_param($stmt_details, "s", $order_code);
mysqli_stmt_execute($stmt_details);
$result_details = mysqli_stmt_get_result($stmt_details);
$order_items = [];
$total_quantity = 0;
while ($row = mysqli_fetch_assoc($result_details)) {
    $order_items[] = $row;
    $total_quantity += $row['quantity'];
}
mysqli_stmt_close($stmt_details);
?>

<?php
include_once 'header.php';
?>

<link rel="stylesheet" href="css/order4.css">
<link rel="stylesheet" href="css/order_detail.css">

<title>Smart Phone | Chi Tiết Đơn Hàng</title>
<div class="account-container">
    <!-- Sidebar -->
    <div class="sidebar1">
        <div class="breadcrumb">
            <i class="fas fa-home" style="color: red;"></i><a href="index.php">Trang chủ</a>
            <i class="fas fa-angle-right"></i>
            <a href="order.php">Đơn hàng</a>
        </div>
        <h3>Tài khoản của <span id="username-display"></span></h3>
        <ul>
            <li><a href="account.php"><i class="fas fa-user"></i> Thông tin tài khoản</a></li>
            <li><a href="nofication.php"><i class="fas fa-bell"></i> Thông báo của tôi</a></li>
            <li class="active"><i class="fas fa-box"></i> Quản lý đơn hàng</li>
            <li><a href="favorite.php"><i class="fas fa-heart"></i> Sản phẩm yêu thích</a></li>
            <li id="logout-btn"><i class="fas fa-sign-out-alt"></i> Đăng xuất</li>
        </ul>
    </div>

    <!-- Order Detail Content -->
    <div class="order-detail-content">
        <div class="order-detail-header">
            <h2>Chi Tiết Đơn Hàng #<?php echo htmlspecialchars($order_code); ?></h2>
            <a href="order.php" class="back-button"><i class="fas fa-arrow-left"></i> Quay lại đơn hàng</a>
        </div>

        <div class="order-info-container">
            <div class="info-section">
                <h3>Thông Tin Tài Khoản</h3>
                <div class="info-content" id="account-info">
                    <!-- Được cập nhật bằng JavaScript -->
                </div>
            </div>

            <div class="info-section">
                <h3>Thông Tin Giao Hàng</h3>
                <div class="info-content" id="shipping-info">
                    <!-- Được cập nhật bằng JavaScript -->
                </div>
            </div>
        </div>

        <div class="order-details-section">
            <h3>Thông Tin Đơn Hàng</h3>
            <div class="info-content">
                <div class="info-row">
                    <div class="info-label">Mã Đơn hàng</div>
                    <div class="info-value1"><?php echo htmlspecialchars($order['order_code']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Phương Thức Thanh Toán</div>
                    <div class="info-value1"><?php echo $order['payment_method'] == 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Thanh toán Online (VNPay)'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Số Lượng</div>
                    <div class="info-value1"><?php echo $total_quantity; ?> sản phẩm</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Đơn giá tạm tính</div>
                    <div class="info-value1 total-price"><?php echo number_format($order['total_amount'] + $order['discount_amount'], 0, ',', '.'); ?>đ</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Giảm giá</div>
                    <div class="info-value1"><?php echo number_format($order['discount_amount'], 0, ',', '.'); ?>đ</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Thành Tiền</div>
                    <div class="info-value1 total-price"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</div>
                </div>
            </div>
        </div>

        <div class="product-list-section">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã Sản Phẩm</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Màu Sắc</th>
                        <th>Số Lượng</th>
                        <th>Đơn Giá</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $index => $item): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($item['product_code']); ?></td>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['color']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['unit_price'], 0, ',', '.'); ?>đ</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Hiển thị username
            var username = localStorage.getItem("username");
            if (username) {
                document.getElementById("username-display").textContent = username;
            }

            // Cập nhật thông tin tài khoản và giao hàng
            const accountInfo = document.getElementById('account-info');
            const shippingInfo = document.getElementById('shipping-info');
            const fullName = localStorage.getItem(username + "_name");
            const phone = localStorage.getItem(username + "_phone");
            const savedAddress = localStorage.getItem(username + "_address");
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

                accountInfo.innerHTML = `
                    <div class="info-row">
                        <div class="info-label">Tên</div>
                        <div class="info-value">${fullName}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Số Điện Thoại</div>
                        <div class="info-value">${phone}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Địa Chỉ</div>
                        <div class="info-value">${fullAddress}</div>
                    </div>
                `;
                shippingInfo.innerHTML = `
                    <div class="info-row">
                        <div class="info-label">Tên</div>
                        <div class="info-value">${fullName}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Số Điện Thoại</div>
                        <div class="info-value">${phone}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Địa Chỉ</div>
                        <div class="info-value">${fullAddress}</div>
                    </div>
                `;
            } else {
                accountInfo.innerHTML = shippingInfo.innerHTML = `
                    <p class="info-value">Thông tin chưa được cập nhật.</p>
                `;
            }

            // Xử lý sidebar
            const menuItems = document.querySelectorAll('.sidebar1 ul li');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    menuItems.forEach(li => li.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Xử lý đăng xuất
            const logoutBtn = document.getElementById("logout-btn");
            const logoutModal = document.getElementById("logout-modal");
            const cancelLogout = document.getElementById("cancel-logout");
            const confirmLogout = document.getElementById("confirm-logout");

            logoutModal.style.display = "none";

            logoutBtn.addEventListener("click", function(event) {
                event.stopPropagation();
                logoutModal.style.display = "flex";
            });

            cancelLogout.addEventListener("click", function() {
                logoutModal.style.display = "none";
            });

            confirmLogout.addEventListener("click", function() {
                window.location.href = "index.php?logout=true";
            });

            window.addEventListener("click", function(event) {
                if (event.target === logoutModal) {
                    logoutModal.style.display = "none";
                }
            });
        });
    </script>

    <!-- Modal xác nhận đăng xuất -->
    <div id="logout-modal" class="modal">
        <div class="modal-content">
            <p>Bạn muốn thoát tài khoản?</p>
            <div class="modal-actions">
                <button id="cancel-logout" class="cancel-btn">Không</button>
                <button id="confirm-logout" class="confirm-btn">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'footer.php';
?>