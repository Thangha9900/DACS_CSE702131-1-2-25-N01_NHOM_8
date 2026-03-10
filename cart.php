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
        <table class="table table-bordered">
            <tr>
                <th>STT</th>
                <th>Thumbnail</th>
                <th>Tiêu Đề</th>
                <th>Giá</th>
                <th>Số Lượng</th>
                <th>Tổng Giá</th>
                <th></th>
            </tr>
            <?php
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            $index = 0;
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['discount'] * $item['num'];
                echo '<tr>
                        <td>' . (++$index) . '</td>
                        <td><img src="assets/photos/' . $item['thumbnail'] . '" style="height: 80px"/></td>
                        <td>' . $item['title'] . '</td>
                        <td>' . number_format($item['discount']) . ' VND</td>
                        <td style="display: flex"><button class="btn btn-light" style="border: solid #e0dede 1px; border-radius: 0px;" onclick="addMoreCart(' . $item['id'] . ', -1)">-</button>
                        <input type="number" id="num_' . $item['id'] . '" value="' . $item['num'] . '" class="form-control" style="width: 90px; border-radius: 0px" onchange="fixCartNum(' . $item['id'] . ')"/>
                        <button class="btn btn-light" style="border: solid #e0dede 1px; border-radius: 0px;" onclick="addMoreCart(' . $item['id'] . ', 1)">+</button>
                        </td>
                        <td>' . number_format($item['discount'] * $item['num']) . ' VND</td>
                        <td><button class="btn btn-danger" onclick="updateCart(' . $item['id'] . ', 0)">Xoá</button></td>
                    </tr>';
            }
            ?>

            <tr>
                <th colspan="5" style="text-align: right;">TỔNG TIỀN</th>
                <th><?= number_format($total) ?> VND</th>
                <td></td>
            </tr>

        </table>
    </div>
    <div style="margin-top: 10px; margin-bottom: 40px;" class="row">
        <div style="width: 100%;">
            <a href="checkout.php">
                <button class="btn btn-success" style="border-radius: 0px; font-size: 22px; float: left; margin: 0px;">
                    TIẾP TỤC THANH TOÁN
                </button>
            </a>

            <a href="#remove_cart">
                <button class="btn btn-secondary" style="border-radius: 0px; font-size: 22px; float: right;" onclick="removeAllCart()">
                    XOÁ GIỎ HÀNG
                </button>
            </a>
        </div>
    </div>
</div>

<script type="text/javascript">
    function addMoreCart(id, delta) {
        num = parseInt($('#num_' + id).val())
        num += delta
        $('#num_' + id).val(num)

        updateCart(id, num);
    }

    function fixCartNum(id) {
        $('#num_' + id).val(Math.abs($('#num_' + id).val()))

        updateCart(id, $('#num_' + id).val());
    }

    function removeAllCart() {
        option = confirm('Bạn có chắc chắn muốn xóa giỏ hàng này không?')
        if (option) {
            $.post('api/ajax_request.php', {
                'action': 'remove_all_cart'
            }, function(data) {
                showNotification('✓ Giỏ hàng đã được xóa!', 'success', 2000);
                setTimeout(() => {
                    location.reload()
                }, 500);
            })
        }
    }

    function updateCart(productId, num) {
        $.post('api/ajax_request.php', {
            'action': 'update_cart',
            'id': productId,
            'num': num
        }, function(data) {
            const response = JSON.parse(data || '{}');
            if (response.success) {
                $('.cart_count').text(response.cartCount);
                if (num == 0) {
                    showNotification('✓ Sản phẩm đã được xóa khỏi giỏ hàng!', 'success', 1500);
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showNotification('✓ Cập nhật giỏ hàng thành công!', 'success', 2000);
                }
            }
        })
    }
</script>

<?php
require_once('layouts/footer.php');
?>