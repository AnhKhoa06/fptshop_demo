<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login1.html');
    exit();
}
?>

<?php
    include_once 'header.php'
?>
 <title>Thông Tin Tài Khoản</title>
    <div class="account-container">
        <!-- Sidebar -->
        <div class="sidebar">
        <h3>Tài khoản của <span id="username-display"></span></h3>
            <ul>
                <li class="active"><i class="fas fa-user"></i> Thông tin tài khoản</li>
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
        <!-- Content -->
            <section class="account-content">
                <h2>Thông tin cá nhân</h2>
                <div class="profile">
                    <div class="avatar-container">
                        <i class="fa fa-user avatar-icon"></i>
                        <span class="edit-icon">✎</span>
                    </div>

                    <div class="info">
                        <div class="input-row">
                            <label>Họ & Tên</label>
                            <input type="text" id="fullname" placeholder="Thêm họ và tên">
                        </div>

                        <div class="input-row">
                            <label>Nickname</label>
                            <input type="text" id="nickname" placeholder="Thêm nickname">
                        </div>

                        <div class="input-row">
                            <label>Ngày sinh</label>
                            <select id="dob-day">
                                <option value="">Ngày</option>
                            </select>
                            <select id="dob-month">
                                <option value="">Tháng</option>
                            </select>
                            <select id="dob-year">
                                <option value="">Năm</option>
                            </select>
                        </div>

                     

                        <div class="input-row">
                            <label>Giới tính</label>
                            <div class="gender-options">
                                <input type="radio" name="gender" value="Nam" id="gender-male">
                                <label for="gender-male">Nam</label>

                                <input type="radio" name="gender" value="Nữ" id="gender-female">
                                <label for="gender-female">Nữ</label>

                                <input type="radio" name="gender" value="Khác" id="gender-other">
                                <label for="gender-other">Khác</label>
                            </div>
                        </div>

                        <div class="input-row nationality-row">
                            <label>Quốc tịch</label>
                            <select id="nationality">
                                <option value="">Chọn quốc tịch</option>
                                <option value="Vietnam">Việt Nam</option>
                                <option value="USA">Hoa Kỳ</option>
                                <option value="UK">Anh</option>
                                <option value="France">Pháp</option>
                                <option value="Germany">Đức</option>
                                <option value="Japan">Nhật Bản</option>
                            </select>
                        </div>

                        <button class="btn-save">Lưu thay đổi</button>
                    </div>
                </div>
            </section>

            <script>
                function populateDateFields() {
                    let daySelect = document.getElementById("dob-day");
                    let monthSelect = document.getElementById("dob-month");
                    let yearSelect = document.getElementById("dob-year");

                    // Tạo các option cho ngày, tháng, năm
                    for (let i = 1; i <= 31; i++) {
                        let option = document.createElement("option");
                        option.value = i;
                        option.textContent = i;
                        daySelect.appendChild(option);
                    }

                    for (let i = 1; i <= 12; i++) {
                        let option = document.createElement("option");
                        option.value = i;
                        option.textContent = `Tháng ${i}`;
                        monthSelect.appendChild(option);
                    }

                    const yearNow = new Date().getFullYear();
                    for (let i = yearNow; i >= 1900; i--) {
                        let option = document.createElement("option");
                        option.value = i;
                        option.textContent = i;
                        yearSelect.appendChild(option);
                    }

                    // Lấy thông tin từ localStorage cho ngày/tháng/năm
                    let currentUsername = localStorage.getItem("username");
                    daySelect.value = localStorage.getItem(currentUsername + "_dob_day") || "";
                    monthSelect.value = localStorage.getItem(currentUsername + "_dob_month") || "";
                    yearSelect.value = localStorage.getItem(currentUsername + "_dob_year") || "";
                }


                window.addEventListener("load", populateDateFields);
                
                // Hàm để điền lại giá trị vào các trường thông tin
                document.addEventListener("DOMContentLoaded", function() {
                    let currentUsername = localStorage.getItem("username"); // Lấy tài khoản đang đăng nhập

                    if (currentUsername) {
                        var fullName = localStorage.getItem(currentUsername + "_name");
                        var nickname = localStorage.getItem(currentUsername + "_nickname");
                        var dobDay = localStorage.getItem(currentUsername + "_dob_day");
                        var dobMonth = localStorage.getItem(currentUsername + "_dob_month");
                        var dobYear = localStorage.getItem(currentUsername + "_dob_year");
                        var gender = localStorage.getItem(currentUsername + "_gender");
                        var nationality = localStorage.getItem(currentUsername + "_nationality");

                        // Điền các thông tin vào form
                        if (fullName) document.getElementById("fullname").value = fullName;
                        if (nickname) document.getElementById("nickname").value = nickname;
                        if (dobDay) document.getElementById("dob-day").value = dobDay;
                        if (dobMonth) document.getElementById("dob-month").value = dobMonth;
                        if (dobYear) document.getElementById("dob-year").value = dobYear;
                        if (gender) {
                            document.querySelector(`.gender-options input[value='${gender}']`).checked = true;
                        }
                        if (nationality) document.getElementById("nationality").value = nationality;
                    }

                    // Hiển thị tên người dùng trong sidebar
                    var username = localStorage.getItem("username");
                    if (username) {
                        document.getElementById("username-display").textContent = username;
                    }
                });


                // Hàm để lưu thông tin vào localStorage khi người dùng nhấn nút "Lưu thay đổi"
                document.querySelector(".btn-save").addEventListener("click", function () {
                    let currentUsername = localStorage.getItem("username"); // Lấy tài khoản đang đăng nhập
                    if (!currentUsername) {
                        alert("Không tìm thấy tài khoản đăng nhập!");
                        return;
                    }

                    // Lấy giá trị từ các trường input
                    let fullName = document.getElementById("fullname").value.trim();
                    let nickname = document.getElementById("nickname").value.trim();
                    let dobDay = document.getElementById("dob-day").value;
                    let dobMonth = document.getElementById("dob-month").value;
                    let dobYear = document.getElementById("dob-year").value;
                    let nationality = document.getElementById("nationality").value;

                    // Kiểm tra giới tính đã chọn
                    let genderChecked = document.querySelector(".gender-options input:checked");
                    let gender = genderChecked ? genderChecked.value : "";

                    // Kiểm tra nếu có trường nào chưa nhập
                    if (!fullName) {
                        alert("Vui lòng nhập Họ & Tên.");
                        return;
                    }
                    if (!nickname) {
                        alert("Vui lòng nhập Nickname.");
                        return;
                    }
                    if (!dobDay || !dobMonth || !dobYear) {
                        alert("Vui lòng chọn đầy đủ Ngày sinh.");
                        return;
                    }
                    if (!gender) {
                        alert("Vui lòng chọn Giới tính.");
                        return;
                    }
                    if (!nationality) {
                        alert("Vui lòng chọn Quốc tịch.");
                        return;
                    }

                    // Lưu thông tin vào localStorage theo tài khoản hiện tại
                    localStorage.setItem(currentUsername + "_name", fullName);
                    localStorage.setItem(currentUsername + "_nickname", nickname);
                    localStorage.setItem(currentUsername + "_dob_day", dobDay);
                    localStorage.setItem(currentUsername + "_dob_month", dobMonth);
                    localStorage.setItem(currentUsername + "_dob_year", dobYear);
                    localStorage.setItem(currentUsername + "_gender", gender);
                    localStorage.setItem(currentUsername + "_nationality", nationality);

                    // Hiển thị thông báo thành công
                    alert("Đã lưu thông tin của bạn!");
                });


            </script>

            <!-- Right Panel -->
            <aside class="right-panel">
                <h3>Số điện thoại & Email</h3>
                <div class="info-group">
                    <i class="fas fa-phone"></i> 
                    <span id="phone-display"></span> 
                    <a href="phone.php"><button>Cập nhật</button></a>
                </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
               

                const currentUsername = localStorage.getItem("username");
                if (currentUsername) {
                    const phone = localStorage.getItem(currentUsername + "_phone");
                    if (phone) {
                        document.getElementById("phone-display").textContent = phone;
                    }
                }
            });
        </script>

                <div class="info-group">
                    <i class="fas fa-envelope"></i>
                    <span id="email-display"></span>
                    <a href="email.php"><button>Cập nhật</button></a>
                </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
              

                const currentUsername = localStorage.getItem("username");
                if (currentUsername) {
                    const email = localStorage.getItem(currentUsername + "_email");
                    if (email) {
                        document.getElementById("email-display").textContent = email;
                    }
                }
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var currentUsername = localStorage.getItem("username");

                if (currentUsername) {
                    var phone = localStorage.getItem(currentUsername + "_phone");
                    var email = localStorage.getItem(currentUsername + "_email");

                    if (phone) {
                        document.getElementById("phone-display").textContent = phone;
                    }
                    if (email) {
                        document.getElementById("email-display").textContent = email;
                    }
                }
            });
        </script>


                
                <h3>Bảo mật</h3>
                <div class="info-group">
                    <i class="fas fa-lock"></i> Thiết lập mật khẩu 
                    <a href="password.php"><button type="button">Cập nhật</button></a>
                </div>

                <div class="info-group">
                    <i class="fas fa-key"></i> Thiết lập mã PIN <button>Thiết lập</button>
                </div>
                <div class="info-group">
                    <i class="fas fa-trash"></i> Yêu cầu xóa tài khoản <button>Yêu cầu</button>
                </div>

                <h3>Liên kết mạng xã hội</h3>
                <div class="info-group">
                    <i class="fab fa-facebook"></i> Facebook <button>Liên kết</button>
                </div>
                <div class="info-group">
                    <i class="fab fa-google"></i> Google <button class="linked">Đã liên kết</button>
                </div>
            </aside>

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
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const logoutBtn = document.getElementById("logout-btn"); // Nút đăng xuất
                const logoutModal = document.getElementById("logout-modal"); // Modal xác nhận
                const cancelLogout = document.getElementById("cancel-logout");
                const confirmLogout = document.getElementById("confirm-logout");
        
               
        
                // Ẩn modal khi tải trang
                logoutModal.style.display = "none";
        
                // Khi bấm "Đăng xuất" -> Hiện modal xác nhận
                logoutBtn.addEventListener("click", function (event) {
                    event.stopPropagation(); // Ngăn chặn sự kiện lan ra ngoài
                    logoutModal.style.display = "flex";
                });
        
                // Khi bấm "Không" -> Ẩn modal
                cancelLogout.addEventListener("click", function () {
                    logoutModal.style.display = "none";
                });
                
                document.getElementById("confirm-logout").addEventListener("click", function() {
                    // Đưa người dùng trở về trang index.php với tham số logout
                    window.location.href = "index.php?logout=true";
                });
                                
        
                // Khi bấm ra ngoài modal -> Ẩn modal
                window.addEventListener("click", function (event) {
                    if (event.target === logoutModal) {
                        logoutModal.style.display = "none";
                    }
                });
            });
        </script>
        </div>
    </div>
<?php
    include_once 'footer.php'
?>

<?php if (isset($_SESSION['phone_updated'])): ?>
<script>
    const username = localStorage.getItem("username");
    const newPhone = "<?php echo $_SESSION['phone_updated']; ?>";
    if (username) {
        localStorage.setItem(`${username}_phone`, newPhone);
        localStorage.setItem("phoneUpdateSuccess", "true");
    }
</script>
<?php unset($_SESSION['phone_updated']); endif; ?>

<?php if (isset($_SESSION['email_updated'])): ?>
<script>
    const username = localStorage.getItem("username");
    const newEmail = "<?php echo $_SESSION['email_updated']; ?>";
    if (username) {
        localStorage.setItem(`${username}_email`, newEmail);
        localStorage.setItem("emailUpdateSuccess", "true");
    }
</script>
<?php unset($_SESSION['email_updated']); endif; ?>


<?php if (isset($_SESSION['password_updated'])): ?>
<script>
    localStorage.setItem("passwordUpdateSuccess", "true");
</script>
<?php unset($_SESSION['password_updated']); endif; ?>

