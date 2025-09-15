<?php
session_start();
include_once 'header.php';
require_once 'admin/config/db.php';

$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

if ($sort === 'high_to_low') {
    $sql = "SELECT * FROM products WHERE brand_id IN (1, 16, 34) ORDER BY price_discount DESC LIMIT 20";
} elseif ($sort === 'low_to_high') {
    $sql = "SELECT * FROM products WHERE brand_id IN (1, 16, 34) ORDER BY price_discount ASC LIMIT 20";
} elseif ($sort === 'hot_deals') {
    $sql = "SELECT DISTINCT p.* FROM products p 
            INNER JOIN flash_sales fs ON p.prd_id = fs.product_id 
            WHERE p.brand_id IN (1, 16, 34) 
            AND fs.start_time <= NOW() AND fs.end_time >= NOW() 
            ORDER BY p.prd_id ASC LIMIT 20";
} elseif ($sort === 'view_count') {
    $sql = "SELECT * FROM products WHERE brand_id IN (1, 16, 34) ORDER BY views DESC LIMIT 20";
} else {
    $sql = "SELECT * FROM products WHERE brand_id IN (1, 16, 34) ORDER BY prd_id ASC LIMIT 20";
}
?>

<title>Apple (iPhone)</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="brand/css11.css">

<!-- Breadcrumb -->
<div class="breadcrumb">
    <i class="fas fa-home" style="color: red;"></i><a href="index.php">Trang chủ</a>
    <i class="fas fa-angle-right"></i>
    <span>Apple</span>
</div>

