<?php
    require_once 'config/db.php';
?>
<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ./admin/login1.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="admin2.css">
    <title>My Admin</title>
</head>
<body>
    <!-- Sidebar bên trái -->
    <div class="sidebar d-flex flex-column bg-dark text-white" style="width: 250px; height: 100vh; position: fixed;">
        <div class="text-center py-4 border-bottom">
            <div class="d-flex align-items-center justify-content-center">
                <i class="fas fa-user-circle fa-3x rounded-circle mb-2" style="background-color: #ccc; padding: 10px;"></i>
                <div class="ml-3">
                    <h5 class="mb-0">Admin</h5>
                    <small class="text-success">● Online</small>
                </div>
            </div>
        </div>

        
        <div class="p-3 border-bottom">
            <input type="text" class="form-control form-control-sm" placeholder="Search...">
        </div>

        <div class="px-3 mt-2 mb-1 text-muted" style="font-size: 13px;">MAIN NAVIGATION</div>
        
        <nav class="nav flex-column px-2">
            <a href="index.php?page_layout=danhsach" class="nav-link text-white">
                <i class="fas fa-box" style="margin-right: 7px;"></i> Quản lý Sản Phẩm
            </a>
            <a href="index.php?page_layout=danhmuc" class="nav-link text-white">
                <i class="fas fa-folder" style="margin-right: 7px;"></i> Quản lý Danh Mục
            </a>
            <a href="index.php?page_layout=danhmuc" class="nav-link text-white">
                <i class="fas fa-users" style="margin-right: 7px;"></i> Quản lý Khách Hàng
            </a>

            <a href="../logout.php" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">Đăng xuất</a>
        </nav>
    </div>
    

    <div class="main-content">
    <?php
        if(isset($_GET['page_layout'])){
            switch ($_GET['page_layout']) {
                // --- Phần sản phẩm ---
                case 'danhsach':
                    require_once 'sanpham/danhsach.php';
                    break;

                case 'them':
                    require_once 'sanpham/them.php';
                    break;
                
                case 'sua':
                    require_once 'sanpham/sua.php';
                    break;

                case 'xoa':
                    require_once 'sanpham/xoa.php';
                    break;

                // --- Phần danh mục ---
                case 'danhmuc':
                    require_once 'danhmuc/danhsach.php';
                    break;

                case 'them_danhmuc':
                    require_once 'danhmuc/them.php';
                    break;

                case 'sua_danhmuc':
                    require_once 'danhmuc/sua.php';
                    break;

                case 'xoa_danhmuc':
                    require_once 'danhmuc/xoa.php';
                    break;

                default:
                    require_once 'sanpham/danhsach.php';
                    break;
            }
        }else{
            require_once 'sanpham/danhsach.php';
        }
    ?>
    </div>
    
</body>
</html>