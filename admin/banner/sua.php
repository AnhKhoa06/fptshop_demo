<?php
// Xử lý form trước khi xuất HTML
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $image = $_FILES['image']['name'];
    $created_at = $_POST['created_at'];

    if ($id > 0) {
        if ($image) {
            $image_name = time() . '_' . basename($image);
            $target_dir = "./img/";
            $target_file = $target_dir . $image_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $sql = "UPDATE banners SET image = ?, created_at = ? WHERE id = ?";
                $stmt = mysqli_prepare($connect, $sql);
                mysqli_stmt_bind_param($stmt, "ssi", $image_name, $created_at, $id);
            } else {
                echo "<p style='color: red;'>Lỗi khi upload hình ảnh!</p>";
            }
        } else {
            $sql = "UPDATE banners SET created_at = ? WHERE id = ?";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, "si", $created_at, $id);
        }

        if (isset($stmt)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            header("Location: index.php?page_layout=banner");
            exit();
        }
    }
}

// Lấy dữ liệu để hiển thị form
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$row = null;
if ($id > 0) {
    $sql = "SELECT id, image, created_at FROM banners WHERE id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}
?>

<div class="container mt-4">
    <h2>Sửa Banner</h2>
    <?php if ($row === null): ?>
        <p style="color: red;">Không tìm thấy banner với ID <?php echo $id; ?>!</p>
    <?php else: ?>
        <form action="index.php?page_layout=sua_banner" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="form-group">
                <label for="image">Hình ảnh</label>
                <input type="file" class="form-control-file" id="image" name="image">
                <?php if ($row['image']) echo "<img src='./img/" . htmlspecialchars($row['image']) . "' width='100'>"; ?>
            </div>
            <div class="form-group">
                <label for="created_at">Thời gian tạo</label>
                <input type="datetime-local" class="form-control" id="created_at" name="created_at" value="<?php echo date('Y-m-d\TH:i', strtotime($row['created_at'])); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="index.php?page_layout=banner" class="btn btn-secondary">Quay lại</a>
        </form>
    <?php endif; ?>
</div>