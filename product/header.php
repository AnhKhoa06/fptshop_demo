<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css2.css">
    
<div>
     <!-- Header -->
     <header class="header">
        <div class="container">
            <div class="logo1">
                <div class="logo">
                    <a href="#"> <img src="../img/download.png" alt="Apple"></a>
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
                    <a href="../index.php">
                        <i class="fas fa-home"></i> Trang chủ
                    </a>
                </nav>
            </div>
            
            
            <div class="user-actions">
                <div class="user-dropdown">
                    <a href="<?php echo isset($_SESSION['user']) ? '../account.php' : '../login1.html'; ?>">
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
                                                    <a href="#" class="brand-badge apple"><img src="../img/macbook.png" alt="Macbook" class="brand-icon0"></a>
                                                    <a href="#" class="brand-badge dell"><img src="../img/dell.png" alt="Dell" class="brand-icon2"></a>
                                                    <a href="#" class="brand-badge hp"><img src="../img/hp.png" alt="HP" class="brand-icon1"></a>
                                                    <a href="#" class="brand-badge lenovo"><img src="../img/lenovo.png" alt="Lenovo" class="brand-icon1"></a>
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
                                                            <img src="../img/mbairm2.jpg" alt="MacBook Air M2">
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
                                                            <img src="../img/dell13.jpg" alt="Dell XPS 13">
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
                                                    <a href="#"><img src="../img/Menu_1_221dbfad01.webp" alt="Khuyến mãi laptop"></a>
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
                                                    <a href="#" class="brand-badge apple"><img src="../img/ipadlogo.png" alt="iPad" class="brand-icon3"></a>
                                                    <a href="#" class="brand-badge samsung"><img src="../img/ipadsslogo.png" alt="Samsung Tab" class="brand-icon5"></a>
                                                    <a href="#" class="brand-badge xiaomi"><img src="../img/xiaomi7.png" alt="Xiaomi Pad" class="brand-icon4"></a>
                                                    <a href="#" class="brand-badge lenovo"><img src="../img/lenovo1.jpg" alt="Lenovo Tab" class="brand-icon6"></a>
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
                                                            <img src="../img/ipadgen10.webp" alt="iPad Gen 10">
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
                                                            <img src="../img/ipadair5.webp" alt="iPad Air 5">
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
                                                    <a href="#"><img src="../img/ipad.png" alt="Khuyến mãi laptop"></a>
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
                                                        <img src="../img/sacduphong.webp" alt="Sạc dự phòng" class="brand-icon">
                                                        Sạc dự phòng
                                                    </a>
                                                    <a href="#" class="brand-badge samsung">
                                                        <img src="../img/sacduphong.webp" alt="Tai nghe không dây" class="brand-icon">
                                                        Tai nghe không dây
                                                    </a>
                                                    <a href="#" class="brand-badge oppo">
                                                        <img src="../img/banphimco.webp" alt="Bàn phím cơ" class="brand-icon">
                                                        Bàn phím cơ
                                                    </a>
                                                    <a href="#" class="brand-badge xiaomi">
                                                        <img src="../img/saccap.webp" alt="Sạc, Cáp" class="brand-icon">
                                                        Sạc, Cáp
                                                    </a>
                                                    <a href="#" class="brand-badge xiaomi">
                                                        <img src="../img/hubchuyendoi.webp" alt="Hup chuyển đổi" class="brand-icon">
                                                        Hub chuyển đổi
                                                    </a>
                                                    <a href="#" class="brand-badge xiaomi">
                                                        <img src="../img/airpodpro.webp" alt="Air Pods" class="brand-icon">
                                                        Air Pods
                                                    </a>
                                                    <a href="#" class="brand-badge xiaomi">
                                                        <img src="../img/tannhiet.webp" alt="Quạt tản nhiệt" class="brand-icon">
                                                        Quạt tản nhiệt
                                                    </a>
                                                    <a href="#" class="brand-badge xiaomi">
                                                        <img src="../img/oplung.webp" alt="Ốp lưng Magsafe" class="brand-icon">
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
                                                            <img src="../img/sac.webp" alt="Pin sạc dự phòng Magsafe Innostyle">
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
                                                            <img src="../img/airpod2.webp" alt="Tai nghe AirPods 3 2022 Hộp sạc dây">
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
                                                    <a href="#"><img src="../img/phukien.webp" alt="Khuyến mãi laptop"></a>
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
                                    <a href="#"><img src="../img/samsung.png" alt="Samsung" class="brand-icon"> Chuyên trang Samsung</a>
                                </li>
                                <li>
                                    <a href="#"><img src="../img/xiaomi7.png" alt="Xiaomi" class="brand-icon"> Chuyên trang Xiaomi</a>
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
                                    <a href="#" class="brand-badge samsung"><img src="../img/samsung.png" alt="Samsung" class="brand-icon"> Samsung</a>
                                    <a href="#" class="brand-badge oppo"><img src="../img/oppo.png" alt="OPPO" class="brand-icon"> OPPO</a>
                                    <a href="#" class="brand-badge xiaomi"><img src="../img/xiaomi7.png" alt="Xiaomi" class="brand-icon"> Xiaomi</a>
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
                                            <img src="../img/anh1.png" alt="Samsung Galaxy Z Fold6 5G">
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
                                            <img src="../img/anh2.png" alt="Samsung Galaxy S24 FE 5G">
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
                                    <a href="https://fptshop.com.vn/dien-thoai?utm_source=masoffer&traffic_id=672a2df1da24c9000129ce28&gad_source=1&gclid=Cj0KCQjwv_m-BhC4ARIsAIqNeBtGt6VLhaspjEL_i-iAmEI0-bHBZ0M8dLxOtc-c0QvwMiZO-08_Tm0aAmu_EALw_wcB"><img src="../img/anh3.png" alt="Điện thoại trong tay - Xem ngay điểm thưởng"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../script.js"></script> <!-- Thêm dòng này -->

    
    
</body>
</html>