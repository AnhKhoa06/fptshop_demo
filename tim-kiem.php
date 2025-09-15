<?php
session_start();
include_once 'header.php';
?>
<?php
require_once 'admin/config/db.php';

function normalizeSearchQuery($query) {
    // Chuyển về chữ thường và loại bỏ khoảng trắng thừa
    $query = strtolower(trim($query));
    
    // Ánh xạ từ khóa viết tắt/văn bản viết liền sang tên sản phẩm
    $mappings = [
        'sss23' => 'Samsung S23',
        'vivox900' => 'Vivo X900',
        'samsungs23' => 'Samsung S23',
        'samsung s23' => 'Samsung S23',
        'iphonese2022' => 'iPhone SE 2022',
        'ssultra25' => 'Samsung Ultra 25',
        'pocox3' => 'Poco X3',
        'vivo x900' => 'Vivo X900', // Thêm biến thể với khoảng trắng
        'samsung galaxy s23' => 'Samsung Galaxy S23', // Hỗ trợ tên đầy đủ
        'vivox' => 'Vivo X', // Hỗ trợ tiền tố
        'ss' => 'Samsung', // Hỗ trợ viết tắt thương hiệu
        'iphone16pro' => 'iPhone 16 Pro',
        'iphone14promax' => 'iPhone 14 Promax',
        'iphone14pro' => 'iPhone 14 Pro',
        'ipad' => 'Ipad',
        'ss s23' => 'Samsung S23'
    ];
    
    // Nếu có ánh xạ, trả về tên chuẩn và thoát
    if (array_key_exists($query, $mappings)) {
        return $mappings[$query];
    }
    
    // Chuẩn hóa tên thương hiệu trước khi tách
    $brandReplacements = [
        'vivo' => 'Vivo',
        
        'samsung' => 'Samsung',
        'iphone' => 'iPhone',
        'poco' => 'Poco',
    ];
    $normalized = $query;
    foreach ($brandReplacements as $key => $value) {
        $normalized = str_ireplace($key, $value, $normalized);
    }
    
    // Chỉ tách chữ và số nếu không có khoảng trắng và không phải từ khóa phức hợp
    if (strpos($normalized, ' ') === false && !preg_match('/^[a-z]+$/', $normalized)) {
        $normalized = preg_replace('/(?<=[a-z])(?=[0-9])/', ' ', $normalized);
    }
    
    // Đưa chữ cái đầu mỗi từ thành hoa
    return ucwords(trim($normalized));
}

// Khai báo biến $search_query trước khi sử dụng
$search_query = isset($_GET['s']) ? normalizeSearchQuery(trim($_GET['s'])) : '';
$products = [];

// Xác định thứ tự sắp xếp (mặc định là "Liên quan")
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'relevant'; // Khai báo và khởi tạo $sort
$order_by = "prd_id DESC";
if (isset($_GET['sort'])) {
    switch ($_GET['sort']) {
        case 'price_high':
            $order_by = "price_discount DESC";
            break;
        case 'price_low':
            $order_by = "price_discount ASC";
            break;
        case 'view_count':
            $order_by = "views DESC";
            break;
        case 'hot_deals':
            $order_by = "p.prd_id ASC"; 
            break;
        case 'relevant':
        default:
            $order_by = "prd_id DESC";
            break;
    }
}

if (!empty($search_query)) {
    if ($sort === 'hot_deals') {
        $sql = "SELECT DISTINCT p.* FROM products p 
                INNER JOIN brands b ON p.brand_id = b.brand_id 
                INNER JOIN flash_sales fs ON p.prd_id = fs.product_id 
                WHERE p.prd_name LIKE ? 
                AND fs.start_time <= NOW() AND fs.end_time >= NOW() 
                ORDER BY $order_by LIMIT 20";
    } else {
        $sql = "SELECT * FROM products 
                INNER JOIN brands ON products.brand_id = brands.brand_id 
                WHERE prd_name LIKE ? 
                ORDER BY $order_by LIMIT 20";
    }
    $stmt = mysqli_prepare($connect, $sql);
    $search_term = "%" . $search_query . "%";
    mysqli_stmt_bind_param($stmt, "s", $search_term);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}
?>

<title>Kết quả tìm kiếm cho: "<?php echo isset($_GET['s']) ? htmlspecialchars(trim($_GET['s'])) : ''; ?>"</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="brand/css11.css">
<!-- Bo loc sp -->

<div class="breadcrumb">
    <i class="fas fa-home" style="color: red;"></i><a href="index.php">Trang chủ</a>
    <i class="fas fa-angle-right"></i>
    <span>Tìm Kiếm</span>
</div>

