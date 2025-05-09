<?php
session_start();
    include_once 'header.php'
?>
<?php
    require_once '../admin/config/db.php';

$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

if ($sort === 'high_to_low') {
    $sql = "SELECT * FROM products WHERE brand_id = 17 ORDER BY price_discount DESC LIMIT 20";
} elseif ($sort === 'low_to_high') {
    $sql = "SELECT * FROM products WHERE brand_id = 17 ORDER BY price_discount ASC LIMIT 20";
} else {
    $sql = "SELECT * FROM products WHERE brand_id = 17 ORDER BY prd_id ASC LIMIT 20";
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css1.css">
<!-- Bo loc sp -->
<div class="breadcrumb">
    <a href="../index.php">Trang chủ</a>
    <i class="fas fa-angle-right"></i>
    <span>Xiaomi</span>
</div>
<h2 class="brand-title">
    <img src="../img/xiaomi7.png" alt="Xiaomi Logo" class="brand-logo" style="width: 75px; height: 27px vertical-align: middle; margin-right: -15px;">
    Xiaomi
</h2>
<div class="product-page">
    <div class="sidebar">
        <div class="filter-container">
            <div class="filter-group1 sort-only">
                <span class="filter-title">Sắp xếp theo</span>
                <div class="filters">
                <button class="filter-btn" data-sort="high_to_low"><i class="fas fa-sort-amount-down-alt"></i> Giá Cao - Thấp</button>
                <button class="filter-btn" data-sort="low_to_high"><i class="fas fa-sort-amount-up-alt"></i> Giá Thấp - Cao</button>
                    <button class="filter-btn"><i class="fas fa-percent"></i> Khuyến Mãi Hot</button>
                    <button class="filter-btn view-more"><i class="fas fa-eye"></i> Xem nhiều</button>
                </div>
            </div>
            <div class="filter-group">
                <span class="filter-title">Chọn theo tiêu chí</span>
                <div class="filters">
                    <button class="filter-btn"><i class="fas fa-filter"></i> Bộ lọc</button>
                    <button class="filter-btn"><i class="fas fa-truck"></i> Sẵn hàng</button>
                    <button class="filter-btn"><i class="fas fa-money-bill"></i> Giá</button>
                    <div class="dropdown">
                        <button class="filter-btn dropdown-toggle">Kiểu màn hình <i class="fas fa-chevron-down"></i></button>
                        <div class="dropdown-menu">
                            <a href="#">OLED</a>
                            <a href="#">LCD</a>
                            <a href="#">AMOLED</a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="filter-btn dropdown-toggle">Tính năng camera <i class="fas fa-chevron-down"></i></button>
                        <div class="dropdown-menu">
                            <a href="#">Chụp đêm</a>
                            <a href="#">Xóa phông</a>
                            <a href="#">Zoom quang</a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="filter-btn dropdown-toggle">Bộ nhớ trong <i class="fas fa-chevron-down"></i></button>
                        <div class="dropdown-menu">
                            <a href="#">64GB</a>
                            <a href="#">128GB</a>
                            <a href="#">256GB</a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="filter-btn dropdown-toggle">Tính năng đặc biệt <i class="fas fa-chevron-down"></i></button>
                        <div class="dropdown-menu">
                            <a href="#">Chống nước</a>
                            <a href="#">Face ID</a>
                            <a href="#">5G</a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="filter-btn dropdown-toggle">Tần số quét <i class="fas fa-chevron-down"></i></button>
                        <div class="dropdown-menu">
                            <a href="#">60Hz</a>
                            <a href="#">90Hz</a>
                            <a href="#">120Hz</a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="filter-btn dropdown-toggle">Nhu cầu sử dụng <i class="fas fa-chevron-down"></i></button>
                        <div class="dropdown-menu">
                            <a href="#">Chơi game</a>
                            <a href="#">Chụp ảnh</a>
                            <a href="#">Làm việc</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="product-display">
        <div class="product-list">
            <?php
            $query = mysqli_query($connect, $sql);
            while($row = mysqli_fetch_assoc($query)) {
                // Tính % giảm giá
                $discount = 0;
                if ($row['price'] > 0 && $row['price_discount'] > 0) {
                    $discount = 100 - round($row['price_discount'] / $row['price'] * 100);
                }
            ?>
            <div class="product">
                <?php if($discount >= 14): ?>
                <span class="label exclusive">
                    <img src="../img/samset.png" alt="⚡" class="lightning-icon">
                    Giá Siêu Rẻ
                </span>
                <?php endif; ?>

                <?php
                $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $row['prd_name']), '-'));
                ?>
                <a href="../product/<?php echo $slug; ?>.php">
                    <img src="../admin/img/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['prd_name']); ?>">
                </a>

                <div class="product-badges">
                    <img src="../img/baohanh.png" alt="18 tháng bảo hành" class="badge">
                    <img src="../img/tragop.png" alt="Trả góp" class="badge">
                </div>

                <a href="../product/<?php echo $slug; ?>.php">
                    <h3><?php echo htmlspecialchars($row['prd_name']); ?></h3>
                </a>

                <p class="price-container">
                    <div class="price-wrapper">
                        <span class="price"><?php echo number_format($row['price_discount'], 0, ',', '.'); ?></span>
                        <span class="currency">đ</span>
                    </div>
                    <div class="discount-wrapper">
                        <?php if($discount > 0): ?>
                        <span class="discount-label">-<?php echo $discount; ?>%</span>
                        <span class="original-price"><?php echo number_format($row['price'], 0, ',', '.'); ?>₫</span>
                        <?php endif; ?>
                    </div>
                </p>

                <p><?php echo htmlspecialchars($row['ram']) . ' - ' . htmlspecialchars($row['rom']); ?></p>

                <div class="rating">
                    <i class="fas fa-star"></i>
                    <span class="rating-score">5.0 |</span>
                </div>

                <div class="product-buttons">
                    <a href="../product/<?php echo $slug; ?>.php">
                        <button class="buy-now">Mua Ngay</button>
                    </a>
                    <button class="add-to-cart"><i class="fas fa-shopping-cart"></i></button>
                    <button class="favorite"><i class="fas fa-heart"></i></button>
                </div>
                
            </div>
            <?php } ?>
        </div>
    </div>
</div>



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
        document.querySelectorAll('.favorite').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.classList.toggle('active');
            });
        });
    </script>
<?php

    include_once 'footer.php'
?>