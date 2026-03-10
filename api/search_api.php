<?php
session_start();
require_once('../utils/utility.php');
require_once('../database/dbhelpere.php');

header('Content-Type: application/json');

$action = getPost('action');

switch ($action) {
    case 'search_products':
        searchProducts();
        break;
    case 'search_suggest':
        searchSuggest();
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}

function searchProducts() {
    $keyword = getPost('keyword');
    
    if ($keyword == null || $keyword == '') {
        echo json_encode([
            'status' => 'success',
            'data' => [],
            'count' => 0
        ]);
        return;
    }

    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    mysqli_set_charset($conn, 'utf8');

    if (!$conn) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection error']);
        return;
    }

    $keyword_search = mysqli_real_escape_string($conn, $keyword);
    $sql = "select Product.id, Product.title, Product.thumbnail, Product.discount, Category.name as category_name from
        Product left join Category on Product.category_id =
        Category.id where Product.title LIKE '%$keyword_search%' 
        OR Product.description LIKE '%$keyword_search%'
        OR Category.name LIKE '%$keyword_search%'
        order by Product.updated_at desc limit 0,20";
    
    $result = mysqli_query($conn, $sql);
    $data = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    mysqli_close($conn);

    echo json_encode([
        'status' => 'success',
        'data' => $data,
        'count' => count($data)
    ]);
}

function searchSuggest() {
    $keyword = getPost('keyword');
    
    if ($keyword == null || strlen($keyword) < 2) {
        echo json_encode([
            'status' => 'success',
            'suggestions' => []
        ]);
        return;
    }

    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    mysqli_set_charset($conn, 'utf8');

    if (!$conn) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection error']);
        return;
    }

    $keyword_search = mysqli_real_escape_string($conn, $keyword);
    $sql = "select distinct Product.title from Product 
        where Product.title LIKE '%$keyword_search%'
        limit 0,10";
    
    $result = mysqli_query($conn, $sql);
    $suggestions = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $suggestions[] = $row['title'];
        }
    }

    mysqli_close($conn);

    echo json_encode([
        'status' => 'success',
        'suggestions' => $suggestions
    ]);
}
?>
