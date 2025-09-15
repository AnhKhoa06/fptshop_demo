<?php
$order_code = isset($_GET['code']) ? $_GET['code'] : '';
if (empty($order_code)) {
    header('Location: index.php?page_layout=donhang');
    exit();
}

// Lấy thông tin đơn hàng
$query_order = "SELECT o.order_code, o.payment_method, o.total_amount, o.created_at, o.status, o.discount_amount, u.address, u.name, u.phone 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.order_code = ?";
$stmt_order = mysqli_prepare($connect, $query_order);
mysqli_stmt_bind_param($stmt_order, "s", $order_code);
mysqli_stmt_execute($stmt_order);
$result_order = mysqli_stmt_get_result($stmt_order);
$order = mysqli_fetch_assoc($result_order);
mysqli_stmt_close($stmt_order);

if (!$order) {
    header('Location: index.php?page_layout=donhang');
    exit();
}

// Lấy chi tiết sản phẩm
$query_details = "SELECT product_code, product_name, color, quantity, unit_price 
                  FROM order_details 
                  WHERE order_id = (SELECT id FROM orders WHERE order_code = ?)";
$stmt_details = mysqli_prepare($connect, $query_details);
mysqli_stmt_bind_param($stmt_details, "s", $order_code);
mysqli_stmt_execute($stmt_details);
$result_details = mysqli_stmt_get_result($stmt_details);
$order_items = [];
$total_quantity = 0;
while ($row = mysqli_fetch_assoc($result_details)) {
    $order_items[] = $row;
    $total_quantity += $row['quantity'];
}
mysqli_stmt_close($stmt_details);

// Parse địa chỉ từ JSON
$address = json_decode($order['address'], true) ?? [];
$full_address = isset($address['street']) && isset($address['ward']['name']) && isset($address['district']['name']) && isset($address['province']['name'])
    ? $address['street'] . ', ' . $address['ward']['name'] . ', ' . $address['district']['name'] . ', ' . $address['province']['name']
    : 'Chưa có địa chỉ';
?>

<div class="container-fluid mt-4">
    <h2>Chi Tiết Đơn Hàng #<?php echo htmlspecialchars($order_code); ?></h2>
    <a href="index.php?page_layout=donhang" class="btn btn-secondary mb-3">Quay Lại</a>

    <div class="card mb-4">
        <div class="card-header">Thông Tin Khách Hàng</div>
        <div class="card-body">
            <p><strong>Tên:</strong> <?php echo htmlspecialchars($order['name'] ?? 'Khách hàng chưa cập nhật tên'); ?></p>
            <p><strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
            <p><strong>Địa Chỉ:</strong> <?php echo htmlspecialchars($full_address); ?></p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Thông Tin Đơn Hàng</div>
        <div class="card-body">
            <p><strong>Mã Đơn Hàng:</strong> <?php echo htmlspecialchars($order['order_code']); ?></p>
            <p><strong>Phương Thức Thanh Toán:</strong> <?php echo $order['payment_method'] == 'cod' ? 'Tiền mặt' : 'VNPay'; ?></p>
            <p><strong>Trạng Thái:</strong> <span class="status-<?php echo strtolower(str_replace(' ', '-', $order['status'])); ?>"><?php echo htmlspecialchars($order['status']); ?></span></p>
            <p><strong>Thời Gian:</strong> <?php echo date('d-m-Y H:i:s', strtotime($order['created_at'])); ?></p>
            <p><strong>Giá Gốc:</strong> <?php echo number_format($order['total_amount'] + $order['discount_amount'], 0, ',', '.'); ?>đ</p>
            <p><strong>Giảm Giá:</strong> <?php echo number_format($order['discount_amount'], 0, ',', '.'); ?>đ</p>
            <p><strong>Thành Tiền:</strong> <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</p>
            <p><strong>Địa Chỉ Giao Hàng:</strong> <?php echo htmlspecialchars($full_address); ?></p>
            <?php if ($order['status'] == 'Chờ xác nhận'): ?>
                <button class="btn btn-warning btn-sm confirm-order-btn" data-order-code="<?php echo $order['order_code']; ?>" style="margin-top: 10px; margin-left: 5px;">Xác Nhận</button>
                <button class="btn btn-danger btn-sm cancel-order-btn" data-order-code="<?php echo $order['order_code']; ?>" style="margin-top: 10px; margin-left: 5px;">Hủy Đơn</button>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Danh Sách Sản Phẩm</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>STT</th>
                        <th>Mã Sản Phẩm</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Màu Sắc</th>
                        <th>Số Lượng</th>
                        <th>Đơn Giá</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $index => $item): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($item['product_code']); ?></td>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['color']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['unit_price'], 0, ',', '.'); ?>đ</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Xử lý nút Xác Nhận
    const confirmButtons = document.querySelectorAll('.confirm-order-btn');
    confirmButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderCode = this.getAttribute('data-order-code');
            if (confirm('Bạn có chắc chắn muốn xác nhận đơn hàng ' + orderCode + '?')) {
                fetch('update_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order_code: orderCode, status: 'Đang xử lý' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Đã xác nhận đơn hàng ' + orderCode);
                        const row = this.closest('.card-body');
                        const statusSpan = row.querySelector('p:nth-child(3) span');
                        statusSpan.className = 'status-đang-xử-lý';
                        statusSpan.textContent = 'Đang xử lý';
                        this.remove();
                    } else {
                        alert(data.message || 'Lỗi khi xác nhận đơn hàng');
                    }
                })
                .catch(error => alert('Lỗi: ' + error));
            }
        });
    });

    // Xử lý nút Hủy Đơn
    const cancelButtons = document.querySelectorAll('.cancel-order-btn');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderCode = this.getAttribute('data-order-code');
            if (confirm('Bạn có chắc chắn muốn hủy đơn hàng ' + orderCode + '?')) {
                fetch('update_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order_code: orderCode, status: 'Hủy đơn' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Đã hủy đơn hàng ' + orderCode);
                        const cardBody = document.querySelector('.card-body'); // Lấy trực tiếp phần tử .card-body
                        if (cardBody) {
                            const statusSpan = cardBody.querySelector('p:nth-child(3) span');
                            if (statusSpan) {
                                statusSpan.className = 'status-hủy-đơn';
                                statusSpan.textContent = 'Hủy đơn';
                            }
                            this.remove();
                            const confirmButton = cardBody.querySelector('.confirm-order-btn');
                            if (confirmButton) confirmButton.remove(); // Xóa nút Xác Nhận nếu tồn tại
                        }
                    } else {
                        alert(data.message || 'Lỗi khi hủy đơn hàng');
                    }
                })
                .catch(error => alert('Lỗi: ' + error));
            }
        });
    });

});
</script>