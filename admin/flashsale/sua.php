<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM flash_sales WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $flash_sale = mysqli_fetch_assoc($result);
    $current_sold = $flash_sale['sold'] ?? 0; // Lấy giá trị sold hiện tại
    mysqli_stmt_close($stmt);
}

// Định dạng thời gian hiện tại cho input
$start_time_value = date('Y-m-d\TH:i', strtotime($flash_sale['start_time']));
$end_time_value = date('Y-m-d\TH:i', strtotime($flash_sale['end_time']));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $discount = $_POST['discount'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $color = $_POST['color'];
    $rom = $_POST['rom'];

    // Chuyển đổi định dạng thời gian
    $start_time = date('Y-m-d H:i:s', strtotime($start_time));
    $end_time = date('Y-m-d H:i:s', strtotime($end_time));

    // Lấy giá gốc (price) từ product_colors dựa trên product_id, color và rom
    $price_query = "SELECT price FROM product_colors WHERE product_id = ? AND color = ? AND rom = ?";
    $price_stmt = mysqli_prepare($connect, $price_query);
    mysqli_stmt_bind_param($price_stmt, "iss", $product_id, $color, $rom);
    mysqli_stmt_execute($price_stmt);
    $price_result = mysqli_stmt_get_result($price_stmt);
    $price_row = mysqli_fetch_assoc($price_result);
    $original_price = $price_row['price'] ?? 0;
    mysqli_stmt_close($price_stmt);

    // Tính giá đã giảm
    $price_discount = $original_price * (1 - $discount / 100);

    // Kiểm tra xem có thay đổi product_id, color hoặc rom không
    $reset_sold = false;
    if ($product_id != $flash_sale['product_id'] || $color != $flash_sale['color'] || $rom != $flash_sale['rom']) {
        $reset_sold = true;
    }

    // Quyết định giá trị sold
    $sold_value = $reset_sold ? 0 : $current_sold;

    // Cập nhật Flash Sale hiện tại, bao gồm cả sold
    $query = "UPDATE flash_sales SET product_id = ?, discount = ?, start_time = ?, end_time = ?, color = ?, rom = ?, price_discount = ?, sold = ? WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "iissssidi", $product_id, $discount, $start_time, $end_time, $color, $rom, $price_discount, $sold_value, $id);
    if (mysqli_stmt_execute($stmt)) {
        // Đồng bộ thời gian cho tất cả Flash Sale khác
        $update_query = "UPDATE flash_sales SET start_time = ?, end_time = ? WHERE id != ?";
        $update_stmt = mysqli_prepare($connect, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ssi", $start_time, $end_time, $id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);

        echo "<script>alert('Sửa Flash Sale thành công!'); window.location.href='index.php?page_layout=flashsale';</script>";
    } else {
        error_log("Lỗi khi sửa Flash Sale: " . mysqli_error($connect));
        echo "<script>alert('Lỗi khi sửa Flash Sale! Vui lòng kiểm tra log.'); window.location.href='index.php?page_layout=flashsale';</script>";
    }
    mysqli_stmt_close($stmt);
}

// Lấy danh sách sản phẩm
$products_query = "SELECT prd_id, prd_name FROM products";
$products_result = mysqli_query($connect, $products_query);

// Lấy danh sách màu sắc của sản phẩm hiện tại
$colors_query = "SELECT DISTINCT color FROM product_colors WHERE product_id = ?";
$colors_stmt = mysqli_prepare($connect, $colors_query);
mysqli_stmt_bind_param($colors_stmt, "i", $flash_sale['product_id']);
mysqli_stmt_execute($colors_stmt);
$colors_result = mysqli_stmt_get_result($colors_stmt);

// Lấy danh sách ROM của sản phẩm hiện tại
$roms_query = "SELECT DISTINCT rom FROM product_colors WHERE product_id = ?";
$roms_stmt = mysqli_prepare($connect, $roms_query);
mysqli_stmt_bind_param($roms_stmt, "i", $flash_sale['product_id']);
mysqli_stmt_execute($roms_stmt);
$roms_result = mysqli_stmt_get_result($roms_stmt);
?>

<div class="container mt-4">
    <h2>Sửa Flash Sale</h2>
    <form method="POST" action="">
        <div class="row">
            <!-- Cột trái -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="product_id">Sản Phẩm</label>
                    <select name="product_id" id="product_id" class="form-control" required>
                        <option value="">Chọn sản phẩm</option>
                        <?php while ($product = mysqli_fetch_assoc($products_result)): ?>
                            <option value="<?php echo $product['prd_id']; ?>" <?php if ($product['prd_id'] == $flash_sale['product_id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($product['prd_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="color">Màu sắc</label>
                    <select name="color" id="color" class="form-control" required>
                        <option value="">Chọn màu sắc</option>
                        <?php while ($color = mysqli_fetch_assoc($colors_result)): ?>
                            <option value="<?php echo htmlspecialchars($color['color']); ?>" <?php if ($color['color'] == $flash_sale['color']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($color['color']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_time">Thời Gian Bắt Đầu</label>
                    <input type="datetime-local" name="start_time" class="form-control" value="<?php echo $start_time_value; ?>" required>
                    <small class="form-text text-muted">Thời gian này sẽ áp dụng cho tất cả sản phẩm Flash Sale.</small>
                </div>
            </div>
            <!-- Cột phải -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="rom">Dung lượng (ROM)</label>
                    <select name="rom" id="rom" class="form-control" required>
                        <option value="">Chọn dung lượng</option>
                        <?php while ($rom = mysqli_fetch_assoc($roms_result)): ?>
                            <option value="<?php echo htmlspecialchars($rom['rom']); ?>" <?php if ($rom['rom'] == $flash_sale['rom']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($rom['rom']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="discount">Giảm Giá (%)</label>
                    <input type="number" name="discount" class="form-control" value="<?php echo $flash_sale['discount']; ?>" required min="0" max="100">
                </div>
                <div class="form-group">
                    <label for="end_time">Thời Gian Kết Thúc</label>
                    <input type="datetime-local" name="end_time" class="form-control" value="<?php echo $end_time_value; ?>" required>
                    <small class="form-text text-muted">Thời gian này sẽ áp dụng cho tất cả sản phẩm Flash Sale.</small>
                </div>
            </div>
        </div>
        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Sửa</button>
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
                    dataType: 'html',
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
                    dataType: 'html',
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