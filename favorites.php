<?php
require_once('layouts/header.php');
?>

<style>
    .container {
        max-width: 1300px;
    }
</style>

<div class="container" style="margin-top: 20px; margin-bottom: 20px;">
    <div class="row">
        <h2>Danh Sách Yêu Thích</h2>
        <table class="table table-bordered">
            <tr>
                <th>STT</th>
                <th>Thumbnail</th>
                <th>Tiêu Đề</th>
                <th>Giá</th>
                <th>Danh Mục</th>
                <th></th>
            </tr>
            <?php
            if (!isset($_SESSION['favorites'])) {
                $_SESSION['favorites'] = [];
            }
            if (count($_SESSION['favorites']) == 0) {
                echo '<tr><td colspan="6" style="text-align: center; padding: 20px;"><h4>Chưa có sản phẩm yêu thích</h4></td></tr>';
            } else {
                $index = 0;
                foreach ($_SESSION['favorites'] as $item) {
                    echo '<tr>
                            <td>' . (++$index) . '</td>
                            <td><img src="assets/photos/' . $item['thumbnail'] . '" style="height: 80px"/></td>
                            <td><a href="detail.php?id=' . $item['id'] . '">' . $item['title'] . '</a></td>
                            <td>' . number_format($item['discount']) . ' VND</td>
                            <td>' . $item['category_name'] . '</td>
                            <td>
                                <button class="btn btn-success" onclick="addCart(' . $item['id'] . ', 1)" style="margin-bottom: 5px; width: 100%;"><i class="bi bi-cart-plus-fill"></i> Thêm giỏ hàng</button>
                                <button class="btn btn-danger" onclick="removeFavorite(' . $item['id'] . ')" style="width: 100%;">Xoá</button>
                            </td>
                        </tr>';
                }
            }
            ?>
        </table>
    </div>
</div>

<script type="text/javascript">
    function removeFavorite(id) {
        $.post('api/ajax_request.php', {
            'action': 'remove_favorite',
            'id': id
        }, function(data) {
            const response = JSON.parse(data);
            if (response.success) {
                $('.favorite_count').text(response.favoriteCount);
                showNotification('✓ Đã xóa khỏi mục yêu thích!', 'success', 1500);
                setTimeout(() => {
                    location.reload();
                }, 500);
            }
        })
    }
</script>

<?php
require_once('layouts/footer.php');
?>
