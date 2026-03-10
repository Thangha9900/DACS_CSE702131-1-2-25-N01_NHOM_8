<footer style="background-color:#000;color:white;padding-top:40px;">
    <div class="container">
        <div class="row">

            <div class="col-md-4">
                <h4 style="margin-bottom:15px;font-weight:bold;">GIỚI THIỆU</h4>
                <ul style="list-style:none;padding:0;line-height:28px;">
                    <li><b>ABCD GROUP</b></li>
                    <li>
                        <a href="#" style="color:white; text-decoration:none;">
                            <i class="bi bi-envelope-fill"></i> support@abcdgroup.com
                        </a>
                    </li>

                    <li>
                        <a href="#" style="color:white; text-decoration:none;">
                            <i class="bi bi-telephone-fill"></i> 0988 888 888
                        </a>
                    </li>

                    <li>
                        <a href="#" style="color:white; text-decoration:none;">
                            <i class="bi bi-geo-alt-fill"></i> Ha Noi, Viet Nam
                        </a>
                    </li>
                    <li style="margin-top:10px;">
                        ABCD Group chuyên cung cấp các giải pháp website và thương mại điện tử
                        cho doanh nghiệp và cửa hàng. Chúng tôi luôn nỗ lực mang đến sản phẩm
                        chất lượng và trải nghiệm tốt nhất cho khách hàng.
                    </li>
                </ul>
            </div>

            <div class="col-md-4">
                <h4 style="margin-bottom:15px;font-weight:bold;">SẢN PHẨM MỚI</h4>
                <ul style="list-style:none;padding:0;line-height:28px;">
                    <li>Áo thun thời trang</li>
                    <li>Quần jean nam nữ</li>
                    <li>Áo polo cao cấp</li>
                    <li>Thời trang cho trẻ em</li>
                    <li>Combo khuyến mãi mùa hè</li>
                </ul>
            </div>

            <div class="col-md-4">
                <h4 style="margin-bottom:15px;font-weight:bold;">TIN TỨC</h4>
                <ul style="list-style:none;padding:0;line-height:28px;">
                    <li>Xu hướng thời trang 2026</li>
                    <li>Cách phối đồ đơn giản</li>
                    <li>Mẹo bảo quản quần áo</li>
                    <li>Khuyến mãi cuối năm</li>
                    <li>Top sản phẩm bán chạy</li>
                </ul>
            </div>

        </div>
    </div>

    <div style="background-color:#222;text-align:center;padding:15px;margin-top:30px;">
        © 2025 ABCD GROUP - Thiết kế website thương mại điện tử. All rights reserved.
    </div>
</footer>

<?php
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}
$count = 0;
// var_dump($_SESSION['cart']);
foreach ($_SESSION['cart'] as $item)
    $count += $item['num'];
$favor_count = count($_SESSION['favorites']);
?>

<style>
    .notification-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }

    .notification {
        padding: 15px 20px;
        margin-bottom: 10px;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease-out;
        word-wrap: break-word;
    }

    .notification.success {
        background-color: #28a745;
        color: white;
        border-left: 4px solid #218838;
    }

    .notification.error {
        background-color: #dc3545;
        color: white;
        border-left: 4px solid #c82333;
    }

    .notification.info {
        background-color: #17a2b8;
        color: white;
        border-left: 4px solid #117a8b;
    }

    .notification.warning {
        background-color: #ffc107;
        color: #333;
        border-left: 4px solid #e0a800;
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    .notification.hide {
        animation: slideOut 0.3s ease-out forwards;
    }
</style>

<div id="notificationContainer" class="notification-container"></div>

<script class="text/javascript">
    function showNotification(message, type = 'success', duration = 3000) {
        const container = document.getElementById('notificationContainer');
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const icon = {
            'success': '<i class="bi bi-check-circle"></i>',
            'error': '<i class="bi bi-exclamation-circle"></i>',
            'info': '<i class="bi bi-info-circle"></i>',
            'warning': '<i class="bi bi-exclamation-triangle"></i>'
        };
        
        notification.innerHTML = `${icon[type] || ''} ${message}`;
        container.appendChild(notification);

        if (duration > 0) {
            setTimeout(() => {
                notification.classList.add('hide');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, duration);
        }
    }

    function addCart(productId, num) {
        $.post('api/ajax_request.php', {
            'action': 'cart',
            'id': productId,
            'num': num
        }, function(data) {
            const response = JSON.parse(data);
            if (response.success) {
                $('.cart_count').text(response.cartCount);
                showNotification('✓ Đã thêm vào giỏ hàng!', 'success');
            }
        })
    }

    function addFavorite(productId) {
        $.post('api/ajax_request.php', {
            'action': 'favorites',
            'id': productId
        }, function(data) {
            const response = JSON.parse(data);
            if (response.success) {
                $('.favorite_count').text(response.favoriteCount);
                $('.badge-favorites').text(response.favoriteCount);
                showNotification('❤ Đã thêm vào mục yêu thích!', 'success');
            }
        })
    }
</script>

<!-- Cart start -->

<span class="cart_icon">
    <span class="cart_count"><?= $count ?></span>
    <a href="cart.php">
    <img src="assets/photos/giohang.jpg" style="border-radius:50%;">
</a>
</span>

<!-- Cart end -->

<!-- Search Script -->
<script src="assets/js/search.js"></script>

</body>

</html>