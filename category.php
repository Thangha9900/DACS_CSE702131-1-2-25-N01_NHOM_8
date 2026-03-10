<?php
require_once('layouts/header.php');

$category_id = getGet('id');

if ($category_id == null || $category_id == '') {
    $sql = "select Product.*, Category.name as category_name from
        Product left join Category on Product.category_id =
        Category.id order by Product.updated_at desc limit 0,12";
} else {
    $sql = "select Product.*, Category.name as category_name from
        Product left join Category on Product.category_id =
        Category.id where Product.category_id = $category_id
        order by Product.updated_at desc limit 0,12";
}

$lastestItems = executeResult($sql);
?>
<link rel="stylesheet" href="assets/css/products.css">

<div class="container" style="margin-top: 20px; margin-bottom: 20px;">
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
</div>

<?php
require_once('layouts/footer.php');
?>