<h2 class="brand-title" style="width: 400px;">Chuyên Trang Apple</h2>
<div class="product-page">
    <div class="sidebar">
        <div class="filter-container">
            <div class="filter-group1 sort-only">
                <span class="filter-title">Sắp xếp theo</span>
                <div class="filters">
                    <button class="filter-btn" data-sort="high_to_low"><i class="fas fa-sort-amount-down-alt"></i> Giá Cao - Thấp</button>
                    <button class="filter-btn" data-sort="low_to_high"><i class="fas fa-sort-amount-up-alt"></i> Giá Thấp - Cao</button>
                    <button class="filter-btn" data-sort="hot_deals"><i class="fas fa-percent"></i> Khuyến Mãi Hot</button>
                    <button class="filter-btn" data-sort="view_count"><i class="fas fa-eye"></i> Xem nhiều</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HTML -->
    <div id="cartModal" class="modal">
        <div><i class="fas fa-shopping-cart" style="color: #00c853; font-size: 40px;"></i></div>
        <p>Sản phẩm đã được thêm vào giỏ hàng</p>
        <button onclick="window.location.href='cart.php'">Xem giỏ hàng</button>
    </div>

    <div class="product-display">
        <div class="product-list">
            <?php
            $query = mysqli_query($connect, $sql);
            while ($row = mysqli_fetch_assoc($query)) {
                // Tính % giảm giá
                $discount = 0;
                if ($row['price'] > 0 && $row['price_discount'] > 0) {
                    $discount = 100 - round($row['price_discount'] / $row['price'] * 100);
                }

                // Kiểm tra Flash Sale
                $flash_sale_stmt = mysqli_prepare($connect, "SELECT discount, price_discount FROM flash_sales WHERE product_id = ? AND start_time <= NOW() AND end_time >= NOW() LIMIT 1");
                mysqli_stmt_bind_param($flash_sale_stmt, "i", $row['prd_id']);
                mysqli_stmt_execute($flash_sale_stmt);
                $flash_sale_result = mysqli_stmt_get_result($flash_sale_stmt);
                $flash_sale = mysqli_fetch_assoc($flash_sale_result);
                mysqli_stmt_close($flash_sale_stmt);

                // Tạo slug
                $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $row['prd_name']), '-'));
            ?>
            <div class="product">
                <?php if ($flash_sale): ?>
                <span class="label exclusive">
                    <img src="img/samset.png" alt="⚡" class="lightning-icon">
                    Giá Siêu Rẻ
                </span>
                <?php endif; ?>

                <a href="product/<?php echo $slug; ?>.php">
                    <img src="admin/img/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['prd_name']); ?>">
                </a>

                <div class="product-badges">
                    <img src="img/docquyen1.png" alt="18 tháng bảo hành" class="badge">
                    <img src="img/tragop.png" alt="Trả góp" class="badge">
                </div>

                <a href="product/<?php echo $slug; ?>.php">
                    <h3><?php echo htmlspecialchars($row['prd_name']); ?></h3>
                </a>

                <p class="price-container">
                    <div class="price-wrapper">
                        <span class="price">
                            <?php 
                            if ($flash_sale && isset($flash_sale['price_discount'])) {
                                echo number_format($flash_sale['price_discount'], 0, ',', '.');
                            } else {
                                echo number_format($row['price_discount'], 0, ',', '.');
                            }
                            ?>
                        </span>
                        <span class="currency">đ</span>
                    </div>
                    <div class="discount-wrapper">
                        <?php 
                        $display_discount = 0;
                        if ($flash_sale && isset($flash_sale['discount'])) {
                            $display_discount = $flash_sale['discount'];
                        } elseif ($discount > 0) {
                            $display_discount = $discount;
                        }
                        if ($display_discount > 0): ?>
                            <span class="discount-label">-<?php echo $display_discount; ?>%</span>
                            <span class="original-price">
                                <?php 
                                if ($flash_sale && isset($flash_sale['price_discount']) && $row['price'] > 0) {
                                    echo number_format($row['price'], 0, ',', '.');
                                } elseif ($row['price'] > 0) {
                                    echo number_format($row['price'], 0, ',', '.');
                                }
                                ?>₫
                            </span>
                        <?php endif; ?>
                    </div>
                </p>

                <p><?php echo htmlspecialchars($row['ram']) . ' - ' . htmlspecialchars($row['rom']); ?></p>

                <div class="rating">
                    <i class="fas fa-star"></i>
                    <span class="rating-score">4.8 |</span>
                </div>

                <div class="product-buttons">
                    <a href="product/<?php echo $slug; ?>.php">
                        <button class="buy-now">Mua Ngay</button>
                    </a>
                    <button class="favorite" data-product-id="<?php echo $row['prd_id']; ?>" data-color="<?php echo urlencode($default_color['color'] ?? 'Mặc định'); ?>" data-rom="<?php echo urlencode($default_color['rom'] ?? '128 GB'); ?>"><i class="fas fa-heart"></i></button>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <script>
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const modal = document.getElementById('cartModal');
                const overlay = document.querySelector('.overlay'); // Ensure overlay exists in your CSS

                setTimeout(() => {
                    modal.classList.add('active');
                    if (overlay) overlay.classList.add('active');
                }, 10);

                setTimeout(() => {
                    modal.classList.remove('active');
                    if (overlay) overlay.classList.remove('active');
                }, 1000);
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".dropdown-toggle").forEach(button => {
                button.addEventListener("click", function (e) {
                    e.stopPropagation();
                    const menu = this.nextElementSibling;
                    const isVisible = menu.style.display === "block";
                    document.querySelectorAll(".dropdown-menu").forEach(m => m.style.display = "none");
                    document.querySelectorAll(".dropdown-toggle").forEach(btn => btn.classList.remove("active"));
                    if (!isVisible) {
                        menu.style.display = "block";
                        this.classList.add("active");
                    }
                });
            });

            document.addEventListener("click", function () {
                document.querySelectorAll(".dropdown-menu").forEach(menu => menu.style.display = "none");
                document.querySelectorAll(".dropdown-toggle").forEach(btn => btn.classList.remove("active"));
            });

            // Toggle active state for non-dropdown filter buttons
            document.querySelectorAll(".filter-btn:not(.dropdown-toggle)").forEach(btn => {
                btn.addEventListener("click", function () {
                    // Handle sorting buttons
                    if (this.dataset.sort) {
                        // Update URL with sort parameter
                        const sortValue = this.dataset.sort;
                        const url = new URL(window.location.href);
                        url.searchParams.set('sort', sortValue);
                        window.location.href = url.toString();
                    } else {
                        // Toggle active class for other filter buttons
                        this.classList.toggle("active");
                    }
                });
            });

            // Exclusive selection for sort buttons
            document.querySelectorAll(".sort-only .filter-btn").forEach(btn => {
                btn.addEventListener("click", function (e) {
                    e.stopPropagation();
                    document.querySelectorAll(".sort-only .filter-btn").forEach(b => b.classList.remove("active"));
                    this.classList.add("active");
                });
            });

            // Set active state for current sort button on page load
            const currentSort = '<?php echo $sort; ?>';
            document.querySelectorAll(".sort-only .filter-btn").forEach(btn => {
                if (btn.dataset.sort === currentSort) {
                    btn.classList.add("active");
                }
            });
        });
    </script>

    <script>
        // Khởi tạo sự kiện yêu thích
        document.querySelectorAll('.favorite').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const color = this.getAttribute('data-color');
                const rom = this.getAttribute('data-rom');
                const isActive = btn.classList.contains('active');
                const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>;

                if (!userId) {
                    alert('Bạn cần đăng nhập để thêm sản phẩm yêu thích!');
                    window.location.href = 'login1.html';
                    return;
                }

                if (isActive) {
                    // Xóa khỏi danh sách yêu thích
                    fetch('remove_favorite.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ user_id: userId, product_id: productId, color: decodeURIComponent(color), rom: decodeURIComponent(rom) })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            btn.classList.remove('active');
                            alert('Đã xóa sản phẩm khỏi danh sách yêu thích!');
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi xóa sản phẩm yêu thích.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Đã xảy ra lỗi khi xóa sản phẩm yêu thích.');
                    });
                } else {
                    // Thêm vào danh sách yêu thích
                    fetch('add_favorite.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ user_id: userId, product_id: productId, color: decodeURIComponent(color), rom: decodeURIComponent(rom) })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            btn.classList.add('active');
                            alert('Đã thêm sản phẩm vào danh sách yêu thích!');
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi thêm sản phẩm yêu thích.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Đã xảy ra lỗi khi thêm sản phẩm yêu thích.');
                    });
                }
            });

            // Kiểm tra xem sản phẩm đã có trong danh sách yêu thích chưa
            const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>;
            if (userId) {
                const productId = btn.getAttribute('data-product-id');
                const color = btn.getAttribute('data-color');
                const rom = btn.getAttribute('data-rom');
                fetch('check_favorite.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId, product_id: productId, color: decodeURIComponent(color), rom: decodeURIComponent(rom) })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.is_favorite) {
                        btn.classList.add('active');
                    }
                });
            }
        });
    </script>
</div>

<?php
include_once 'footer.php';
?>