<?php
session_start();
$username = isset($_SESSION['user']) ? $_SESSION['user'] : "Khách";
    include_once 'header.php';

?>
<?php
    require_once '../admin/config/db.php';
?>
<title>Smart Phone | Oppo Find X5 256GB</title>
<link rel="stylesheet" href="../product7.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
<div>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="../index.php">
        <i class="fas fa-home" style="color: red;"></i> Trang chủ
    </a>
        <i class="fa-solid fa-angle-right"></i>
    <a href="../index.php">Điện thoại</a>
        <i class="fa-solid fa-angle-right"></i>
    <a href="../brand/oppo.php">Oppo</a>
        <i class="fa-solid fa-angle-right"></i>
    <span>Oppo Find X5</span>
</div>


<?php
// Giả định product_id của iPhone 15 là 1
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 12; // Thay đổi ID này dựa trên sản phẩm thực tế, ví dụ: lấy từ $_GET['id']

// Tăng số lượt xem (views) cho sản phẩm
$update_views = mysqli_prepare($connect, "UPDATE products SET views = views + 1 WHERE prd_id = ?");
mysqli_stmt_bind_param($update_views, "i", $product_id);
mysqli_stmt_execute($update_views);
mysqli_stmt_close($update_views);

// Truy vấn thông tin sản phẩm từ bảng products
$sql_product = "SELECT gpu, gpu_rating, screen, screen_rating, pin, pin_rating, prd_name, product_code, front_camera, rear_camera, ram, cpu, operating_system, specifications, review_content
                FROM products 
                WHERE prd_id = ?";
$stmt_product = mysqli_prepare($connect, $sql_product);
mysqli_stmt_bind_param($stmt_product, "i", $product_id);
mysqli_stmt_execute($stmt_product);
$result_product = mysqli_stmt_get_result($stmt_product);
$product = mysqli_fetch_assoc($result_product);
mysqli_stmt_close($stmt_product);

// Gán giá trị mặc định nếu không có dữ liệu
$gpu = $product['gpu'] ?? 'Không xác định';
$gpu_rating = $product['gpu_rating'] ?? 'hiệu năng tốt';
$screen = $product['screen'] ?? 'Không xác định';
$screen_rating = $product['screen_rating'] ?? 'vừa';
$pin = $product['pin'] ?? 'Không xác định';
$pin_rating = $product['pin_rating'] ?? 'trung bình';
$product_name = $product['prd_name'] ?? 'iPhone 15';
$product_code = $product['product_code'] ?? 'Không xác định';
$front_camera = $product['front_camera'] ?? 'Không xác định';
$rear_camera = $product['rear_camera'] ?? 'Không xác định';
$ram = $product['ram'] ?? 'Không xác định';
$cpu = $product['cpu'] ?? 'Không xác định';
$operating_system = $product['operating_system'] ?? 'Không xác định';
$specifications = $product['specifications'] ?? '[]'; // Lấy cột specifications
$review_content = $product['review_content'] ?? 'Chưa có bài viết đánh giá.';

// Lấy thông tin từ bảng product_colors
$sql_colors = "SELECT color, rom, price, price_discount, installment, image, images_detail 
               FROM product_colors 
               WHERE product_id = ?";
$stmt_colors = mysqli_prepare($connect, $sql_colors);
mysqli_stmt_bind_param($stmt_colors, "i", $product_id);
mysqli_stmt_execute($stmt_colors);
$result_colors = mysqli_stmt_get_result($stmt_colors);

// Kiểm tra và debug dữ liệu
if (mysqli_num_rows($result_colors) === 0) {
    echo "<!-- Không có dữ liệu màu sắc từ database -->";
}

$color_data = mysqli_fetch_all($result_colors, MYSQLI_ASSOC);

// Debug dữ liệu chi tiết
echo "<!-- Debug color_data: " . htmlspecialchars(print_r($color_data, true)) . " -->";

// Kiểm tra kiểu dữ liệu của $color_data
if (!is_array($color_data)) {
    echo "<!-- Lỗi: color_data không phải là mảng -->";
    $color_data = [];
}

// Tạo $color_images từ $color_data
$color_images = [];
foreach ($color_data as $row) {
    if (!is_array($row)) {
        echo "<!-- Lỗi: Phần tử trong color_data không phải mảng: " . htmlspecialchars(print_r($row, true)) . " -->";
        continue;
    }
    $images_detail = !empty($row['images_detail']) ? explode(',', $row['images_detail']) : [];
    $filtered_images_detail = [];
    foreach ($images_detail as $detail_image) {
        if (!empty($detail_image) && file_exists("../admin/img/" . trim($detail_image))) {
            $filtered_images_detail[] = trim($detail_image);
        }
    }
    $color_images[] = [
        'color' => $row['color'] ?? 'Mặc định',
        'image' => $row['image'] ?? 'default.png',
        'images_detail' => $filtered_images_detail
    ];
}

// Debug $color_images
echo "<!-- Debug color_images: " . htmlspecialchars(print_r($color_images, true)) . " -->";

mysqli_stmt_close($stmt_colors);

// Lấy ROM duy nhất
$unique_roms = !empty($color_data) ? array_unique(array_column($color_data, 'rom')) : ['128 GB'];
$default_rom = !empty($unique_roms) ? reset($unique_roms) : '128 GB';

// Lấy màu từ URL hoặc mặc định là màu đầu tiên
$selected_color = isset($_GET['color']) ? urldecode($_GET['color']) : ($color_images[0]['color'] ?? 'Mặc định');
// Tìm rom tương ứng với color từ color_data
$selected_rom = $default_rom;
foreach ($color_data as $item) {
    if ($item['color'] === $selected_color) {
        $selected_rom = $item['rom'];
        break;
    }
}

// Lấy dữ liệu mặc định hoặc từ color đã chọn
$default_color = !empty($color_data) ? array_filter($color_data, fn($item) => $item['color'] === $selected_color && $item['rom'] === $selected_rom) : [];
$default_color = array_values($default_color)[0] ?? $color_data[0] ?? ['color' => 'Mặc định', 'price_discount' => 15990000, 'price' => 22990000, 'installment' => 0, 'image' => 'default.png'];
$default_new_price = $default_color['price_discount'] ?? 15990000;
$default_old_price = $default_color['price'] ?? ($default_new_price + 7000000);
$default_discount = $default_old_price > 0 ? round(100 - ($default_new_price / $default_old_price) * 100) : 0;
$default_installment_add = $default_color['installment'] ?? 0;
$default_installment_price = round($default_new_price / 12 + $default_installment_add);

