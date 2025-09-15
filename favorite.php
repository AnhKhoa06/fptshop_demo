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

<title>Smart Phone | Sản phẩm yêu thích</title>
<div class="account-container">
    <!-- Sidebar -->
    <div class="sidebar1">
        <div class="breadcrumb">
            <i class="fas fa-home" style="color: red;"></i><a href="index.php">Trang chủ</a>
            <i class="fas fa-angle-right"></i>
            <span>Yêu Thích</span>
        </div>
        <h3>Tài khoản của <span id="username-display"></span></h3>
        <ul>
            <li><a href="account.php"><i class="fas fa-user"></i> Thông tin tài khoản</a></li>
            <li><a href="nofication.php"><i class="fas fa-bell"></i> Thông báo của tôi</a></li>
            <li><a href="order.php"><i class="fas fa-box"></i> Quản lý đơn hàng</a></li>
            <li class="active"><i class="fas fa-heart"></i> Sản phẩm yêu thích</li>
            <li id="logout-btn"><i class="fas fa-sign-out-alt"></i> Đăng xuất</li>
        </ul>
    </div>  

    <!-- Nội dung chính -->
    <div class="main-content">
        <h3>Sản Phẩm Yêu Thích</h3>
        <?php
        // Truy vấn danh sách sản phẩm yêu thích từ bảng favorites trước
        $fav_query = "SELECT * FROM favorites WHERE user_id = ?";
        $fav_stmt = mysqli_prepare($connect, $fav_query);
        mysqli_stmt_bind_param($fav_stmt, "i", $user_id);
        mysqli_stmt_execute($fav_stmt);
        $fav_result = mysqli_stmt_get_result($fav_stmt);

        if (mysqli_num_rows($fav_result) == 0) {
            echo '<p style="color: gray; margin-top: 20px;">Bạn chưa có sản phẩm yêu thích nào.</p>';
        } else {
            echo '<div class="favorite-products">';
            while ($fav_row = mysqli_fetch_assoc($fav_result)) {
                // Giải mã color và rom từ favorites
                $decoded_color = urldecode($fav_row['color']);
                $decoded_rom = urldecode($fav_row['rom']);

                // Truy vấn thông tin sản phẩm từ products và product_colors
                $prod_query = "SELECT p.prd_name, p.ram, pc.image, pc.price, pc.price_discount, pc.rom 
                            FROM products p 
                            JOIN product_colors pc ON p.prd_id = pc.product_id 
                            WHERE p.prd_id = ? AND pc.color = ? AND pc.rom = ?";
                $prod_stmt = mysqli_prepare($connect, $prod_query);
                mysqli_stmt_bind_param($prod_stmt, "iss", $fav_row['product_id'], $decoded_color, $decoded_rom);
                mysqli_stmt_execute($prod_stmt);
                $prod_result = mysqli_stmt_get_result($prod_stmt);
                $row = mysqli_fetch_assoc($prod_result);

                if ($row) {
                    // Kiểm tra Flash Sale
                    $flash_sale_query = "SELECT discount, price_discount 
                                        FROM flash_sales 
                                        WHERE product_id = ? AND color = ? AND rom = ? AND start_time <= NOW() AND end_time >= NOW() LIMIT 1";
                    $flash_sale_stmt = mysqli_prepare($connect, $flash_sale_query);
                    mysqli_stmt_bind_param($flash_sale_stmt, "iss", $fav_row['product_id'], $decoded_color, $decoded_rom);
                    mysqli_stmt_execute($flash_sale_stmt);
                    $flash_sale_result = mysqli_stmt_get_result($flash_sale_stmt);
                    $flash_sale = mysqli_fetch_assoc($flash_sale_result);
                    mysqli_stmt_close($flash_sale_stmt);

                    // Tính giá và phần trăm giảm giá
                    $display_price_discount = $flash_sale ? $flash_sale['price_discount'] : $row['price_discount'];
                    $display_discount = $flash_sale ? $flash_sale['discount'] : ($row['price'] > 0 ? round(100 - ($row['price_discount'] / $row['price']) * 100) : 0);

                    // Tạo slug từ tên sản phẩm
                    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $row['prd_name']), '-'));
                    // Tạo đường dẫn dựa trên slug
                    $product_url = "product/{$slug}.php?product_id=" . $fav_row['product_id'] . "&color=" . urlencode($decoded_color) . "&rom=" . urlencode($decoded_rom);
                    ?>
                    <div class="favorite-item">
                        <a href="<?php echo $product_url; ?>">
                            <img src="admin/img/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['prd_name']); ?>">
                        </a>
                        <div class="favorite-info">
                            <a href="<?php echo $product_url; ?>">
                                <h4><?php echo htmlspecialchars($row['prd_name']); ?></h4>
                            </a>
                            <p class="spec"><?php echo htmlspecialchars($row['ram']) . ' - ' . htmlspecialchars($row['rom']); ?></p>
                            <p class="price-container">
                                <span class="price"><?php echo number_format($display_price_discount, 0, ',', '.'); ?>₫</span>
                                <?php if ($display_discount > 0): ?>
                                    <span class="discount-label">-<?php echo $display_discount; ?>%</span>
                                    <span class="original-price"><?php echo number_format($row['price'], 0, ',', '.'); ?>₫</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <button class="add-to-cart" 
                                data-product-id="<?php echo $fav_row['product_id']; ?>" 
                                data-color="<?php echo htmlspecialchars($decoded_color); ?>" 
                                data-rom="<?php echo htmlspecialchars($decoded_rom); ?>" 
                                data-price="<?php echo $row['price']; ?>" 
                                data-price-discount="<?php echo $display_price_discount; ?>">
                            Thêm vào giỏ <span class="arrow-icon">→</span>
                        </button>
                    </div>
                    <?php
                }
                mysqli_stmt_close($prod_stmt);
            }
            echo '</div>';
        }
        mysqli_stmt_close($fav_stmt);
        ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Xử lý nút "Thêm vào giỏ"
            document.querySelectorAll('.add-to-cart').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = btn.getAttribute('data-product-id');
                    const color = btn.getAttribute('data-color');
                    const rom = btn.getAttribute('data-rom');
                    const price = parseFloat(btn.getAttribute('data-price')) || 0;
                    const priceDiscount = parseFloat(btn.getAttribute('data-price-discount')) || 0;

                    // Lấy thông tin từ các phần tử gần đó (dựa trên cấu trúc HTML)
                    const productName = btn.closest('.favorite-item').querySelector('h4').textContent;
                    const image = 'admin/img/' + btn.closest('.favorite-item').querySelector('img').getAttribute('src').replace('admin/img/', '');

                    const formData = new FormData();
                    formData.append('product_id', productId);
                    formData.append('product_name', productName);
                    formData.append('color', color);
                    formData.append('rom', rom);
                    formData.append('price', price);
                    formData.append('price_discount', priceDiscount);
                    formData.append('image', image);
                    formData.append('quantity', 1);

                    fetch('product/add_to_cart.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Đã thêm sản phẩm vào giỏ hàng!');
                            updateCartBadge();
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Đã xảy ra lỗi khi thêm vào giỏ hàng.');
                    });
                });
            });

            // Hàm cập nhật số lượng giỏ hàng (nếu có badge)
            function updateCartBadge() {
                fetch('product/get_cart_count.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.count !== undefined) {
                        const cartBadges = document.querySelectorAll('.cart-badge');
                        cartBadges.forEach(badge => {
                            badge.textContent = data.count;
                        });
                    }
                })
                .catch(error => console.error('Lỗi khi cập nhật số lượng giỏ hàng:', error));
            }
        });
    </script>



    <script>
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