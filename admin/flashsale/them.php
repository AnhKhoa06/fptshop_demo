<?php
date_default_timezone_set('Asia/Ho_Chi_Minh'); // Thiết lập múi giờ Việt Nam

// Lấy thời gian hiện tại làm mặc định
$current_time = date('Y-m-d H:i:s', time());
$default_start_time = date('Y-m-d H:i:00', strtotime($current_time)); // Thời gian hiện tại
$default_end_time = date('Y-m-d H:i:00', strtotime('+30 minutes', strtotime($current_time))); // 30 phút sau

// Định dạng cho input datetime-local
$start_time_value = date('Y-m-d\TH:i', strtotime($default_start_time));
$end_time_value = date('Y-m-d\TH:i', strtotime($default_end_time));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $discount = $_POST['discount'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $color = $_POST['color'];
    $rom = $_POST['rom']; // Lấy ROM từ form

    // Chuyển đổi định dạng thời gian từ datetime-local sang Y-m-d H:i:s
    $start_time = date('Y-m-d H:i:s', strtotime($start_time));
    $end_time = date('Y-m-d H:i:s', strtotime($end_time));

    // Lấy giá gốc (price) từ product_colors dựa trên product_id, color và rom
    $price_query = "SELECT price FROM product_colors WHERE product_id = ? AND color = ? AND rom = ?";
    $price_stmt = mysqli_prepare($connect, $price_query);
    mysqli_stmt_bind_param($price_stmt, "iss", $product_id, $color, $rom);
    mysqli_stmt_execute($price_stmt);
    $price_result = mysqli_stmt_get_result($price_stmt);
    $price_row = mysqli_fetch_assoc($price_result);
    $original_price = $price_row['price'] ?? 0; // Giá gốc, mặc định 0 nếu không tìm thấy

    // Tính giá đã giảm
    $price_discount = $original_price * (1 - $discount / 100);

    // Thêm Flash Sale mới với price_discount và rom
    $query = "INSERT INTO flash_sales (product_id, discount, sold, start_time, end_time, color, rom, price_discount) VALUES (?, ?, 0, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "iissssd", $product_id, $discount, $start_time, $end_time, $color, $rom, $price_discount);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Thêm Flash Sale thành công!'); window.location.href='index.php?page_layout=flashsale';</script>";
    } else {
        error_log("Lỗi khi thêm Flash Sale: " . mysqli_error($connect));
        echo "<script>alert('Lỗi khi thêm Flash Sale! Vui lòng kiểm tra log.'); window.location.href='index.php?page_layout=flashsale';</script>";
    }
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($price_stmt);
}

// Lấy danh sách sản phẩm
$products_query = "SELECT prd_id, prd_name FROM products";
$products_result = mysqli_query($connect, $products_query);
?>

<div class="container mt-4">
    <h2>Thêm Flash Sale</h2>
    <form method="POST" action="">
        <div class="row">
            <!-- Cột trái -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="product_id">Sản Phẩm</label>
                    <select name="product_id" id="product_id" class="form-control" required>
                        <option value="">Chọn sản phẩm</option>
                        <?php while ($product = mysqli_fetch_assoc($products_result)): ?>
                            <option value="<?php echo $product['prd_id']; ?>"><?php echo htmlspecialchars($product['prd_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="color">Màu sắc</label>
                    <select name="color" id="color" class="form-control" required>
                        <option value="">Chọn màu sắc</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_time">Thời Gian Bắt Đầu</label>
                    <input type="datetime-local" name="start_time" class="form-control" value="<?php echo $start_time_value; ?>" required>
                    <small class="form-text text-muted">Đặt bằng hoặc trước giờ hiện tại để Flash Sale bắt đầu ngay.</small>
                </div>
            </div>
            <!-- Cột phải -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="rom">Dung lượng (ROM)</label>
                    <select name="rom" id="rom" class="form-control" required>
                        <option value="">Chọn dung lượng</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="discount">Giảm Giá (%)</label>
                    <input type="number" name="discount" class="form-control" required min="0" max="100">
                </div>
                <div class="form-group">
                    <label for="end_time">Thời Gian Kết Thúc</label>
                    <input type="datetime-local" name="end_time" class="form-control" value="<?php echo $end_time_value; ?>" required>
                    <small class="form-text text-muted">Đặt thời gian kết thúc sau thời gian bắt đầu để Flash Sale diễn ra trong khoảng thời gian mong muốn.</small>
                </div>
            </div>
        </div>
        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="index.php?page_layout=flashsale" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#product_id').change(function() {
            var product_id = $(this).val();
            if (product_id) {
                // Lấy danh sách màu sắc
                $.ajax({
                    url: 'get_colors.php',
                    type: 'POST',
                    data: { product_id: product_id },
                    success: function(response) {
                        $('#color').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.log('Lỗi AJAX (màu sắc):', xhr.status, status, error);
                        alert('Đã xảy ra lỗi khi lấy danh sách màu sắc. Mã lỗi: ' + xhr.status + ', Thông tin: ' + error);
                        $('#color').html('<option value="">Chọn màu sắc</option>');
                    }
                });

                // Lấy danh sách ROM
                $.ajax({
                    url: 'get_roms.php',
                    type: 'POST',
                    data: { product_id: product_id },
                    success: function(response) {
                        $('#rom').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.log('Lỗi AJAX (ROM):', xhr.status, status, error);
                        alert('Đã xảy ra lỗi khi lấy danh sách ROM. Mã lỗi: ' + xhr.status + ', Thông tin: ' + error);
                        $('#rom').html('<option value="">Chọn dung lượng</option>');
                    }
                });
            } else {
                $('#color').html('<option value="">Chọn màu sắc</option>');
                $('#rom').html('<option value="">Chọn dung lượng</option>');
            }
        });
    });
</script>