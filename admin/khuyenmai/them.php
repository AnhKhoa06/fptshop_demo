<?php
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
        echo "<script>alert('Lỗi: Loại điều kiện không hợp lệ!'); history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO promotions (promo_code, discount, discount_type, expiry_date, condition_type, condition_value, condition_message, brand_id) 
            VALUES ('$promo_code', $discount, '$discount_type', '$expiry_date', '$condition_type', $condition_value, '$condition_message', " . ($brand_id ?? 'NULL') . ")";
    if (mysqli_query($connect, $sql)) {
        echo "<script>window.location.href='index.php?page_layout=khuyenmai';</script>";
    } else {
        echo "<script>alert('Lỗi khi thêm khuyến mãi: " . mysqli_error($connect) . "'); history.back();</script>";
    }
}
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2>Thêm khuyến mãi</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mã khuyến mãi</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="promo_code" id="promo_code" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-info" id="generate_code">Tạo tự động</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Giảm giá</label>
                            <div class="row">
                                <div class="col-12">
                                    <input type="number" step="0.01" class="form-control" name="discount" min="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Ngày hết hạn</label>
                            <input type="date" class="form-control" name="expiry_date" required>
                        </div>
                        <div class="form-group">
                            <label>Loại điều kiện</label>
                            <select class="form-control" name="condition_type" required>
                                <option value="quantity">Số lượng sản phẩm</option>
                                <option value="total">Tổng đơn hàng</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kiểu giảm giá</label>
                            <select class="form-control" name="discount_type" required>
                                <option value="percent">Phần trăm (%)</option>
                                <option value="fixed">Tiền mặt (đ)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Giá trị điều kiện</label>
                            <input type="number" step="0.01" class="form-control" name="condition_value" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>Thông báo điều kiện</label>
                            <input type="text" class="form-control" name="condition_message" required>
                        </div>
                        <div class="form-group">
                            <label>Danh mục áp dụng</label>
                            <select class="form-control" name="brand_id" required>
                                <option value="all">Tất cả</option>
                                <?php while ($row_brand = mysqli_fetch_assoc($query_brand)) { ?>
                                    <option value="<?php echo $row_brand['brand_id']; ?>"><?php echo htmlspecialchars($row_brand['brand_name']); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Thêm</button>
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