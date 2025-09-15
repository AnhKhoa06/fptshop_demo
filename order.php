<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login1.html');
    exit();
}

require_once 'admin/config/db.php';
$user_id = $_SESSION['user_id'];

// Lấy danh sách đơn hàng
$query_orders = "SELECT order_code, created_at, payment_method, status, total_amount 
                 FROM orders 
                 WHERE user_id = ? 
                 ORDER BY created_at DESC";
$stmt_orders = mysqli_prepare($connect, $query_orders);
mysqli_stmt_bind_param($stmt_orders, "i", $user_id);
mysqli_stmt_execute($stmt_orders);
$result_orders = mysqli_stmt_get_result($stmt_orders);
$orders = [];
while ($row = mysqli_fetch_assoc($result_orders)) {
    $orders[] = $row;
}
mysqli_stmt_close($stmt_orders);
?>

<?php
include_once 'header.php';
?>

<link rel="stylesheet" href="css/order5.css">

<title>Smart Phone | Quản Lý Đơn Hàng</title>
<div class="account-container">
    <!-- Sidebar -->
    <div class="sidebar1">
        <div class="breadcrumb">
            <i class="fas fa-home" style="color: red;"></i><a href="index.php">Trang chủ</a>
            <i class="fas fa-angle-right"></i>
            <span>Đơn hàng</span>
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

    <!-- Order management content -->
    <div class="order-content">
        <h2 class="order-title">Quản lý đơn hàng</h2>
        
        <table class="order-table">
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Thời gian</th>
                    <th>PTTT</th>
                    <th>Trạng thái</th>
                    <th>Tổng tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_code']); ?></td>
                            <td><?php echo date('d-m-Y / H:i:s', strtotime($order['created_at'])); ?></td>
                            <td><?php echo $order['payment_method'] == 'cod' ? 'Tiền mặt' : 'VNPay'; ?></td>
                            <td>
                                <span class="status-<?php echo strtolower(str_replace(' ', '-', $order['status'])); ?>">
                                    <?php echo htmlspecialchars(str_replace('Hủy', 'Hủy ', $order['status'])); ?>
                                </span>
                            </td>
                            <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</td>
                            <td>
                                <?php if ($order['status'] == 'Chờ xác nhận'): ?>
                                    <button class="action-btn cancel-order-btn" data-order-code="<?php echo $order['order_code']; ?>">Hủy đơn</button>
                                <?php elseif ($order['status'] == 'Đang xử lý'): ?>
                                    <button class="action-btn receive-order-btn" data-order-code="<?php echo $order['order_code']; ?>">Nhận hàng</button>
                                <?php endif; ?>
                                <button class="action-btn view-btn" data-order-code="<?php echo $order['order_code']; ?>">Xem chi tiết</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Bạn chưa có đơn hàng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hiển thị username
            var username = localStorage.getItem("username");
            if (username) {
                document.getElementById("username-display").textContent = username;
            }

            // Xử lý nút Xem chi tiết
            const viewButtons = document.querySelectorAll('.view-btn');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const orderCode = this.getAttribute('data-order-code');
                    window.location.href = 'order_detail.php?code=' + orderCode;
                });
            });

            // Xử lý nút Hủy đơn
            const cancelButtons = document.querySelectorAll('.cancel-order-btn');
            cancelButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const orderCode = this.getAttribute('data-order-code');
                    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng ' + orderCode + '?')) {
                        fetch('cancel_order.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ order_code: orderCode })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Đã hủy đơn hàng ' + orderCode);
                                const row = this.closest('tr');
                                row.querySelector('td:nth-child(4)').innerHTML = '<span class="status-hủy-đơn">Hủy đơn</span>';
                                this.remove();
                            } else {
                                alert(data.message || 'Lỗi khi hủy đơn hàng');
                            }
                        })
                        .catch(error => alert('Lỗi: ' + error));
                    }
                });
            });

            // Xử lý nút Nhận hàng
            const receiveButtons = document.querySelectorAll('.receive-order-btn');
            receiveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const orderCode = this.getAttribute('data-order-code');
                    if (confirm('Bạn đã nhận được hàng ' + orderCode + '?')) {
                        fetch('admin/update_status.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ order_code: orderCode, status: 'Đã giao' })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Đã xác nhận nhận hàng ' + orderCode);
                                const row = this.closest('tr');
                                row.querySelector('td:nth-child(4)').innerHTML = '<span class="status-đã-giao">Đã giao</span>';
                                this.remove();
                            } else {
                                alert(data.message || 'Lỗi khi xác nhận nhận hàng');
                            }
                        })
                        .catch(error => alert('Lỗi: ' + error));
                    }
                });
            });

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

            logoutModal.addEventListener("click", function(event) {
                if (event.target === logoutModal) {
                    logoutModal.style.display = "none";
                }
            });

            cancelLogout.addEventListener("click", function() {
                logoutModal.style.display = "none";
            });

            confirmLogout.addEventListener("click", function() {
                window.location.href = "index.php?logout=true";
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