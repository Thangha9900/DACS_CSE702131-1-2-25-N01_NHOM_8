<?php
session_start();
require_once('../utils/utility.php');
require_once('../database/dbhelpere.php');

$action = getPost('action');

switch ($action) {
    case 'cart':
        addToCart();
        break;
    case 'update_cart':
        updateCart();
        break;
    case 'checkout':
        checkout();
        break;
    case 'remove_all_cart':
        removeAllCart();
        break;
    case 'favorites':
        addToFavorites();
        break;
    case 'remove_favorite':
        removeFavorite();
        break;
}

function removeAllCart()
{
    unset($_SESSION['cart']);
}

function checkout()
{
    if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
        return;
    }

    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    mysqli_set_charset($conn, 'utf8');

    if (!$conn) {
        die("Database connection error: " . mysqli_connect_error());
    }

    $fullName = mysqli_real_escape_string($conn, getPost("fullname"));
    $email = mysqli_real_escape_string($conn, getPost("email"));
    $phone_number = mysqli_real_escape_string($conn, getPost("phone_number"));
    $address = mysqli_real_escape_string($conn, getPost("address"));
    $note = mysqli_real_escape_string($conn, getPost("note"));

    $user = getUserToken();
    $userId = 0;
    if ($user != null) {
        $userId = $user['id'];
    }

    $orderDate = date('Y-m-d H:i:s');
    $totalMoney = 0;
    foreach ($_SESSION['cart'] as $item) {
        $totalMoney += $item['discount'] * $item['num'];
    }

    $sql = "insert into Orders(user_id, fullname, email, phone_number, address, note, order_date, status, total_money) values ($userId, '$fullName', '$email', '$phone_number', '$address', '$note', '$orderDate', 0, $totalMoney)";
    
    if (!mysqli_query($conn, $sql)) {
        die("Error inserting order: " . mysqli_error($conn));
    }

    $orderId = mysqli_insert_id($conn);
    $_SESSION['last_order_id'] = $orderId;

    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['id'];
        $price = $item['discount'];
        $num = $item['num'];
        $totalMoney = $price * $num;

        $sql = "insert into Order_Details(order_id, product_id, price, num, total_money) values ($orderId, $product_id, $price, $num, $totalMoney)";
        
        if (!mysqli_query($conn, $sql)) {
            die("Error inserting order details: " . mysqli_error($conn));
        }
    }

    mysqli_close($conn);

    echo json_encode(['orderId' => $orderId]);

    unset($_SESSION['cart']);
}

function updateCart()
{
    $id = getPost('id');
    $num = getPost('num');

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['id'] == $id) {
            if ($num <= 0) {
                array_splice($_SESSION['cart'], $i, 1);
            } else {
                $_SESSION['cart'][$i]['num'] = $num;
            }
            break;
        }
    }

    $count = 0;
    foreach ($_SESSION['cart'] as $item)
        $count += $item['num'];

    echo json_encode([
        'success' => true,
        'cartCount' => $count
    ]);
}

function addToCart()
{
    $id = getPost('id');
    $num = getPost('num');

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $isFind = false;
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['id'] == $id) {
            $_SESSION['cart'][$i]['num'] += $num;
            $isFind = true;
            break;
        }
    }

    if (!$isFind) {
        $sql = "select product.*, category.name as category_name
            from product left join category on
            product.category_id = category.id where product.id = $id";
        $product = executeResult($sql, true);
        $product['num'] = $num;
        $_SESSION['cart'][] = $product;
    }

    $count = 0;
    foreach ($_SESSION['cart'] as $item)
        $count += $item['num'];

    echo json_encode([
        'success' => true,
        'cartCount' => $count
    ]);
}

function addToFavorites()
{
    $id = getPost('id');

    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }

    $isFind = false;
    for ($i = 0; $i < count($_SESSION['favorites']); $i++) {
        if ($_SESSION['favorites'][$i]['id'] == $id) {
            $isFind = true;
            break;
        }
    }

    if (!$isFind) {
        $sql = "select product.*, category.name as category_name
            from product left join category on
            product.category_id = category.id where product.id = $id";
        $product = executeResult($sql, true);
        $_SESSION['favorites'][] = $product;
    }

    $count = count($_SESSION['favorites']);

    echo json_encode([
        'success' => true,
        'favoriteCount' => $count
    ]);
}

function removeFavorite()
{
    $id = getPost('id');

    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }

    for ($i = 0; $i < count($_SESSION['favorites']); $i++) {
        if ($_SESSION['favorites'][$i]['id'] == $id) {
            array_splice($_SESSION['favorites'], $i, 1);
            break;
        }
    }

    $count = count($_SESSION['favorites']);

    echo json_encode([
        'success' => true,
        'favoriteCount' => $count
    ]);
}
