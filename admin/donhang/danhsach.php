<?php
// Lấy danh sách đơn hàng
$query_orders = "SELECT o.order_code, o.payment_method, o.status, o.total_amount, u.name, u.phone, u.address 
                 FROM orders o 
                 JOIN users u ON o.user_id = u.id 
                 ORDER BY o.created_at DESC";
$result_orders = mysqli_query($connect, $query_orders);
$orders = [];
while ($row = mysqli_fetch_assoc($result_orders)) {
    $orders[] = $row;
}
?>

<div class="container-fluid mt-4">
    <h2>Quản lý Đơn Hàng</h2>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Mã ĐH</th>
                <th>Khách Hàng</th>
                <th>Địa Chỉ</th>
                <th>Số Điện Thoại</th>
                <th>Phương Thức Thanh Toán</th>
                <th>Trạng Thái</th>
                <th>Tổng Tiền</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_code']); ?></td>
                        <td><?php echo htmlspecialchars($order['name'] ?? 'Khách hàng chưa cập nhật tên'); ?></td>
                        <td>
                            <?php
                            $address = json_decode($order['address'], true) ?? [];
                            $short_address = isset($address['district']['name']) && isset($address['province']['name']) 
                                ? $address['district']['name'] . ', ' . $address['province']['name'] 
                                : 'Chưa có địa chỉ';
                            echo htmlspecialchars($short_address);
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($order['phone'] ?? 'N/A'); ?></td>
                        <td><?php echo $order['payment_method'] == 'cod' ? 'Tiền mặt' : 'VNPay'; ?></td>
                        <td>
                            <span class="status-<?php echo strtolower(str_replace(' ', '-', $order['status'])); ?>">
                                <?php echo htmlspecialchars($order['status']); ?>
                            </span>
                        </td>
                        <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</td>
                        <td>
                            <a href="index.php?page_layout=chitiet_donhang&code=<?php echo $order['order_code']; ?>" class="btn btn-info btn-sm">Xem Chi Tiết</a>
                            <?php if ($order['status'] == 'Chờ xác nhận'): ?>
                                <button class="btn btn-warning btn-sm confirm-order-btn" data-order-code="<?php echo $order['order_code']; ?>">Xác Nhận</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Không có đơn hàng nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
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
                        const row = this.closest('tr');
                        row.querySelector('td:nth-child(6)').innerHTML = '<span class="status-đang-xử-lý">Đang xử lý</span>';
                        this.remove();
                    } else {
                        alert(data.message || 'Lỗi khi xác nhận đơn hàng');
                    }
                })
                .catch(error => alert('Lỗi: ' + error));
            }
        });
    });
});
</script>