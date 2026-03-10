<?php
require_once('layouts/header.php');
?>

<!-- Social Media Sidebar -->
<div class="social-sidebar">
    <a href="https://www.facebook.com" target="_blank" class="social-icon facebook" title="Facebook">
        <i class="bi bi-facebook"></i>
    </a>
    <a href="https://zalo.me" target="_blank" class="social-icon zalo" title="Zalo">
        <i class="bi bi-chat-fill"></i>
    </a>
    <a href="https://www.instagram.com" target="_blank" class="social-icon instagram" title="Instagram">
        <i class="bi bi-instagram"></i>
    </a>
</div>

<link rel="stylesheet" href="assets/css/homepage.css">

<div class="container">

    <div id="demo" class="carousel slide" data-ride="carousel">

        <!-- Indicators -->
        <ul class="carousel-indicators">
            <li data-target="#demo" data-slide-to="0" class="active"></li>
            <li data-target="#demo" data-slide-to="1"></li>
            <li data-target="#demo" data-slide-to="2"></li>
        </ul>

        <!-- slideshow -->
        <div class="carousel-inner">

            <div class="carousel-item active">
                <img src="assets/photos/banner1.jpg" class="banner-img">
            </div>

            <div class="carousel-item">
                <img src="assets/photos/banner2.jpg" class="banner-img">
            </div>

            <div class="carousel-item">
                <img src="assets/photos/banner3.jpg" class="banner-img">
            </div>

        </div>

        <a class="carousel-control-prev" href="#demo" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>

        <a class="carousel-control-next" href="#demo" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>

    </div>

</div>
<!-- banner top -->
<div class="container">
    <h1 class="section-title">
        SẢN PHẨM MỚI NHẤT</h1>
    <div class="row">
        <?php
        foreach ($lastestItems as $item) {
            echo '<div class="col-md-3 col-6 product-item">
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <span class="product-badge">MỚI</span>
                        <img src="assets/photos/' . $item['thumbnail'] . '">
                        <div class="product-overlay">
                            <a href="detail.php?id=' . $item['id'] . '"><i class="bi bi-eye"></i> XEM NGAY</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <p class="product-category">' . $item['category_name'] . '</p>
                        <p class="product-title">' . $item['title'] . '</p>
                        <p class="product-price">' . number_format($item['discount']) . ' VND</p>
                        <button class="btn btn-success add-to-cart-btn" onclick="addCart(' . $item['id'] . ', 1)"><i class="bi bi-cart-plus-fill"></i> Thêm giỏ hàng</button>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>
</div>

<!-- danh muc san pham -->
<?php
$i = 0;

foreach ($menuItems as $item) {

    $sql = "select product.*, category.name as category_name from
            product left join category on product.category_id = category.id
            where product.category_id = " . $item['id'] . "
            order by product.updated_at desc limit 0,4";

    $items = executeResult($sql);

    if ($items == null || count($items) == 0) continue;

    $sectionClass = ($i % 2 == 0) ? "light" : "dark";

    echo '<div class="category-section ' . $sectionClass . '">
            <div class="container">
            <h3 class="category-title" style="magin">' . $item['name'] . '</h3>
            <div class="row">';

    foreach ($items as $pItem) {
        echo '<div class="col-md-3 col-6 product-item">
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img src="assets/photos/' . $pItem['thumbnail'] . '">
                        <div class="product-overlay">
                            <a href="detail.php?id=' . $pItem['id'] . '"><i class="bi bi-eye"></i> XEM NGAY</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <p class="product-category">' . $pItem['category_name'] . '</p>
                        <p class="product-title">' . $pItem['title'] . '</p>
                        <p class="product-price">' . number_format($pItem['discount']) . ' VND</p>
                        <button class="btn btn-success add-to-cart-btn" onclick="addCart(' . $pItem['id'] . ', 1)"><i class="bi bi-cart-plus-fill"></i> Thêm giỏ hàng</button>
                    </div>
                </div>
              </div>';
    }

    echo '</div></div></div>';

    $i++;
}
?>
<?php
?>

<?php
require_once('layouts/footer.php');
?>