<h2 class="brand-title1">Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($search_query); ?>"</h2>
<div class="product-page">
    <div class="sidebar">
        <div class="filter-container">
            <div class="filter-group1 sort-only">
                <span class="filter-title">Sắp xếp theo</span>
                <div class="filters">
                    <button class="filter-btn" data-sort="relevant" <?php echo (!$sort || $sort === 'relevant') ? 'class="active"' : ''; ?>><i class="fas fa-sort"></i> Liên quan</button>
                    <button class="filter-btn" data-sort="price_high" <?php echo ($sort === 'price_high') ? 'class="active"' : ''; ?>><i class="fas fa-sort-amount-down-alt"></i> Giá Cao - Thấp</button>
                    <button class="filter-btn" data-sort="price_low" <?php echo ($sort === 'price_low') ? 'class="active"' : ''; ?>><i class="fas fa-sort-amount-up-alt"></i> Giá Thấp - Cao</button>
                    <button class="filter-btn" data-sort="view_count" <?php echo ($sort === 'view_count') ? 'class="active"' : ''; ?>><i class="fas fa-eye"></i> Xem nhiều</button>
                    <button class="filter-btn" data-sort="hot_deals" <?php echo ($sort === 'hot_deals') ? 'class="active"' : ''; ?>><i class="fas fa-percent"></i> Khuyến Mãi Hot</button>
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
            <?php if (empty($products)): ?>
                <p>Không tìm thấy sản phẩm nào phù hợp với "<?php echo htmlspecialchars($search_query); ?>"</p>
            <?php else: ?>
                <?php foreach ($products as $row): ?>
                    <?php
                    // Tính % giảm giá mặc định
                    $discount = 0;
                    if ($row['price'] > 0 && $row['price_discount'] > 0) {
                        $discount = 100 - round($row['price_discount'] / $row['price'] * 100);
                    }

                    // Lấy màu sắc và ROM mặc định từ product_colors
                    $product_id = $row['prd_id'];
                    $default_color_stmt = mysqli_prepare($connect, "SELECT color, rom FROM product_colors WHERE product_id = ? LIMIT 1");
                    mysqli_stmt_bind_param($default_color_stmt, "i", $product_id);
                    mysqli_stmt_execute($default_color_stmt);
                    $default_color_result = mysqli_stmt_get_result($default_color_stmt);
                    $default_color = mysqli_fetch_assoc($default_color_result) ?: ['color' => 'Mặc định', 'rom' => '128 GB'];
                    mysqli_stmt_close($default_color_stmt);

                    // Kiểm tra Flash Sale
                    $flash_sale_stmt = mysqli_prepare($connect, "SELECT discount, price_discount FROM flash_sales WHERE product_id = ? AND start_time <= NOW() AND end_time >= NOW() LIMIT 1");
                    mysqli_stmt_bind_param($flash_sale_stmt, "i", $product_id);
                    mysqli_stmt_execute($flash_sale_stmt);
                    $flash_sale_result = mysqli_stmt_get_result($flash_sale_stmt);
                    $flash_sale = mysqli_fetch_assoc($flash_sale_result);
                    mysqli_stmt_close($flash_sale_stmt);

                    // Tạo slug
                    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $row['prd_name']), '-'));

                    // Xác định đường dẫn
                    $product_url = "product/{$slug}.php";
                    if ($flash_sale) {
                        $product_url .= "?product_id={$product_id}&color=" . urlencode($default_color['color']) . "&rom=" . urlencode($default_color['rom']) . "&flash_sale=1";
                    }
                    ?>
                    <div class="product">
                        <?php if ($flash_sale): ?>
                        <span class="label exclusive">
                            <img src="img/samset.png" alt="⚡" class="lightning-icon">
                            Giá Siêu Rẻ
                        </span>
                        <?php endif; ?>

                        <a href="<?php echo $product_url; ?>">
                            <img src="admin/img/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['prd_name']); ?>">
                        </a>

                        <div class="product-badges">
                            <img src="img/baohanh.png" alt="18 tháng bảo hành" class="badge">
                            <img src="img/doimoi.png" alt="Trả góp" class="badge">
                        </div>

                        <a href="<?php echo $product_url; ?>">
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
                            <a href="<?php echo $product_url; ?>">
                                <button class="buy-now">Mua Ngay</button>
                            </a>  
                            <button class="favorite" data-product-id="<?php echo $row['prd_id']; ?>" data-color="<?php echo urlencode($default_color['color']); ?>" data-rom="<?php echo urlencode($default_color['rom']); ?>"><i class="fas fa-heart"></i></button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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

        document.querySelectorAll(".filter-btn:not(.dropdown-toggle)").forEach(btn => {
            btn.addEventListener("click", function () {
                if (this.dataset.sort) {
                    const sortValue = this.dataset.sort;
                    const url = new URL(window.location.href);
                    const currentSearch = url.searchParams.get('s') || '';
                    url.searchParams.set('s', currentSearch);
                    url.searchParams.set('sort', sortValue);
                    window.location.href = url.toString();
                } else {
                    this.classList.toggle("active");
                }
            });
        });

        document.querySelectorAll(".sort-only .filter-btn").forEach(btn => {
            btn.addEventListener("click", function (e) {
                e.stopPropagation();
                document.querySelectorAll(".sort-only .filter-btn").forEach(b => b.classList.remove("active"));
                this.classList.add("active");
            });
        });

        const currentSort = '<?php echo $sort; ?>';
        document.querySelectorAll(".sort-only .filter-btn").forEach(btn => {
            if (btn.dataset.sort === currentSort) {
                btn.classList.add("active");
            }
        });
    });

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


<?php
include_once 'footer.php';
?>