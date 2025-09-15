<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login1.html');
    exit();
}
?>

<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Smart Phone | Thông Tin Tài Khoản</title>
    <link rel="icon" type="image/png" href="img/logofpt7.png">
<div class="account-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="breadcrumb">
            <i class="fas fa-home" style="color: red;"></i><a href="index.php">Trang chủ</a>
            <i class="fas fa-angle-right"></i>
            <span>Tài Khoản</span>
        </div>
        <h3>Tài khoản của <span id="username-display"></span></h3>
        <ul>
            <li class="active"><i class="fas fa-user"></i> Thông tin tài khoản</li>
            <li><a href="nofication.php"><i class="fas fa-bell"></i> Thông báo của tôi</a></li>
            <li><a href="order.php"><i class="fas fa-box"></i> Quản lý đơn hàng</a></li>
            <li><a href="favorite.php"><i class="fas fa-heart"></i> Sản phẩm yêu thích</a></li>
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
                    
                    <div class="form-row">
                        <h3 class="tieude">Địa chỉ</h3>
                        <div class="form-group">
                            <label class="form-label">Tỉnh / Thành phố *</label>
                            <select id="province" class="form-control" required>
                                <option value="">-- Chọn Tỉnh/Thành phố --</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Quận / Huyện *</label>
                            <select id="district" class="form-control" required disabled>
                                <option value="">-- Chọn Quận/Huyện --</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Phường / Thị trấn *</label>
                            <select id="ward" class="form-control" required disabled>
                                <option value="">-- Chọn Phường/Thị trấn --</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Số nhà / đường</label>
                            <input type="text" id="street" class="form-control1" placeholder="Nhập số nhà, tên đường">
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" id="default-address" class="form-check-input">
                        <label for="default-address">Đặt địa chỉ làm mặc định</label>
                    </div>

                    <button class="btn-save">Lưu thay đổi</button>
                </div>
            </div>
        </section>

        <script>
            // Khai báo addressData trước
            const addressData = {
                provinces: [],
                districts: {},
                wards: {}
            };
            let addressDataLoaded = false;

            fetch('https://provinces.open-api.vn/api/?depth=3')
                .then(response => response.json())
                .then(data => {
                    const provinces = data.map(p => ({ code: p.code, name: p.name }));
                    
                    const districts = {};
                    const wards = {};
                    
                    data.forEach(province => {
                        districts[province.code] = province.districts.map(d => ({ 
                            code: d.code, 
                            name: d.name 
                        }));
                        
                        province.districts.forEach(district => {
                            wards[district.code] = district.wards.map(w => ({
                                code: w.code,
                                name: w.name
                            }));
                        });
                    });
                    
                    addressData.provinces = provinces;
                    addressData.districts = districts;
                    addressData.wards = wards;
                    
                    addressDataLoaded = true;
                    console.log('Address data loaded successfully:', addressData);

                    populateProvinces();
                    restoreAddressFromLocalStorage();
                })
                .catch(error => {
                    console.error('Error loading address data:', error);
                    alert('Không thể tải dữ liệu địa chỉ. Vui lòng thử lại sau.');
                });

            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');
            const streetInput = document.getElementById('street');
            const defaultAddressCheckbox = document.getElementById('default-address');

            function populateProvinces() {
                if (!addressDataLoaded || !addressData.provinces) {
                    console.error('Provinces data is not available or not loaded yet');
                    return;
                }
                provinceSelect.innerHTML = '<option value="">-- Chọn Tỉnh/Thành phố --</option>';
                addressData.provinces.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.code;
                    option.textContent = province.name;
                    provinceSelect.appendChild(option);
                });
            }

            function populateDistricts(provinceCode) {
                if (!addressDataLoaded) {
                    console.error('Address data not loaded yet');
                    return;
                }
                districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
                wardSelect.innerHTML = '<option value="">-- Chọn Phường/Thị trấn --</option>';
                
                if (!provinceCode) {
                    districtSelect.disabled = true;
                    wardSelect.disabled = true;
                    return;
                }
                
                const districts = addressData.districts[provinceCode] || [];
                if (districts.length === 0) {
                    console.warn('No districts found for province code:', provinceCode);
                    return;
                }
                districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.code;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
                
                districtSelect.disabled = false;
                wardSelect.disabled = true;
            }

            function populateWards(districtCode) {
                if (!addressDataLoaded) {
                    console.error('Address data not loaded yet');
                    return;
                }
                wardSelect.innerHTML = '<option value="">-- Chọn Phường/Thị trấn --</option>';
                
                if (!districtCode) {
                    wardSelect.disabled = true;
                    return;
                }
                
                const wards = addressData.wards[districtCode] || [];
                if (wards.length === 0) {
                    console.warn('No wards found for district code:', districtCode);
                    return;
                }
                wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.code;
                    option.textContent = ward.name;
                    wardSelect.appendChild(option);
                });
                
                wardSelect.disabled = false;
            }

            provinceSelect.addEventListener('change', function() {
                populateDistricts(this.value);
            });

            districtSelect.addEventListener('change', function() {
                populateWards(this.value);
            });

            function processAddress() {
                try {
                    if (!addressDataLoaded) {
                        throw new Error('Dữ liệu địa chỉ chưa được tải xong.');
                    }

                    console.log('Processing address with values:');
                    console.log('provinceSelect.value:', provinceSelect.value);
                    console.log('districtSelect.value:', districtSelect.value);
                    console.log('wardSelect.value:', wardSelect.value);

                    if (!addressData.provinces || !addressData.districts || !addressData.wards) {
                        throw new Error('Dữ liệu địa chỉ không đầy đủ.');
                    }

                    const selectedProvince = addressData.provinces.find(p => p.code == provinceSelect.value);
                    if (!selectedProvince) {
                        throw new Error('Không tìm thấy tỉnh/thành phố với mã: ' + provinceSelect.value);
                    }
                    console.log('Selected province:', selectedProvince);

                    if (!addressData.districts[provinceSelect.value]) {
                        throw new Error('Không tìm thấy danh sách quận/huyện cho tỉnh/thành phố: ' + provinceSelect.value);
                    }
                    const selectedDistrict = addressData.districts[provinceSelect.value].find(d => d.code == districtSelect.value);
                    if (!selectedDistrict) {
                        throw new Error('Không tìm thấy quận/huyện với mã: ' + districtSelect.value);
                    }
                    console.log('Selected district:', selectedDistrict);

                    if (!addressData.wards[districtSelect.value]) {
                        throw new Error('Không tìm thấy danh sách phường/thị trấn cho quận/huyện: ' + districtSelect.value);
                    }
                    const selectedWard = addressData.wards[districtSelect.value].find(w => w.code == wardSelect.value);
                    if (!selectedWard) {
                        throw new Error('Không tìm thấy phường/thị trấn với mã: ' + wardSelect.value);
                    }
                    console.log('Selected ward:', selectedWard);

                    const streetValue = streetInput.value.trim();
                    const fullAddress = [
                        streetValue,
                        selectedWard ? selectedWard.name : '',
                        selectedDistrict ? selectedDistrict.name : '',
                        selectedProvince ? selectedProvince.name : ''
                    ].filter(Boolean).join(', ');
                    
                    const addressDataResult = {
                        province: {
                            code: provinceSelect.value,
                            name: selectedProvince.name
                        },
                        district: {
                            code: districtSelect.value,
                            name: selectedDistrict.name
                        },
                        ward: {
                            code: wardSelect.value,
                            name: selectedWard.name
                        },
                        street: streetValue,
                        isDefault: defaultAddressCheckbox.checked,
                        fullAddress
                    };
                    
                    console.log('Address data processed successfully:', addressDataResult);
                    return addressDataResult;
                } catch (error) {
                    console.error('Error in processAddress:', error.message);
                    alert('Có lỗi xảy ra khi xử lý địa chỉ: ' + error.message + ' Vui lòng thử lại.');
                    return null;
                }
            }

            function restoreAddressFromLocalStorage() {
                if (!addressDataLoaded) {
                    console.warn('Address data not loaded yet, skipping restore');
                    return;
                }
                let currentUsername = localStorage.getItem("username");
                if (currentUsername) {
                    const savedAddress = localStorage.getItem(currentUsername + "_address");
                    if (savedAddress) {
                        try {
                            const address = JSON.parse(savedAddress);
                            if (address.province.code) {
                                provinceSelect.value = address.province.code;
                                populateDistricts(address.province.code);
                            }
                            if (address.district.code) {
                                districtSelect.value = address.district.code;
                                populateWards(address.district.code);
                            }
                            if (address.ward.code) {
                                wardSelect.value = address.ward.code;
                            }
                            if (address.street) {
                                streetInput.value = address.street;
                            }
                            defaultAddressCheckbox.checked = address.isDefault || false;
                        } catch (error) {
                            console.error('Error parsing address from localStorage:', error);
                        }
                    }
                }
            }

            function populateDateFields() {
                let daySelect = document.getElementById("dob-day");
                let monthSelect = document.getElementById("dob-month");
                let yearSelect = document.getElementById("dob-year");

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

                let currentUsername = localStorage.getItem("username");
                daySelect.value = localStorage.getItem(currentUsername + "_dob_day") || "";
                monthSelect.value = localStorage.getItem(currentUsername + "_dob_month") || "";
                yearSelect.value = localStorage.getItem(currentUsername + "_dob_year") || "";
            }

            window.addEventListener("load", populateDateFields);
            
            document.addEventListener("DOMContentLoaded", function() {
                let currentUsername = localStorage.getItem("username");

                if (currentUsername) {
                    var fullName = localStorage.getItem(currentUsername + "_name");
                    var nickname = localStorage.getItem(currentUsername + "_nickname");
                    var dobDay = localStorage.getItem(currentUsername + "_dob_day");
                    var dobMonth = localStorage.getItem(currentUsername + "_dob_month");
                    var dobYear = localStorage.getItem(currentUsername + "_dob_year");
                    var gender = localStorage.getItem(currentUsername + "_gender");

                    if (fullName) document.getElementById("fullname").value = fullName;
                    if (nickname) document.getElementById("nickname").value = nickname;
                    if (dobDay) document.getElementById("dob-day").value = dobDay;
                    if (dobMonth) document.getElementById("dob-month").value = dobMonth;
                    if (dobYear) document.getElementById("dob-year").value = dobYear;
                    if (gender) {
                        document.querySelector(`.gender-options input[value='${gender}']`).checked = true;
                    }
                }

                var username = localStorage.getItem("username");
                if (username) {
                    document.getElementById("username-display").textContent = username;
                }
            });

            document.querySelector(".btn-save").addEventListener("click", function () {
                console.log("Nút Lưu thay đổi được nhấn");

                let currentUsername = localStorage.getItem("username");
                if (!currentUsername) {
                    alert("Không tìm thấy tài khoản đăng nhập!");
                    return;
                }

                let fullName = document.getElementById("fullname").value.trim();
                let nickname = document.getElementById("nickname").value.trim();
                let dobDay = document.getElementById("dob-day").value;
                let dobMonth = document.getElementById("dob-month").value;
                let dobYear = document.getElementById("dob-year").value;

                let genderChecked = document.querySelector(".gender-options input:checked");
                let gender = genderChecked ? genderChecked.value : "";

                let addressData = processAddress();
                if (!addressData) {
                    return;
                }

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
                if (!addressData.province.code || !addressData.district.code || !addressData.ward.code) {
                    alert("Vui lòng chọn đầy đủ Tỉnh/Thành phố, Quận/Huyện, và Phường/Thị trấn.");
                    return;
                }

                localStorage.setItem(currentUsername + "_name", fullName);
                localStorage.setItem(currentUsername + "_nickname", nickname);
                localStorage.setItem(currentUsername + "_dob_day", dobDay);
                localStorage.setItem(currentUsername + "_dob_month", dobMonth);
                localStorage.setItem(currentUsername + "_dob_year", dobYear);
                localStorage.setItem(currentUsername + "_gender", gender);
                localStorage.setItem(currentUsername + "_address", JSON.stringify(addressData));

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
                        const email = localStorage.getItem(currentUsername + "_email") || "Chưa cập nhật";
                        document.getElementById("email-display").textContent = email;
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
                const logoutBtn = document.getElementById("logout-btn");
                const logoutModal = document.getElementById("logout-modal");
                const cancelLogout = document.getElementById("cancel-logout");
                const confirmLogout = document.getElementById("confirm-logout");

                logoutModal.style.display = "none";

                logoutBtn.addEventListener("click", function (event) {
                    event.stopPropagation();
                    logoutModal.style.display = "flex";
                });

                cancelLogout.addEventListener("click", function () {
                    logoutModal.style.display = "none";
                });

                confirmLogout.addEventListener("click", function() {
                    window.location.href = "index.php?logout=true";
                });

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
include_once 'footer.php';
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