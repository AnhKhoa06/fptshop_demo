<?php
    $id = $_GET['id'];

    // Lấy thông tin danh mục
    $sql = "SELECT * FROM brands WHERE brand_id = $id";
    $query = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($query);

    if (isset($_POST['submit'])) {
        $brand_name = $_POST['brand_name'];

        if ($_FILES['image']['name'] != "") {
            $image = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];
            move_uploaded_file($image_tmp, 'img1/'.$image);
    
            $sql_update = "UPDATE brands SET brand_name = '$brand_name', image1 = '$image' WHERE brand_id = $id";
        } else {
            $sql_update = "UPDATE brands SET brand_name = '$brand_name' WHERE brand_id = $id";
        }

        mysqli_query($connect, $sql_update);
        header('Location: index.php?page_layout=danhmuc');
    }
?>

<div class="container" style="margin-left: 20px; padding-top: 25px;">
    <h2>Sửa danh mục</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="brand_name">Tên danh mục (Thương hiệu)</label>
            <input type="text" class="form-control" id="brand_name" name="brand_name" value="<?php echo htmlspecialchars($row['brand_name']); ?>" style="width: 300px;" required>
        </div>
        <div class="form-group">
            <label>Hình ảnh hiện tại</label><br>
            <img src="img1/<?php echo $row['image1']; ?>" width="100">
        </div>
        <div class="form-group">
            <label for="image">Đổi hình ảnh (nếu có)</label>
            <input type="file" name="image" class="form-control-file" style="width: 300px;">
        </div>
        <button type="submit" name="submit" class="btn btn-success">Cập nhật</button>
    </form>
</div>
