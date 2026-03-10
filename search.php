<?php
require_once('layouts/header.php');

$keyword = getGet('keyword');

if ($keyword == null || $keyword == '') {
    $lastestItems = array();
} else {
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    mysqli_set_charset($conn, 'utf8');
    
    if (!$conn) {
        die("Database connection error: " . mysqli_connect_error());
    }
    
    $keyword_search = mysqli_real_escape_string($conn, $keyword);
    $sql = "select Product.*, Category.name as category_name from
        Product left join Category on Product.category_id =
        Category.id where Product.title LIKE '%$keyword_search%' 
        OR Product.description LIKE '%$keyword_search%'
        OR Category.name LIKE '%$keyword_search%'
        order by Product.updated_at desc limit 0,12";
    $lastestItems = executeResult($sql);
    mysqli_close($conn);
}
?>
<link rel="stylesheet" href="assets/css/products.css">
<link rel="stylesheet" href="assets/css/search.css">

<div class="container" style="margin-top: 30px; margin-bottom: 20px;">
    <div class="search-header">
        <h2>Kết quả tìm kiếm cho: <span class="keyword-highlight"><?php echo htmlspecialchars($keyword); ?></span></h2>
        <p class="search-count">
            <?php
            if (count($lastestItems) > 0) {
                echo 'Tìm thấy <strong>' . count($lastestItems) . '</strong> sản phẩm';
            } else {
                if ($keyword != '') {
                    echo 'Không tìm thấy sản phẩm nào';
                }
            }
            ?>
        </p>
    </div>

    <?php
    if ($keyword == '' || $keyword == null) {
        ?>
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle"></i> Vui lòng nhập từ khóa để tìm kiếm sản phẩm
        </div>
        <?php
    } elseif (count($lastestItems) == 0) {
        ?>
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-exclamation-triangle"></i> Không tìm thấy sản phẩm phù hợp với từ khóa "<strong><?php echo htmlspecialchars($keyword); ?></strong>"
        </div>
        <?php
    } else {
        ?>
        <div class="row">
            <?php
            foreach ($lastestItems as $item) {
                echo '<div class="col-md-3 col-6 product-item">
                    <div class="product-card">
                        <div class="product-image-wrapper">
                            <img src="assets/photos/' . $item['thumbnail'] . '">
                            <div class="product-overlay">
                                <a href="detail.php?id=' . $item['id'] . '"><i class="bi bi-eye"></i> XEM NGAY</a>
                            </div>
                        </div>
                        <div class="product-info">
                            <p class="product-category">' . $item['category_name'] . '</p>
                            <a href="detail.php?id=' . $item['id'] . '" class="product-title">' . $item['title'] . '</a>
                            <p class="product-price">' . number_format($item['discount']) . ' VND</p>
                            <button class="btn btn-success add-to-cart-btn" onclick="addCart(' . $item['id'] . ', 1)"><i class="bi bi-cart-plus-fill"></i> Thêm giỏ hàng</button>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>

<?php
require_once('layouts/footer.php');
?>
