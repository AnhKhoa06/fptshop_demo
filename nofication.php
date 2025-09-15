<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login1.html');
    exit();
}

require_once 'admin/config/db.php';
$user_id = $_SESSION['user_id'];


?>

<?php
include_once 'header.php';
?>

<link rel="stylesheet" href="css/favorite2.css">

<title>Smart Phone | Thông báo</title>
<div class="account-container">
    <!-- Sidebar -->
    <div class="sidebar1">
        <div class="breadcrumb">
            <i class="fas fa-home" style="color: red;"></i><a href="index.php">Trang chủ</a>
            <i class="fas fa-angle-right"></i>
            <span>Thông Báo</span>
        </div>
        <h3>Tài khoản của <span id="username-display"></span></h3>
        <ul>
            <li><a href="account.php"><i class="fas fa-user"></i> Thông tin tài khoản</a></li>
            <li class="active"><i class="fas fa-bell"></i> Thông báo của tôi</li>
            <li><a href="order.php"><i class="fas fa-box"></i> Quản lý đơn hàng</a></li>
            <li><a href="favorite.php"><i class="fas fa-heart"></i> Sản phẩm yêu thích</a></li>
            <li id="logout-btn"><i class="fas fa-sign-out-alt"></i> Đăng xuất</li>
        </ul>
    </div>  

    <div class="notification-content">
        <h2 class="notification-title">Thông Báo</h2>
        <?php
        // Kiểm tra nếu có thông báo chưa bị xóa trong 5 phút gần nhất
        $notifications = [];
        $time_threshold = date('Y-m-d H:i:s', strtotime('-5 minutes')); // 5 phút trước
        $query = "SELECT order_code, message FROM notifications WHERE user_id = ? AND created_at >= ? AND is_deleted = 0 ORDER BY created_at DESC";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "is", $user_id, $time_threshold);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $notifications[] = [
                'order_code' => htmlspecialchars($row['order_code']),
                'message' => htmlspecialchars($row['message'])
            ];
        }
        mysqli_stmt_close($stmt);

        if (!empty($notifications)) {
            foreach ($notifications as $notification) {
                echo '<div class="notification-box" data-order-code="' . $notification['order_code'] . '">';
                echo '<span class="close-btn" onclick="removeNotification(this, \'' . $notification['order_code'] . '\')">×</span>';
                echo '<h2><i class="fas fa-check-circle tick-icon"></i> Đơn hàng đã được đặt</h2>';
                echo '<p>' . $notification['message'] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<div class="notification-box">';
            echo '<p>Chưa có thông báo nào. Vui lòng kiểm tra lại sau.</p>';
            echo '</div>';
        }
        ?>
    </div>




    <script>

        // Hàm xóa thông báo
        function removeNotification(element, orderCode) {
            // Gửi yêu cầu AJAX để cập nhật trạng thái is_deleted
            fetch('delete_notification.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_code: orderCode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notificationBox = element.parentElement;
                    notificationBox.style.display = 'none'; // Ẩn thông báo
                } else {
                    alert('Lỗi khi xóa thông báo: ' + (data.message || 'Không rõ nguyên nhân'));
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Lỗi khi xóa thông báo: ' + error);
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Hiển thị username
            var username = localStorage.getItem("username");
            if (username) {
                document.getElementById("username-display").textContent = username;
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