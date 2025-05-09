
<?php
    require_once 'admin/config/db.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Phone | Click Là Mua</title>
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link rel="stylesheet" href="style.css">
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
                <input type="text" id="search-input" placeholder="Nhập tên điện thoại, máy tính,... cần tìm">
                <button type="submit"><i class="fas fa-search"></i></button>
                <div class="search-tags">
                    <a href="#" id="tag-iphone16">iphone 16</a>
                    <a href="#" id="tag-ipad">poco x3</a>
                    <a href="#" id="tag-oppo">iphone 12prm</a>
                    <a href="#" id="tag-samsung">ss s25ultra</a>
                
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
                            <a href="" style="text-align: center; display: block; padding: 10px;">
                                <?php echo htmlspecialchars($_SESSION['user']); ?>
                            </a>
                        <?php else: ?>
                            <a href="login1.html" style="text-align: center; display: block; padding: 10px;">
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


                
            
            <div class="cart-section">
                <!-- Giỏ hàng -->
                <a href="cart.php" class="cart">
                    <i class="fas fa-shopping-cart"></i> Giỏ hàng
                    <span class="cart-badge">0</span>
                </a>
            
                <!-- Giao đến -->
                <div class="delivery-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Giao đến:</span>
                    <a href="#" class="delivery-address">170 An Dương Vương, TP Quy Nhơn, Bình Định.</a>
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
                                                    <a href="#" class="brand-badge apple"><img src="img/macbook.png" alt="Macbook" class="brand-icon0"></a>
                                                    <a href="#" class="brand-badge dell"><img src="img/dell.png" alt="Dell" class="brand-icon2"></a>
                                                    <a href="#" class="brand-badge hp"><img src="img/hp.png" alt="HP" class="brand-icon1"></a>
                                                    <a href="#" class="brand-badge lenovo"><img src="img/lenovo.png" alt="Lenovo" class="brand-icon1"></a>
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
                                                    <div class="k12">
                                                        <div class="brand-category1 price-category">
                                                            <h4>Theo phân khúc giá</h4>
                                                            <ul>
                                                                <li><a href="#">Dưới 10 triệu</a></li>
                                                                <li><a href="#">Từ 10 - 15 triệu</a></li>
                                                                <li><a href="#">Từ 15 - 20 triệu</a></li>
                                                                <li><a href="#">Từ 20 - 30 triệu</a></li>
                                                                <li><a href="#">Trên 30 triệu</a></li>
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
                                                        <div class="best-seller-info">
                                                            <h4>MacBook Air M2</h4>
                                                            <div class="price-info">
                                                                <span class="current-price">26.990.000 đ</span>
                                                                <span class="discount">10%</span>
                                                            </div>
                                                            <div class="original-price">29.990.000 đ</div>
                                                        </div>
                                                    </div>
                                    
                                                    <div class="best-seller-item12">
                                                        <div class="best-seller-image">
                                                            <img src="img/dell13.jpg" alt="Dell XPS 13">
                                                        </div>
                                                        <div class="best-seller-info">
                                                            <h4>Dell XPS 13</h4>
                                                            <div class="price-info">
                                                                <span class="current-price">32.490.000 đ</span>
                                                                <span class="discount">15%</span>
                                                            </div>
                                                            <div class="original-price">38.290.000 đ</div>
                                                        </div>
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
                                                    <a href="#" class="brand-badge apple"><img src="img/ipadlogo.png" alt="iPad" class="brand-icon3"></a>
                                                    <a href="#" class="brand-badge samsung"><img src="img/ipadsslogo.png" alt="Samsung Tab" class="brand-icon5"></a>
                                                    <a href="#" class="brand-badge xiaomi"><img src="img/xiaomi7.png" alt="Xiaomi Pad" class="brand-icon4"></a>
                                                    <a href="#" class="brand-badge lenovo"><img src="img/lenovo1.jpg" alt="Lenovo Tab" class="brand-icon6"></a>
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
                                                    <div class="k120">
                                                        <div class="brand-category1 price-category">
                                                            <h4>Theo phân khúc giá</h4>
                                                            <ul>
                                                                <li><a href="#">Dưới 3 triệu</a></li>
                                                                <li><a href="#">3 - 6 triệu</a></li>
                                                                <li><a href="#">6 - 10 triệu</a></li>
                                                                <li><a href="#">10 - 15 triệu</a></li>
                                                                <li><a href="#">Trên 15 triệu</a></li>
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
                                                        <div class="best-seller-info">
                                                            <h4>iPad Gen 10 (2022)</h4>
                                                            <div class="price-info">
                                                                <span class="current-price">10.990.000 đ</span>
                                                                <span class="discount">9%</span>
                                                            </div>
                                                            <div class="original-price">11.990.000 đ</div>
                                                        </div>
                                                    </div>

                                                    <div class="best-seller-item12">
                                                        <div class="best-seller-image">
                                                            <img src="img/ipadair5.webp" alt="iPad Air 5">
                                                        </div>
                                                        <div class="best-seller-info">
                                                            <h4>iPad Air 5 M1</h4>
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

                                <li class="menu-item" id="accessories-menu-item">
                                    <a href="#"><i class="fa-solid fa-headphones"></i> Phụ kiện</a>
                                    <div class="submenu" id="accessories-submenu">
                                        <div class="ipad-menu-right">
                                            <div class="ipad-brands-container">
                                                <h3>🔥Gợi ý cho bạn</h3>
                                                <div class="popular-brands1">
                                                    <a href="#" class="brand-badge apple">
                                                        <img src="img/sacduphong.webp" alt="Sạc dự phòng" class="brand-icon">
                                                        Sạc dự phòng
                                                    </a>
                                                    <a href="#" class="brand-badge samsung">
                                                        <img src="img/tainghekday.webp" alt="Tai nghe không dây" class="brand-icon">
                                                        Tai nghe không dây
                                                    </a>
                                                    <a href="#" class="brand-badge oppo">
                                                        <img src="img/banphimco.webp" alt="Bàn phím cơ" class="brand-icon">
                                                        Bàn phím cơ
                                                    </a>
                                                    <a href="#" class="brand-badge xiaomi">
                                                        <img src="img/saccap.webp" alt="Sạc, Cáp" class="brand-icon">
                                                        Sạc, Cáp
                                                    </a>
                                                    <a href="#" class="brand-badge xiaomi">
                                                        <img src="img/hubchuyendoi.webp" alt="Hup chuyển đổi" class="brand-icon">
                                                        Hub chuyển đổi
                                                    </a>
                                                    <a href="#" class="brand-badge xiaomi">
                                                        <img src="img/airpodpro.webp" alt="Air Pods" class="brand-icon">
                                                        Air Pods
                                                    </a>
                                                    <a href="#" class="brand-badge xiaomi">
                                                        <img src="img/tannhiet.webp" alt="Quạt tản nhiệt" class="brand-icon">
                                                        Quạt tản nhiệt
                                                    </a>
                                                    <a href="#" class="brand-badge xiaomi">
                                                        <img src="img/oplung.webp" alt="Ốp lưng Magsafe" class="brand-icon">
                                                        Ốp lưng Magsafe
                                                    </a>
                                                </div>
                                                
                                    
                                                <div class="brand-categories12">
                                                    <div class="k20">
                                                        <div class="brand-category1">
                                                            <h4>Âm thanh <i class="fa-solid fa-angle-right"></i></h4>
                                                            <ul>
                                                                <li><a href="#">Tai nghe nhét tai</a></li>
                                                                <li><a href="#">Tai nghe chụp tai</a></li>
                                                                <li><a href="#">Tai nghe không dây</a></li>
                                                                <li><a href="#">Loa Bluetooth</a></li>
                                                                <li><a href="#">Loa vi tính</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="k21">
                                                        <div class="brand-category1">
                                                            <h4>Phụ kiện di động <i class="fa-solid fa-angle-right"></i></h4>
                                                            <ul>
                                                                <li><a href="#">Sạc, Cáp</a></li>
                                                                <li><a href="#">Sạc dự phòng</a></li>
                                                                <li><a href="#">Bao da, Ốp lưng</a></li>
                                                                <li><a href="#">Miếng dán màn hình</a></li>
                                                                <li><a href="#">Bút cảm ứng</a></li>
                                                                <li><a href="#">Thiết bị định vị</a></li>
                                                                <li><a href="#">Gậy chụp ảnh, Gimbal</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="k22">
                                                        <div class="brand-category1">
                                                            <h4>Phụ kiện Laptop <i class="fa-solid fa-angle-right"></i></h4>
                                                            <ul>
                                                                <li><a href="#">Chuột</a></li>
                                                                <li><a href="#">Bàn phím</a></li>
                                                                <li><a href="#">Balo, Túi xách</a></li>
                                                                <li><a href="#">Bút trình chiếu</a></li>
                                                                <li><a href="#">Webcam</a></li>
                                                                <li><a href="#">Giá đỡ</a></li>
                                                                <li><a href="#">Miếng lót chuột</a></li>
                                                                <li><a href="#">Hub chuyển đổi</a></li>
                                                                <li><a href="#">Phủ bàn phím</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <div class="k23">
                                                        <div class="brand-category1">
                                                            <h4>Gaming Gear <i class="fa-solid fa-angle-right"></i></h4>
                                                            <ul>
                                                                <li><a href="#">Thiết bị chơi game</a></li>
                                                                <li><a href="#">Tai nghe</a></li>
                                                                <li><a href="#">Loa</a></li>
                                                                <li><a href="#">Chuột, Bàn phím</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="k24">
                                                        <div class="brand-category1">
                                                            <h4>Thiết bị lưu trữ dữ liệu <i class="fa-solid fa-angle-right"></i></h4>
                                                            <ul>
                                                                <li><a href="#">USB</a></li>
                                                                <li><a href="#">Thẻ nhớ</a></li>
                                                                <li><a href="#">Ổ cứng di động</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="k25">
                                                        <div class="brand-category1">
                                                            <h4>Phụ kiện khác <i class="fa-solid fa-angle-right"></i></h4>
                                                            <ul>
                                                                <li><a href="#">TV Box</a></li>
                                                                <li><a href="#">Máy tính cầm tay</a></li>
                                                                <li><a href="#">Pin kiềm</a></li>
                                                                <li><a href="#">Mực in</a></li>
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
                                                            <img src="img/sac.webp" alt="Pin sạc dự phòng Magsafe Innostyle">
                                                        </div>
                                                        <div class="best-seller-info1">
                                                            <h4>Pin sạc dự phòng Magsafe Innostyle</h4>
                                                            <div class="price-info">
                                                                <span class="current-price">899.000 đ</span>
                                                                <span class="discount">30%</span>
                                                            </div>
                                                            <div class="original-price">1.290.000 đ</div>
                                                        </div>
                                                    </div>

                                                    <div class="best-seller-item2">
                                                        <div class="best-seller-image">
                                                            <img src="img/airpod2.webp" alt="Tai nghe AirPods 3 2022 Hộp sạc dây">
                                                        </div>
                                                        <div class="best-seller-info1">
                                                            <h4>Tai nghe AirPods 3 2022 Hộp sạc dây</h4>
                                                            <div class="price-info">
                                                                <span class="current-price">16.490.000 đ</span>
                                                                <span class="discount">8%</span>
                                                            </div>
                                                            <div class="original-price">17.990.000 đ</div>
                                                        </div>
                                                    </div>
                                                </div>
                                    
                                                <div class="promo-banner11">
                                                    <a href="#"><img src="img/phukien.webp" alt="Khuyến mãi laptop"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        

                                </li>

                                <li class="separator"></li>
                                <li>
                                    <a href="#"><i class="fa-brands fa-apple"></i> Chuyên trang Apple</a>
                                </li>
                                <li>
                                    <a href="#"><img src="img/samsung.png" alt="Samsung" class="brand-icon"> Chuyên trang Samsung</a>
                                </li>
                                <li>
                                    <a href="#"><img src="img/xiaomi7.png" alt="Xiaomi" class="brand-icon"> Chuyên trang Xiaomi</a>
                                </li>
                                <li class="separator"></li>
                                <li>
                                    <a href="#"><i class="fa-solid fa-headset"></i> Tai nghe, Sạc dự phòng, Sạc không dây</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa-solid fa-desktop"></i> Màn hình, Cường lực, Ốp lưng</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa-solid fa-fan"></i> Tản nhiệt, Combo dây sạc nhanh</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa-solid fa-keyboard"></i> Bàn phím, Con chuột, Pin</a>
                                </li>
                                <li class="separator"></li>
                                <li>
                                    <a href="#">Máy cũ</a>
                                </li>
                                <li>
                                    <a href="#">Thông tin hay</a>
                                </li>
                                <li>
                                    <a href="#">Sim thẻ - Thanh toán tiện ích</a>
                                </li>
                            </ul>
                        </div>
                        <div class="phone-menu-right">
                            <div class="phone-brands-container">
                                <h3>🔥Thương hiệu nổi bật</h3>
                                <div class="popular-brands">
                                    <a href="#" class="brand-badge apple"><i class="fa-brands fa-apple"></i> iPhone</a>
                                    <a href="#" class="brand-badge samsung"><img src="img/samsung.png" alt="Samsung" class="brand-icon"> Samsung</a>
                                    <a href="#" class="brand-badge oppo"><img src="img/oppo.png" alt="OPPO" class="brand-icon"> OPPO</a>
                                    <a href="#" class="brand-badge xiaomi"><img src="img/xiaomi7.png" alt="Xiaomi" class="brand-icon"> Xiaomi</a>
                                </div>

                                <div class="brand-categories">
                                    <div class="brand-category">
                                        <h4>Apple (iPhone) <i class="fa-solid fa-angle-right"></i></h4>
                                        <ul>
                                            <li><a href="#">iPhone 16 Series</a></li>
                                            <li><a href="#">iPhone 15 Series</a></li>
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
                                    <div class="k1">
                                        <div class="brand-category price-category">
                                            <h4>Theo phân khúc giá</h4>
                                            <ul>
                                                <li><a href="#">Dưới 2 triệu</a></li>
                                                <li><a href="#">Từ 2 - 4 triệu</a></li>
                                                <li><a href="#">Từ 4 - 7 triệu</a></li>
                                                <li><a href="#">Từ 7 - 13 triệu</a></li>
                                                <li><a href="#">Từ 13 - 21 triệu</a></li>
                                                <li><a href="#">Từ 21 - 32 triệu</a></li>
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
                                        <div class="best-seller-info">
                                            <h4>Samsung Galaxy Z Fold6 5G</h4>
                                            <div class="price-info">
                                                <span class="current-price">36.690.000 đ</span>
                                                <span class="discount">17%</span>
                                            </div>
                                            <div class="original-price">43.990.000 đ</div>
                                        </div>
                                    </div>
                                    
                                    <div class="best-seller-item">
                                        <div class="best-seller-image">
                                            <img src="img/anh2.png" alt="Samsung Galaxy S24 FE 5G">
                                        </div>
                                        <div class="best-seller-info">
                                            <h4>Samsung Galaxy S24 FE 5G</h4>
                                            <div class="price-info">
                                                <span class="current-price">13.490.000 đ</span>
                                                <span class="discount">21%</span>
                                            </div>
                                            <div class="original-price">16.990.000 đ</div>
                                        </div>
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
                <div><img src="img/banner9.webp" alt="Banner 1"></div>
                <div><img src="img/banner2.png" alt="Banner 2"></div>
                <div><img src="img/banner3.png" alt="Banner 3"></div>
                <div><img src="img/banner10.webp" alt="Banner 4"></div>
                <div><img src="img/banner4.png" alt="Banner 5"></div>
                <div><img src="img/banner5.png" alt="Banner 6"></div>
                <div><img src="img/banner.webp" alt="Banner 7"></div>
                <div><img src="img/banner6.png" alt="Banner 8"></div>
                <div><img src="img/banner7.png" alt="Banner 9"></div>
                <div><img src="img/banner8.png" alt="Banner 10"></div>
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
            $sql_brand = "SELECT * FROM brands";
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

        <!-- Danh sách sản phẩm -->
        <div class="tabs">
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
                $sql = "SELECT * FROM products INNER JOIN brands ON products.brand_id = brands.brand_id ORDER BY prd_id ASC LIMIT 15";

                $query = mysqli_query($connect, $sql);
                while($row = mysqli_fetch_assoc($query)) {
                    // Tính % giảm giá
                    $discount = 0;
                    if ($row['price'] > 0 && $row['price_discount'] > 0) {
                        $discount = 100 - round($row['price_discount'] / $row['price'] * 100);
                    }
                ?>
                <div class="product">
                    <?php if($discount >= 12): ?>
                    <span class="label exclusive">
                        <img src="img/samset.png" alt="⚡" class="lightning-icon">
                        Giá Siêu Rẻ
                    </span>
                    <?php endif; ?>

                    <?php
                    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $row['prd_name']), '-'));
                    ?>
                    <a href="product/<?php echo $slug; ?>.php">
                        <img src="admin/img/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['prd_name']); ?>">
                    </a>

                    <div class="product-badges">
                        <img src="img/baohanh.png" alt="18 tháng bảo hành" class="badge">
                        <img src="img/doimoi.png" alt="Trả góp" class="badge">
                    </div>

                    <a href="product/<?php echo $slug; ?>.php">
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
                        <span class="rating-score">4.8 |</span>
                    </div>

                    <div class="product-buttons">
                        <a href="product/<?php echo $slug; ?>.php">
                            <button class="buy-now">Mua Ngay</button>
                        </a>
                        <button class="add-to-cart"><i class="fas fa-shopping-cart"></i></button>
                        <button class="favorite"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </section>

        <!-- Danh sách Sản phẩm Độc quyền -->
        <section id="exclusive-products" class="product-section">
            <div class="product-list">
                <?php
                $sql = "SELECT * FROM products WHERE brand_id = 14 ORDER BY prd_id ASC LIMIT 15";

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
                        <img src="img/samset.png" alt="⚡" class="lightning-icon">
                        Giá Siêu Rẻ
                    </span>
                    <?php endif; ?>

                    <?php
                    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $row['prd_name']), '-'));
                    ?>
                    <a href="product/<?php echo $slug; ?>.php">
                        <img src="admin/img/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['prd_name']); ?>">
                    </a>

                    <div class="product-badges">
                        <img src="img/docquyen1.png" alt="18 tháng bảo hành" class="badge">
                        <img src="img/tragop.png" alt="Trả góp" class="badge">
                    </div>

                    <a href="/product/<?php echo $row['prd_id']; ?>.html">
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
                        <span class="rating-score">4.9 |</span>
                    </div>

                    <div class="product-buttons">
                        <button class="buy-now">Mua Ngay</button>
                        <button class="add-to-cart"><i class="fas fa-shopping-cart"></i></button>
                        <button class="favorite"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </section>

        <div class="fs_wrapper">
            <div class="fs_main-container">
                <div class="fs_header">
                    <div class="fs_title-block">
                        <img src="img/flashsale.png" alt="⚡" class="lightning-icon1">
                        
                        <div class="fs_timer">
                            <div class="fs_timer-digit" id="fs_hours">01</div>
                            <div class="fs_timer-colon">:</div>
                            <div class="fs_timer-digit" id="fs_minutes">29</div>
                            <div class="fs_timer-colon">:</div>
                            <div class="fs_timer-digit" id="fs_seconds">51</div>
                        </div>
                    </div>
                    <a href="#" class="fs_view-all">Xem tất cả</a>
                </div>
                <div class="fs_products-wrapper">
                    <div class="fs_products">
                        <!-- Product 1 -->
                        <div class="fs_products-group" id="group1">
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-38%</div>
                                <div class="zoom">
                                    <a href="#"><img src="img/iphone12promax.jpg" class="fs_product-img" alt="Same As Ever Book"></a>
                                </div>
                                <h3 class="fs_product-name">iPhone 12 PRM</h3>
                                <div class="fs_product-cost">15.990.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-sold" style="width: 60%; border-radius: 30px;">
                                        <span class="fs_icon-flame">🔥</span>
                                        Đã bán 6
                                    </div>
                                </div>
                            </div>
            
                            <!-- Product 2 -->
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-38%</div>
                                <div class="zoom">
                                    <img src="img/iphonexsmax.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">iPhone XS Max</h3>
                                <div class="fs_product-cost">9.599.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container1">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
            
                            <!-- Product 3 -->
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-47%</div>
                                <div class="zoom">
                                    <img src="img/iphone8plus.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">iPhone 8 Plus</h3>
                                <div class="fs_product-cost">6.000.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
            
                            <!-- Product 4 -->
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-40%</div>
                                <div class="zoom">
                                    <img src="img/vivov25.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">Vivo V25</h3>
                                <div class="fs_product-cost">8.490.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-sold" style="width: 70%; border-radius: 30px;">
                                        <span class="fs_icon-flame">🔥</span>
                                        Đã bán 9
                                    </div>
                                </div>
                            </div>
            
                            <!-- Product 5 -->
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-34%</div>
                                <div class="zoom">
                                    <img src="img/oppo-reno8.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">Oppo Reno8</h3>
                                <div class="fs_product-cost">7.990.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
            
                            <!-- Product 6 -->
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-36%</div>
                                <div class="zoom">
                                    <img src="img/iphone11promax.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">iPhone 11 PRM</h3>
                                <div class="fs_product-cost">8.990.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-sold" style="width: 90%; border-radius: 30px;">
                                        <span class="fs_icon-flame">🔥</span>
                                        Đã bán 12
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fs_products-group" id="group2" style="display: none;">                           
                            <!-- 6 sản phẩm mới thêm vào -->
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-28%</div>
                                <div class="zoom">
                                    <img src="img/oppo-findx5.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">Oppo FindX5</h3>
                                <div class="fs_product-cost">11.990.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
                            
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-22%</div>
                                <div class="zoom">
                                    <img src="img/samsung-s22.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">Samsung S22</h3>
                                <div class="fs_product-cost">16.990.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-sold" style="width: 60%; border-radius: 30px;">
                                        <span class="fs_icon-flame">🔥</span>
                                        Đã bán 8
                                    </div>
                                </div>
                            </div>
                            
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-30%</div>
                                <div class="zoom">
                                    <img src="img/samsung-a73.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">Samsung A73</h3>
                                <div class="fs_product-cost">9.499.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
                            
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-25%</div>
                                <div class="zoom">
                                    <img src="img/iphone13promax.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">iPhone 13 PRM</h3>
                                <div class="fs_product-cost">19.000.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-sold" style="width: 80%; border-radius: 30px;">
                                        <span class="fs_icon-flame">🔥</span>
                                        Đã bán 14
                                    </div>
                                </div>
                            </div>
                            
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-18%</div>
                                <div class="zoom">
                                    <img src="img/iphone15plus.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">iPhone 15 Mini</h3>
                                <div class="fs_product-cost">26.000.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
                            
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-32%</div>
                                <div class="zoom">
                                    <img src="img/realme-10.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">Realme 10</h3>
                                <div class="fs_product-cost">7.490.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
                        </div>
                        <div class="fs_products-group" id="group3" style="display: none;">                           
                            <!-- 6 sản phẩm mới thêm vào -->
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-28%</div>
                                <div class="zoom">
                                    <img src="img/iphone12.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">iPhone 12</h3>
                                <div class="fs_product-cost">9.999.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-18%</div>
                                <div class="zoom">
                                    <img src="img/xiaomi-mi11.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">Xiaomi-Mi11</h3>
                                <div class="fs_product-cost">5.990.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-sold" style="width: 30%; border-radius: 30px;">
                                        <span class="fs_icon-flame">🔥</span>
                                         2
                                    </div>
                                </div>
                            </div>
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-30%</div>
                                <div class="zoom">
                                    <img src="img/xiaomi-redmi9.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">Xiaomi Red9</h3>
                                <div class="fs_product-cost">7.999.900<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-22%</div>
                                <div class="zoom">
                                    <img src="img/iphone15promax.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">iPhone 15 PRM</h3>
                                <div class="fs_product-cost">25.500.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-34%</div>
                                <div class="zoom">
                                    <img src="img/vivox80.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">Vivo X80</h3>
                                <div class="fs_product-cost">8.990.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-sold" style="width: 86%; border-radius: 30px;">
                                        <span class="fs_icon-flame">🔥</span>
                                        Đã bán 15
                                    </div>
                                </div>
                            </div>
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-36%</div>
                                <div class="zoom">
                                    <img src="img/realme-9pro.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">Realme 9 Pro</h3>
                                <div class="fs_product-cost">6.190.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
                        </div>
                        <div class="fs_products-group" id="group4" style="display: none;">                           
                            <!-- 6 sản phẩm mới thêm vào -->
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-28%</div>
                                <div class="zoom">
                                    <img src="img/oppo-k10.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">Oppo K10</h3>
                                <div class="fs_product-cost">5.490.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-36%</div>
                                <div class="zoom">
                                    <img src="img/samsung-m53.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">Samsung M53</h3>
                                <div class="fs_product-cost">7.990.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-sold" style="width: 60%; border-radius: 30px;">
                                        <span class="fs_icon-flame">🔥</span>
                                        Đã bán 7
                                    </div>
                                </div>
                            </div>
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-34%</div>
                                <div class="zoom">
                                    <img src="img/sss23.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">Samsung S23</h3>
                                <div class="fs_product-cost">21.990.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-34%</div>
                                <div class="zoom">
                                    <img src="img/iphone6splus.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">iPhone 6S Plus</h3>
                                <div class="fs_product-cost">2.190.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-sold" style="width: 90%; border-radius: 30px;">
                                        <span class="fs_icon-flame">🔥</span>
                                        Đã bán 22
                                    </div>
                                </div>
                            </div>
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-34%</div>
                                <div class="zoom">
                                    <img src="img/iphone13mini.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">iPhone 13 Plus</h3>
                                <div class="fs_product-cost">11.999.900<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-sold" style="width: 70%; border-radius: 30px;">
                                        <span class="fs_icon-flame">🔥</span>
                                        Đã bán 14
                                    </div>
                                </div>
                            </div>
                            <div class="fs_product-item">
                                <div class="fs_discount-tag">-34%</div>
                                <div class="zoom">
                                    <img src="img/iphone15.jpg" class="fs_product-img" alt="Same As Ever Book">
                                </div>
                                <h3 class="fs_product-name">iPhone 15</h3>
                                <div class="fs_product-cost">23.990.000<sup class="fs_currency">đ</sup></div>
                                <div class="fs_status-container">
                                    <div class="fs_status-new">Vừa mở bán</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fs_nav-controls">
                    <div class="fs_nav-btn fs_prev">❮</div>
                    <div class="fs_nav-btn fs_next">❯</div>
                </div>
            </div>
        </div>

        <script>
            let currentGroup = 1;
            const totalGroups = 4; // Tổng số nhóm sản phẩm
            const btnNext = document.querySelector('.fs_next');
            const btnPrev = document.querySelector('.fs_prev');

            function updateButtons() {
                btnPrev.style.opacity = currentGroup === 1 ? "0.5" : "1";
                btnPrev.style.pointerEvents = currentGroup === 1 ? "none" : "auto";

                btnNext.style.opacity = currentGroup === totalGroups ? "0.5" : "1";
                btnNext.style.pointerEvents = currentGroup === totalGroups ? "none" : "auto";
            }
            btnNext.addEventListener('click', () => {
                if (currentGroup < totalGroups) {
                    document.getElementById('group' + currentGroup).style.display = 'none';
                    currentGroup++;
                    document.getElementById('group' + currentGroup).style.display = 'flex';
                    updateButtons();
                }
            });
            btnPrev.addEventListener('click', () => {
                if (currentGroup > 1) {
                    document.getElementById('group' + currentGroup).style.display = 'none';
                    currentGroup--;
                    document.getElementById('group' + currentGroup).style.display = 'flex';
                    updateButtons();
                }
            });

            updateButtons(); // Kiểm tra trạng thái nút ban đầu
        </script>
        

        <script>
            function updateFlashSaleCountdown() {
                const hoursElement = document.getElementById('fs_hours');
                const minutesElement = document.getElementById('fs_minutes');
                const secondsElement = document.getElementById('fs_seconds');
                
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
                        }
                    }
                }
                hoursElement.innerText = hours.toString().padStart(2, '0');
                minutesElement.innerText = minutes.toString().padStart(2, '0');
                secondsElement.innerText = seconds.toString().padStart(2, '0');
            }
            
            // Update countdown every second
            setInterval(updateFlashSaleCountdown, 1000);
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
    <!-- Phần dịch vụ footer -->
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
                    <i class="fab fa-facebook"></i> Facebook <br>
                    <i class="fab fa-youtube"></i> YouTube <br>
                    <i class="fab fa-tiktok"></i> TikTok <br>
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
                    <i class="fab fa-cc-visa"></i> Visa <br>
                    <i class="fab fa-cc-mastercard"></i> MasterCard <br>
                    <i class="fab fa-cc-paypal"></i> PayPal <br>
                    <i class="fab fa-apple-pay"></i> Apple Pay <br>
                </div>
                <h4>✅ Chứng nhận</h4>
                <div class="certification-icons">
                    <i class="fas fa-check-circle"></i> Bảo vệ DMCA <br>
                    <i class="fas fa-certificate"></i> Bộ Công Thương <br>
                </div>
            </div>
        </div>
    </footer>
    <footer class="custom-footer">
        <div class="search-suggestions">
            <strong>Mọi người cũng tìm kiếm:</strong>
            <span>iPhone 16 | iPhone 16 Pro Max | iPhone | Laptop | Samsung | iPhone 15 | Laptop gaming | Màn hình | Màn hình văn phòng | Màn hình gaming | PC | iPad | iPad Pro | iPad Air | Dreame L10 Ultra | Amazfit Bip 5 | S25 Ultra | Samsung S25 | Apple Watch | Macbook | Macbook Pro | Mac Mini M4 | Laptop Dell | Laptop Asus | Laptop AI | Laptop MSI | Laptop lenovo | Acer</span>
        </div>
        <div class="company-info">
            © 2025 - 2028 Nhóm 7: Web Bán Điện Thoại • Địa chỉ: 170 An Dương Vương, Phường Nguyễn Văn Cừ, TP Quy Nhơn, Bình Định • GPDKKD số 0311609355 do Sở KHĐT Bình Định cấp ngày 08/03/2025. • GP số 47/GP-TTĐT do Sở TTTT Bình Định cấp ngày 02/04/2025 • Điện thoại: <strong>(028) 3579 37048</strong> • Email: <a href="https://workspace.google.com/intl/vi/gmail/">anhkhoale2406@gmail.com</a> • Chịu trách nhiệm nội dung: Lê Anh Khoa.
        </div>
    </footer>

    <!-- JS -->
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
        document.querySelectorAll('.favorite').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.classList.toggle('active');
            });
        });
    </script>

    <script src="slider.js"></script>
    <script src="script.js"></script> <!-- Thêm dòng này -->

</body>
</html>
