<?php
    $id = $_GET['id'];

    $sql_brand = "SELECT * FROM brands";
    $query_brand = mysqli_query($connect, $sql_brand);

    $sql_up = "SELECT * FROM products WHERE prd_id = ?";
    $stmt_up = mysqli_prepare($connect, $sql_up);
    mysqli_stmt_bind_param($stmt_up, "i", $id);
    mysqli_stmt_execute($stmt_up);
    $result_up = mysqli_stmt_get_result($stmt_up);
    $row_up = mysqli_fetch_assoc($result_up);
    mysqli_stmt_close($stmt_up);

    // Lấy dữ liệu từ bảng product_colors và lưu theo màu sắc
    $sql_colors = "SELECT * FROM product_colors WHERE product_id = ?";
    $stmt_colors = mysqli_prepare($connect, $sql_colors);
    mysqli_stmt_bind_param($stmt_colors, "i", $id);
    mysqli_stmt_execute($stmt_colors);
    $query_colors = mysqli_stmt_get_result($stmt_colors);
    $color_data = [];
    while ($row = mysqli_fetch_assoc($query_colors)) {
        $color_data[$row['color']] = $row;
    }
    mysqli_stmt_close($stmt_colors);

    if (isset($_POST['sbm'])) {
        $prd_name = $_POST['prd_name'];
        $product_code = $_POST['product_code'];
        $front_camera = $_POST['front_camera'];
        $rear_camera = $_POST['rear_camera'];
        $cpu = $_POST['cpu'];
        $gpu = $_POST['gpu'];
        $gpu_rating = $_POST['gpu_rating'];
        $screen = $_POST['screen'];
        $screen_rating = $_POST['screen_rating'];
        $pin = $_POST['pin'];
        $pin_rating = $_POST['pin_rating'];
        $operating_system = $_POST['operating_system'];
        $review_content = $_POST['review_content'];

        if ($_FILES['image']['name'] == '') {
            $image = $row_up['image'];
        } else {
            $image = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];
            move_uploaded_file($image_tmp, 'img/' . $image);
        }

        $price = $_POST['price'];
        $price_discount = $_POST['price_discount'];
        $ram = $_POST['ram'];
        $rom = $_POST['rom'];
        $quantity = $_POST['quantity'];
        $brand_id = $_POST['brand_id'];
        $color = $_POST['color'][0]; // Lấy màu đầu tiên làm màu mặc định
        $specifications = $_POST['specifications'] ?? '[]'; // Lấy dữ liệu thông số kỹ thuật, mặc định là mảng rỗng nếu không có

        // Cập nhật bảng products
        $sql = "UPDATE products SET 
            prd_name = ?, 
            product_code = ?,  
            front_camera = ?,  
            rear_camera = ?,    
            cpu = ?,
            gpu = ?,
            gpu_rating = ?,
            screen = ?,
            screen_rating = ?,
            pin = ?,
            pin_rating = ?,
            image = ?, 
            price = ?, 
            price_discount = ?, 
            ram = ?, 
            rom = ?,
            operating_system = ?,
            review_content = ?,
            quantity = ?, 
            brand_id = ?,
            color = ?,
            specifications = ?
            WHERE prd_id = ?";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssssssiissssiissi", 
            $prd_name, $product_code, $front_camera, $rear_camera, $cpu, $gpu, $gpu_rating, 
            $screen, $screen_rating, $pin, $pin_rating, $image, $price, $price_discount, 
            $ram, $rom, $operating_system, $review_content, $quantity, $brand_id, $color, $specifications, $id
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Lấy danh sách màu sắc hiện tại trong database
        $existing_colors = array_keys($color_data);
        $submitted_colors = $_POST['color'];
        $old_colors = $_POST['old_color'] ?? []; // Lấy mảng color cũ từ form

        // Xóa các màu không còn trong form
        foreach ($existing_colors as $existing_color) {
            if (!in_array($existing_color, $submitted_colors)) {
                $sql_delete = "DELETE FROM product_colors WHERE product_id = ? AND color = ?";
                $stmt_delete = mysqli_prepare($connect, $sql_delete);
                mysqli_stmt_bind_param($stmt_delete, "is", $id, $existing_color);
                mysqli_stmt_execute($stmt_delete);
                mysqli_stmt_close($stmt_delete);
            }
        }

        // Cập nhật hoặc thêm mới vào bảng product_colors
        $colors = $_POST['color'];
        $roms = $_POST['rom_version'];
        $prices = $_POST['price_version'];
        $price_discounts = $_POST['price_discount_version'];
        $installments = $_POST['installment'];
        $color_images = $_FILES['color_images'];
        $images_details = $_FILES['images_detail'];

        foreach ($colors as $index => $color) {
            if (empty($color)) continue; // Bỏ qua nếu màu trống

            $rom_version = $roms[$index];
            $price_version = $prices[$index];
            $price_discount_version = $price_discounts[$index];
            $installment = $installments[$index];
            $old_color = isset($old_colors[$index]) ? $old_colors[$index] : '';

            // Xử lý ảnh màu
            if (!empty($color_images['name'][$index])) {
                $color_image = $color_images['name'][$index];
                $color_image_tmp = $color_images['tmp_name'][$index];
                $target_path = 'img/' . $color_image;
                move_uploaded_file($color_image_tmp, $target_path);
            } else {
                // Nếu không upload ảnh màu mới, giữ nguyên giá trị cũ từ color cũ
                $color_image = isset($color_data[$old_color]) ? $color_data[$old_color]['image'] : '';
            }

            // Xử lý ảnh chi tiết
            $images_detail = array();
            $has_new_images = !empty($images_details['name'][$index]) && array_filter($images_details['name'][$index], function($name) { return $name != ''; });
            if ($has_new_images) {
                foreach ($images_details['name'][$index] as $key => $name) {
                    if ($name != '') {
                        $tmp_name = $images_details['tmp_name'][$index][$key];
                        $target_path = 'img/' . $name;
                        move_uploaded_file($tmp_name, $target_path);
                        $images_detail[] = $name;
                    }
                }
                $images_detail_str = implode(',', $images_detail);
            } else {
                // Nếu không upload ảnh chi tiết mới, giữ nguyên giá trị cũ từ color cũ
                $images_detail_str = isset($color_data[$old_color]) ? $color_data[$old_color]['images_detail'] : '';
            }

            // Kiểm tra xem màu đã tồn tại trong database chưa
            if (isset($color_data[$old_color]) && $old_color !== '') {
                // Nếu màu cũ tồn tại, cập nhật bản ghi
                if ($old_color !== $color) {
                    // Nếu màu thay đổi, xóa bản ghi cũ và thêm mới
                    $sql_delete = "DELETE FROM product_colors WHERE product_id = ? AND color = ?";
                    $stmt_delete = mysqli_prepare($connect, $sql_delete);
                    mysqli_stmt_bind_param($stmt_delete, "is", $id, $old_color);
                    mysqli_stmt_execute($stmt_delete);
                    mysqli_stmt_close($stmt_delete);

                    // Thêm bản ghi mới với màu mới
                    $sql_insert_color = "INSERT INTO product_colors 
                        (product_id, color, rom, price, price_discount, installment, image, images_detail) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_insert = mysqli_prepare($connect, $sql_insert_color);
                    mysqli_stmt_bind_param($stmt_insert, "issiiiss", 
                        $id, $color, $rom_version, $price_version, $price_discount_version, 
                        $installment, $color_image, $images_detail_str
                    );
                    mysqli_stmt_execute($stmt_insert);
                    mysqli_stmt_close($stmt_insert);
                } else {
                    // Nếu màu không thay đổi, cập nhật bản ghi hiện có
                    $sql_update_color = "UPDATE product_colors SET 
                        rom = ?, 
                        price = ?, 
                        price_discount = ?, 
                        installment = ?, 
                        image = ?, 
                        images_detail = ? 
                        WHERE product_id = ? AND color = ?";
                    $stmt_update = mysqli_prepare($connect, $sql_update_color);
                    mysqli_stmt_bind_param($stmt_update, "siiissis", 
                        $rom_version, $price_version, $price_discount_version, $installment, 
                        $color_image, $images_detail_str, $id, $color
                    );
                    mysqli_stmt_execute($stmt_update);
                    mysqli_stmt_close($stmt_update);
                }
            } else {
                // Nếu không có màu cũ (thêm mới), thêm bản ghi mới
                $sql_insert_color = "INSERT INTO product_colors 
                    (product_id, color, rom, price, price_discount, installment, image, images_detail) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_insert = mysqli_prepare($connect, $sql_insert_color);
                mysqli_stmt_bind_param($stmt_insert, "issiiiss", 
                    $id, $color, $rom_version, $price_version, $price_discount_version, 
                    $installment, $color_image, $images_detail_str
                );
                mysqli_stmt_execute($stmt_insert);
                mysqli_stmt_close($stmt_insert);
            }
        }

        header('location: index.php?page_layout=danhsach');
    }
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2>Sửa sản phẩm</h2>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <!-- === THÔNG TIN SẢN PHẨM === -->
                <h4>Thông tin sản phẩm</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mã sản phẩm</label>
                            <input type="text" name="product_code" class="form-control" required value="<?= htmlspecialchars($row_up['product_code']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Tên sản phẩm</label>
                            <input type="text" name="prd_name" class="form-control" required value="<?= htmlspecialchars($row_up['prd_name']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Giá gốc (mặc định)</label>
                            <input type="number" name="price" class="form-control" required value="<?= $row_up['price']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Thương hiệu</label>
                            <select class="form-control" name="brand_id">
                                <?php 
                                mysqli_data_seek($query_brand, 0); // Reset con trỏ để lặp lại
                                while($row_brand = mysqli_fetch_assoc($query_brand)) {
                                    $selected = ($row_brand['brand_id'] == $row_up['brand_id']) ? 'selected' : '';
                                ?>
                                    <option value="<?= $row_brand['brand_id']; ?>" <?= $selected; ?>>
                                        <?= htmlspecialchars($row_brand['brand_name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Camera trước</label>
                            <input type="text" name="front_camera" class="form-control" required value="<?= htmlspecialchars($row_up['front_camera']); ?>">
                        </div>
                        <div class="form-group">
                            <label>CPU</label>
                            <input type="text" name="cpu" class="form-control" required value="<?= htmlspecialchars($row_up['cpu']); ?>">
                        </div>
                        <div class="form-group">
                            <label>RAM</label>
                            <input type="text" name="ram" class="form-control" required value="<?= htmlspecialchars($row_up['ram']); ?>">
                        </div>
                        <div class="form-group">
                            <label>DL Pin</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" name="pin" class="form-control" required placeholder="Nhập dung lượng pin (mAh)" value="<?= htmlspecialchars($row_up['pin']); ?>">
                                </div>
                                <div class="col-md-4">
                                    <select name="pin_rating" class="form-control" required>
                                        <option value="" disabled>Chọn mức</option>
                                        <option value="trung bình" <?= $row_up['pin_rating'] == 'trung bình' ? 'selected' : ''; ?>>Trung bình</option>
                                        <option value="cao" <?= $row_up['pin_rating'] == 'cao' ? 'selected' : ''; ?>>Cao</option>
                                        <option value="rất cao" <?= $row_up['pin_rating'] == 'rất cao' ? 'selected' : ''; ?>>Rất cao</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Bài viết đánh giá</label>
                            <textarea name="review_content" id="review_content" class="form-control" rows="10"><?= htmlspecialchars($row_up['review_content'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Ảnh sản phẩm</label><br>
                            <input type="file" name="image">
                            <small>Hiện tại: <?= htmlspecialchars($row_up['image']); ?></small>
                        </div>
                        <div class="form-group">
                            <label>Giá khuyến mãi (mặc định)</label>
                            <input type="number" name="price_discount" class="form-control" required value="<?= $row_up['price_discount']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Màn hình</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" name="screen" class="form-control" required placeholder="Nhập kích thước màn hình (inch)" value="<?= htmlspecialchars($row_up['screen']); ?>">
                                </div>
                                <div class="col-md-4">
                                    <select name="screen_rating" class="form-control" required>
                                        <option value="" disabled>Chọn mức</option>
                                        <option value="vừa" <?= $row_up['screen_rating'] == 'vừa' ? 'selected' : ''; ?>>Vừa</option>
                                        <option value="lớn" <?= $row_up['screen_rating'] == 'lớn' ? 'selected' : ''; ?>>Lớn</option>
                                        <option value="rất lớn" <?= $row_up['screen_rating'] == 'rất lớn' ? 'selected' : ''; ?>>Rất lớn</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Camera sau</label>
                            <input type="text" name="rear_camera" class="form-control" required value="<?= htmlspecialchars($row_up['rear_camera']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Chip(GPU)</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" name="gpu" class="form-control" required placeholder="Nhập tên GPU" value="<?= htmlspecialchars($row_up['gpu']); ?>">
                                </div>
                                <div class="col-md-4">
                                    <select name="gpu_rating" class="form-control" required>
                                        <option value="" disabled>Chọn mức</option>
                                        <option value="hiệu năng tốt" <?= $row_up['gpu_rating'] == 'hiệu năng tốt' ? 'selected' : ''; ?>>Hiệu năng tốt</option>
                                        <option value="hiệu năng rất tốt" <?= $row_up['gpu_rating'] == 'hiệu năng rất tốt' ? 'selected' : ''; ?>>Hiệu năng rất tốt</option>
                                        <option value="hiệu năng vượt trội" <?= $row_up['gpu_rating'] == 'hiệu năng vượt trội' ? 'selected' : ''; ?>>Hiệu năng vượt trội</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>ROM</label>
                            <input type="text" name="rom" class="form-control" required value="<?= htmlspecialchars($row_up['rom']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Hệ điều hành</label>
                            <input type="text" name="operating_system" class="form-control" placeholder="Nhập hệ điều hành (VD: Android 14, iOS 17)" required value="<?= htmlspecialchars($row_up['operating_system']); ?>">
                        </div>
                        <!-- === THÔNG SỐ KỸ THUẬT === -->
                        <div class="form-group">
                            <label>Thông số kỹ thuật</label>
                            <div id="pasteArea" contenteditable="true" style="border: 1px solid #ccc; min-height: 100px; padding: 5px; margin-bottom: 10px;"></div>
                            <div id="specTable"></div>
                            <input type="hidden" name="specifications" id="specifications" value='<?= htmlspecialchars($row_up['specifications'] ?? '[]'); ?>'>
                            <div id="errorMessage" style="color: red; display: none;">Không thể parse dữ liệu bảng. Vui lòng kiểm tra nội dung sao chép.</div>
                        </div>
                    </div>
                </div>

                <!-- === THÔNG TIN MÀU SẮC, ROM & GIÁ === -->
                <h4 class="mt-4">Thông tin màu sắc, ROM và giá của mỗi phiên bản</h4>
                <div id="color_fields">
                    <?php if (!empty($color_data)) { 
                        $index = 0;
                        foreach ($color_data as $color => $color_row) { ?>
                            <div class="row color-row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Màu sắc</label>
                                        <input type="text" name="color[]" class="form-control" placeholder="Nhập màu" required value="<?= htmlspecialchars($color_row['color']); ?>">
                                        <input type="hidden" name="old_color[]" value="<?= htmlspecialchars($color_row['color']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>ROM (phiên bản)</label>
                                        <input type="text" name="rom_version[]" class="form-control" placeholder="Nhập ROM" required value="<?= htmlspecialchars($color_row['rom']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Ảnh màu</label>
                                        <input type="file" name="color_images[]" class="form-control">
                                        <small>Hiện tại: <?= htmlspecialchars($color_row['image']); ?></small>
                                    </div>
                                    <div class="form-group">
                                        <label>Ảnh chi tiết (chọn nhiều)</label><br>
                                        <input type="file" name="images_detail[<?= $index; ?>][]" multiple>
                                        <?php if (!empty($color_row['images_detail'])) { ?>
                                            <small>Hiện tại: <?= htmlspecialchars($color_row['images_detail']); ?></small>
                                        <?php } else { ?>
                                            <small>Hiện tại: Không có ảnh chi tiết</small>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Giá gốc (phiên bản)</label>
                                        <input type="number" name="price_version[]" class="form-control" placeholder="Nhập giá gốc" required value="<?= $color_row['price']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Giá khuyến mãi (phiên bản)</label>
                                        <input type="number" name="price_discount_version[]" class="form-control" placeholder="Nhập giá KM" required value="<?= $color_row['price_discount']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Số tiền trả góp (phiên bản)</label>
                                        <input type="number" name="installment[]" class="form-control" placeholder="Nhập số tiền trả góp" required value="<?= $color_row['installment']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label> </label>
                                        <?php if ($index == 0) { ?>
                                            <button type="button" class="btn btn-secondary add_color form-control">Thêm màu sắc</button>
                                        <?php } else { ?>
                                            <button type="button" class="btn btn-danger remove_color form-control" data-product-id="<?= $id; ?>">Xóa màu sắc</button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php $index++; }
                    } else { ?>
                        <div class="row color-row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Màu sắc</label>
                                    <input type="text" name="color[]" class="form-control" placeholder="Nhập màu" required value="<?= htmlspecialchars($row_up['color']); ?>">
                                    <input type="hidden" name="old_color[]" value="<?= htmlspecialchars($row_up['color']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>ROM (phiên bản)</label>
                                    <input type="text" name="rom_version[]" class="form-control" placeholder="Nhập ROM" required value="<?= htmlspecialchars($row_up['rom']); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Ảnh màu</label>
                                    <input type="file" name="color_images[]" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Ảnh chi tiết (chọn nhiều)</label><br>
                                    <input type="file" name="images_detail[0][]" multiple required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Giá gốc (phiên bản)</label>
                                    <input type="number" name="price_version[]" class="form-control" placeholder="Nhập giá gốc" required value="<?= $row_up['price']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Giá khuyến mãi (phiên bản)</label>
                                    <input type="number" name="price_discount_version[]" class="form-control" placeholder="Nhập giá KM" required value="<?= $row_up['price_discount']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Số tiền trả góp (phiên bản)</label>
                                    <input type="number" name="installment[]" class="form-control" placeholder="Nhập số tiền trả góp" required value="0">
                                </div>
                                <div class="form-group">
                                    <label> </label>
                                    <button type="button" class="btn btn-secondary add_color form-control">Thêm màu sắc</button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <!-- === SỐ LƯỢNG === -->
                <h4 class="mt-4">Số lượng</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Số lượng</label>
                            <input type="number" name="quantity" class="form-control" required value="<?= $row_up['quantity']; ?>">
                        </div>
                    </div>
                </div>

                <button name="sbm" class="btn btn-success mt-3" type="submit">Sửa</button>
            </form>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
            <link href="https://unpkg.com/tabulator-tables/dist/css/tabulator.min.css" rel="stylesheet">
            <script src="https://unpkg.com/tabulator-tables/dist/js/tabulator.min.js"></script>
            <style>
                #specTable .tabulator-cell {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                #specTable .tabulator-header {
                    background-color: #f5f5f5;
                }
            </style>
            <script>
                // Khởi tạo CKEditor
                ClassicEditor
                    .create(document.querySelector('#review_content'))
                    .catch(error => {
                        console.error(error);
                    });

                let colorIndex = <?= count($color_data) > 0 ? count($color_data) : 1 ?>;
                document.querySelector('.add_color').addEventListener('click', function() {
                    const field = `
                    <div class="row color-row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Màu sắc</label>
                                <input type="text" name="color[]" class="form-control" placeholder="Nhập màu" required>
                                <input type="hidden" name="old_color[]" value="">
                            </div>
                            <div class="form-group">
                                <label>ROM (phiên bản)</label>
                                <input type="text" name="rom_version[]" class="form-control" placeholder="Nhập ROM" required>
                            </div>
                            <div class="form-group">
                                <label>Ảnh màu</label>
                                <input type="file" name="color_images[]" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Ảnh chi tiết (chọn nhiều)</label><br>
                                <input type="file" name="images_detail[${colorIndex}][]" multiple required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Giá gốc (phiên bản)</label>
                                <input type="number" name="price_version[]" class="form-control" placeholder="Nhập giá gốc" required>
                            </div>
                            <div class="form-group">
                                <label>Giá khuyến mãi (phiên bản)</label>
                                <input type="number" name="price_discount_version[]" class="form-control" placeholder="Nhập giá KM" required>
                            </div>
                            <div class="form-group">
                                <label>Số tiền trả góp (phiên bản)</label>
                                <input type="number" name="installment[]" class="form-control" placeholder="Nhập số tiền trả góp" required value="0">
                            </div>
                            <div class="form-group">
                                <label> </label>
                                <button type="button" class="btn btn-danger remove_color form-control" data-product-id="<?= $id; ?>">Xóa màu sắc</button>
                            </div>
                        </div>
                    </div>`;
                    document.querySelector('#color_fields').insertAdjacentHTML('beforeend', field);
                    colorIndex++;

                    document.querySelectorAll('.remove_color').forEach(button => {
                        button.removeEventListener('click', handleRemoveColor);
                        button.addEventListener('click', handleRemoveColor);
                    });
                });

                function handleRemoveColor() {
                    const colorInput = this.closest('.row').querySelector('input[name^="color"]');
                    const colorValue = colorInput ? colorInput.value : 'không xác định';
                    const productId = this.getAttribute('data-product-id');
                    if (confirm('Bạn có chắc muốn xóa màu ' + colorValue + ' không?')) {
                        $.ajax({
                            url: 'delete_color.php',
                            method: 'POST',
                            data: { product_id: productId, color: colorValue },
                            success: function(response) {
                                if (response === 'success') {
                                    this.closest('.row').remove();
                                } else {
                                    alert('Xóa màu thất bại. Vui lòng thử lại.');
                                }
                            }.bind(this),
                            error: function() {
                                alert('Đã xảy ra lỗi khi xóa màu.');
                            }
                        });
                    }
                }

                document.querySelectorAll('.remove_color').forEach(button => {
                    button.addEventListener('click', handleRemoveColor);
                });

                // Xử lý thông số kỹ thuật giống như trong them.php
                let table;
                document.getElementById('pasteArea').addEventListener('paste', function(e) {
                    e.preventDefault();
                    let text = (e.clipboardData || window.clipboardData).getData('text/html');
                    console.log('Nội dung HTML dán:', text); // Debug nội dung HTML
                    document.getElementById('pasteArea').innerHTML = text;

                    if (table) {
                        table.destroy();
                    }

                    let data = parseTableData(text);
                    if (data.length === 0) {
                        document.getElementById('errorMessage').style.display = 'block';
                        document.getElementById('specifications').value = '[]';
                        return;
                    }

                    document.getElementById('errorMessage').style.display = 'none';
                    table = new Tabulator("#specTable", {
                        data: data,
                        columns: [
                            { title: "Thông số", field: "param" },
                            { title: "Chi tiết", field: "value" }
                        ],
                        layout: "fitData",
                        resizableColumns: false,
                        selectable: false
                    });

                    document.getElementById('specifications').value = JSON.stringify(data);
                });

                function parseTableData(html) {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    let data = [];

                    let rows = doc.getElementsByTagName('tr');
                    for (let row of rows) {
                        let cols = [...row.getElementsByTagName('td'), ...row.getElementsByTagName('th')];
                        if (cols.length >= 2) {
                            let param = cols[0].innerText.trim();
                            let value = cols[1].innerText.trim();
                            if (param !== '' || value !== '') {
                                data.push({ param: param, value: value });
                            }
                        }
                    }

                    if (data.length === 0) {
                        let items = doc.querySelectorAll('ul.text-specifi > li');
                        for (let item of items) {
                            let paramAside = item.querySelector('aside:first-child');
                            let valueAside = item.querySelector('aside:nth-child(2)');
                            if (paramAside && valueAside) {
                                let param = paramAside.innerText.trim().replace(/:$/, '');
                                let valueElements = valueAside.querySelectorAll('span, a');
                                let value = '';
                                valueElements.forEach((el, index) => {
                                    value += el.innerText.trim();
                                    if (index < valueElements.length - 1) value += ', ';
                                });
                                if (param !== '' || value !== '') {
                                    data.push({ param: param, value: value });
                                }
                            }
                        }
                    }

                    return data;
                }

                // Hiển thị dữ liệu ban đầu từ specifications dưới dạng HTML thô
                let initialData = <?= $row_up['specifications'] ? json_encode(json_decode($row_up['specifications'], true)) : '[]' ?>;
                if (initialData.length > 0) {
                    let htmlTable = '<table border="1"><tr><th>Thông số</th><th>Chi tiết</th></tr>';
                    initialData.forEach(row => {
                        htmlTable += `<tr><td>${row.param}</td><td>${row.value}</td></tr>`;
                    });
                    htmlTable += '</table>';
                    document.getElementById('pasteArea').innerHTML = htmlTable;
                    document.getElementById('specifications').value = JSON.stringify(initialData);
                }
            </script>
        </div>
    </div>
</div>