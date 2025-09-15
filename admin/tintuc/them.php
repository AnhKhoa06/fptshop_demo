<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image = $_FILES['image']['name'];
    $category = $_POST['category'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Xử lý upload ảnh
    if ($image) {
        $target_dir = "../img/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        $image = "img/" . basename($image); // Lưu đường dẫn tương đối
    }

    // Thêm vào cơ sở dữ liệu
    $query = "INSERT INTO news (image, category, title, content, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $image, $category, $title, $content);
    if (mysqli_stmt_execute($stmt)) {
        header('Location: index.php?page_layout=tintuc');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Lỗi khi thêm tin tức: " . mysqli_stmt_error($stmt) . "</div>";
    }
    mysqli_stmt_close($stmt);
}
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2>Thêm Tin Tức</h2>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="image">Ảnh</label>
                    <input type="file" class="form-control-file" id="image" name="image" required>
                </div>
                <div class="form-group">
                    <label for="category">Chủ đề</label>
                    <input type="text" class="form-control" id="category" name="category" required>
                </div>
                <div class="form-group">
                    <label for="title">Tiêu đề</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="content">Nội dung</label>
                    <textarea name="content" id="content" class="form-control" rows="10"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Thêm</button>
                <a href="index.php?page_layout=tintuc" class="btn btn-secondary">Quay lại</a>
            </form>
            <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
            <script>
                ClassicEditor
                    .create(document.querySelector('#content'))
                    .catch(error => {
                        console.error(error);
                    });
            </script>
        </div>
    </div>
</div>