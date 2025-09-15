<?php
if (!isset($_GET['id'])) {
    header('Location: index.php?page_layout=khuyenmai');
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM promotions WHERE promo_id = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$promotion = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$promotion) {
    header('Location: index.php?page_layout=khuyenmai');
    exit();
}

$sql_brand = "SELECT * FROM brands";
$query_brand = mysqli_query($connect, $sql_brand);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $promo_code = $_POST['promo_code'] ?? '';
    $discount = floatval($_POST['discount'] ?? 0);
    $discount_type = $_POST['discount_type'] ?? '';
    $expiry_date = $_POST['expiry_date'] ?? '';
    $condition_type = $_POST['condition_type'] ?? '';
    $condition_value = floatval($_POST['condition_value'] ?? 0);
    $condition_message = $_POST['condition_message'] ?? '';
    $brand_id = $_POST['brand_id'] == 'all' ? NULL : intval($_POST['brand_id'] ?? 0);

    // Kiểm tra giá trị condition_type
    if (empty($condition_type) || !in_array($condition_type, ['quantity', 'total'])) {
        echo "<script>alert('Lỗi: Loại điều kiện không hợp lệ! Giá trị nhận được: " . htmlspecialchars($condition_type) . "'); history.back();</script>";
        exit();
    }

    $sql = "UPDATE promotions SET 
            promo_code = '$promo_code', 
            discount = $discount, 
            discount_type = '$discount_type', 
            expiry_date = '$expiry_date', 
            condition_type = '$condition_type', 
            condition_value = $condition_value, 
            condition_message = '$condition_message', 
            brand_id = " . ($brand_id ?? 'NULL') . " 
            WHERE promo_id = $id";
    if (mysqli_query($connect, $sql)) {
        echo "<script>window.location.href='index.php?page_layout=khuyenmai';</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật khuyến mãi: " . mysqli_error($connect) . "'); history.back();</script>";
    }
}
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2>Sửa khuyến mãi</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mã khuyến mãi</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="promo_code" id="promo_code" value="<?php echo htmlspecialchars($promotion['promo_code']); ?>" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-info" id="generate_code">Tạo tự động</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Giảm giá</label>
                            <div class="row">
                                <div class="col-12">
                                    <input type="number" step="0.01" class="form-control" name="discount" value="<?php echo $promotion['discount']; ?>" min="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Ngày hết hạn</label>
                            <input type="date" class="form-control" name="expiry_date" value="<?php echo $promotion['expiry_date']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Loại điều kiện</label>
                            <select class="form-control" name="condition_type" required>
                                <option value="quantity" <?php echo $promotion['condition_type'] == 'quantity' ? 'selected' : ''; ?>>Số lượng sản phẩm</option>
                                <option value="total" <?php echo $promotion['condition_type'] == 'total' ? 'selected' : ''; ?>>Tổng đơn hàng</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kiểu giảm giá</label>
                            <select class="form-control" name="discount_type" required>
                                <option value="percent" <?php echo $promotion['discount_type'] == 'percent' ? 'selected' : ''; ?>>Phần trăm (%)</option>
                                <option value="fixed" <?php echo $promotion['discount_type'] == 'fixed' ? 'selected' : ''; ?>>Tiền mặt (đ)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Giá trị điều kiện</label>
                            <input type="number" step="0.01" class="form-control" name="condition_value" value="<?php echo $promotion['condition_value']; ?>" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>Thông báo điều kiện</label>
                            <input type="text" class="form-control" name="condition_message" value="<?php echo htmlspecialchars($promotion['condition_message']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Danh mục áp dụng</label>
                            <select class="form-control" name="brand_id" required>
                                <option value="all" <?php echo is_null($promotion['brand_id']) ? 'selected' : ''; ?>>Tất cả</option>
                                <?php while ($row_brand = mysqli_fetch_assoc($query_brand)) { ?>
                                    <option value="<?php echo $row_brand['brand_id']; ?>" <?php echo $promotion['brand_id'] == $row_brand['brand_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($row_brand['brand_name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="index.php?page_layout=khuyenmai" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('generate_code').addEventListener('click', function() {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        const length = Math.floor(Math.random() * (9 - 6 + 1)) + 6;
        let newCode = '';
        for (let i = 0; i < length; i++) {
            newCode += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        document.getElementById('promo_code').value = newCode;
    });
</script>