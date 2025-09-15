<?php
session_start();
require_once 'admin/config/db.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $clientId = "624818447896-jjv3o42mr5a6ov845cm2oc5fgae009fe.apps.googleusercontent.com";
    $clientSecret = "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"; // Thay bằng client secret thực tế
    $redirectUri = "http://localhost/fpt_shop/fptshop_demo/google_callback.php";

    // Lấy access token từ Google
    $tokenUrl = "https://oauth2.googleapis.com/token";
    $tokenData = [
        'code' => $code,
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'redirect_uri' => $redirectUri,
        'grant_type' => 'authorization_code',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $tokenResponse = curl_exec($ch);
    curl_close($ch);

    $tokenData = json_decode($tokenResponse, true);
    if (isset($tokenData['access_token'])) {
        $accessToken = $tokenData['access_token'];

        // Lấy thông tin người dùng từ Google
        $userInfoUrl = "https://www.googleapis.com/oauth2/v2/userinfo";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $userInfoResponse = curl_exec($ch);
        curl_close($ch);

        $userInfo = json_decode($userInfoResponse, true);
        if (isset($userInfo['email']) && isset($userInfo['name'])) {
            $email = $userInfo['email'];
            $name = $userInfo['name'];

            // Kiểm tra xem tài khoản đã tồn tại trong database chưa
            $checkQuery = "SELECT * FROM users WHERE email = ? AND provider = 'google'";
            $stmt = mysqli_prepare($connect, $checkQuery);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                // Đăng nhập nếu tài khoản đã tồn tại
                $user = mysqli_fetch_assoc($result);
                if ($user['status'] == 'active') {
                    $_SESSION['user'] = $user['username'];
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $email; // Lưu email vào session

                    // Chuyển sản phẩm từ session sang database (giữ nguyên logic cũ)
                    if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                        $user_id = $_SESSION['user_id'];
                        foreach ($_SESSION['cart'] as $item) {
                            $checkQuery = "SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND color = ? AND rom = ?";
                            $stmt = mysqli_prepare($connect, $checkQuery);
                            mysqli_stmt_bind_param($stmt, "iiss", $user_id, $item['product_id'], $item['color'], $item['rom']);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);

                            if (mysqli_num_rows($result) > 0) {
                                $cartItem = mysqli_fetch_assoc($result);
                                $newQuantity = $cartItem['quantity'] + $item['quantity'];
                                $updateQuery = "UPDATE cart SET quantity = ? WHERE id = ?";
                                $stmt = mysqli_prepare($connect, $updateQuery);
                                mysqli_stmt_bind_param($stmt, "ii", $newQuantity, $cartItem['id']);
                                mysqli_stmt_execute($stmt);
                            } else {
                                $insertQuery = "INSERT INTO cart (user_id, product_id, product_name, color, rom, price, price_discount, quantity, image) 
                                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                $stmt = mysqli_prepare($connect, $insertQuery);
                                mysqli_stmt_bind_param($stmt, "iisssiiis", $user_id, $item['product_id'], $item['product_name'], $item['color'], $item['rom'], $item['price'], $item['price_discount'], $item['quantity'], $item['image']);
                                mysqli_stmt_execute($stmt);
                            }
                            mysqli_stmt_close($stmt);
                        }
                        unset($_SESSION['cart']);
                    }

                    // Chuyển hướng và truyền dữ liệu qua query string
                    header('Location: index.php?username=' . urlencode($user['username']) . '&email=' . urlencode($email));
                    exit();
                }
            } else {
                // Sử dụng name từ Google làm username, loại bỏ ký tự không hợp lệ
                $username = $userInfo['name']; // Sử dụng trực tiếp name từ Google, giữ nguyên định dạng
                if (empty(trim($username))) {
                    $username = 'google_user_' . time(); // Nếu name rỗng, dùng timestamp
                }
                $password = md5(uniqid()); // Tạo mật khẩu ngẫu nhiên
                $phone = ''; // Có thể yêu cầu thêm thông tin sau

                // Kiểm tra và xử lý trùng lặp username
                $originalUsername = $username;
                $counter = 1;
                while (true) {
                    $checkQuery = "SELECT username FROM users WHERE username = ?";
                    $stmt = mysqli_prepare($connect, $checkQuery);
                    mysqli_stmt_bind_param($stmt, "s", $username);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) == 0) break;
                    $username = $originalUsername . '_' . $counter++;
                    mysqli_stmt_close($stmt);
                }

                // Chuẩn hóa username để phù hợp với database (nếu cần, giữ nguyên tên gốc)
                $username = mysqli_real_escape_string($connect, $username); // Tránh lỗi SQL injection

                $insertQuery = "INSERT INTO users (username, email, password, phone, role, status, provider) VALUES (?, ?, ?, ?, 'user', 'active', 'google')";
                $stmt = mysqli_prepare($connect, $insertQuery);
                mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $password, $phone);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // Lấy user_id vừa tạo
                $userIdQuery = "SELECT id FROM users WHERE email = ? AND provider = 'google'";
                $stmt = mysqli_prepare($connect, $userIdQuery);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_assoc($result);
                $_SESSION['user'] = $username;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $email; // Lưu email vào session
                $_SESSION['name'] = $userInfo['name'];

                header('Location: index.php?username=' . urlencode($username) . '&email=' . urlencode($email));
                exit();
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Không thể lấy thông tin từ Google.'); window.location.href='login1.html';</script>";
        }
    } else {
        echo "<script>alert('Không thể lấy access token từ Google.'); window.location.href='login1.html';</script>";
    }
} else {
    echo "<script>alert('Không có mã xác thực từ Google.'); window.location.href='login1.html';</script>";
}