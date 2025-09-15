<?php
if (!isset($_GET['id'])) {
    header('Location: index.php?page_layout=tintuc');
    exit();
}

$news_id = intval($_GET['id']);
$query = "SELECT * FROM news WHERE news_id = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $news_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$news = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$news) {
    header('Location: index.php?page_layout=tintuc');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image = $news['image'];
    $category = $_POST['category'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Xử lý upload ảnh mới (nếu có)
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "../img/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        $image = "img/" . basename($image);
    }

    // Cập nhật cơ sở dữ liệu
    $query = "UPDATE news SET image = ?, category = ?, title = ?, content = ? WHERE news_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $image, $category, $title, $content, $news_id);
    if (mysqli_stmt_execute($stmt)) {
        header('Location: index.php?page_layout=tintuc');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Lỗi khi sửa tin tức: " . mysqli_stmt_error($stmt) . "</div>";
    }
    mysqli_stmt_close($stmt);
}
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2>Sửa Tin Tức</h2>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="image">Ảnh</label>
                    <input type="file" class="form-control-file" id="image" name="image">
                    <small>Hiện tại: <img src="../<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" style="width: 50px; height: 50px; object-fit: cover;"></small>
                </div>
                <div class="form-group">
                    <label for="category">Chủ đề</label>
                    <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($news['category']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="title">Tiêu đề</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="content">Nội dung</label>
                    <textarea name="content" id="content" class="form-control" rows="10"><?php echo htmlspecialchars($news['content']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
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