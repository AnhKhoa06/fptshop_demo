<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login1.html');
    exit();
}
?>
<?php
include_once 'header/header.php';
?>
<div class="account-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Tài khoản của <span id="username-display"></span></h3>
        <ul>
            <li><a href="account.php"><i class="fas fa-user"></i> Thông tin tài khoản</a></li>
            <li><i class="fas fa-bell"></i> Thông báo của tôi</li>
            <li><i class="fas fa-box"></i> Quản lý đơn hàng</li>
            <li><i class="fas fa-exchange-alt"></i> Quản lý đổi trả</li>
            <li><i class="fas fa-map-marker-alt"></i> Sổ địa chỉ</li>
            <li><i class="fas fa-credit-card"></i> Thông tin thanh toán</li>
            <li><i class="fas fa-star"></i> Đánh giá sản phẩm</li>
            <li><i class="fas fa-heart"></i> Sản phẩm yêu thích</li>
            <li id="logout-btn"><i class="fas fa-sign-out-alt"></i> Đăng xuất</li>
        </ul>
    </div>
    <script>
        const menuItems = document.querySelectorAll('.sidebar ul li');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                 menuItems.forEach(li => li.classList.remove('active'));
                 item.classList.add('active');
            });
         });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var username = localStorage.getItem("username");
            if (username) {
                document.getElementById("username-display").textContent = username;
            }
        });
    </script>    
    <div id="account-section">
        <section class="account-phone-content">
            <h2>Cập nhật số điện thoại</h2>
            <div class="phone-update-form">
                <form action="save_update.php" method="POST">
                    <div class="phone-form-group">
                        <label for="phone">Số điện thoại</label>
                        <div class="phone-input-icon">
                            <i class="fas fa-phone"></i>
                            <input type="text" id="phone" name="phone" required>
                        </div>
                    </div>

                    <div class="phone-form-actions">
                        <button type="submit" class="phone-btn-save">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <?php if (isset($_SESSION['phone_success']) || isset($_SESSION['phone_error'])): ?>
    <div class="popup-overlay">
        <div class="popup-box">
            <p>
                <?php
                if (isset($_SESSION['phone_success'])) {
                    echo $_SESSION['phone_success'];
                    unset($_SESSION['phone_success']);
                } elseif (isset($_SESSION['phone_error'])) {
                    echo $_SESSION['phone_error'];
                    unset($_SESSION['phone_error']);
                }
                ?>
            </p>
            <button onclick="window.location.href='account.php'">OK</button>
        </div>
    </div>
    <?php endif; ?>

    <script>
        document.querySelector("form").addEventListener("submit", function(e) {
             // Ngăn không gửi về save_update.php

            const newPhone = document.getElementById("phone").value.trim();
            const currentUsername = localStorage.getItem("username");

            if (!newPhone) {
                alert("Vui lòng nhập số điện thoại.");
                return;
            }

            // Lưu vào localStorage
            localStorage.setItem(currentUsername + "_phone", newPhone);

            // Đặt cờ để hiển thị thông báo ở account.php
            localStorage.setItem("phoneUpdateSuccess", "true");

            // Chuyển hướng về trang tài khoản
            window.location.href = "account.php";
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const currentUsername = localStorage.getItem("username");
            if (currentUsername) {
                const phone = localStorage.getItem(currentUsername + "_phone");
                if (phone) {
                    document.getElementById("phone").value = phone;
                }
            }
        });
    </script>
</div>
<?php
include_once 'footer.php';
?>
