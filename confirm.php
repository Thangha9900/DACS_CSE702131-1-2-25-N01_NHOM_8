<?php
require_once('layouts/header.php');

$fullname = $_POST['fullname'] ?? ($_SESSION['checkout_data']['fullname'] ?? '');
$email = $_POST['email'] ?? ($_SESSION['checkout_data']['email'] ?? '');
$phone = $_POST['phone'] ?? ($_SESSION['checkout_data']['phone'] ?? '');
$address = $_POST['address'] ?? ($_SESSION['checkout_data']['address'] ?? '');
$note = $_POST['note'] ?? ($_SESSION['checkout_data']['note'] ?? '');

if (!empty($_POST)) {
    $_SESSION['checkout_data'] = [
        'fullname' => $fullname,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'note' => $note
    ];
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['discount'] * $item['num'];
}
?>

<style>
    .container {
        max-width: 1300px;
    }
</style>

<div class="container" style="margin-top: 20px; margin-bottom: 20px;">
    <h2 style="color:#28a745; margin-bottom:30px;">
        XÁC NHẬN ĐƠN HÀNG</h2>
    <div class="row">
        <div class="col-md-6">
            <h4 style="margin-bottom:20px;">Thông tin khách hàng</h4>
            <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($fullname); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($phone); ?></p>
            <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($address); ?></p>
            <p><strong>Ghi chú:</strong> <?php echo nl2br(htmlspecialchars($note)); ?></p>
        </div>
        <div class="col-md-6">
            <h4>Sản phẩm đã chọn</h4>
            <table class="table table-bordered">
                <tr>
                    <th>STT</th>
                    <th>Tiêu Đề</th>
                    <th>Giá</th>
                    <th>Số Lượng</th>
                    <th>Tổng Giá</th>
                </tr>
                <?php
                $index = 0;
                foreach ($_SESSION['cart'] as $item) {
                    echo '<tr>
                            <td>' . (++$index) . '</td>
                            <td>' . htmlspecialchars($item['title']) . '</td>
                            <td>' . number_format($item['discount']) . ' VND</td>
                            <td>' . $item['num'] . '</td>
                            <td>' . number_format($item['discount'] * $item['num']) . ' VND</td>
                        </tr>';
                }
                ?>
                <tr>
                    <th colspan="4">TỔNG TIỀN</th>
                    <th><?php echo number_format($total); ?> VND</th>
                </tr>
            </table>
        </div>
        <div class="col-md-12" style="margin-top: 30px; text-align: center;">
            <button onclick="confirmCheckout()" class="btn btn-success btn-lg" style="margin-right: 15px; padding: 12px 40px;">
                <i class="bi bi-check-circle"></i> XÁC NHẬN THANH TOÁN
            </button>
            <form method="post" action="checkout.php" style="display: inline;">
                <input type="hidden" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                <input type="hidden" name="address" value="<?php echo htmlspecialchars($address); ?>">
                <input type="hidden" name="note" value="<?php echo htmlspecialchars($note); ?>">
                <button type="submit" class="btn btn-secondary btn-lg" style="padding: 12px 40px;">
                    <i class="bi bi-arrow-left"></i> QUAY LẠI
                </button>
            </form>
        </div>
    </div>

    <form id="checkoutForm" style="display: none;">
        <input type="hidden" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
        <input type="hidden" name="address" value="<?php echo htmlspecialchars($address); ?>">
        <input type="hidden" name="note" value="<?php echo htmlspecialchars($note); ?>">
    </form>
</div>

<script type="text/javascript">
    function confirmCheckout() {
        $.post('api/ajax_request.php', {
            'action': 'checkout',
            'fullname': $('[name=fullname]').val(),
            'email': $('[name=email]').val(),
            'phone_number': $('[name=phone]').val(),
            'address': $('[name=address]').val(),
            'note': $('[name=note]').val()
        }, function(data) {
            try {
                var resp = typeof data === 'object' ? data : JSON.parse(data);
                var orderId = resp.orderId;
                if (orderId) {
                    window.open('complete.php?id=' + orderId, '_self');
                } else {
                    alert('Có lỗi khi tạo đơn hàng, vui lòng thử lại.');
                }
            } catch (e) {
                console.error('Invalid response', data);
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        }).fail(function() {
            alert('Có lỗi kết nối, vui lòng thử lại.');
        });
    }
</script>

<?php
require_once('layouts/footer.php');
?>