// Kiểm tra Flash Sale
date_default_timezone_set('Asia/Ho_Chi_Minh');
$flash_sale_price = null;
$flash_sale_discount = 0;
if (isset($_GET['product_id']) && isset($_GET['color'])) {
    $product_id = $_GET['product_id'];
    $color = urldecode($_GET['color']);
    $current_time = date('Y-m-d H:i:s', time());
    $flash_query = "SELECT discount, price_discount FROM flash_sales WHERE product_id = ? AND color = ? AND start_time <= ? AND end_time >= ?";
    $flash_stmt = mysqli_prepare($connect, $flash_query);
    if (!$flash_stmt) {
        echo "<!-- Lỗi chuẩn bị truy vấn Flash Sale: " . mysqli_error($connect) . " -->";
    }
    mysqli_stmt_bind_param($flash_stmt, "isss", $product_id, $color, $current_time, $current_time);
    if (!mysqli_stmt_execute($flash_stmt)) {
        echo "<!-- Lỗi thực thi truy vấn Flash Sale: " . mysqli_stmt_error($flash_stmt) . " -->";
    }
    $flash_result = mysqli_stmt_get_result($flash_stmt);
    if (!$flash_result) {
        echo "<!-- Lỗi lấy kết quả Flash Sale: " . mysqli_stmt_error($flash_stmt) . " -->";
    }
    $flash_sale = mysqli_fetch_assoc($flash_result);
    if ($flash_sale) {
        $flash_sale_price = $flash_sale['price_discount'];
        $flash_sale_discount = $flash_sale['discount'];
        echo "<!-- Flash Sale found: price_discount = $flash_sale_price, discount = $flash_sale_discount, color = $color -->";
    } else {
        echo "<!-- No Flash Sale found for product_id = $product_id, color = $color -->";
    }
    mysqli_stmt_close($flash_stmt);
} else {
    echo "<!-- Thiếu tham số product_id hoặc color trong URL -->";
}

$flash_sale_roms = [];
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $current_time = date('Y-m-d H:i:s', time());
    $flash_rom_query = "SELECT DISTINCT rom FROM flash_sales WHERE product_id = ? AND start_time <= ? AND end_time >= ?";
    $flash_rom_stmt = mysqli_prepare($connect, $flash_rom_query);
    mysqli_stmt_bind_param($flash_rom_stmt, "iss", $product_id, $current_time, $current_time);
    mysqli_stmt_execute($flash_rom_stmt);
    $flash_rom_result = mysqli_stmt_get_result($flash_rom_stmt);
    while ($row = mysqli_fetch_assoc($flash_rom_result)) {
        if ($row['rom']) {
            $flash_sale_roms[] = $row['rom'];
        }
    }
    mysqli_stmt_close($flash_rom_stmt);
}


// Truy vấn khuyến mãi từ bảng promotions
$brand_id_iphone = 3; // Giả định brand_id của Apple (iPhone) là 1, bạn cần thay đổi theo thực tế
$current_date = date('Y-m-d'); // Ngày hiện tại: 2025-05-26

$sql_promotions = "SELECT promo_code, discount, discount_type, expiry_date, condition_type, condition_value, condition_message, brand_id
                   FROM promotions 
                   WHERE (brand_id = ? OR brand_id IS NULL) 
                   AND expiry_date >= ? 
                   ORDER BY expiry_date ASC";
$stmt_promotions = mysqli_prepare($connect, $sql_promotions);
mysqli_stmt_bind_param($stmt_promotions, "is", $brand_id_iphone, $current_date);
mysqli_stmt_execute($stmt_promotions);
$result_promotions = mysqli_stmt_get_result($stmt_promotions);
$promotions = mysqli_fetch_all($result_promotions, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_promotions);

?>

