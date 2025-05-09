<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login1.html');
    exit();
}

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
            item.addEventListener('click', function () {
                menuItems.forEach(li => li.classList.remove('active'));
                item.classList.add('active');
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var username = localStorage.getItem("username");
            if (username) {
                document.getElementById("username-display").textContent = username;
            }
        });
    </script>

    <!-- Mật khẩu -->
    <div id="account-section">
        <section class="account-password-content">
            <h2>Thiết lập mật khẩu</h2>
            <div class="phone-update-form">
                <form id="password-form" action="save_password.php" method="POST">
                    <div class="phone-form-group">
                        <label for="password">Mật khẩu mới</label>
                        <div class="phone-input-icon">
                            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu mới" required>
                            <i class="far fa-eye toggle-password" onclick="togglePassword('password')"></i>
                        </div>
                        
                    </div>

                    <div class="phone-form-group">
                        <label for="confirm-password">Nhập lại mật khẩu mới</label>
                        <div class="phone-input-icon">
                            <input type="password" id="confirm-password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
                            <i class="far fa-eye toggle-password" onclick="togglePassword('confirm-password')"></i>
                        </div>
                    </div>

                    <div class="phone-form-actions">
                        <button type="submit" class="phone-btn-save" disabled id="save-password-btn">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <?php if (isset($_SESSION['password_success']) || isset($_SESSION['password_error'])): ?>
    <div class="popup-overlay">
        <div class="popup-box">
            <p>
                <?php
                if (isset($_SESSION['password_success'])) {
                    echo $_SESSION['password_success'];
                    unset($_SESSION['password_success']);
                } elseif (isset($_SESSION['password_error'])) {
                    echo $_SESSION['password_error'];
                    unset($_SESSION['password_error']);
                }
                ?>
            </p>
            <button onclick="window.location.href='account.php'">OK</button>
        </div>
    </div>
    <?php endif; ?>



    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = input.nextElementSibling;
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        const passwordInput = document.getElementById("password");
        const confirmInput = document.getElementById("confirm-password");
        const saveBtn = document.getElementById("save-password-btn");

        document.getElementById("password-form").addEventListener("input", function () {
            const password = passwordInput.value.trim();
            const confirm = confirmInput.value.trim();
            saveBtn.disabled = (password === "" || confirm === "" || password !== confirm);
        });

    </script>
</div>
<?php include_once 'footer.php'; ?>
