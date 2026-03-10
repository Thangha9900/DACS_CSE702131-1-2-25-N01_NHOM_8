<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('utils/utility.php');
require_once('database/dbhelpere.php');

$user = getUserToken();
if ($user != null && $user['role_id'] == 1 && strpos($_SERVER['REQUEST_URI'], '/admin/') === false && $_SERVER['REQUEST_METHOD'] == 'GET') {
    header('Location: admin/index.php');
    die();
}

$sql = "select * from Category";
$menuItems = executeResult($sql);

$sql = "select Product.*, Category.name as category_name from 
Product left join Category on Product.category_id = 
Category.id order by Product.updated_at desc limit 0,8";
$lastestItems = executeResult($sql);

// Tính toán số lượng giỏ hàng và yêu thích
$cart_count = 0;
$favorites_count = 0;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

foreach ($_SESSION['cart'] as $item) {
    $cart_count += $item['num'];
}
$favorites_count = count($_SESSION['favorites']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="assets/photos/logo.jpg">
    <title>Trang Chủ </title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">
    
    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="assets/css/header.css">
</head>

<body>
    <!-- Menu START -->
    <div class="container" style="position: relative;">
        <ul class="nav">
            <li class="nav-item" style="margin-top: 0px !important;">
                <a href="index.php">
                    <img src="assets/photos/logo.jpg" style="height:100px;">
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php">Trang Chủ</a>
            </li>

            <?php
            foreach ($menuItems as $item) {
                echo '<li class="nav-item">
            <a class="nav-link" href="category.php?id=' . $item['id'] . '">' . $item['name'] . '</a>
          </li>';
            }
            ?>

            <li class="nav-item">
                <a class="nav-link" href="contact.php">PHẢN HỒI</a>
            </li>

            <?php
            if (isset($_SESSION['user'])) {
                echo '<li class="nav-item">
                    <a class="nav-link" href="my_orders.php" style="color: #ff6b6b;">ĐƠN HÀNG CỦA TÔI</a>
                </li>';
            }
            ?>

        </ul>

        <!-- Search Bar START -->
        <div class="search-form-container" style="margin-top: 15px; margin-bottom: 20px;">
            <form action="search.php" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <input type="text" name="keyword" id="search-input" placeholder="Tìm kiếm sản phẩm..." style="flex: 1; min-width: 200px; padding: 10px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" autocomplete="off">
                <button type="submit" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;"><i class="bi bi-search"></i> Tìm kiếm</button>
                <a href="favorites.php" style="position: relative; padding: 10px 20px; background-color: #ff6b6b; color: white; border-radius: 4px; text-decoration: none; display: flex; align-items: center; gap: 5px;"><i class="bi bi-heart-fill"></i> Yêu thích <span class="badge-favorites" style="position: absolute; top: -8px; right: -8px; background-color: #fff; color: #ff6b6b; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;"><?php echo $favorites_count; ?></span></a>
                <a href="cart.php" style="position: relative; padding: 10px 20px; background-color: #17a2b8; color: white; border-radius: 4px; text-decoration: none; display: flex; align-items: center; gap: 5px;"><i class="bi bi-cart-fill"></i> Giỏ hàng <span class="badge-cart" style="position: absolute; top: -8px; right: -8px; background-color: #fff; color: #17a2b8; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;"><?php echo $cart_count; ?></span></a>
            </form>
            <div id="autocomplete-results" class="autocomplete-results"></div>
        </div>
        <!-- Search Bar END -->

        <?php
        if (isset($_SESSION['user'])) {
            echo '<div class="user-info" style="margin-top:20px;">
                    <i class="bi bi-person-circle" style="font-size: 24px; color: #28a745;"></i>
                    <a href="my_profile.php" style="color: #28a745; text-decoration: none; font-weight: bold; font-size: 18px;">' . $_SESSION['user']['fullname'] . '</a>
                    <a href="admin/authen/logout.php" style="color: red; margin-left: 5px;">Đăng Xuất</a>
                </div>';
        } else {
            echo '<div class="auth-section" style="margin-top:20px;">
                    <a href="admin/authen/login.php" class="btn-login">Đăng Nhập</a>
                    <a href="admin/authen/register.php" class="btn-register">Đăng Ký</a>
                </div>';
        }
        ?>
    </div>