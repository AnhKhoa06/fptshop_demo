<?php
session_start();
$username = isset($_SESSION['user']) ? $_SESSION['user'] : "Khách";
    include_once 'header.php';

?>

<title>iPhone 15 128GB</title>
<link rel="stylesheet" href="../product1.css">
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
    <a href="../brand/apple-iphone.php">Apple (iPhone)</a>
        <i class="fa-solid fa-angle-right"></i>
    <span>iPhone 15</span>
</div>

<div id="product-container" class="product-detail">
    <!-- Cột trái -->
    <div class="product-left">
        <!-- Vùng hiển thị ảnh chính -->
        <div class="product-image-wrapper">
            <button class="image-nav-btn image-nav-left">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="product-main-image">
                <img src="../img/iphone15.png" alt="iPhone 15" id="current-product-image">
            </div>
            <button class="image-nav-btn image-nav-right">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <!-- Thumbnails đổi ảnh -->
        <div class="product-thumbnails">
            <img src="../img/iphone15.png" alt="Đen">
            <img src="../img/ip15.1.webp" alt="Hồng">
            <img src="../img/ip15.2.webp" alt="Xanh dương">
            <img src="../img/ip15.4.webp" alt="Xanh lá">
            <img src="../img/ip15.5.webp" alt="Vàng">
            <img src="../img/ip15.6.webp" alt="Vàng">
            <img src="../img/ip15.7.webp" alt="Vàng">
        </div>

        <script>
            const thumbnailImages = document.querySelectorAll(".product-thumbnails img");
            const mainImage = document.getElementById("current-product-image");
            const leftBtn = document.querySelector(".image-nav-left");
            const rightBtn = document.querySelector(".image-nav-right");
            let currentIndex = 0;
        
            function updateMainImage(index) {
                mainImage.src = thumbnailImages[index].src;
                // Xóa cả class 'active' và 'hovered' khỏi tất cả ảnh
                thumbnailImages.forEach(img => {
                    img.classList.remove("active");
                    img.classList.remove("hovered");
                });
                // Đặt ảnh hiện tại được chọn là 'active' và 'hovered'
                thumbnailImages[index].classList.add("active");
                thumbnailImages[index].classList.add("hovered");
                currentIndex = index;
            }
            // Click vào ảnh nhỏ để đổi ảnh chính
            thumbnailImages.forEach((img, index) => {
                img.addEventListener("click", () => updateMainImage(index));
            });
            // Nút chuyển trái
            leftBtn.addEventListener("click", () => {
                if (currentIndex > 0) {
                    updateMainImage(currentIndex - 1);
                }
            });
            // Nút chuyển phải
            rightBtn.addEventListener("click", () => {
                if (currentIndex < thumbnailImages.length - 1) {
                    updateMainImage(currentIndex + 1);
                }
            });
            // Hiển thị ảnh đầu tiên khi load
            updateMainImage(0);
        </script>

        <!-- Thông số nổi bật -->
        <div class="specs-highlight">
            <h3>Thông số nổi bật</h3>
            <!-- Chip -->
            <div class="specs-block">
                <div class="specs-left">
                    <p class="spec-title">GPU (Chip)</p>
                    <p class="spec-value">Apple GPU 6 nhân</p>
                    <a href="#">Tìm hiểu chip trên điện thoại iPhone</a>
                </div>
                <div class="specs-right">
                    <div class="spec-option gray">
                        <i class="fas fa-microchip"></i>
                        <p>Hiệu năng tốt</p>
                    </div>
                    <div class="spec-option active">
                        <i class="fas fa-microchip"></i>
                        <p>Hiệu năng rất tốt</p>
                    </div>
                    <div class="spec-option gray">
                        <i class="fas fa-microchip"></i>
                        <p>Hiệu năng vượt trội</p>
                    </div>
                </div>
            </div>

            <!-- Kích thước màn hình -->
            <div class="specs-block">
                <div class="specs-left">
                    <p class="spec-title">Kích thước màn hình</p>
                    <p class="spec-value">AMOLED 6.43" Full HD+</p>
                    <a href="#">Chọn kích thước màn hình phù hợp cho iPhone</a>
                </div>
                <div class="specs-right">
                    <div class="spec-option active">
                        <i class="fas fa-mobile-alt"></i>
                        <p>Vừa</p>
                    </div>
                    <div class="spec-option gray">
                        <i class="fas fa-mobile-alt"></i>
                        <p>Lớn</p>
                    </div>
                    <div class="spec-option gray">
                        <i class="fas fa-mobile-alt"></i>
                        <p>Rất lớn</p>
                    </div>
                </div>
            </div>

            <!-- Thời lượng pin -->
            <div class="specs-block">
                <div class="specs-left">
                    <p class="spec-title">Dung lượng pin</p>
                    <p class="spec-value">20 giờ</p>
                    <a href="#">Chọn iPhone có dung lượng pin phù hợp</a>
                </div>
                <div class="specs-right">
                    <div class="spec-option active">
                        <i class="fas fa-battery-half"></i>
                        <p>Trung bình</p>
                    </div>
                    <div class="spec-option gray">
                        <i class="fas fa-battery-three-quarters"></i>
                        <p>Cao</p>
                    </div>
                    <div class="spec-option gray">
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
                        <p>
                            Hệ thống camera sau được trang bị độ phân, trọng đó có camera chính 64 MP, camera góc siêu rộng 8 MP và camera macro 2 MP cùng camera trước 32 MP luôn sẵn sàng bắt trọn mọi cảm xúc trong khung hình, giúp người dùng thoải mái ghi lại những khoảnh khắc trong cuộc sống một cách ấn tượng nhất.<br><br>
                            iPhone 15 Pro Max mẫu điện thoại mới nhất của Apple cuối cùng cũng đã chính thức được ra mắt vào tháng 09/2023. Mẫu điện thoại này sở hữu một con chip với hiệu năng mạnh mẽ Apple A17 Pro, màn hình đẹp.
                            Hệ thống camera sau được trang bị độ phân, trọng đó có camera chính 64 MP, camera góc siêu rộng 8 MP và camera macro 2 MP cùng camera trước 32 MP luôn sẵn sàng bắt trọn mọi cảm xúc trong khung hình, giúp người dùng thoải mái ghi lại những khoảnh khắc trong cuộc sống một cách ấn tượng nhất.<br><br>
                            iPhone 15 Pro Max mẫu điện thoại mới nhất của Apple cuối cùng cũng đã chính thức được ra mắt vào tháng 09/2023. Mẫu điện thoại này sở hữu một con chip với hiệu năng mạnh mẽ Apple A17 Pro, màn hình đẹp.
                            Hệ thống camera sau được trang bị độ phân, trọng đó có camera chính 64 MP, camera góc siêu rộng 8 MP và camera macro 2 MP cùng camera trước 32 MP luôn sẵn sàng bắt trọn mọi cảm xúc trong khung hình, giúp người dùng thoải mái ghi lại những khoảnh khắc trong cuộc sống một cách ấn tượng nhất.<br><br>
                            Hệ thống camera sau được trang bị độ phân, trọng đó có camera chính 64 MP, camera góc siêu rộng 8 MP và camera macro 2 MP cùng camera trước 32 MP luôn sẵn sàng bắt trọn mọi cảm xúc trong khung hình, giúp người dùng thoải mái ghi lại những khoảnh khắc trong cuộc sống một cách ấn tượng nhất.<br><br>
                            iPhone 15 Pro Max mẫu điện thoại mới nhất của Apple cuối cùng cũng đã chính thức được ra mắt vào tháng 09/2023. Mẫu điện thoại này sở hữu một con chip với hiệu năng mạnh mẽ Apple A17 Pro, màn hình đẹp.
                            Hệ thống camera sau được trang bị độ phân, trọng đó có camera chính 64 MP, camera góc siêu rộng 8 MP và camera macro 2 MP cùng camera trước 32 MP luôn sẵn sàng bắt trọn mọi cảm xúc trong khung hình, giúp người dùng thoải mái ghi lại những khoảnh khắc trong cuộc sống một cách ấn tượng nhất.<br><br>
                            iPhone 15 Pro Max mẫu điện thoại mới nhất của Apple cuối cùng cũng đã chính thức được ra mắt vào tháng 09/2023. Mẫu điện thoại này sở hữu một con chip với hiệu năng mạnh mẽ Apple A17 Pro, màn hình đẹp.
                            Hệ thống camera sau được trang bị độ phân, trọng đó có camera chính 64 MP, camera góc siêu rộng 8 MP và camera macro 2 MP cùng camera trước 32 MP luôn sẵn sàng bắt trọn mọi cảm xúc trong khung hình, giúp người dùng thoải mái ghi lại những khoảnh khắc trong cuộc sống một cách ấn tượng nhất.<br><br>
                            Hệ thống camera sau được trang bị độ phân, trọng đó có camera chính 64 MP, camera góc siêu rộng 8 MP và camera macro 2 MP cùng camera trước 32 MP luôn sẵn sàng bắt trọn mọi cảm xúc trong khung hình, giúp người dùng thoải mái ghi lại những khoảnh khắc trong cuộc sống một cách ấn tượng nhất.<br><br>
                            iPhone 15 Pro Max mẫu điện thoại mới nhất của Apple cuối cùng cũng đã chính thức được ra mắt vào tháng 09/2023. Mẫu điện thoại này sở hữu một con chip với hiệu năng mạnh mẽ Apple A17 Pro, màn hình đẹp.
                            Hệ thống camera sau được trang bị độ phân, trọng đó có camera chính 64 MP, camera góc siêu rộng 8 MP và camera macro 2 MP cùng camera trước 32 MP luôn sẵn sàng bắt trọn mọi cảm xúc trong khung hình, giúp người dùng thoải mái ghi lại những khoảnh khắc trong cuộc sống một cách ấn tượng nhất.<br><br>
                            iPhone 15 Pro Max mẫu điện thoại mới nhất của Apple cuối cùng cũng đã chính thức được ra mắt vào tháng 09/2023. Mẫu điện thoại này sở hữu một con chip với hiệu năng mạnh mẽ Apple A17 Pro, màn hình đẹp.
                            Hệ thống camera sau được trang bị độ phân, trọng đó có camera chính 64 MP, camera góc siêu rộng 8 MP và camera macro 2 MP cùng camera trước 32 MP luôn sẵn sàng bắt trọn mọi cảm xúc trong khung hình, giúp người dùng thoải mái ghi lại những khoảnh khắc trong cuộc sống một cách ấn tượng nhất.<br><br>
                            Hệ thống camera sau được trang bị độ phân, trọng đó có camera chính 64 MP, camera góc siêu rộng 8 MP và camera macro 2 MP cùng camera trước 32 MP luôn sẵn sàng bắt trọn mọi cảm xúc trong khung hình, giúp người dùng thoải mái ghi lại những khoảnh khắc trong cuộc sống một cách ấn tượng nhất.<br><br>
                            iPhone 15 Pro Max mẫu điện thoại mới nhất của Apple cuối cùng cũng đã chính thức được ra mắt vào tháng 09/2023. Mẫu điện thoại này sở hữu một con chip với hiệu năng mạnh mẽ Apple A17 Pro, màn hình đẹp.
                            Hệ thống camera sau được trang bị độ phân, trọng đó có camera chính 64 MP, camera góc siêu rộng 8 MP và camera macro 2 MP cùng camera trước 32 MP luôn sẵn sàng bắt trọn mọi cảm xúc trong khung hình, giúp người dùng thoải mái ghi lại những khoảnh khắc trong cuộc sống một cách ấn tượng nhất.<br><br>
                            iPhone 15 Pro Max mẫu điện thoại mới nhất của Apple cuối cùng cũng đã chính thức được ra mắt vào tháng 09/2023. Mẫu điện thoại này sở hữu một con chip với hiệu năng mạnh mẽ Apple A17 Pro, màn hình đẹp.
                            Hệ thống camera sau được trang bị độ phân, trọng đó có camera chính 64 MP, camera góc siêu rộng 8 MP và camera macro 2 MP cùng camera trước 32 MP luôn sẵn sàng bắt trọn mọi cảm xúc trong khung hình, giúp người dùng thoải mái ghi lại những khoảnh khắc trong cuộc sống một cách ấn tượng nhất.<br><br>
                        </p>
                    </div>
                    <div class="read-more" id="readMoreBtn">
                        Đọc thêm <i class="fas fa-chevron-down"></i>
                    </div>
                    <script>
                        const readMoreBtn = document.getElementById('readMoreBtn');
                        const contentWrapper = document.getElementById('contentWrapper');

                        readMoreBtn.addEventListener('click', () => {
                            contentWrapper.classList.toggle('expanded');
                            readMoreBtn.classList.toggle('expanded');
                            if (!contentWrapper.classList.contains('expanded')) {
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

                    // Kiểm tra kết nối cơ sở dữ liệu
                    if (!$connect) {
                        $reviews_html = '<p>Lỗi kết nối cơ sở dữ liệu: ' . mysqli_connect_error() . '</p>';
                    } else {
                        // Truy vấn đánh giá
                        $product_id = 1; // Thay bằng ID sản phẩm thực tế (ví dụ: từ $_GET['product_id'])
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
                                    $reviews_html .= '<div class="user-review" style="display: flex; margin-top: 15px;">';
                                    $reviews_html .= '<i class="fas fa-user-circle" style="font-size: 32px; color: gray; margin-right: 10px;"></i>';
                                    $reviews_html .= '<div>';
                                    $reviews_html .= '<strong style="margin-right: 10px;">' . htmlspecialchars($review['username']) . '</strong>';
                                    $reviews_html .= '<span class="review-date">' . $review_date . '</span>';
                                    $reviews_html .= '<div class="user-stars" style="color: gold;">' . $stars . '</div>';
                                    $reviews_html .= '<p class="user-review-content">' . htmlspecialchars($review['content']) . '</p>';
                                    $reviews_html .= '</div>';
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

                        mysqli_close($connect);
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

                // Gửi yêu cầu AJAX
                fetch('submit_review.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        rating: selectedRating,
                        content: content,
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

                        // Username (giả sử đã có biến PHP gán xuống JS)
                        const username = "<?php echo isset($_SESSION['user']) ? $_SESSION['user'] : 'Người dùng'; ?>";

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
            }
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
        <h1 class="product-title">iPhone 15 128 GB</h1>
        <div class="product-meta">
            <span class="product-id">Mã sản phẩm: 30THANG5</span>
            <span class="divider">|</span>
            <span class="rating">⭐ 4.8</span>
            <span class="divider">|</span>
            <a href="#">Đã bán: 1.2K</a>
            <span class="divider">|</span>
            <a href="#">34 đánh giá</a>
        </div>

        <!-- Dung lượng -->
        <div class="product-options">
            <div class="option">
                <label><strong>Dung lượng</strong></label>
                <div class="storage-options">
                    <div class="storage">128 GB</div>
                    <div class="storage">256 GB</div>
                    <div class="storage">512 GB</div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const storageOptions = document.querySelectorAll('.storage');
                    const title = document.querySelector('.product-title');
                    const newPriceEl = document.querySelector('.new-price');
                    const oldPriceEl = document.querySelector('.old-price');
                    const discountEl = document.querySelector('.discount1');
                    const installmentEl = document.querySelector('.installment-price');

                    // Cấu hình từng mức giá theo dung lượng
                    const config = {
                        "128 GB": {
                            newPrice: 15990000,
                            installmentAdd: 0
                        },
                        "256 GB": {
                            newPrice: 22990000,
                            installmentAdd: 250000
                        },
                        "512 GB": {
                            newPrice: 29990000,
                            installmentAdd: 500000
                        }
                    };

                    storageOptions.forEach(option => {
                        option.addEventListener('click', () => {
                            storageOptions.forEach(o => o.classList.remove('active'));
                            option.classList.add('active');

                            const selected = option.textContent.trim();
                            const { newPrice, installmentAdd } = config[selected];
                            const oldPrice = newPrice + 7000000;
                            const discountPercent = Math.round(100 - (newPrice / oldPrice) * 100);
                            const installmentPrice = Math.round(newPrice / 12 + installmentAdd);

                            // Cập nhật nội dung
                            title.textContent = 'iPhone 15 ' + selected;
                            newPriceEl.textContent = newPrice.toLocaleString('vi-VN');
                            oldPriceEl.textContent = oldPrice.toLocaleString('vi-VN');
                            discountEl.textContent = discountPercent + '%';
                            installmentEl.textContent = installmentPrice.toLocaleString('vi-VN');
                        });
                    });
                });
            </script>

            <!-- Màu sắc -->
            <div class="option">
                <label><strong>Màu sắc</strong></label>
                <div class="color-options">
                    <div class="color">
                        <img src="../img/hong.webp" alt="Hồng">
                        <span>Hồng</span>
                    </div>
                    <div class="color">
                        <img src="../img/xanhduong.webp" alt="Xanh dương">
                        <span>Xanh dương</span>
                    </div>
                    <div class="color">
                        <img src="../img/đen.webp" alt="Đen">
                        <span>Đen</span>
                    </div>
                    <div class="color">
                        <img src="../img/xanhla.webp" alt="Xanh lá">
                        <span>Xanh lá</span>
                    </div>
                    <div class="color">
                        <img src="../img/vang.webp" alt="Vàng">
                        <span>Vàng</span>
                    </div>
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
                <!-- Mua ngay -->
                <div class="buy-now">
                    <p class="label1">Mua ngay với giá</p>
                    <div class="price-line">
                        <span class="new-price">15.990.000</span>
                        <span class="currency1">₫</span>
                    </div>
                    <div class="old-line">
                        <span class="old-price">22.990.000</span>
                        <span class="currency1">₫</span>
                        <span class="discount1">30%</span>
                    </div>
                </div>

                <!-- Hoặc -->
                <div class="middle">
                    <p class="or-text">Hoặc</p>
                </div>

                <!-- Trả góp -->
                <div class="installment">
                    <p class="label1">Trả góp</p>
                    <div class="price-line">
                        <span class="installment-price">1.344.500</span>
                        <span class="currency1">₫</span>
                        <span class="per-month">/tháng</span>
                    </div>
                    <button class="btn-installment mt-2">Trả góp ngay</button>
                </div>

            </div>

            <!-- Khuyến mãi nổi bật -->
            <div class="promotions">
                <h4>Khuyến mãi được hưởng</h4>
                <ul>
                    <a href="#">
                        <li>Giảm ngay 7.000.000₫ áp dụng đến 10/05</li>
                    </a>
                    <a href="#">
                        <li>Trả góp 0%</li>
                    </a>
                </ul>
            </div>

            <!-- Ưu đãi sinh viên -->
            <div class="student-offer">
                <span class="tag">🎓 Đặc quyền sinh viên</span>
                <div class="discount-box">
                    <div class="left">
                        <p>Giảm ngay</p>
                        <strong>200.000 <span>₫</span></strong>
                    </div>
                    <button class="btn-confirm">Xác thực ngay</button>
                </div>
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


        <div class="technical-specs">
            <h3>THÔNG SỐ KỸ THUẬT</h3>
            <ul>
                <li><span>Màn Hình:</span> AMOLED 6.43" Full HD+</li>
                <li><span>Camera Trước:</span> 12MP</li>
                <li><span>Camera Sau:</span> Chính 64 MP & Phụ 8 MP, 2 MP</li>
                <li><span>Ram:</span> 6 GB</li>
                <li><span>Bộ Nhớ Trong:</span> 128 GB, 256GB</li>
                <li><span>CPU:</span> Apple A18 Pro 6 nhân</li>
                <li><span>GPU (Chip):</span> Apple GPU 6 nhân</li>
                <li><span>Dung Lượng Pin:</span> 20 giờ</li>
                <li><span>Hệ Điều Hành:</span> iOS 18</li>
            </ul>
            <button class="detail-button1">Xem cấu hình chi tiết</button>
        </div>

        <div class="related-products">
            <h3>SẢN PHẨM CÙNG LOẠI</h3>

            <div class="product-item">
                <img src="../img/samsung-m53.jpg" alt="Galaxy Z Fold3 5G" class="product-thumb">
                <div class="product-info">
                    <p class="product-name">Galaxy Z Fold3 5G...</p>
                    <div class="rating">★★★★</div>
                    <div class="price-info">
                        <span class="price-sale">45.990.000₫</span>
                        <span class="price-original">49.990.000₫</span>
                        <span class="discount">-8%</span>
                    </div>
                </div>
            </div>

            <div class="product-item">
                <img src="../img/samsung-a73.jpg" alt="Galaxy Z Fold3 5G" class="product-thumb">
                <div class="product-info">
                    <p class="product-name">Samsung Galaxy M53...</p>
                    <div class="rating">★★★★★</div>
                    <div class="price-info">
                        <span class="price-sale">32.990.000₫</span>
                        <span class="price-original">39.990.000₫</span>
                        <span class="discount">-19%</span>
                    </div>
                </div>
            </div>

            <div class="product-item">
                <img src="../img/realme-9pro.jpg" alt="Galaxy Z Fold3 5G" class="product-thumb">
                <div class="product-info">
                    <p class="product-name">Oppo Reno 8Pro...</p>
                    <div class="rating">★★★★</div>
                    <div class="price-info">
                        <span class="price-sale">25.990.000₫</span>
                        <span class="price-original">29.990.000₫</span>
                        <span class="discount">-12%</span>
                    </div>
                </div>
            </div>

            <div class="product-item">
                <img src="../img/oppo-a77.jpg" alt="Galaxy Z Fold3 5G" class="product-thumb">
                <div class="product-info">
                    <p class="product-name">Xiaomi Redmi 14...</p>
                    <div class="rating">★★★</div>
                    <div class="price-info">
                        <span class="price-sale">5.990.000₫</span>
                        <span class="price-original">9.990.000₫</span>
                        <span class="discount">-21%</span>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>


</body>


  
<?php
    include_once 'footer.php';
?>