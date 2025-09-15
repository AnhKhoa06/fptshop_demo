
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image = $_FILES['image']['name'];
    $created_at = $_POST['created_at'];
    $target_dir = "./img/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    $sql = "INSERT INTO banners (image, created_at) VALUES (?, ?)";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $image, $created_at);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: index.php?page_layout=banner");
    exit();
}
?>

<div class="container mt-4">
    <h2>Thêm Banner</h2>
    <form action="index.php?page_layout=them_banner" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="file" class="form-control-file" id="image" name="image" required>
        </div>
        <div class="form-group">
            <label for="created_at">Thời gian tạo</label>
            <input type="datetime-local" class="form-control" id="created_at" name="created_at" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="index.php?page_layout=banner" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

