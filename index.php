
<?php
    require_once 'admin/config/db.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Phone | Điện Thoại, Laptop, Ipad chính hãng</title>
    <link rel="icon" type="image/png" href="img/logofpt7.png">
    <link rel="stylesheet" href="style25.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<div>

     <!-- Header -->
     <header class="header">
        <div class="container">
            <div class="logo1">
                <div class="logo">
                    <a href="#"> <img src="img/download.png" alt="Apple"></a>
                </div>
            </div>
            <!-- Danh mục -->
            <button class="menu-btn">
                <a href="#"><i class="fa-solid fa-bars"></i> Danh mục</a>
            </button>
            <!-- Thanh tìm kiếm -->
            <div class="search-box">
                <form id="search-form" action="tim-kiem.php" method="get" onsubmit="return validateSearch()">
                    <input type="text" id="search-input" name="s" placeholder="Nhập tên điện thoại, máy tính,... cần tìm">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                <div class="search-tags">
                    <a href="#" id="tag-iphone16" onclick="setSearchValue('iphone 16'); return false;">iphone 16</a>
                    <a href="#" id="tag-ipad" onclick="setSearchValue('laptop acer'); return false;">laptop acer</a>
                    <a href="#" id="tag-oppo" onclick="setSearchValue('iphone 12 pro'); return false;">iphone 12 pro</a>
                    <a href="#" id="tag-samsung" onclick="setSearchValue('vivo v25'); return false;">vivov25</a>
                </div>
            </div>
        
            <div class="nav-container">
                <nav class="nav-links">
                    <a href="index.php">
                        <i class="fas fa-home"></i> Trang chủ
                    </a>
                </nav>
            </div>
            
            
            <?php
            session_start();
            ?>
            <div class="user-actions">
                <div class="user-dropdown">
                    <a href="<?php echo isset($_SESSION['user']) ? 'account.php' : 'login1.html'; ?>">
                        <button class="user-icon">
                            <i class="fas fa-user"></i>
                        </button>
                    </a>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['user'])): ?>
                            <a href="" style="text-align: center; display: block; padding: 5px;">
                                <?php echo htmlspecialchars($_SESSION['user']); ?>
                            </a>
                        <?php else: ?>
                            <a href="login1.html" style="text-align: center; display: block; padding: 5px;">
                                Đăng ký / Đăng nhập
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
            

            // Kiểm tra nếu người dùng nhấn xác nhận đăng xuất
            if (isset($_GET['logout'])) {
                // Hủy phiên đăng nhập
                session_unset();
                session_destroy();
                // Chuyển hướng về trang index.php sau khi đăng xuất
                header("Location: index.php");
                exit();
            }
            ?>


                
            <?php
            
            // Tính số lượng sản phẩm riêng biệt trong giỏ hàng để hiển thị trong header
            $totalCartItems = 0;

            if (isset($_SESSION['user_id'])) {
                // Người dùng đã đăng nhập, lấy từ database
                $user_id = $_SESSION['user_id'];
                $cartCountQuery = "SELECT COUNT(*) AS total FROM cart WHERE user_id = ?";
                $stmt = mysqli_prepare($connect, $cartCountQuery);
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if ($row = mysqli_fetch_assoc($result)) {
                    $totalCartItems = $row['total'] ?: 0; 
                }
            } else {
                // Người dùng chưa đăng nhập, lấy từ session
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    $totalCartItems = count($_SESSION['cart']);
                }
            }

            // Lấy địa chỉ động từ cơ sở dữ liệu
            $delivery_address = 'Chưa có địa chỉ';
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $query = "SELECT address, name, phone FROM users WHERE id = ?";
                $stmt = mysqli_prepare($connect, $query);
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);

                if ($user && !empty($user['address'])) {
                    $address = json_decode($user['address'], true) ?? [];
                    $full_address = isset($address['street']) && isset($address['ward']['name']) && isset($address['district']['name']) && isset($address['province']['name'])
                        ? $address['street'] . ', ' . $address['ward']['name'] . ', ' . $address['district']['name'] . ', ' . $address['province']['name']
                        : 'Chưa có địa chỉ';
                    $delivery_address = $full_address;
                }
            }

            ?>
            <div class="cart-section">
                <!-- Giỏ hàng -->
                <a href="cart.php" class="cart">
                    <i class="fas fa-shopping-cart"></i> Giỏ hàng
                    <span class="cart-badge"><?php echo $totalCartItems; ?></span>
                </a>
            
                <!-- Giao đến -->
                <div class="delivery-location" id="delivery-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Giao đến:</span>
                    <?php if (isset($_SESSION['user_id']) && $delivery_address !== 'Chưa có địa chỉ'): ?>
                        <a href="account.php" class="delivery-address" onclick="updateAddress(event)"><?php echo htmlspecialchars($delivery_address); ?></a>
                    <?php else: ?>
                        <a href="account.php" class="delivery-address">Thêm địa chỉ ?</a>
                    <?php endif; ?>
                </div>
            </div>
                
            
        </div>
    </header>
    
    <div class="menu-overlay"></div>
        <!-- Bảng danh mục -->
        <div id="menu-container">
            <div class="menu-box">
                <!-- Submenu for Phone Category -->
                <div class="phone-mega-menu" id="phone-mega-menu">
                    <div class="phone-menu-container">
                        <div class="phone-menu-left">
                            <ul class="main-category-list">
                                <li>
                                    <a href="#"><i class="fa-solid fa-mobile-screen-button"></i> Điện thoại</a>
                                </li>
                                <li class="menu-item" id="laptop-menu-item">
                                    <a href="#"><i class="fa-solid fa-laptop"></i> Laptop</a>
                                    <div class="submenu" id="laptop-submenu">
                                        <div class="laptop-menu-right">
                                            <div class="laptop-brands-container">
                                                <h3>🔥Thương hiệu nổi bật</h3>
                                                <div class="popular-brands">
                                                    <a href="brand/macbook.php" class="brand-badge apple"><img src="img/macbook.png" alt="Macbook" class="brand-icon0"></a>
                                                    <a href="brand/dell.php" class="brand-badge dell"><img src="img/dell.png" alt="Dell" class="brand-icon2"></a>
                                                    <a href="brand/hp.php" class="brand-badge hp"><img src="img/hp.png" alt="HP" class="brand-icon1"></a>
                                                    <a href="brand/lenovo.php" class="brand-badge lenovo"><img src="img/lenovo.png" alt="Lenovo" class="brand-icon1"></a>
                                                </div>
                                    
                                                <div class="brand-categories">
                                                    <div class="brand-category1">
                                                        <h4>Apple (MacBook) <i class="fa-solid fa-angle-right"></i></h4>
                                                        <ul>
                                                            <li><a href="#">MacBook Air</a></li>
                                                            <li><a href="#">MacBook Pro 13 inch</a></li>
                                                            <li><a href="#">MacBook Pro 14 inch</a></li>
                                                            <li><a href="#">MacBook Pro 16 inch</a></li>
                                                        </ul>
                                                    </div>
                                    
                                                    <div class="brand-category1">
                                                        <h4>Dell <i class="fa-solid fa-angle-right"></i></h4>
                                                        <ul>
                                                            <li><a href="#">Dell XPS</a></li>
                                                            <li><a href="#">Dell Inspiron</a></li>
                                                            <li><a href="#">Dell Latitude</a></li>
                                                            <li><a href="#">Dell Vostro</a></li>
                                                            <li><a href="#">Dell G Series</a></li>
                                                        </ul>
                                                    </div>
                                    
                                                    <div class="brand-category1">
                                                        <h4>HP <i class="fa-solid fa-angle-right"></i></h4>
                                                        <ul>
                                                            <li><a href="#">HP Pavilion</a></li>
                                                            <li><a href="#">HP Envy</a></li>
                                                            <li><a href="#">HP Spectre</a></li>
                                                            <li><a href="#">HP Omen</a></li>
                                                        </ul>
                                                    </div>
                                    
                                                    <div class="k9">
                                                        <div class="brand-category1">
                                                            <h4>Lenovo <i class="fa-solid fa-angle-right"></i></h4>
                                                            <ul>
                                                                <li><a href="#">Lenovo ThinkPad</a></li>
                                                                <li><a href="#">Lenovo IdeaPad</a></li>
                                                                <li><a href="#">Lenovo Legion</a></li>
                                                                <li><a href="#">Lenovo Yoga</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="k10">
                                                        <div class="brand-category1">
                                                            <h4>Asus <i class="fa-solid fa-angle-right"></i></h4>
                                                            <ul>
                                                                <li><a href="#">Asus ZenBook</a></li>
                                                                <li><a href="#">Asus VivoBook</a></li>
                                                                <li><a href="#">Asus ROG</a></li>
                                                                <li><a href="#">Asus TUF Gaming</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="k11">
                                                        <div class="brand-category1">
                                                            <h4>Thương hiệu khác <i class="fa-solid fa-angle-right"></i></h4>
                                                            <ul>
                                                                <li><a href="#">Acer</a></li>
                                                                <li><a href="#">MSI</a></li>
                                                                <li><a href="#">Microsoft Surface</a></li>
                                                                <li><a href="#">LG Gram</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>    
                                               
                                                </div>
                                            </div>
                                    
                                            <div class="best-seller">
                                                <h3>⚡Bán chạy nhất</h3>
                                                <div class="best-seller-items1">
                                                    <div class="best-seller-item1">
                                                        <div class="best-seller-image">
                                                            <img src="img/mbairm2.jpg" alt="MacBook Air M2">
                                                        </div>
                                                        <a href="product/laptop-macbook-air-13.php">
                                                            <div class="best-seller-info">
                                                                <h4>MacBook Air 13</h4>
                                                                <div class="price-info">
                                                                    <span class="current-price">26.990.000 đ</span>
                                                                    <span class="discount">10%</span>
                                                                </div>
                                                                <div class="original-price">29.990.000 đ</div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    
                                                    <div class="best-seller-item12">
                                                        <div class="best-seller-image">
                                                            <img src="img/dell13.jpg" alt="Dell XPS 13">
                                                        </div>
                                                        <a href="product/laptop-acer-nitro-v-15.php">
                                                            <div class="best-seller-info">
                                                                <h4>Acer Nitro V 15 </h4>
                                                                <div class="price-info">
                                                                    <span class="current-price">32.490.000 đ</span>
                                                                    <span class="discount">15%</span>
                                                                </div>
                                                                <div class="original-price">38.290.000 đ</div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                    
                                                <div class="promo-banner1">
                                                    <a href="#"><img src="img/Menu_1_221dbfad01.webp" alt="Khuyến mãi laptop"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                
                                <li class="menu-item" id="ipad-menu-item">
                                    <a href="#"><i class="fa-solid fa-tablet-screen-button"></i> Máy tính bảng</a>
                                    <div class="submenu" id="ipad-submenu">
                                        <div class="ipad-menu-right">
                                            <div class="ipad-brands-container">
                                                <h3>🔥Thương hiệu nổi bật</h3>
                                                <div class="popular-brands">
                                                    <a href="brand/ipad.php" class="brand-badge apple"><img src="img/ipadlogo.png" alt="iPad" class="brand-icon3"></a>
                                                    <a href="brand/samsung-tablet.php" class="brand-badge samsung"><img src="img/ipadsslogo.png" alt="Samsung Tab" class="brand-icon5"></a>
                                                    <a href="brand/xiaomi-tablet.php" class="brand-badge xiaomi"><img src="img/xiaomi7.png" alt="Xiaomi Pad" class="brand-icon4"></a>
                                                    <a href="brand/lenovo-tablet.php" class="brand-badge lenovo"><img src="img/lenovo1.jpg" alt="Lenovo Tab" class="brand-icon6"></a>
                                                </div>
                                    
                                                <div class="brand-categories1">
                                                    <div class="brand-category1">
                                                        <h4>Apple (iPad) <i class="fa-solid fa-angle-right"></i></h4>
                                                        <ul>
                                                            <li><a href="#">iPad Gen 10</a></li>
                                                            <li><a href="#">iPad Gen 9</a></li>
                                                            <li><a href="#">iPad mini</a></li>
                                                            <li><a href="#">iPad Air</a></li>
                                                            <li><a href="#">iPad Pro M1 / M2</a></li>
                                                        </ul>
                                                    </div>
                                    
                                                    <div class="brand-category1">
                                                        <h4>Samsung <i class="fa-solid fa-angle-right"></i></h4>
                                                        <ul>
                                                            <li><a href="#">Galaxy Tab A Series</a></li>
                                                            <li><a href="#">Galaxy Tab S6 / S7 </a></li>
                                                            <li><a href="#">Galaxy Tab S9+ / FE</a></li>
                                                            <li><a href="#">Galaxy Tab A9+</a></li>
                                                            <li><a href="#">Galaxy Tab S9 Ultra</a></li>
                                                        </ul>
                                                    </div>
                                    
                                                    <div class="brand-category1">
                                                        <h4>Xiaomi <i class="fa-solid fa-angle-right"></i></h4>
                                                        <ul>
                                                            <li><a href="#">Xiaomi Pad 5</a></li>
                                                            <li><a href="#">Xiaomi Pad 6</a></li>
                                                            <li><a href="#">Xiaomi Pad 6 Pro</a></li>
                                                            <li><a href="#">Xiaomi Pad 9 Pro</a></li>
                                                        </ul>
                                                    </div>
                                    
                                                    <div class="k90">
                                                        <div class="brand-category1">
                                                            <h4>Lenovo <i class="fa-solid fa-angle-right"></i></h4>
                                                            <ul>
                                                                <li><a href="#">Lenovo Tab M10</a></li>
                                                                <li><a href="#">Lenovo Tab M11</a></li>
                                                                <li><a href="#">Lenovo Idea Tab Pro</a></li>
                                                                <li><a href="#">Lenovo Yoga Tab</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="k100">
                                                        <div class="brand-category1">
                                                            <h4>Huawei <i class="fa-solid fa-angle-right"></i></h4>
                                                            <ul>
                                                                <li><a href="#">Huawei MatePad 11.5</a></li>
                                                                <li><a href="#">Huawei MatePad Pro 13.2</a></li>
                                                                <li><a href="#">Huawei MatePad Air</a></li>
                                                                <li><a href="#">Huawei MatePad SE</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="k110">
                                                        <div class="brand-category1">
                                                            <h4>Thương hiệu khác <i class="fa-solid fa-angle-right"></i></h4>
                                                            <ul>
                                                                <li><a href="#">Realme Pad</a></li>
                                                                <li><a href="#">ZenPad</a></li>
                                                                <li><a href="#">Surface Pro</a></li>
                                                                <li><a href="#">Alldocube</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>    
                        
                                                </div>
                                            </div>
                                    
                                            <div class="best-seller1">
                                                <h3>⚡Bán chạy nhất</h3>
                                                <div class="best-seller-items1">
                                                    <div class="best-seller-item1">
                                                        <div class="best-seller-image">
                                                            <img src="img/ipadgen10.webp" alt="iPad Gen 10">
                                                        </div>
                                                        <a href="product/ipad-air-m3.php">
                                                            <div class="best-seller-info">
                                                                <h4>iPad Air M3 256GB</h4>
                                                                <div class="price-info">
                                                                    <span class="current-price">10.990.000 đ</span>
                                                                    <span class="discount">9%</span>
                                                                </div>
                                                                <div class="original-price">11.990.000 đ</div>
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <div class="best-seller-item12">
                                                        <div class="best-seller-image">
                                                            <img src="img/ipadair5.webp" alt="iPad Air 5">
                                                        </div>
                                                        <a href="product/ipad-mini-7.php">
                                                            <div class="best-seller-info">
                                                                <h4>iPad Mini 7 128GB</h4>
                                                                <div class="price-info">
                                                                    <span class="current-price">16.490.000 đ</span>
                                                                    <span class="discount">8%</span>
                                                                </div>
                                                                <div class="original-price">17.990.000 đ</div>
                                                            </div>

                                                    </div>
                                                </div>
                                    
                                                <div class="promo-banner1">
                                                    <a href="#"><img src="img/ipad.png" alt="Khuyến mãi laptop"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                
                                

                                <li class="separator"></li>
                                <li>
                                    <a href="apple.php"><i class="fa-brands fa-apple"></i> Chuyên trang Apple</a>
                                </li>
                                <li>
                                    <a href="samsung.php"><img src="img/samsung.png" alt="Samsung" class="brand-icon"> Chuyên trang Samsung</a>
                                </li>
                                <li>
                                    <a href="#"><img src="img/xiaomi7.png" alt="Xiaomi" class="brand-icon"> Chuyên trang Xiaomi</a>
                                </li>
                                <li class="separator"></li>
                            
                                <li class="separator"></li>
                            </ul>
                        </div>
                        <div class="phone-menu-right">
                            <div class="phone-brands-container">
                                <h3>🔥Thương hiệu nổi bật</h3>
                                <div class="popular-brands">
                                    <a href="brand/apple-iphone.php" class="brand-badge apple"><i class="fa-brands fa-apple"></i> iPhone</a>
                                    <a href="brand/samsung.php" class="brand-badge samsung"><img src="img/samsung.png" alt="Samsung" class="brand-icon"> Samsung</a>
                                    <a href="brand/oppo.php" class="brand-badge oppo"><img src="img/oppo.png" alt="OPPO" class="brand-icon"> OPPO</a>
                                    <a href="brand/xiaomi.php" class="brand-badge xiaomi"><img src="img/xiaomi7.png" alt="Xiaomi" class="brand-icon"> Xiaomi</a>
                                </div>

                                <div class="brand-categories">
                                    <div class="brand-category">
                                        <h4>Apple (iPhone) <i class="fa-solid fa-angle-right"></i></h4>
                                        <ul>
                                            <li><a href="iphone16-series.php">iPhone 16 Series</a></li>
                                            <li><a href="iphone15-series.php">iPhone 15 Series</a></li>
                                            <li><a href="#">iPhone 14 Series</a></li>
                                            <li><a href="#">iPhone 13 Series</a></li>
                                            <li><a href="#">iPhone 11 Series</a></li>
                                        </ul>
                                    </div>
                                    
                                    <div class="brand-category">
                                        <h4>Samsung <i class="fa-solid fa-angle-right"></i></h4>
                                        <ul>
                                            <li><a href="#">Galaxy AI</a></li>
                                            <li><a href="#">Galaxy S Series</a></li>
                                            <li><a href="#">Galaxy Z Series</a></li>
                                            <li><a href="#">Galaxy A Series</a></li>
                                            <li><a href="#">Galaxy M Series</a></li>
                                        </ul>
                                    </div>

                                    <div class="brand-category">
                                        <h4>Xiaomi <i class="fa-solid fa-angle-right"></i></h4>
                                        <ul>
                                            <li><a href="#">Poco Series</a></li>
                                            <li><a href="#">Xiaomi Series</a></li>
                                            <li><a href="#">Redmi Note Series</a></li>
                                            <li><a href="#">Redmi Series</a></li>
                                        </ul>
                                    </div>

                                    <div class="brand-category">
                                        <h4>OPPO <i class="fa-solid fa-angle-right"></i></h4>
                                        <ul>
                                            <li><a href="#">OPPO Reno Series</a></li>
                                            <li><a href="#">OPPO A Series</a></li>
                                            <li><a href="#">OPPO Find Series</a></li>
                                        </ul>
                                    </div>

                                    <div class="brand-category">
                                        <h4>HONOR <i class="fa-solid fa-angle-right"></i></h4>
                                        <ul>
                                            <li><a href="#">HONOR Magic Series</a></li>
                                            <li><a href="#">HONOR X Series</a></li>
                                            <li><a href="#">HONOR Series</a></li>
                                        </ul>
                                    </div>

                                    <div class="brand-category">
                                        <h4>Thương hiệu khác <i class="fa-solid fa-angle-right"></i></h4>
                                        <ul>
                                            <li><a href="#">Tecno</a></li>
                                            <li><a href="#">Realme</a></li>
                                            <li><a href="#">Vivo</a></li>
                                            <li><a href="#">Inoi</a></li>
                                            <li><a href="#">Benco</a></li>
                                            <li><a href="#">TCL</a></li>
                                            <li><a href="#">Nubia - ZTE</a></li>
                                        </ul>
                                    </div>
                                    <div class="k1">
                                        <div class="brand-category">
                                            <h4>Phổ thông 4G <i class="fa-solid fa-angle-right"></i></h4>
                                            <ul>
                                                <li><a href="#">Nokia</a></li>
                                                <li><a href="#">Itel</a></li>
                                                <li><a href="#">Masstel</a></li>
                                                <li><a href="#">Mobell</a></li>
                                                <li><a href="#">Viettel</a></li>
                                            </ul>
                                        </div>

                                        
                                    </div>
                                   
                                </div>
                            </div>

                            <div class="best-seller">
                                <h3>⚡Bán chạy nhất</h3>
                                <div class="best-seller-items">
                                    <div class="best-seller-item">
                                        <div class="best-seller-image">
                                            <img src="img/anh1.png" alt="Samsung Galaxy Z Fold6 5G">
                                        </div>
                                        <a href="product/samsung-s23.php">
                                            <div class="best-seller-info">
                                                <h4>Samsung Galaxy Galaxy S23</h4>
                                                <div class="price-info">
                                                    <span class="current-price">36.690.000 đ</span>
                                                    <span class="discount">17%</span>
                                                </div>
                                                <div class="original-price">43.990.000 đ</div>
                                            </div>
                                        </a>
                                    </div>
                                    
                                    <div class="best-seller-item">
                                        <div class="best-seller-image">
                                            <img src="img/anh2.png" alt="Samsung Galaxy S24 FE 5G">
                                        </div>
                                        <a href="product/samsung-z-flip6-5g.php">
                                            <div class="best-seller-info">
                                                <h4>Samsung Galaxy Z Flip6 5G</h4>
                                                <div class="price-info">
                                                    <span class="current-price">13.490.000 đ</span>
                                                    <span class="discount">21%</span>
                                                </div>
                                                <div class="original-price">16.990.000 đ</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="promo-banner">
                                    <a href="https://fptshop.com.vn/dien-thoai?utm_source=masoffer&traffic_id=672a2df1da24c9000129ce28&gad_source=1&gclid=Cj0KCQjwv_m-BhC4ARIsAIqNeBtGt6VLhaspjEL_i-iAmEI0-bHBZ0M8dLxOtc-c0QvwMiZO-08_Tm0aAmu_EALw_wcB"><img src="img/anh3.png" alt="Điện thoại trong tay - Xem ngay điểm thưởng"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        <div class="container">
            <!-- Slider Banner -->
            <section class="slider">
                <?php
                $sql = "SELECT image FROM banners ORDER BY created_at ASC";
                $result = mysqli_query($connect, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $image_path = "admin/img/" . htmlspecialchars($row['image']);
                    echo "<div>";
                    echo "<img src='$image_path' alt='Banner' style='max-width: 100%; height: auto;'>";
                    echo "</div>";
                }
                ?>
            </section>
            <div class="custom-progress-bar">
                <div class="progress"></div>
            </div>
        </div>

        <div class="commitment">
            <a href="#"><i class="fas fa-check-circle"></i> <strong>Cam kết</strong></a> |
            <a href="#"><i class="fas fa-cog"></i> 100% hàng thật</a> |
            <a href="#"><i class="fas fa-truck"></i> Freeship mọi đơn</a> |
            <a href="#"><i class="fas fa-sync-alt"></i> Hoàn 200% nếu hàng giả</a> |
            <a href="#"><i class="fas fa-box"></i> 30 ngày đổi trả</a> |
            <a href="#"><i class="fas fa-shipping-fast"></i> Giao nhanh 2h</a> |
            <a href="#"><i class="fas fa-tags"></i> Giá siêu rẻ</a>
        </div>

        <div class="title-wrapper">
            <div class="dienthoai-container">
                <a href="#" class="dienthoai-title">Điện thoại</a>
            </div>
            <div class="phone-title">
                <h2>THƯƠNG HIỆU</h2>
            </div>
        </div>
     
        <?php
            // Kết nối cơ sở dữ liệu và lấy tất cả thương hiệu
            $sql_brand = "SELECT * FROM brands ORDER BY brand_id ASC LIMIT 15";
            $query_brand = mysqli_query($connect, $sql_brand);

            // Kiểm tra nếu có dữ liệu
            if(mysqli_num_rows($query_brand) > 0) {
        ?>
                <div class="phone-grid">
                    <?php while($row = mysqli_fetch_assoc($query_brand)) { 
                        // Tạo slug từ brand_name
                        $brand_slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $row['brand_name']), '-'));
                    ?>
                        <div class="phone-card">
                            <a href="brand/<?php echo $brand_slug; ?>.php">
                                <div class="phone-image">
                                    <img src="admin/img1/<?php echo htmlspecialchars($row['image1']); ?>" width="100%; alt="<?php echo htmlspecialchars($row['brand_name']); ?>">
                                </div>
                                <div class="brand-name"><?php echo htmlspecialchars($row['brand_name']); ?></div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
        <?php
            } else {
                // Thông báo khi không có thương hiệu
                echo '<p>Không có thương hiệu nào để hiển thị.</p>';
            }
        ?>

        <!-- Modal HTML -->
        <div id="cartModal" class="modal">
            <div><i class="fas fa-shopping-cart" style="color: #00c853; font-size: 40px;"></i></div>
            <p>Sản phẩm đã được thêm vào giỏ hàng</p>
            <button onclick="window.location.href='cart.php'">Xem giỏ hàng</button>
        </div>
        
        <!-- Danh sách sản phẩm -->
        <div class="tabs phone-tabs">
            <button class="tab-btn active" data-tab="new-products">
                <img src="img/logomoi.png" alt="New Icon"> Sản phẩm mới
            </button>
            <button class="tab-btn" data-tab="exclusive-products">
                <img src="img/logodocquyen.png" alt="Exclusive Icon"> Độc quyền
            </button>
        </div>

        <!-- Danh sách Sản phẩm Mới -->
        <section id="new-products" class="product-section active">
            <div class="product-list">
                <?php
                // Số sản phẩm trên mỗi trang
                $products_per_page = 15;
                // Trang hiện tại (mặc định là 1 nếu không có từ GET)
                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                // Tính OFFSET
                $offset = ($current_page - 1) * $products_per_page;

                // Lấy 15 brand_id đầu tiên
                $brand_query = mysqli_query($connect, "SELECT brand_id FROM brands ORDER BY brand_id ASC LIMIT 15");
                $brand_ids = [];
                while ($brand_row = mysqli_fetch_assoc($brand_query)) {
                    $brand_ids[] = $brand_row['brand_id'];
                }

                // Nếu không có brand_id nào, hiển thị thông báo
                if (empty($brand_ids)) {
                    echo '<p>Không có thương hiệu nào để hiển thị sản phẩm.</p>';
                } else {
                    // Tạo danh sách placeholder cho IN clause
                    $placeholders = implode(',', array_fill(0, count($brand_ids), '?'));

                    // Lấy tổng số sản phẩm thuộc 15 brand_id
                    $total_products_query = mysqli_prepare($connect, "SELECT COUNT(*) as total FROM products WHERE brand_id IN ($placeholders)");
                    mysqli_stmt_bind_param($total_products_query, str_repeat('i', count($brand_ids)), ...$brand_ids);
                    mysqli_stmt_execute($total_products_query);
                    $total_products_result = mysqli_stmt_get_result($total_products_query);
                    $total_products = mysqli_fetch_assoc($total_products_result)['total'];
                    mysqli_stmt_close($total_products_query);

                    // Tính tổng số trang
                    $total_pages = ceil($total_products / $products_per_page);

                    // Đảm bảo current_page không vượt quá total_pages
                    if ($current_page > $total_pages) {
                        $current_page = $total_pages;
                        $offset = ($current_page - 1) * $products_per_page;
                    }

                    // Lấy sản phẩm thuộc 15 brand_id
                    $sql = "SELECT * FROM products INNER JOIN brands ON products.brand_id = brands.brand_id WHERE products.brand_id IN ($placeholders) ORDER BY prd_id ASC LIMIT ? OFFSET ?";
                    $stmt = mysqli_prepare($connect, $sql);
                    $params = array_merge($brand_ids, [$products_per_page, $offset]);
                    $types = str_repeat('i', count($brand_ids)) . 'ii';
                    mysqli_stmt_bind_param($stmt, $types, ...$params);
                    mysqli_stmt_execute($stmt);
                    $query = mysqli_stmt_get_result($stmt);

                    while ($row = mysqli_fetch_assoc($query)) {
                        // Tính % giảm giá mặc định
                        $discount = 0;
                        if ($row['price'] > 0 && $row['price_discount'] > 0) {
                            $discount = 100 - round($row['price_discount'] / $row['price'] * 100);
                        }

                        // Lấy màu sắc và ROM mặc định từ product_colors cho sản phẩm này
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
                <?php 
                    } // Đóng vòng lặp while
                    mysqli_stmt_close($stmt);
                } // Đóng khối else của if (empty($brand_ids))
                ?>
            </div>



            <!-- Phân trang cho Sản phẩm Mới -->
            <div class="pagination">
                <div class="button1 prev <?php echo $current_page === 1 ? 'disabled' : ''; ?>"><i class="fas fa-chevron-left"></i> Prev</div>
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active_class = ($i === $current_page) ? 'active' : '';
                    echo "<div class='index $active_class' data-page='$i'>$i</div>";
                }
                ?>
                <div class="button1 next <?php echo $current_page === $total_pages ? 'disabled' : ''; ?>"> Next <i class="fas fa-chevron-right"></i></div>
            </div>
        </section>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const pagination = document.querySelector(".pagination");
                const prevBtn = pagination.querySelector(".prev");
                const nextBtn = pagination.querySelector(".next");
                const pageIndices = pagination.querySelectorAll(".index");

                let currentPage = <?= $current_page; ?>;
                const totalPages = <?= $total_pages; ?>;

                // Hàm chuyển trang với anchor hash
                function goToPage(page) {
                    if (page < 1 || page > totalPages || page === currentPage) return;
                    window.location.href = `?page=${page}#new-products`; // Thêm #new-products vào URL
                }

                // Xử lý click vào số trang
                pageIndices.forEach(index => {
                    index.addEventListener("click", () => {
                        const page = parseInt(index.getAttribute("data-page"));
                        goToPage(page);
                    });
                });

                // Xử lý nút Prev
                prevBtn.addEventListener("click", () => {
                    goToPage(currentPage - 1);
                });

                // Xử lý nút Next
                nextBtn.addEventListener("click", () => {
                    goToPage(currentPage + 1);
                });

                // Điều chỉnh vị trí cuộn nếu cần (nếu không muốn dùng anchor hash)
                window.addEventListener('load', function () {
                    if (window.location.hash === '#new-products') {
                        window.scrollTo({
                            top: 700, // Tùy chỉnh vị trí chính xác
                            behavior: 'smooth'
                        });
                    }
                });
            });
        </script>
        
        <!-- Danh sách Sản phẩm Độc quyền -->
        <section id="exclusive-products" class="product-section">
            <div class="product-list">
                <?php
                $sql = "SELECT * FROM products WHERE brand_id = 1 ORDER BY prd_id ASC LIMIT 15";

                $query = mysqli_query($connect, $sql);
                while($row = mysqli_fetch_assoc($query)) {
                    // Tính % giảm giá
                    $discount = 0;
                    if ($row['price'] > 0 && $row['price_discount'] > 0) {
                        $discount = 100 - round($row['price_discount'] / $row['price'] * 100);
                    }

                    // Lấy màu sắc và ROM mặc định từ product_colors cho sản phẩm này
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
                        <img src="admin/img/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['prd_name']); ?>">
                    </a>

                    <div class="product-badges">
                        <img src="img/docquyen1.png" alt="18 tháng bảo hành" class="badge">
                        <img src="img/tragop.png" alt="Trả góp" class="badge">
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
                        <span class="rating-score">4.9 |</span>
                    </div>

                    <div class="product-buttons">
                        <a href="product/<?php echo $slug; ?>.php">
                            <button class="buy-now">Mua Ngay</button>
                        </a>
                        <button class="favorite" data-product-id="<?php echo $row['prd_id']; ?>" data-color="<?php echo urlencode($default_color['color']); ?>" data-rom="<?php echo urlencode($default_color['rom']); ?>"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
                <?php } ?>
            </div>


        </section>

        <script>
            // Modal logic for both sections
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const modal = document.getElementById('cartModal');
                        

                    setTimeout(() => {
                        modal.classList.add('active');
                        overlay.classList.add('active');
                    }, 10);

                    setTimeout(() => {
                        modal.classList.remove('active');
                        overlay.classList.remove('active');
                    }, 2000); // Đã sửa thời gian từ 2000ms thành 4000ms như yêu cầu ban đầu
                });
            });
        </script>



        <?php
        require_once 'admin/config/db.php';
        date_default_timezone_set('Asia/Ho_Chi_Minh'); // Thiết lập múi giờ Việt Nam

        // Truy vấn Flash Sale còn hiệu lực
        $current_time = date('Y-m-d H:i:s', time());
        $query = "SELECT fs.*, p.prd_name, pc.price, pc.image 
                FROM flash_sales fs 
                LEFT JOIN products p ON fs.product_id = p.prd_id 
                LEFT JOIN product_colors pc ON fs.product_id = pc.product_id AND fs.color = pc.color 
                WHERE fs.start_time <= ? AND fs.end_time >= ? 
                ORDER BY fs.start_time ASC";
        $stmt = mysqli_prepare($connect, $query);
        if (!$stmt) {
            die("Lỗi chuẩn bị truy vấn");
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $current_time, $current_time);
            if (!mysqli_stmt_execute($stmt)) {
                die("Lỗi thực thi truy vấn");
            } else {
                $result = mysqli_stmt_get_result($stmt);
                $flash_sales = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }
        }
        mysqli_stmt_close($stmt);

        // Tính thời gian còn lại (dùng cho countdown timer)
        $remaining_time = 0;
        $has_flash_sale = !empty($flash_sales);
        if ($has_flash_sale) {
            $end_time = strtotime($flash_sales[0]['end_time']);
            $current_time = time();
            $remaining_time = max(0, $end_time - $current_time); // Số giây còn lại
        } else {
            $remaining_time = (1 * 3600); // Mặc định 01:00:00
        }
        $hours = floor($remaining_time / 3600);
        $minutes = floor(($remaining_time % 3600) / 60);
        $seconds = $remaining_time % 60;

        // Chia sản phẩm thành các nhóm
        $group_size = 6; // Số sản phẩm mỗi nhóm
        $groups = array_chunk($flash_sales, $group_size);
        $total_groups = count($groups); // Số nhóm thực tế
        ?>

        <div class="fs_wrapper">
            <div class="fs_main-container">
                <div class="fs_header">
                    <div class="fs_title-block">
                        <img src="img/flashsale.png" alt="⚡" class="lightning-icon1">
                        <?php if ($has_flash_sale): ?>
                            <div class="fs_timer">
                                <div class="fs_timer-digit" id="fs_hours"><?php echo str_pad($hours, 2, '0', STR_PAD_LEFT); ?></div>
                                <div class="fs_timer-colon">:</div>
                                <div class="fs_timer-digit" id="fs_minutes"><?php echo str_pad($minutes, 2, '0', STR_PAD_LEFT); ?></div>
                                <div class="fs_timer-colon">:</div>
                                <div class="fs_timer-digit" id="fs_seconds"><?php echo str_pad($seconds, 2, '0', STR_PAD_LEFT); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="fs_timer" style="color: #888;">
                                Không có sản phẩm Flash Sale nào đang diễn ra
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="fs_products-wrapper">
                    <div class="fs_products">
                        <?php if (!empty($groups)): ?>
                            <?php foreach ($groups as $index => $group): ?>
                                <div class="fs_products-group" id="group<?php echo $index + 1; ?>" style="<?php echo $index > 0 ? 'display: none;' : ''; ?>">
                                    <?php foreach ($group as $item): ?>
                                        <div class="fs_product-item">
                                            <div class="fs_discount-tag">-<?php echo isset($item['discount']) ? $item['discount'] : 0; ?>%</div>
                                            <div class="zoom">
                                                <a href="product/<?php echo htmlspecialchars(strtolower(str_replace(' ', '-', $item['prd_name']))); ?>.php?product_id=<?php echo $item['product_id']; ?>&color=<?php echo urlencode($item['color']); ?>">
                                                    <img src="admin/img/<?php echo htmlspecialchars($item['image'] ?? 'default_image.jpg'); ?>" class="fs_product-img" alt="<?php echo htmlspecialchars($item['prd_name']); ?>">
                                                </a>
                                            </div>
                                            <h3 class="fs_product-name">
                                                <a href="product/<?php echo htmlspecialchars(strtolower(str_replace(' ', '-', $item['prd_name']))); ?>.php?product_id=<?php echo $item['product_id']; ?>&color=<?php echo urlencode($item['color']); ?>">
                                                    <?php echo htmlspecialchars($item['prd_name']); ?>
                                                </a>
                                            </h3>
                                            <div class="fs_product-cost"><?php echo number_format(isset($item['price_discount']) ? $item['price_discount'] : 0, 0, ',', '.'); ?><sup class="fs_currency">đ</sup></div>
                                            <div class="fs_status-container">
                                                <div class="fs_status-new">
                                                    <?php if (isset($item['sold']) && $item['sold'] > 0): ?>
                                                        <span class="fs_status-text">Đã bán <?php echo $item['sold']; ?></span>
                                                        <div class="fs_sold-bar" style="width: <?php echo min(100, ($item['sold'] * 2.5)); ?>%;"></div>
                                                    <?php else: ?>
                                                        Vừa mở bán
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không có sản phẩm Flash Sale nào đang diễn ra.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="fs_nav-controls">
                    <div class="fs_nav-btn fs_prev">❮</div>
                    <div class="fs_nav-btn fs_next">❯</div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                console.log('Script loaded, initializing flash sale slider at', new Date().toLocaleString());

                // Countdown timer
                const hasFlashSale = <?php echo json_encode($has_flash_sale); ?>;
                const hoursElement = document.getElementById('fs_hours');
                const minutesElement = document.getElementById('fs_minutes');
                const secondsElement = document.getElementById('fs_seconds');

                let countdownInterval;

                function updateFlashSaleCountdown() {
                    let hours = parseInt(hoursElement.innerText);
                    let minutes = parseInt(minutesElement.innerText);
                    let seconds = parseInt(secondsElement.innerText);
                    
                    seconds--;
                    
                    if (seconds < 0) {
                        seconds = 59;
                        minutes--;
                        
                        if (minutes < 0) {
                            minutes = 59;
                            hours--;
                            
                            if (hours < 0) {
                                hours = 0;
                                minutes = 0;
                                seconds = 0;
                                clearInterval(countdownInterval);
                                window.location.reload(); // Tải lại trang khi hết thời gian
                            }
                        }
                    }
                    hoursElement.innerText = hours.toString().padStart(2, '0');
                    minutesElement.innerText = minutes.toString().padStart(2, '0');
                    secondsElement.innerText = seconds.toString().padStart(2, '0');
                }

                if (hasFlashSale) {
                    countdownInterval = setInterval(updateFlashSaleCountdown, 1000);
                }

                // Navigation controls
                const productsContainer = document.querySelector('.fs_products');
                const btnNext = document.querySelector('.fs_nav-btn.fs_next');
                const btnPrev = document.querySelector('.fs_nav-btn.fs_prev');

                if (!productsContainer || !btnNext || !btnPrev) {
                    console.error('Không tìm thấy các phần tử:', { productsContainer, btnNext, btnPrev });
                    return;
                }

                const totalGroups = <?php echo $total_groups; ?>; // Số nhóm động từ PHP
                let currentGroup = 1;

                const updateTransform = () => {
                    const translateX = -((currentGroup - 1) * 100); // Di chuyển theo phần trăm
                    productsContainer.style.transform = `translateX(${translateX}%)`;
                    console.log('Updated transform, currentGroup:', currentGroup, 'translateX:', translateX, 'at', new Date().toLocaleString());
                };

                const updateButtons = () => {
                    btnPrev.style.opacity = currentGroup === 1 ? "0.5" : "1";
                    btnPrev.style.pointerEvents = currentGroup === 1 ? "none" : "auto";
                    btnNext.style.opacity = currentGroup === totalGroups ? "0.5" : "1";
                    btnNext.style.pointerEvents = currentGroup === totalGroups ? "none" : "auto";
                    console.log('Buttons updated, currentGroup:', currentGroup);
                };

                btnNext.addEventListener('click', () => {
                    if (currentGroup < totalGroups) {
                        currentGroup++;
                        updateTransform();
                        updateButtons();
                    }
                });

                btnPrev.addEventListener('click', () => {
                    if (currentGroup > 1) {
                        currentGroup--;
                        updateTransform();
                        updateButtons();
                    }
                });

                // Ẩn nút điều hướng nếu chỉ có 1 nhóm
                if (totalGroups <= 1) {
                    btnNext.style.display = 'none';
                    btnPrev.style.display = 'none';
                } else {
                    updateButtons();
                }

                console.log('Initial button visibility:', {
                    prevVisible: btnPrev.offsetParent !== null,
                    nextVisible: btnNext.offsetParent !== null,
                    totalGroups: totalGroups
                });
            });
        </script>


        <div class="title-wrapper1">
            <div class="dienthoai-container1">
                <a href="#" class="dienthoai-title1">Laptop</a>
            </div>
            <div class="phone-title1">
                <h2>THƯƠNG HIỆU NỔI BẬT</h2>
            </div>
        </div>

        <?php
            // Kết nối cơ sở dữ liệu và lấy tất cả thương hiệu
            $sql_brand = "SELECT * FROM brands WHERE brand_id BETWEEN 16 AND 24 ORDER BY brand_id ASC";
            $query_brand = mysqli_query($connect, $sql_brand);

            // Kiểm tra nếu có dữ liệu
            if(mysqli_num_rows($query_brand) > 0) {
        ?>
                <div class="phone-grid">
                    <?php while($row = mysqli_fetch_assoc($query_brand)) { 
                        // Tạo slug từ brand_name
                        $brand_slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $row['brand_name']), '-'));
                    ?>
                        <div class="phone-card">
                            <a href="brand/<?php echo $brand_slug; ?>.php">
                                <div class="phone-image">
                                    <img src="admin/img1/<?php echo htmlspecialchars($row['image1']); ?>" width="100%; alt="<?php echo htmlspecialchars($row['brand_name']); ?>">
                                </div>
                                <div class="brand-name"><?php echo htmlspecialchars($row['brand_name']); ?></div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
        <?php
            } else {
                // Thông báo khi không có thương hiệu
                echo '<p>Không có thương hiệu nào để hiển thị.</p>';
            }
        ?>


        <!-- Danh sách Laptop -->
        <div class="tabs laptop-tabs" > <!-- Ẩn tab của Laptop để tránh xung đột -->
            <button class="tab-btn active" data-tab="laptops">
                <img src="img/logomoi.png" alt="New Icon"> Sản phẩm mới
            </button>
        </div>

        <!-- Danh sách Laptop -->
        <section id="laptops" class="product-section active">
            <div class="laptop-container">
                <button class="lap-nav-button prev"><i class="fas fa-chevron-left"></i></button>
                <div class="laptop-list">
                    <?php
                    // Số sản phẩm trên mỗi "màn hình"
                    $products_per_page = 5;
                    // Lấy tất cả sản phẩm (không dùng OFFSET vì trượt ngang)
                    $sql = "SELECT * FROM products INNER JOIN brands ON products.brand_id = brands.brand_id WHERE products.brand_id BETWEEN 16 AND 24 ORDER BY prd_id DESC";
                    $query = mysqli_query($connect, $sql);

                    while ($row = mysqli_fetch_assoc($query)) {
                        // Tính % giảm giá mặc định
                        $discount = 0;
                        if ($row['price'] > 0 && $row['price_discount'] > 0) {
                            $discount = 100 - round($row['price_discount'] / $row['price'] * 100);
                        }

                        // Lấy màu sắc và ROM mặc định từ product_colors cho sản phẩm này
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
                            <img src="img/baohanh3.png" alt="18 tháng bảo hành" class="badge">
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
                    <?php } ?>
                </div>
                <button class="lap-nav-button next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                console.log('Script loaded, initializing laptop slider at', new Date().toLocaleString());
                const laptopList = document.querySelector('.laptop-list');
                const prevButton = document.querySelector('.lap-nav-button.prev');
                const nextButton = document.querySelector('.lap-nav-button.next');

                if (!laptopList || !prevButton || !nextButton) {
                    console.error('Không tìm thấy các phần tử:', { laptopList, prevButton, nextButton });
                    return;
                }

                let currentPosition = 0;
                const itemWidth = 238; // Độ rộng của mỗi sản phẩm (tùy chỉnh theo CSS)
                const gap = 15; // Khoảng cách giữa các sản phẩm (tùy chỉnh theo CSS)
                const itemsPerScreen = 4; // Số sản phẩm hiển thị trên mỗi "màn hình"
                const slideAmount = itemsPerScreen * (itemWidth + gap); // Tổng khoảng cách trượt

                const updateTransform = () => {
                    laptopList.style.transform = `translateX(-${currentPosition}px)`;
                    console.log('Updated transform, currentPosition:', currentPosition, 'at', new Date().toLocaleString());
                };

                prevButton.addEventListener('click', () => {
                    console.log('Prev button clicked, currentPosition:', currentPosition);
                    if (currentPosition > 0) {
                        currentPosition = Math.max(0, currentPosition - slideAmount);
                        updateTransform();
                    }
                });

                nextButton.addEventListener('click', () => {
                    console.log('Next button clicked, currentPosition:', currentPosition);
                    const maxTranslate = laptopList.scrollWidth - laptopList.parentElement.clientWidth;
                    if (currentPosition < maxTranslate) {
                        currentPosition = Math.min(maxTranslate, currentPosition + slideAmount);
                        updateTransform();
                    }
                });

                // Kiểm tra nút hiển thị
                console.log('Prev button visible:', prevButton.offsetParent !== null);
                console.log('Next button visible:', nextButton.offsetParent !== null);

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
            });
        </script>



        <div class="title-wrapper2">
            <div class="dienthoai-container2">
                <a href="#" class="dienthoai-title2">Máy Tính Bảng</a>
            </div>
            <div class="phone-title2">
                <h2>THƯƠNG HIỆU NỔI BẬT</h2>
            </div>
        </div>

        <?php
        // Kết nối cơ sở dữ liệu và lấy các thương hiệu có brand_id >= 25
        $sql_brand = "SELECT * FROM brands WHERE brand_id >= 25 ORDER BY brand_id ASC";
        $query_brand = mysqli_query($connect, $sql_brand);

        // Kiểm tra nếu có dữ liệu
        if(mysqli_num_rows($query_brand) > 0) {
        ?>
            <div class="phone-grid">
                <?php while($row = mysqli_fetch_assoc($query_brand)) { 
                    // Tạo slug từ brand_name
                    $brand_slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $row['brand_name']), '-'));
                ?>
                    <div class="phone-card">
                        <a href="brand/<?php echo $brand_slug; ?>.php">
                            <div class="phone-image">
                                <img src="admin/img1/<?php echo htmlspecialchars($row['image1']); ?>" width="100%" alt="<?php echo htmlspecialchars($row['brand_name']); ?>">
                            </div>
                            <div class="brand-name"><?php echo htmlspecialchars($row['brand_name']); ?></div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php
        } else {
            // Thông báo khi không có thương hiệu
            echo '<p>Không có thương hiệu nào để hiển thị.</p>';
        }
        ?>

        <!-- Danh sách Máy Tính Bảng -->
        <div class="tabs tablet-tabs"> <!-- Ẩn tab của Máy Tính Bảng để tránh xung đột -->
            <button class="tab-btn active" data-tab="tablets">
                <img src="img/logomoi.png" alt="New Icon"> Sản phẩm mới
            </button>
        </div>

        <!-- Danh sách Máy Tính Bảng -->
        <section id="tablets" class="product-section active">
            <div class="tablet-container">
                <button class="tab-nav-button prev"><i class="fas fa-chevron-left"></i></button>
                <div class="tablet-list">
                    <?php
                    // Số sản phẩm trên mỗi "màn hình"
                    $products_per_page = 5;
                    // Lấy tất cả sản phẩm có brand_id >= 25 (không dùng OFFSET vì trượt ngang)
                    $sql = "SELECT * FROM products INNER JOIN brands ON products.brand_id = brands.brand_id WHERE products.brand_id >= 25 ORDER BY prd_id DESC";
                    $query = mysqli_query($connect, $sql);

                    while ($row = mysqli_fetch_assoc($query)) {
                        // Tính % giảm giá mặc định
                        $discount = 0;
                        if ($row['price'] > 0 && $row['price_discount'] > 0) {
                            $discount = 100 - round($row['price_discount'] / $row['price'] * 100);
                        }

                        // Lấy màu sắc và ROM mặc định từ product_colors cho sản phẩm này
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
                            <img src="img/doimoi.png" alt="18 tháng bảo hành" class="badge">
                            <img src="img/baohanh1.png" alt="Trả góp" class="badge">
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
                    <?php } ?>
                </div>
                <button class="tab-nav-button next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </section>

        <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Script loaded, initializing tablet slider at', new Date().toLocaleString());
            const tabletList = document.querySelector('.tablet-list');
            const prevButton = document.querySelector('.tab-nav-button.prev');
            const nextButton = document.querySelector('.tab-nav-button.next');

            if (!tabletList || !prevButton || !nextButton) {
                console.error('Không tìm thấy các phần tử:', { tabletList, prevButton, nextButton });
                return;
            }

            let currentPosition = 0;
            const itemWidth = 238; // Độ rộng của mỗi sản phẩm (tùy chỉnh theo CSS)
            const gap = 15; // Khoảng cách giữa các sản phẩm (tùy chỉnh theo CSS)
            const itemsPerScreen = 4; // Số sản phẩm hiển thị trên mỗi "màn hình"
            const slideAmount = itemsPerScreen * (itemWidth + gap); // Tổng khoảng cách trượt

            const updateTransform = () => {
                tabletList.style.transform = `translateX(-${currentPosition}px)`;
                console.log('Updated transform, currentPosition:', currentPosition, 'at', new Date().toLocaleString());
            };

            prevButton.addEventListener('click', () => {
                console.log('Prev button clicked, currentPosition:', currentPosition);
                if (currentPosition > 0) {
                    currentPosition = Math.max(0, currentPosition - slideAmount);
                    updateTransform();
                }
            });

            nextButton.addEventListener('click', () => {
                console.log('Next button clicked, currentPosition:', currentPosition);
                const maxTranslate = tabletList.scrollWidth - tabletList.parentElement.clientWidth;
                if (currentPosition < maxTranslate) {
                    currentPosition = Math.min(maxTranslate, currentPosition + slideAmount);
                    updateTransform();
                }
            });

            // Kiểm tra nút hiển thị
            console.log('Prev button visible:', prevButton.offsetParent !== null);
            console.log('Next button visible:', nextButton.offsetParent !== null);

        });
        </script>


    
        <section>
            <div class="magazine-title">
                <h2>TIN TỨC & SỰ KIỆN</h2>
            </div>
            <div class="magazine-container">
                <button class="mag-nav-button prev"><i class="fas fa-chevron-left"></i></button>
                <div class="magazine">
                    <?php
                    $sql = "SELECT news_id, image, category, title FROM news ORDER BY created_at ASC LIMIT 8"; // Lấy 8 tin tức mới nhất
                    $query = mysqli_query($connect, $sql);
                    while ($row = mysqli_fetch_assoc($query)) {
                        $news_id = htmlspecialchars($row['news_id']);
                        $image = htmlspecialchars($row['image']);
                        $category = htmlspecialchars($row['category']);
                        $title = htmlspecialchars($row['title']);
                    ?>
                        <a href="news.php?id=<?php echo $news_id; ?>" class="magazine-item-link">
                            <div class="magazine-item">
                                <div class="danhmuc"><?php echo $category; ?></div>
                                <div class="tieude"><span><?php echo $title; ?></span></div>
                                <div class="icon"><i class="fas fa-laptop"></i></div>
                                <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>">
                            </div>
                        </a>
                    <?php } ?>
                </div>
                <button class="mag-nav-button next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                console.log('Script loaded, initializing magazine slider at', new Date().toLocaleString());
                const magazine = document.querySelector('.magazine');
                const prevButton = document.querySelector('.mag-nav-button.prev');
                const nextButton = document.querySelector('.mag-nav-button.next');

                if (!magazine || !prevButton || !nextButton) {
                    console.error('Không tìm thấy các phần tử:', { magazine, prevButton, nextButton });
                    return;
                }

                let currentPosition = 0;
                const itemWidth = 238;
                const gap = 15;
                const itemsPerScreen = 4;
                const slideAmount = itemsPerScreen * (itemWidth + gap); // 1012px
                const maxPosition = slideAmount;

                const updateTransform = () => {
                    magazine.style.transform = `translateX(-${currentPosition}px)`;
                    console.log('Updated transform, currentPosition:', currentPosition, 'at', new Date().toLocaleString());
                };

                prevButton.addEventListener('click', () => {
                    console.log('Prev button clicked, currentPosition:', currentPosition);
                    if (currentPosition > 0) {
                        currentPosition = Math.max(0, currentPosition - slideAmount);
                        updateTransform();
                    }
                });

                nextButton.addEventListener('click', () => {
                    console.log('Next button clicked, currentPosition:', currentPosition);
                    const maxTranslate = magazine.scrollWidth - magazine.parentElement.clientWidth;
                    if (currentPosition < maxTranslate) {
                        currentPosition = Math.min(maxTranslate, currentPosition + slideAmount);
                        updateTransform();
                    }
                });

                // Kiểm tra nút hiển thị
                console.log('Prev button visible:', prevButton.offsetParent !== null);
                console.log('Next button visible:', nextButton.offsetParent !== null);
            });
        </script>


        <div class="footer-top">
            <div class="footer-item">
                <div class="icon-wrapper">
                    <i class="fa-solid fa-circle-check"></i> <!-- Dấu check trong vòng tròn -->
                </div>
                <h3>Thương hiệu đảm bảo</h3>
                <p>Nhập khẩu, bảo hành chính hãng</p>
            </div>
            
            <div class="footer-item">
                <div class="icon-wrapper">
                    <i class="fa-solid fa-rotate-left"></i>
                </div>
                <h3>Đổi trả dễ dàng</h3>
                <p>Theo chính sách đổi trả tại Shop</p>
            </div>
            <div class="footer-item">
                <div class="icon-wrapper">
                    <i class="fa-solid fa-box"></i>
                </div>
                <h3>Sản phẩm chất lượng</h3>
                <p>Đảm bảo tương thích và độ bền cao</p>
            </div>
            <div class="footer-item">
                <div class="icon-wrapper">
                    <i class="fa-solid fa-truck"></i>
                </div>
                <h3>Giao hàng tận nơi</h3>
                <p>Tại 63 tỉnh thành</p>
            </div>
        </div>
    </div>
    <!-- Container bao quanh footer chính -->
    <div class="footer-container">
        <footer class="footer">
            <div class="footer-top1">
                <div class="footer-top1-left">
                    <h3>📍 Hệ thống cửa hàng trên toàn quốc</h3>
                    <p>Bao gồm Cửa hàng, Trung tâm Laptop, Studio, Garmin Brand Store</p>
                </div>
                <button class="store-btn">
                    <i class="fas fa-store"></i> Xem danh sách cửa hàng
                </button>
            </div>
            
            <hr>
            <div class="footer-bottom">
                <div class="footer-section">
                    <h4>🔗 Kết nối với chúng tôi</h4>
                    <div class="social-icons">
                        <div><i class="fab fa-facebook"></i> Facebook</div>
                        <div><i class="fab fa-youtube"></i> YouTube</div>
                        <div><i class="fab fa-tiktok"></i> TikTok</div>
                    </div>
                    <h4>📞 Tổng đài miễn phí</h4>
                    <p><i class="fas fa-phone"></i> <b>1800.6601</b> (Nhánh 1) - Tư vấn mua hàng</p>
                    <p><i class="fas fa-headset"></i> <b>1800.6616</b> (8h00 - 22h00) - Góp ý, khiếu nại</p>
                </div>
                <div class="footer-section">
                    <h4>Về chúng tôi</h4>
                    <ul>
                        <li>Giới thiệu công ty</li>
                        <li>Quy chế hoạt động</li>
                        <li>Dự án doanh nghiệp</li>
                        <li>Tin tức khuyến mãi</li>
                        <li>Giới thiệu máy đổi trả</li>
                        <li>Hướng dẫn mua hàng & thanh toán</li>
                        <li>Tra cứu hóa đơn điện tử</li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Chính sách</h4>
                    <ul>
                        <li>Chính sách bảo hành</li>
                        <li>Chính sách đổi trả</li>
                        <li>Chính sách bảo mật</li>
                        <li>Chính sách trả góp</li>
                        <li>Chính sách giao hàng & lắp đặt</li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>💳 Hỗ trợ thanh toán</h4>
                    <div class="payment-icons">
                        <div><i class="fab fa-cc-visa"></i> Visa</div>
                        <div><i class="fab fa-cc-mastercard"></i> MasterCard</div>
                        <div><i class="fab fa-cc-paypal"></i> PayPal</div>
                        <div><i class="fab fa-apple-pay"></i> Apple Pay</div>
                    </div>
                    <h4>✅ Chứng nhận</h4>
                    <div class="certification-icons">
                        <div><i class="fas fa-check-circle"></i> Bảo vệ DMCA</div>
                        <div><i class="fas fa-certificate"></i> Bộ Công Thương</div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Container bao quanh custom footer -->
    <div class="custom-footer-container">
        <footer class="custom-footer">
            <div class="search-suggestions">
                <strong>Mọi người cũng tìm kiếm:</strong>
                <span>iPhone 16 | iPhone 16 Pro Max | iPhone | Laptop | Samsung | iPhone 15 | Laptop gaming | Màn hình | Màn hình văn phòng | Màn hình gaming | PC | iPad | iPad Pro | iPad Air | Dreame L10 Ultra | Amazfit Bip 5 | S25 Ultra | Samsung S25 | Apple Watch | Macbook | Macbook Pro | Mac Mini M4 | Laptop Dell | Laptop Asus | Laptop AI | Laptop MSI | Laptop lenovo | Acer</span>
            </div>
            <div class="company-info">
                © 2025 - 2028 Nhóm 7: Web Bán Điện Thoại • Địa chỉ: 170 An Dương Vương, Phường Nguyễn Văn Cừ, TP Quy Nhơn, Bình Định • GPDKKD số 0311609355 do Sở KHĐT Bình Định cấp ngày 08/03/2025. • GP số 47/GP-TTĐT do Sở TTTT Bình Định cấp ngày 02/04/2025 • Điện thoại: <strong>(084) 3579 37048</strong> • Email: <a href="https://workspace.google.com/intl/vi/gmail/">anhkhoale2406@gmail.com</a> • Chịu trách nhiệm nội dung: Lê Anh Khoa.
            </div>
        </footer>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.slider').slick({
                dots: true,
                infinite: true,
                speed: 500,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                prevArrow: '<button type="button" class="slick-prev">❮</button>',
                nextArrow: '<button type="button" class="slick-next">❯</button>'
            });

            setTimeout(() => {
                console.log($('.slick-prev').length, $('.slick-next').length);
            }, 1000);
        });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const username = urlParams.get('username');
        const email = urlParams.get('email');
        const name = urlParams.get('name'); // Lấy name từ query string

        if (username && email) {
            localStorage.setItem('username', username);
            localStorage.setItem(username + '_email', email);
            if (name) {
                localStorage.setItem(username + '_name', name); // Lưu name vào localStorage
            }
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
    </script>

    <script src="slider1.js"></script>
    <script src="script6.js"></script> 
</body>
</html>