<div id="product-container" class="product-detail">
    <!-- Cột trái -->
    <div class="product-left">
        <!-- Vùng hiển thị ảnh chính -->
        <div class="product-image-wrapper">
            <button class="image-nav-btn image-nav-left">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="product-main-image">
                <?php
                $default_main_image = !empty($color_images[0]['images_detail']) ? $color_images[0]['images_detail'][0] : 'default.png';
                ?>
                <img src="../admin/img/<?= htmlspecialchars($default_main_image); ?>" alt="iPhone 15" id="current-product-image">
            </div>
            <button class="image-nav-btn image-nav-right">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <!-- Thumbnails đổi ảnh -->
        <div class="product-thumbnails" id="product-thumbnails">
            <?php if (!empty($color_images[0]['images_detail'])): ?>
                <?php foreach ($color_images[0]['images_detail'] as $detail_image): ?>
                    <?php if (!empty($detail_image)): ?>
                        <img src="../admin/img/<?= htmlspecialchars(trim($detail_image)); ?>" alt="<?= htmlspecialchars($color_images[0]['color'] ?? 'Mặc định') . ' - Chi tiết'; ?>">
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <img src="../admin/img/default.png" alt="Mặc định - Chi tiết">
            <?php endif; ?>
        </div>

      

        <!-- Thông số nổi bật -->
        <div class="specs-highlight">
            <h3>Thông số nổi bật</h3>
            <!-- Chip -->
            <div class="specs-block">
                <div class="specs-left">
                    <p class="spec-title">GPU (Chip)</p>
                    <p class="spec-value"><?= htmlspecialchars($gpu); ?></p>
                    <a href="#">Tìm hiểu chip trên điện thoại iPhone</a>
                </div>
                <div class="specs-right">
                    <div class="spec-option <?= $gpu_rating == 'hiệu năng tốt' ? 'active' : 'gray'; ?>">
                        <i class="fas fa-microchip"></i>
                        <p>Hiệu năng tốt</p>
                    </div>
                    <div class="spec-option <?= $gpu_rating == 'hiệu năng rất tốt' ? 'active' : 'gray'; ?>">
                        <i class="fas fa-microchip"></i>
                        <p>Hiệu năng rất tốt</p>
                    </div>
                    <div class="spec-option <?= $gpu_rating == 'hiệu năng vượt trội' ? 'active' : 'gray'; ?>">
                        <i class="fas fa-microchip"></i>
                        <p>Hiệu năng vượt trội</p>
                    </div>
                </div>
            </div>

            <!-- Kích thước màn hình -->
            <div class="specs-block">
                <div class="specs-left">
                    <p class="spec-title">Kích thước màn hình</p>
                    <p class="spec-value"><?= htmlspecialchars($screen); ?></p>
                    <a href="#">Chọn kích thước màn hình phù hợp cho iPhone</a>
                </div>
                <div class="specs-right">
                    <div class="spec-option <?= $screen_rating == 'vừa' ? 'active' : 'gray'; ?>">
                        <i class="fas fa-mobile-alt"></i>
                        <p>Vừa</p>
                    </div>
                    <div class="spec-option <?= $screen_rating == 'lớn' ? 'active' : 'gray'; ?>">
                        <i class="fas fa-mobile-alt"></i>
                        <p>Lớn</p>
                    </div>
                    <div class="spec-option <?= $screen_rating == 'rất lớn' ? 'active' : 'gray'; ?>">
                        <i class="fas fa-mobile-alt"></i>
                        <p>Rất lớn</p>
                    </div>
                </div>
            </div>

            <!-- Thời lượng pin -->
            <div class="specs-block">
                <div class="specs-left">
                    <p class="spec-title">Dung lượng pin</p>
                    <p class="spec-value"><?= htmlspecialchars($pin); ?></p>
                    <a href="#">Chọn iPhone có dung lượng pin phù hợp</a>
                </div>
                <div class="specs-right">
                    <div class="spec-option <?= $pin_rating == 'trung bình' ? 'active' : 'gray'; ?>">
                        <i class="fas fa-battery-half"></i>
                        <p>Trung bình</p>
                    </div>
                    <div class="spec-option <?= $pin_rating == 'cao' ? 'active' : 'gray'; ?>">
                        <i class="fas fa-battery-three-quarters"></i>
                        <p>Cao</p>
                    </div>
                    <div class="spec-option <?= $pin_rating == 'rất cao' ? 'active' : 'gray'; ?>">
                        <i class="fas fa-battery-full"></i>
                        <p>Rất cao</p>
                    </div>
                </div>
            </div>
        </div>




        <div class="product-tabs">
            <div class="tab-header">
                <div class="tab active" data-tab="description">BÀI VIẾT ĐÁNH GIÁ</div>
                <div class="tab" data-tab="reviews">NHẬN XÉT CỦA KHÁCH HÀNG</div>
            </div>

            <div class="tab-content">
                <div class="tab-pane active" id="description">
                    <div class="content-wrapper" id="contentWrapper">
                        <div class="content-inner">
                            <?= $product['review_content'] ?? '<p>Chưa có bài viết đánh giá.</p>'; ?>
                        </div>
                    </div>
                    <div class="read-more" id="readMoreBtn">
                        <span id="readMoreText">Đọc thêm</span> <i class="fas fa-chevron-down"></i>
                    </div>
                    <script>
                        const readMoreBtn = document.getElementById('readMoreBtn');
                        const readMoreText = document.getElementById('readMoreText');
                        const contentWrapper = document.getElementById('contentWrapper');

                        readMoreBtn.addEventListener('click', () => {
                            contentWrapper.classList.toggle('expanded');
                            readMoreBtn.classList.toggle('expanded');
                            if (contentWrapper.classList.contains('expanded')) {
                                readMoreText.textContent = 'Thu gọn';
                            } else {
                                readMoreText.textContent = 'Đọc thêm';
                                contentWrapper.style.opacity = 0;
                                setTimeout(() => {
                                    contentWrapper.style.opacity = 1;
                                }, 10);
                            }
                        });
                    </script>
                </div>

                <div class="tab-pane" id="reviews">
                    <h4 class="review-title">ĐÁNH GIÁ SẢN PHẨM</h4>

                    <div class="rating-stars" id="rating-stars">
                        <span data-value="1">★</span>
                        <span data-value="2">★</span>
                        <span data-value="3">★</span>
                        <span data-value="4">★</span>
                        <span data-value="5">★</span>
                    </div>

                    <textarea id="review-text" placeholder="Nội dung..."></textarea>

                    <button id="btn-review" onclick="submitReview()">Gửi đánh giá</button>


                    <hr class="review-divider">

                    <h4 class="review-title">ĐÁNH GIÁ TỪ NGƯỜI DÙNG</h4>


                    <?php
                    
                    require_once '../admin/config/db.php';

                    // Khởi tạo các biến mặc định 
                    $avg_rating = 0;
                    $review_count = 0;
                    $summary = null;
                    $reviews_html = ''; // Lưu trữ HTML của danh sách đánh giá
                    $current_user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

                    // Kiểm tra kết nối cơ sở dữ liệu
                    if (!$connect) {
                        $reviews_html = '<p>Lỗi kết nối cơ sở dữ liệu: ' . mysqli_connect_error() . '</p>';
                    } else {
                        // Truy vấn đánh giá
                        $product_id = 12; // Thay bằng ID sản phẩm thực tế (ví dụ: từ $_GET['product_id'])
                        $query = "SELECT * FROM reviews WHERE product_id = $product_id ORDER BY review_date DESC";
                        $result = mysqli_query($connect, $query);

                        if (!$result) {
                            $reviews_html = '<p>Lỗi truy vấn đánh giá: ' . mysqli_error($connect) . '</p>';
                        } else {
                            if (mysqli_num_rows($result) === 0) {
                                $reviews_html = '<p id="no-review-message" class="no-reviews" style="color: gray; margin-top: 10px;">';
                                $reviews_html .= 'Chưa có lượt đánh giá nào từ người dùng. Hãy cho chúng tôi biết ý kiến của bạn.';
                                $reviews_html .= '</p>';
                            } else {
                                while ($review = mysqli_fetch_assoc($result)) {
                                    $stars = str_repeat("★", $review['rating']) . str_repeat("☆", 5 - $review['rating']);
                                    $review_date = date('d/m/Y H:i', strtotime($review['review_date']));
                                    $delete_button = ($current_user && $current_user === $review['username']) ? '<button class="delete-review" data-review-id="' . $review['id'] . '">Xóa Đánh Giá</button>' : '';
                                    $reviews_html .= '<div class="user-review" style="display: flex; margin-top: 15px; position: relative;">';
                                    $reviews_html .= '<i class="fas fa-user-circle" style="font-size: 32px; color: gray; margin-right: 10px;"></i>';
                                    $reviews_html .= '<div>';
                                    $reviews_html .= '<strong style="margin-right: 10px;">' . htmlspecialchars($review['username']) . '</strong>';
                                    $reviews_html .= '<span class="review-date">' . $review_date . '</span>';
                                    $reviews_html .= '<div class="user-stars" style="color: gold;">' . $stars . '</div>';
                                    $reviews_html .= '<p class="user-review-content">' . htmlspecialchars($review['content']) . '</p>';
                                    $reviews_html .= '</div>';
                                    $reviews_html .= $delete_button;
                                    $reviews_html .= '</div>';
                                }
                            }
                        }

                        // Tính điểm trung bình và số lượng đánh giá
                        $query_summary = "SELECT AVG(rating) as avg_rating, COUNT(*) as count FROM reviews WHERE product_id = $product_id";
                        $result_summary = mysqli_query($connect, $query_summary);

                        if ($result_summary) {
                            $summary = mysqli_fetch_assoc($result_summary);
                            $avg_rating = isset($summary['avg_rating']) && $summary['avg_rating'] !== null ? round($summary['avg_rating'], 1) : 0;
                            $review_count = (int)$summary['count'];
                        } else {
                            $reviews_html .= '<p>Lỗi truy vấn tóm tắt: ' . mysqli_error($connect) . '</p>';
                        }

                        
                    }
                    ?>

                    <!-- Cập nhật tóm tắt đánh giá -->
                    <div class="user-rating-summary">
                        <span class="score"><?php echo $avg_rating; ?></span>
                        <span class="stars"><?php echo str_repeat("★", round($avg_rating)); ?></span>
                        <span class="count"><i class="fas fa-users"></i> <?php echo $review_count; ?></span>
                    </div>

                    <div id="review-list">
                        <?php echo $reviews_html; ?>
                    </div>

                    <!-- Phần mẫu đánh giá (ẩn, không sử dụng) -->
                    <div class="user-review" id="user-review" style="display: none;">
                        <i class="fas fa-user-circle" style="font-size: 32px; color: gray; margin-right: 10px;"></i>
                        <div>
                            <strong><?php echo isset($username) ? $username : 'Người dùng'; ?></strong>
                            <span class="review-date"><?php echo date('d/m/Y'); ?></span>
                            <div class="user-stars" style="color: gold;"></div>
                            <p class="user-review-content"></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <script>
            let selectedRating = 0; // Biến lưu số sao đã chọn

            // Xử lý khi người dùng chọn sao
            const stars = document.querySelectorAll('.rating-stars span');
            stars.forEach((star, index) => {
                star.addEventListener('click', function () {
                    selectedRating = parseInt(this.getAttribute('data-value'));

                    // Reset màu tất cả sao
                    stars.forEach(s => s.style.color = 'lightgray');

                    // Tô màu từ sao 1 đến sao đã chọn
                    for (let i = 0; i < selectedRating; i++) {
                        stars[i].style.color = 'gold';
                    }
                });
            });

            // Xử lý gửi đánh giá
            function submitReview() {
                const content = document.getElementById('review-text').value.trim();
                const reviewList = document.getElementById('review-list');
                const noReviewMsg = document.getElementById('no-review-message');

                if (selectedRating === 0 || content === "") {
                    alert("Vui lòng chọn sao và nhập nội dung đánh giá.");
                    return;
                }

                // Kiểm tra đăng nhập
                fetch('check_login.php')
                    .then(response => response.json())
                    .then(data => {
                        if (!data.logged_in) {
                            alert('Bạn cần phải đăng nhập để đánh giá sản phẩm.');
                            window.location.href = '../login1.html';
                            return;
                        }

                        // Gửi yêu cầu AJAX nếu đã đăng nhập
                        fetch('submit_review.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                rating: selectedRating,
                                content: content,
                                product_id: <?= $product_id ?>
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Ẩn thông báo "Chưa có đánh giá" nếu phần tử tồn tại
                                if (noReviewMsg) {
                                    noReviewMsg.style.display = "none";
                                }

                                // Tạo phần tử đánh giá mới
                                const reviewItem = document.createElement('div');
                                reviewItem.className = 'user-review';
                                reviewItem.style.display = 'flex';
                                reviewItem.style.marginTop = '15px';

                                // Lấy ngày giờ hiện tại
                                const now = new Date();
                                const dateString = now.toLocaleDateString('vi-VN') + ' ' + now.toLocaleTimeString('vi-VN');

                                // Lấy username từ session
                                const username = "<?php echo isset($_SESSION['user']) ? $_SESSION['user'] : ''; ?>";

                                // Nội dung đánh giá và sao
                                reviewItem.innerHTML = `
                                    <i class="fas fa-user-circle" style="font-size: 32px; color: gray; margin-right: 10px;"></i>
                                    <div>
                                        <strong>${username}</strong>
                                        <span class="review-date">${dateString}</span>
                                        <div class="user-stars" style="color: gold;">${"★".repeat(selectedRating)}${"☆".repeat(5 - selectedRating)}</div>
                                        <p class="user-review-content">${content}</p>
                                    </div>
                                `;

                                // Thêm vào danh sách đánh giá
                                reviewList.appendChild(reviewItem);

                                // Cập nhật số lượng người đánh giá
                                const userCount = document.querySelector('.count');
                                const currentCount = parseInt(userCount.textContent.match(/\d+/)[0]) || 0;
                                userCount.innerHTML = `<i class="fas fa-users"></i> ${currentCount + 1}`;

                                // Cập nhật điểm trung bình (tạm thời giữ nguyên logic cũ, có thể cần tính lại từ DB)
                                document.querySelector('.score').textContent = selectedRating;

                                // Reset form
                                document.getElementById('review-text').value = '';
                                selectedRating = 0;
                                document.querySelectorAll('.rating-stars span').forEach(s => s.style.color = 'lightgray');
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            alert('Lỗi khi gửi đánh giá: ' + error);
                        });
                    })
                    .catch(error => {
                        alert('Lỗi khi kiểm tra đăng nhập: ' + error);
                    });
            }

            // Xử lý xóa đánh giá
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-review')) {
                    const reviewId = e.target.getAttribute('data-review-id');
                    if (confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) {
                        fetch('delete_review.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                review_id: reviewId
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                e.target.closest('.user-review').remove();
                                const userCount = document.querySelector('.count');
                                const currentCount = parseInt(userCount.textContent.match(/\d+/)[0]) || 0;
                                userCount.innerHTML = `<i class="fas fa-users"></i> ${currentCount - 1}`;
                                // Cập nhật điểm trung bình (có thể cần truy vấn lại từ server)
                                document.querySelector('.score').textContent = data.avg_rating || 0;
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            alert('Lỗi khi xóa đánh giá: ' + error);
                        });
                    }
                }
            });
        </script>
        <script>
            document.querySelectorAll(".tab").forEach(tab => {
                tab.addEventListener("click", () => {
                    document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
                    document.querySelectorAll(".tab-pane").forEach(pane => pane.classList.remove("active"));

                    tab.classList.add("active");
                    document.getElementById(tab.dataset.tab).classList.add("active");
                });
            });
        </script>

    </div>

    <!-- Cột phải -->
    <div class="product-right">
        <h1 class="product-title"><?= htmlspecialchars($product_name); ?> <span id="rom-title"><?= htmlspecialchars($default_rom); ?></span></h1>
        <div class="product-meta">
            <span class="product-id">Mã sản phẩm: <?= htmlspecialchars($product_code); ?></span>
            <span class="divider">|</span>
            <span class="rating">⭐ <?php echo number_format($avg_rating, 1); ?></span>
            <span class="divider">|</span>
            <a href="#">Đã bán: 12</a>
            <span class="divider">|</span>
            <a href="#"><?php echo $review_count; ?> đánh giá</a>
        </div>

        <!-- Dung lượng -->
        <div class="product-options">
            <div class="option">
                <label><strong>Dung lượng</strong></label>
                <div class="storage-options">
                    <?php if (!empty($color_data)): ?>
                        <?php foreach ($unique_roms as $rom): ?>
                            <?php
                            $rom_data = array_filter($color_data, fn($item) => $item['rom'] === $rom);
                            $rom_data = array_values($rom_data)[0] ?? $default_color;
                            ?>
                            <div class="storage <?= $rom === $selected_rom ? 'active' : ''; ?>" data-rom="<?= htmlspecialchars($rom); ?>" data-new-price="<?= $rom_data['price_discount'] ?? 15990000; ?>" data-old-price="<?= $rom_data['price'] ?? 22990000; ?>" data-installment-add="<?= $rom_data['installment'] ?? 0; ?>">
                                <?= htmlspecialchars($rom); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="storage active" data-rom="128 GB" data-new-price="15990000" data-old-price="22990000" data-installment-add="0">128 GB</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Màu sắc -->
            <div class="option">
                <label><strong>Màu sắc</strong></label>
                <div class="color-options">
                    <?php if (!empty($color_images)): ?>
                        <?php foreach ($color_images as $index => $color_data): ?>
                            <div class="color <?= $color_data['color'] === $selected_color ? 'active' : ''; ?>" data-color="<?= htmlspecialchars($color_data['color']); ?>" data-images='<?= json_encode($color_data['images_detail']); ?>'>
                                <?php if (!empty($color_data['image']) && file_exists("../admin/img/" . trim($color_data['image']))): ?>
                                    <img src="../admin/img/<?= htmlspecialchars($color_data['image']); ?>" alt="<?= htmlspecialchars($color_data['color']); ?>">
                                <?php else: ?>
                                    <img src="../admin/img/default.png" alt="<?= htmlspecialchars($color_data['color']); ?>">
                                <?php endif; ?>
                                <span><?= htmlspecialchars($color_data['color']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="color active" data-color="Mặc định" data-images='["default.png"]'>
                            <img src="../admin/img/default.png" alt="Mặc định">
                            <span>Mặc định</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            const colorOptions = document.querySelectorAll('.color');
            colorOptions.forEach(option => {
                option.addEventListener('click', () => {
                    colorOptions.forEach(o => o.classList.remove('active'));
                    option.classList.add('active');
                });
            });
        </script>

        <div class="price-promo-box">
            <!-- Giá & Trả góp -->
            <div class="price-row">
                <div class="buy-now">
                    <p class="label1">Mua ngay với giá</p>
                    <div class="price-line">
                        <span class="new-price"><?= number_format($flash_sale_price ?? $default_new_price, 0, ',', '.'); ?></span>
                        <span class="currency1">₫</span>
                    </div>
                    <div class="old-line">
                        <span class="old-price"><?= number_format($default_old_price, 0, ',', '.'); ?></span>
                        <span class="currency1">₫</span>
                        <span class="discount1"><?= $flash_sale_discount > 0 ? $flash_sale_discount : $default_discount; ?>%</span>
                    </div>
                    <?php if ($flash_sale_price !== null): ?>
                        <div class="flash-sale-tag" style="background-color:rgb(247, 231, 200); color:rgb(236, 105, 11); padding: 5px 10px; border-radius: 5px; display: flex; align-items: center; margin-top: 5px; width: 80px;">
                            <i class="fas fa-bolt" style="margin-right: 5px;"></i> Giá sốc
                        </div>
                    <?php endif; ?>
                </div>
                <div class="middle">
                    <p class="or-text">Hoặc</p>
                </div>
                <div class="installment">
                    <p class="label1">Trả góp</p>
                    <div class="price-line">
                        <span class="installment-price"><?= number_format($default_installment_price, 0, ',', '.'); ?></span>
                        <span class="currency1">₫</span>
                        <span class="per-month">/tháng</span>
                    </div>
                    <button class="btn-installment mt-2">Trả góp ngay</button>
                </div>
            </div>

            <!-- Khuyến mãi nổi bật -->
            <div class="promotions">
                <h4>Khuyến mãi được hưởng</h4>
                <?php if (!empty($promotions)): ?>
                    <ul class="promo-list">
                        <?php foreach ($promotions as $promo): ?>
                            <?php
                            // Định dạng giá trị giảm giá
                            $discount_text = $promo['discount_type'] == 'percent' 
                                ? "Giảm ngay <strong>" . $promo['discount'] . "%</strong>"
                                : "Giảm ngay <strong>" . number_format($promo['discount'], 0, ',', '.') . "₫</strong>";

                            // Định dạng điều kiện
                            $condition_text = $promo['condition_type'] == 'quantity'
                                ? "khi mua từ " . $promo['condition_value'] . " sản phẩm trở lên"
                                : "khi tổng đơn hàng từ " . number_format($promo['condition_value'], 0, ',', '.') . "₫ trở lên";

                            // Định dạng ngày hết hạn
                            $expiry_date = date('d/m/Y', strtotime($promo['expiry_date']));
                            ?>
                            <li>
                                Voucher: <strong><?= htmlspecialchars($promo['promo_code']); ?></strong> - 
                                <?= $discount_text; ?> 
                                <?= $condition_text; ?> 
                                (HSD: <?= $expiry_date; ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <p class="promo-note">Sử dụng mã tại trang thanh toán để áp dụng khuyến mãi!</p>
                <?php else: ?>
                    <p>Không có khuyến mãi nào áp dụng.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="action-buttons">
            <div class="quantity-selector">
                <button class="quantity-btn minus">-</button>
                <span id="quantity-value">1</span>
                <button class="quantity-btn plus">+</button>
            </div>

            <button class="btn-buy">
                <i class="fa-solid fa-bag-shopping"></i> Mua ngay
            </button>

            <button class="btn-cart">
                <i class="fa-solid fa-cart-shopping"></i> Thêm vào giỏ
            </button>
        </div>

        <script>
            // Bổ sung JavaScript cho trang chi tiết sản phẩm (thêm vào cuối file hoặc tạo file js riêng)
            document.addEventListener("DOMContentLoaded", function() {

                // Cập nhật số lượng giỏ hàng khi trang được tải
                updateCartBadge();

                // Lắng nghe sự kiện click cho nút "Thêm vào giỏ"
                const addToCartBtn = document.querySelector('.btn-cart');
                const buyNowBtn = document.querySelector('.btn-buy'); // Thêm nút Mua ngay
                
                if (addToCartBtn) {
                    addToCartBtn.addEventListener('click', function() {
                        // Lấy thông tin sản phẩm hiện tại
                        const productId = <?= $product_id ?? 0 ?>; // ID sản phẩm từ PHP
                        const productName = document.querySelector('.product-title').textContent.trim();
                        
                        // Lấy ROM đang được chọn
                        const activeStorage = document.querySelector('.storage.active');
                        const rom = activeStorage ? activeStorage.getAttribute('data-rom') : '';
                        
                        // Lấy màu sắc đang được chọn
                        const activeColor = document.querySelector('.color.active');
                        const color = activeColor ? activeColor.getAttribute('data-color') : '';
                        
                        // Lấy giá tiền
                        const price = parseInt(document.querySelector('.old-price').textContent.replace(/\./g, ''));
                        const priceDiscount = parseInt(document.querySelector('.new-price').textContent.replace(/\./g, ''));
                        
                        // Lấy số lượng
                        const quantity = parseInt(document.getElementById('quantity-value').textContent);
                        
                        // Lấy hình ảnh
                        const activeColorObj = document.querySelector('.color.active');
                        const image = activeColorObj ? activeColorObj.querySelector('img').getAttribute('src').replace('../admin/img/', '') : '';
                        
                        // Gửi dữ liệu bằng Ajax
                        sendAddToCartRequest(productId, productName, color, rom, price, priceDiscount, quantity, image);
                    });
                }


                // Lắng nghe sự kiện click cho nút "Mua ngay"
                if (buyNowBtn) {
                    buyNowBtn.addEventListener('click', function() {
                        // Kiểm tra trạng thái đăng nhập
                        fetch('check_login.php') // Tạo file check_login.php để kiểm tra trạng thái đăng nhập
                            .then(response => response.json())
                            .then(data => {
                                if (!data.logged_in) {
                                    alert('Bạn hãy đăng ký hoặc đăng nhập để mua hàng');
                                    window.location.href = '../login1.html';
                                    return;
                                }

                                // Lấy thông tin sản phẩm hiện tại
                                const productId = <?= $product_id ?? 0 ?>;
                                const productName = document.querySelector('.product-title').textContent.trim();
                                const activeStorage = document.querySelector('.storage.active');
                                const rom = activeStorage ? activeStorage.getAttribute('data-rom') : '';
                                const activeColor = document.querySelector('.color.active');
                                const color = activeColor ? activeColor.getAttribute('data-color') : '';
                                const price = parseInt(document.querySelector('.old-price').textContent.replace(/\./g, ''));
                                const priceDiscount = parseInt(document.querySelector('.new-price').textContent.replace(/\./g, ''));
                                const quantity = parseInt(document.getElementById('quantity-value').textContent);
                                const activeColorObj = document.querySelector('.color.active');
                                const image = activeColorObj ? activeColorObj.querySelector('img').getAttribute('src').replace('../admin/img/', '') : '';

                                // Gửi dữ liệu bằng Ajax và chuyển hướng
                                sendBuyNowRequest(productId, productName, color, rom, price, priceDiscount, quantity, image);
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Đã xảy ra lỗi khi kiểm tra trạng thái đăng nhập');
                            });
                    });
                }

                
                // Hàm gửi request thêm vào giỏ hàng
                function sendAddToCartRequest(productId, productName, color, rom, price, priceDiscount, quantity, image) {
                    const formData = new FormData();
                    formData.append('product_id', productId);
                    formData.append('product_name', productName);
                    formData.append('color', color);
                    formData.append('rom', rom);
                    formData.append('price', price);
                    formData.append('price_discount', priceDiscount);
                    formData.append('quantity', quantity);
                    formData.append('image', image);
                    
                    fetch('add_to_cart.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hiển thị thông báo thành công
                            alert('Đã thêm sản phẩm vào giỏ hàng');
                            
                            // Cập nhật số lượng sản phẩm trong giỏ hàng trên header (nếu có)
                            updateCartBadge();
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Đã xảy ra lỗi khi thêm vào giỏ hàng');
                    });
                }


                // Hàm gửi request cho "Mua ngay"
                function sendBuyNowRequest(productId, productName, color, rom, price, priceDiscount, quantity, image) {
                    const formData = new FormData();
                    formData.append('product_id', productId);
                    formData.append('product_name', productName);
                    formData.append('color', color);
                    formData.append('rom', rom);
                    formData.append('price', price);
                    formData.append('price_discount', priceDiscount);
                    formData.append('quantity', quantity);
                    formData.append('image', image);
                    formData.append('action', 'buy_now'); // Thêm action để phân biệt với thêm vào giỏ

                    fetch('add_to_cart.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.cart_id) {
                            // Chuyển hướng đến checkout.php với cart_id vừa thêm
                            window.location.href = `../checkout.php?cart_id=${data.cart_id}`;
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi xử lý mua ngay');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Đã xảy ra lỗi khi xử lý mua ngay');
                    });
                }
                
                // Hàm cập nhật số lượng sản phẩm trong giỏ hàng (badge)
                function updateCartBadge() {
                    fetch('get_cart_count.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.count !== undefined) {
                            // Cập nhật số lượng hiển thị trên tất cả các badge giỏ hàng
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
            document.addEventListener("DOMContentLoaded", async function () {
                const storageOptions = document.querySelectorAll('.storage');
                const colorOptions = document.querySelectorAll('.color');
                const titleRom = document.querySelector('#rom-title');
                const newPriceEl = document.querySelector('.new-price');
                const oldPriceEl = document.querySelector('.old-price');
                const discountEl = document.querySelector('.discount1');
                const installmentEl = document.querySelector('.installment-price');
                const mainImage = document.querySelector('#current-product-image');
                const thumbnailsContainer = document.querySelector('#product-thumbnails');
                const leftBtn = document.querySelector('.image-nav-left');
                const rightBtn = document.querySelector('.image-nav-right');

                let thumbnailImages = document.querySelectorAll(".product-thumbnails img");
                let currentIndex = 0;

                // Lấy dữ liệu từ PHP
                const colorImagesData = <?= json_encode($color_images); ?>;
                const specifications = <?= $specifications; ?>;
                const flashSaleRoms = <?= json_encode($flash_sale_roms); ?>;

                // Xây dựng config cho tất cả cặp (ROM, màu)
                const config = {};
                colorImagesData.forEach(item => {
                    const rom = item.rom;
                    const color = item.color;
                    const imagesDetail = item.images_detail || [];
                    if (!config[rom]) config[rom] = {};
                    config[rom][color] = { imagesDetail: imagesDetail };
                });

                // Dữ liệu mặc định
                const defaultConfig = {
                    newPrice: 15990000,
                    oldPrice: 22990000,
                    installmentAdd: 0,
                    imagesDetail: ["default.png"]
                };

                // Lấy color và rom từ URL
                const urlParams = new URLSearchParams(window.location.search);
                const selectedColorFromUrl = urlParams.get('color') ? decodeURIComponent(urlParams.get('color')) : (colorImagesData[0]?.color || 'Mặc định');
                const selectedRomFromUrl = urlParams.get('rom') ? decodeURIComponent(urlParams.get('rom')) : '<?= htmlspecialchars($selected_rom); ?>';

                // Sử dụng giá trị từ URL ngay từ đầu
                let selectedRom = selectedRomFromUrl;
                let selectedColor = selectedColorFromUrl;

                // Thêm vào trong <script> của iphone-15.php, sau khi định nghĩa các biến ban đầu

                const isFlashSale = urlParams.get('flash_sale') === '1';
                const productId = urlParams.get('product_id');

                // Hàm cập nhật giá và ROM
                async function updatePriceAndRom(rom, color) {
                    console.log('Updating price for rom:', rom, 'color:', color);
                    const selectedStorage = Array.from(storageOptions).find(option => option.getAttribute('data-rom') === rom);
                    let newPrice = selectedStorage ? parseInt(selectedStorage.getAttribute('data-new-price')) : defaultConfig.newPrice;
                    let oldPrice = selectedStorage ? parseInt(selectedStorage.getAttribute('data-old-price')) : defaultConfig.oldPrice;
                    let installmentAdd = selectedStorage ? parseInt(selectedStorage.getAttribute('data-installment-add')) : defaultConfig.installmentAdd;
                    const flashSaleTag = document.querySelector('.flash-sale-tag');

                    const productId = urlParams.get('product_id');

                    // Kiểm tra xem ROM có thuộc Flash Sale không
                    if (productId && flashSaleRoms.includes(rom)) {
                        try {
                            const response = await fetch(`../get_flash_sale_price.php?product_id=${encodeURIComponent(productId)}&color=${encodeURIComponent(color)}&rom=${encodeURIComponent(rom)}`);
                            const data = await response.json();
                            console.log('Flash Sale response:', data);
                            if (data.success && data.price_discount) {
                                newPrice = data.price_discount;
                                const discountPercent = data.discount || (oldPrice > 0 ? Math.round(100 - (newPrice / oldPrice) * 100) : 0);
                                const installmentPrice = Math.round(newPrice / 12 + installmentAdd);

                                titleRom.textContent = rom;
                                newPriceEl.textContent = newPrice.toLocaleString('vi-VN');
                                oldPriceEl.textContent = oldPrice.toLocaleString('vi-VN');
                                discountEl.textContent = discountPercent + '%';
                                installmentEl.textContent = installmentPrice.toLocaleString('vi-VN');

                                if (flashSaleTag) {
                                    flashSaleTag.style.display = 'flex';
                                }
                            } else {
                                const discountPercent = oldPrice > 0 ? Math.round(100 - (newPrice / oldPrice) * 100) : 0;
                                const installmentPrice = Math.round(newPrice / 12 + installmentAdd);

                                titleRom.textContent = rom;
                                newPriceEl.textContent = newPrice.toLocaleString('vi-VN');
                                oldPriceEl.textContent = oldPrice.toLocaleString('vi-VN');
                                discountEl.textContent = discountPercent + '%';
                                installmentEl.textContent = installmentPrice.toLocaleString('vi-VN');

                                if (flashSaleTag) {
                                    flashSaleTag.style.display = 'none';
                                }
                            }
                        } catch (error) {
                            console.error('Error fetching Flash Sale price:', error);
                            const discountPercent = oldPrice > 0 ? Math.round(100 - (newPrice / oldPrice) * 100) : 0;
                            const installmentPrice = Math.round(newPrice / 12 + installmentAdd);

                            titleRom.textContent = rom;
                            newPriceEl.textContent = newPrice.toLocaleString('vi-VN');
                            oldPriceEl.textContent = oldPrice.toLocaleString('vi-VN');
                            discountEl.textContent = discountPercent + '%';
                            installmentEl.textContent = installmentPrice.toLocaleString('vi-VN');

                            if (flashSaleTag) {
                                flashSaleTag.style.display = 'none';
                            }
                        }
                    } else {
                        // Nếu ROM không thuộc Flash Sale, hiển thị giá gốc
                        const discountPercent = oldPrice > 0 ? Math.round(100 - (newPrice / oldPrice) * 100) : 0;
                        const installmentPrice = Math.round(newPrice / 12 + installmentAdd);

                        titleRom.textContent = rom;
                        newPriceEl.textContent = newPrice.toLocaleString('vi-VN');
                        oldPriceEl.textContent = oldPrice.toLocaleString('vi-VN');
                        discountEl.textContent = discountPercent + '%';
                        installmentEl.textContent = installmentPrice.toLocaleString('vi-VN');

                        if (flashSaleTag) {
                            flashSaleTag.style.display = 'none';
                        }
                    }
                }

                // Hàm cập nhật ảnh chính và thumbnail
                function updateImages(color) {
                    console.log('Updating images for color:', color);
                    let imagesDetail = defaultConfig.imagesDetail;
                    const colorData = colorImagesData.find(item => item.color === color);
                    if (colorData && colorData.images_detail && colorData.images_detail.length > 0) {
                        imagesDetail = colorData.images_detail;
                    }

                    // Cập nhật ảnh chính
                    mainImage.src = "../admin/img/" + (imagesDetail[0] || "default.png");
                    mainImage.alt = color + " - Chi tiết";

                    // Cập nhật thumbnail
                    thumbnailsContainer.innerHTML = '';
                    imagesDetail.forEach(img => {
                        const thumbnail = document.createElement('img');
                        thumbnail.src = "../admin/img/" + (img || "default.png");
                        thumbnail.alt = color + " - Chi tiết";
                        thumbnailsContainer.appendChild(thumbnail);
                    });

                    // Cập nhật lại thumbnailImages và sự kiện
                    thumbnailImages = document.querySelectorAll(".product-thumbnails img");
                    currentIndex = 0;
                    thumbnailImages.forEach((img, index) => {
                        img.addEventListener("click", () => updateMainImage(index));
                    });
                    updateMainImage(0);
                }

                // Hàm cập nhật ảnh chính khi điều hướng
                function updateMainImage(index) {
                    if (thumbnailImages.length === 0) return;
                    mainImage.src = thumbnailImages[index].src;
                    thumbnailImages.forEach(img => {
                        img.classList.remove("active");
                        img.classList.remove("hovered");
                    });
                    thumbnailImages[index].classList.add("active");
                    thumbnailImages[index].classList.add("hovered");
                    currentIndex = index;
                }

                // Cập nhật khi tải trang
                await updatePriceAndRom(selectedRom, selectedColor);
                updateImages(selectedColor);

                // Xử lý chọn ROM
                storageOptions.forEach(option => {
                    option.addEventListener('click', () => {
                        storageOptions.forEach(o => o.classList.remove('active'));
                        option.classList.add('active');
                        selectedRom = option.getAttribute('data-rom');
                        updatePriceAndRom(selectedRom, selectedColor);
                    });
                });

                // Xử lý chọn màu
                colorOptions.forEach(option => {
                    option.addEventListener('click', () => {
                        colorOptions.forEach(o => o.classList.remove('active'));
                        option.classList.add('active');
                        selectedColor = option.getAttribute('data-color');
                        updatePriceAndRom(selectedRom, selectedColor);
                        updateImages(selectedColor);
                    });
                });

                // Xử lý điều hướng ảnh
                leftBtn.addEventListener("click", () => {
                    if (currentIndex > 0) {
                        updateMainImage(currentIndex - 1);
                    }
                });

                rightBtn.addEventListener("click", () => {
                    if (currentIndex < thumbnailImages.length - 1) {
                        updateMainImage(currentIndex + 1);
                    }
                });

                // Hàm cập nhật nội dung modal từ specifications
                function updateModalContent() {
                    const specList = document.getElementById('spec-list');
                    if (!specList) return;
                    specList.innerHTML = '';

                    specifications.forEach(spec => {
                        const li = document.createElement('li');
                        const span = document.createElement('span');
                        const valueDiv = document.createElement('div');
                        valueDiv.className = 'value';
                        span.textContent = spec.param + ':';
                        valueDiv.textContent = spec.value || 'Không xác định';

                        li.appendChild(span);
                        li.appendChild(valueDiv);
                        specList.appendChild(li);
                    });
                }

                // Khởi tạo modal
                const detailButton = document.querySelector('.detail-button1');
                if (detailButton) {
                    detailButton.addEventListener('click', function() {
                        updateModalContent();
                        const detailModal = document.getElementById('detailModal');
                        if (detailModal) {
                            detailModal.style.display = 'block';
                            window.scrollTo({ top: 900, behavior: 'smooth' });
                        }
                    });
                }

                const closeButton = document.querySelector('.close');
                if (closeButton) {
                    closeButton.addEventListener('click', function() {
                        const detailModal = document.getElementById('detailModal');
                        if (detailModal) {
                            detailModal.style.display = 'none';
                        }
                    });
                }

                window.addEventListener('click', function(event) {
                    const detailModal = document.getElementById('detailModal');
                    if (detailModal && event.target === detailModal) {
                        detailModal.style.display = 'none';
                    }
                });
            });
        </script>
        <script>
            const quantityValue = document.getElementById("quantity-value");
            const minusBtn = document.querySelector(".quantity-btn.minus");
            const plusBtn = document.querySelector(".quantity-btn.plus");

            let quantity = 1;

            minusBtn.addEventListener("click", () => {
                if (quantity > 1) {
                    quantity--;
                    quantityValue.textContent = quantity;
                }
            });

            plusBtn.addEventListener("click", () => {
                quantity++;
                quantityValue.textContent = quantity;
            });
        </script>

        <!-- Hotline -->
        <div class="hotline">
            📞 Gọi <strong>1800-6601</strong> để được tư vấn mua hàng (Miễn phí)
        </div>


        <!-- Thông số kỹ thuật -->
        <div class="technical-specs">
            <h3>THÔNG SỐ KỸ THUẬT</h3>
            <ul>
                <li><span>Màn Hình:</span> <?= htmlspecialchars($screen); ?></li>
                <li><span>Camera Trước:</span> <?= htmlspecialchars($front_camera); ?></li>
                <li><span>Camera Sau:</span> <?= htmlspecialchars($rear_camera); ?></li>
                <li><span>Ram:</span> <?= htmlspecialchars($ram); ?></li>
                <li><span>Bộ Nhớ Trong:</span> <?= htmlspecialchars(implode(', ', $unique_roms)); ?></li>
                <li><span>CPU:</span> <?= htmlspecialchars($cpu); ?></li>
                <li><span>GPU (Chip):</span> <?= htmlspecialchars($gpu); ?></li>
                <li><span>Dung Lượng Pin:</span> <?= htmlspecialchars($pin); ?></li>
                <li><span>Hệ Điều Hành:</span> <?= htmlspecialchars($operating_system); ?></li>
            </ul>
            <button class="detail-button1">Xem cấu hình chi tiết</button>
        </div>

        <!-- Modal -->
        <div id="detailModal" class="modal">
            <div class="modal-content">
                <span class="close">×</span>
                <ul id="spec-list"></ul>
            </div>
        </div>

        <?php
        // Lấy thông tin sản phẩm hiện tại
        $product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 12;
        $sql_product = "SELECT brand_id, ram, pin, screen, front_camera, rear_camera FROM products WHERE prd_id = ?";
        $stmt_product = mysqli_prepare($connect, $sql_product);
        mysqli_stmt_bind_param($stmt_product, "i", $product_id);
        mysqli_stmt_execute($stmt_product);
        $result_product = mysqli_stmt_get_result($stmt_product);
        $current_product = mysqli_fetch_assoc($result_product);
        mysqli_stmt_close($stmt_product);

        $brand_id = $current_product['brand_id'] ?? 12; // Giả định brand_id của iPhone là 1
        $ram = $current_product['ram'] ?? '';
        $pin = $current_product['pin'] ?? '';
        $screen = $current_product['screen'] ?? '';
        $front_camera = $current_product['front_camera'] ?? '';
        $rear_camera = $current_product['rear_camera'] ?? '';

        // Truy vấn các sản phẩm cùng loại
        $sql_related = "SELECT prd_id, prd_name, price_discount, price, image, ram, pin, screen, front_camera, rear_camera 
                        FROM products 
                        WHERE brand_id = ? 
                        AND prd_id != ? 
                        AND (ram = ? OR pin = ? OR screen = ? OR front_camera = ? OR rear_camera = ?) 
                        LIMIT 4";
        $stmt_related = mysqli_prepare($connect, $sql_related);
        mysqli_stmt_bind_param($stmt_related, "iisssss", $brand_id, $product_id, $ram, $pin, $screen, $front_camera, $rear_camera);
        mysqli_stmt_execute($stmt_related);
        $result_related = mysqli_stmt_get_result($stmt_related);
        $related_products = mysqli_fetch_all($result_related, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt_related);
        ?>

        <!-- Thay thế phần related-products -->
        <div class="related-products">
            <h3>SẢN PHẨM CÙNG LOẠI</h3>
            <?php if (!empty($related_products)): ?>
                <?php foreach ($related_products as $related): ?>
                    <?php
                    $discount = $related['price'] > 0 ? round(100 - ($related['price_discount'] / $related['price']) * 100) : 0;
                    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $related['prd_name']), '-'));
                    $product_url = "{$slug}.php";
                    ?>
                    <div class="product-item" data-product-url="<?= htmlspecialchars($product_url); ?>">
                        <img src="../admin/img/<?= htmlspecialchars($related['image'] ?? 'default.png'); ?>" alt="<?= htmlspecialchars($related['prd_name']); ?>" class="product-thumb">
                        <div class="product-info">
                            <p class="product-name"><?= htmlspecialchars($related['prd_name']); ?>...</p>
                            <div class="rating">★★★★☆</div> <!-- Giả định rating tĩnh, có thể lấy từ DB nếu có -->
                            <div class="price-info">
                                <span class="price-sale"><?= number_format($related['price_discount'], 0, ',', '.'); ?>₫</span>
                                <?php if ($discount > 0): ?>
                                    <span class="price-original"><?= number_format($related['price'], 0, ',', '.'); ?>₫</span>
                                    <span class="discount">-<?= $discount; ?>%</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không có sản phẩm cùng loại.</p>
            <?php endif; ?>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const productItems = document.querySelectorAll(".product-item");
                productItems.forEach(item => {
                    item.addEventListener("click", function() {
                        const productUrl = this.getAttribute("data-product-url");
                        if (productUrl) {
                            window.location.href = productUrl;
                        }
                    });
                });
            });
        </script>


    </div>
</div>


</body>


  
<?php
mysqli_close($connect);
    include_once 'footer.php';
?>