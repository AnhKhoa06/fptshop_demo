<?php
    // Đảm bảo không có khoảng trắng trước thẻ <?php

    $sql_brand = "SELECT * FROM brands";
    $query_brand = mysqli_query($connect, $sql_brand);

    if(isset($_POST['sbm'])) {
        $prd_name = $_POST['prd_name'];

        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($image_tmp, 'img/' . $image);

        $price = $_POST['price'];
        $price_discount = $_POST['price_discount'];
        $ram = $_POST['ram'];
        $rom = $_POST['rom']; 
        $operating_system = $_POST['operating_system'];
        $review_content = $_POST['review_content'];
        $quantity = $_POST['quantity'];
        $brand_id = $_POST['brand_id'];

        $product_code = $_POST['product_code'];
        $screen = $_POST['screen'];
        $screen_rating = $_POST['screen_rating'];
        $front_camera = $_POST['front_camera'];
        $rear_camera = $_POST['rear_camera'];
        $cpu = $_POST['cpu'];
        $gpu = $_POST['gpu'];
        $gpu_rating = $_POST['gpu_rating'];
        $pin = $_POST['pin'];
        $pin_rating = $_POST['pin_rating'];
        $color = $_POST['color'][0]; // Lấy màu đầu tiên làm màu mặc định
        $specifications = $_POST['specifications']; // Lấy dữ liệu thông số kỹ thuật

        // Debug dữ liệu gửi lên (ghi vào log thay vì echo)
        $logMessage = '';
        if (empty($specifications) || $specifications == '[]') {
            $logMessage = "Cảnh báo: Không có dữ liệu thông số kỹ thuật được lưu.\n";
        } else {
            $logMessage = "Dữ liệu thông số kỹ thuật: " . $specifications . "\n";
        }
        file_put_contents('debug.log', $logMessage, FILE_APPEND);

        // Lưu vào bảng products (thêm cột specifications)
        $sql = "INSERT INTO products 
        (prd_name, product_code, image, price, price_discount, ram, rom, operating_system, review_content, quantity, brand_id, screen, screen_rating, front_camera, rear_camera, cpu, gpu, gpu_rating, pin, pin_rating, color, specifications)
        VALUES 
        ('$prd_name', '$product_code', '$image', $price, $price_discount, '$ram', '$rom', '$operating_system', '$review_content', $quantity, $brand_id, '$screen', '$screen_rating', '$front_camera', '$rear_camera', '$cpu', '$gpu', '$gpu_rating', '$pin', '$pin_rating', '$color', '$specifications')";
        $query = mysqli_query($connect, $sql);
        $product_id = mysqli_insert_id($connect); // Lấy ID của sản phẩm vừa thêm

        // Lưu màu sắc, ROM, giá, trả góp, ảnh màu và ảnh chi tiết vào bảng product_colors
        $colors = $_POST['color'];
        $roms = $_POST['rom_version'];
        $prices = $_POST['price_version'];
        $price_discounts = $_POST['price_discount_version'];
        $installments = $_POST['installment'];
        $color_images = $_FILES['color_images'];
        $images_details = $_FILES['images_detail'];

        foreach ($colors as $index => $color) {
            $rom_version = $roms[$index];
            $price_version = $prices[$index];
            $price_discount_version = $price_discounts[$index];
            $installment = $installments[$index];

            // Xử lý ảnh màu
            $color_image = $color_images['name'][$index];
            $color_image_tmp = $color_images['tmp_name'][$index];
            $target_path = 'img/' . $color_image;
            move_uploaded_file($color_image_tmp, $target_path);

            // Xử lý ảnh chi tiết cho phiên bản này
            $images_detail = array();
            if (!empty($images_details['name'][$index])) {
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
                $images_detail_str = '';
            }

            $sql_color = "INSERT INTO product_colors (product_id, color, rom, price, price_discount, installment, image, images_detail) 
                          VALUES ($product_id, '$color', '$rom_version', $price_version, $price_discount_version, $installment, '$color_image', '$images_detail_str')";
            mysqli_query($connect, $sql_color);
        }

        // Chuyển hướng sau khi lưu thành công
        header('location: index.php?page_layout=danhsach');
        exit(); // Đảm bảo không có mã nào được thực thi sau header
    }
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2>Thêm sản phẩm</h2>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <!-- === THÔNG TIN SẢN PHẨM === -->
                <h4>Thông tin sản phẩm</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mã sản phẩm</label>
                            <input type="text" name="product_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tên sản phẩm</label>
                            <input type="text" name="prd_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Giá gốc (mặc định)</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Thương hiệu</label>
                            <select class="form-control" name="brand_id" required>
                                <option value="" disabled selected>Chọn thương hiệu</option>
                                <?php 
                                mysqli_data_seek($query_brand, 0); // Reset con trỏ
                                while($row_brand = mysqli_fetch_assoc($query_brand)) { ?>
                                    <option value="<?php echo $row_brand['brand_id']; ?>"><?php echo $row_brand['brand_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Camera trước</label>
                            <input type="text" name="front_camera" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>CPU</label>
                            <input type="text" name="cpu" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>RAM</label>
                            <input type="text" name="ram" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>DL Pin</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" name="pin" class="form-control" required placeholder="Nhập dung lượng pin">
                                </div>
                                <div class="col-md-4">
                                    <select name="pin_rating" class="form-control" required>
                                        <option value="" disabled selected>Chọn mức</option>
                                        <option value="trung bình">Trung bình</option>
                                        <option value="cao">Cao</option>
                                        <option value="rất cao">Rất cao</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Bài viết đánh giá</label>
                            <textarea name="review_content" id="review_content" class="form-control" rows="10"></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Ảnh sản phẩm</label><br>
                            <input type="file" name="image" required>
                        </div>
                        <div class="form-group">
                            <label>Giá khuyến mãi (mặc định)</label>
                            <input type="number" name="price_discount" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Màn hình</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" name="screen" class="form-control" required placeholder="Nhập kích thước màn hình (inch)">
                                </div>
                                <div class="col-md-4">
                                    <select name="screen_rating" class="form-control" required>
                                        <option value="" disabled selected>Chọn mức</option>
                                        <option value="vừa">Vừa</option>
                                        <option value="lớn">Lớn</option>
                                        <option value="rất lớn">Rất lớn</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Camera sau</label>
                            <input type="text" name="rear_camera" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Chip (GPU)</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" name="gpu" class="form-control" required placeholder="Nhập tên GPU">
                                </div>
                                <div class="col-md-4">
                                    <select name="gpu_rating" class="form-control" required>
                                        <option value="" disabled selected>Chọn mức</option>
                                        <option value="hiệu năng tốt">Hiệu năng tốt</option>
                                        <option value="hiệu năng rất tốt">Hiệu năng rất tốt</option>
                                        <option value="hiệu năng vượt trội">Hiệu năng vượt trội</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>ROM (mặc định)</label>
                            <input type="text" name="rom" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Hệ điều hành</label>
                            <input type="text" name="operating_system" class="form-control" placeholder="Nhập hệ điều hành (VD: Android 14, iOS 17)" required>
                        </div>
                        <!-- === THÔNG SỐ KỸ THUẬT === -->
                        <div class="form-group">
                            <label>Thông số kỹ thuật</label>
                            <div id="pasteArea" contenteditable="true" style="border: 1px solid #ccc; min-height: 100px; padding: 5px; margin-bottom: 10px;"></div>
                            <div id="specTable"></div>
                            <input type="hidden" name="specifications" id="specifications">
                            <div id="errorMessage" style="color: red; display: none;">Không thể parse dữ liệu bảng. Vui lòng kiểm tra nội dung sao chép.</div>
                        </div>
                    </div>
                </div>

                <!-- === THÔNG TIN MÀU SẮC, ROM & GIÁ === -->
                <h4 class="mt-4">Thông tin màu sắc, ROM và giá</h4>
                <div id="color_fields">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Màu sắc</label>
                                <input type="text" name="color[]" class="form-control" placeholder="Nhập màu" required>
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
                                <input type="file" name="images_detail[0][]" multiple required>
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
                                <input type="number" name="installment[]" class="form-control" placeholder="Nhập số tiền trả góp" required>
                            </div>
                            <div class="form-group">
                                <label> </label>
                                <button type="button" class="btn btn-secondary add_color form-control">Thêm màu sắc</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- === SỐ LƯỢNG === -->
                <h4 class="mt-4">Số lượng</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Số lượng</label>
                            <input type="number" name="quantity" class="form-control" required>
                        </div>
                    </div>
                </div>

                <button name="sbm" class="btn btn-success mt-3" type="submit">Thêm</button>
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

                // Xử lý sao chép và dán bảng thông số kỹ thuật
                let table;
                document.getElementById('pasteArea').addEventListener('paste', function(e) {
                    e.preventDefault();
                    let text = (e.clipboardData || window.clipboardData).getData('text/html');
                    console.log('Nội dung HTML dán:', text); // Debug nội dung HTML
                    document.getElementById('pasteArea').innerHTML = text;

                    // Hủy bảng cũ nếu có
                    if (table) {
                        table.destroy();
                    }

                    // Chuyển nội dung thành bảng Tabulator
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

                    // Lưu dữ liệu bảng vào trường ẩn dưới dạng JSON
                    document.getElementById('specifications').value = JSON.stringify(data);
                });

                function parseTableData(html) {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    let data = [];

                    // Trường hợp 1: Bảng dùng <table>, <tr>, <td>
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

                    // Trường hợp 2: Bảng dùng <ul class="text-specifi"> và <aside> (Thế Giới Di Động)
                    if (data.length === 0) {
                        let items = doc.querySelectorAll('ul.text-specifi > li');
                        for (let item of items) {
                            let paramAside = item.querySelector('aside:first-child');
                            let valueAside = item.querySelector('aside:nth-child(2)');
                            if (paramAside && valueAside) {
                                let param = paramAside.innerText.trim().replace(/:$/, ''); // Loại bỏ dấu ":" ở cuối
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

                // Xử lý thêm/xóa màu sắc
                let colorIndex = 1;
                document.querySelector('.add_color').addEventListener('click', function() {
                    const field = `
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Màu sắc</label>
                                <input type="text" name="color[]" class="form-control" placeholder="Nhập màu" required>
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
                                <input type="number" name="installment[]" class="form-control" placeholder="Nhập số tiền trả góp" required>
                            </div>
                            <div class="form-group">
                                <label> </label>
                                <button type="button" class="btn btn-danger remove_color form-control" data-product-id="">Xóa màu sắc</button>
                            </div>
                        </div>
                    </div>`;
                    document.querySelector('#color_fields').insertAdjacentHTML('beforeend', field);
                    colorIndex++;

                    document.querySelectorAll('.remove_color').forEach(button => {
                        button.addEventListener('click', function() {
                            const colorInput = this.closest('.row').querySelector('input[name^="color"]');
                            const colorValue = colorInput ? colorInput.value : 'không xác định';
                            const productId = this.getAttribute('data-product-id') || '<?php echo isset($product_id) ? $product_id : ''; ?>';
                            if (confirm('Bạn có chắc muốn xóa màu ' + colorValue + ' không?')) {
                                $.ajax({
                                    url: 'delete_color.php',
                                    method: 'POST',
                                    data: { product_id: productId, color: colorValue },
                                    success: function(response) {
                                        if (response === 'success') {
                                            button.closest('.row').remove();
                                        } else {
                                            alert('Xóa màu thất bại. Vui lòng thử lại.');
                                        }
                                    },
                                    error: function() {
                                        alert('Đã xảy ra lỗi khi xóa màu.');
                                    }
                                });
                            }
                        });
                    });
                });
            </script>
        </div>
    </div>
</div>