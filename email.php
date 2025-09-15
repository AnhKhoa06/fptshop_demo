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
<title>Smart Phone | Cập nhật email</title>
<div class="account-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="breadcrumb">
            <i class="fas fa-home" style="color: red;"></i><a href="index.php">Trang chủ</a>
            <i class="fas fa-angle-right"></i><a href="account.php">Tài Khoản</a>
            <i class="fas fa-angle-right"></i>
            <span>Email</span>
        </div>
        <h3>Tài khoản của <span id="username-display"></span></h3>
        <ul>
            <li><a href="account.php"><i class="fas fa-user"></i> Thông tin tài khoản</a></li>
            <li><i class="fas fa-bell"></i> Thông báo của tôi</li>
            <li><i class="fas fa-box"></i> Quản lý đơn hàng</li>
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
            <h2>Cập nhật email</h2>
            <div class="phone-update-form">
                <form action="save_email.php" method="POST">
                    <div class="phone-form-group">
                        <label for="email">Email</label>
                        <div class="phone-input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" required>
                        </div>
                    </div>

                    <div class="phone-form-actions">
                        <button type="submit" class="phone-btn-save">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <?php if (isset($_SESSION['email_success']) || isset($_SESSION['email_error'])): ?>
    <div class="popup-overlay">
        <div class="popup-box">
            <p>
                <?php
                if (isset($_SESSION['email_success'])) {
                    echo $_SESSION['email_success'];
                    unset($_SESSION['email_success']);
                } elseif (isset($_SESSION['email_error'])) {
                    echo $_SESSION['email_error'];
                    unset($_SESSION['email_error']);
                }
                ?>
            </p>
            <button onclick="window.location.href='account.php'">OK</button>
        </div>
    </div>
    <?php endif; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const popupBtn = document.querySelector(".popup-box button");
            if (popupBtn) {
                popupBtn.addEventListener("click", function () {
                    const currentUsername = localStorage.getItem("username");
                    const emailValue = document.getElementById("email").value;
                    if (currentUsername && emailValue) {
                        localStorage.setItem(currentUsername + "_email", emailValue);
                    }
                    window.location.href = "account.php";
                });
            }
        });
    </script>


    <script>
        document.getElementById("email-form").addEventListener("submit", function(e) {
           

            const newEmail = document.getElementById("email").value.trim();
            const currentUsername = localStorage.getItem("username");

            if (!newEmail) {
                alert("Vui lòng nhập email.");
                return;
            }

            // Lưu vào localStorage
            localStorage.setItem(currentUsername + "_email", newEmail);

            // Đặt cờ để hiển thị thông báo ở account.php
            localStorage.setItem("emailUpdateSuccess", "true");

            // Chuyển hướng về trang tài khoản
            window.location.href = "account.php";
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const currentUsername = localStorage.getItem("username");
            if (currentUsername) {
                const email = localStorage.getItem(currentUsername + "_email");
                if (email) {
                    document.getElementById("email").value = email;
                }
            }
        });
    </script>
</div>
<?php
include_once 'footer.php';
?>
