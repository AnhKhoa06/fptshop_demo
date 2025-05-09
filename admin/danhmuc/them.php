<?php
    if (isset($_POST['submit'])) {
        $brand_name = $_POST['brand_name'];

        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
    
        move_uploaded_file($image_tmp, 'img1/'.$image);

        $sql = "INSERT INTO brands (brand_name, image1) VALUES ('$brand_name', '$image')";
        mysqli_query($connect, $sql);
        header('Location: index.php?page_layout=danhmuc');
    }
?>

<div class="container" style="margin-left: 20px; padding-top: 25px;">
    <h2>Thêm danh mục mới</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="brand_name">Tên danh mục (Thương hiệu)</label>
            <input type="text" name="brand_name" class="form-control" style="width: 300px;" required>
        </div>
        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="file" name="image" class="form-control-file" style="width: 300px;" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Thêm</button>
    </form>
</div>
