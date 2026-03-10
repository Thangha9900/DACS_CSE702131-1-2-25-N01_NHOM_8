<?php
require_once('layouts/header.php');

$fullname = $_POST['fullname'] ?? ($_SESSION['checkout_data']['fullname'] ?? '');
$email = $_POST['email'] ?? ($_SESSION['checkout_data']['email'] ?? '');
$phone = $_POST['phone'] ?? ($_SESSION['checkout_data']['phone'] ?? '');
$address = $_POST['address'] ?? ($_SESSION['checkout_data']['address'] ?? '');
$note = $_POST['note'] ?? ($_SESSION['checkout_data']['note'] ?? '');
?>

<style>
    .container {
        max-width: 1300px;
    }
</style>

<div class="container" style="margin-top: 20px; margin-bottom: 20px;">
    <form method="post" action="confirm.php">
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-6">
                <h3>Thông tin khách hàng</h3>
                <div class="form-group">
                    <input required="true" type="text" class="form-control" id="usr" name="fullname" placeholder="Nhập họ * tên" value="<?php echo htmlspecialchars($fullname); ?>">
                </div>
                <div class="form-group">
                    <input required="true" type="email" class="form-control" id="email" name="email" placeholder="Nhập email" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="form-group">
                    <input required="true" type="tel" class="form-control" id="phone" name="phone" placeholder="Nhập sdt" value="<?php echo htmlspecialchars($phone); ?>">
                </div>
                <div class="form-group">
                    <input required="true" type="text" class="form-control" id="address" name="address" placeholder="Nhập địa chỉ" value="<?php echo htmlspecialchars($address); ?>">
                </div>
                <div class="form-group">
                    <label for="pwd">Nội Dung:</label>
                    <textarea class="form-control" rows="3" name="note"><?php echo htmlspecialchars($note); ?></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>STT</th>
                        <th>Tiêu Đề</th>
                        <th>Giá</th>
                        <th>Số Lượng</th>
                        <th>Tổng Giá</th>
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
                            <td>' . $item['title'] . '</td>
                            <td>' . number_format($item['discount']) . ' VND</td>
                            <td>
                                ' . $item['num'] . '
                            </td>
                            <td>' . number_format($item['discount'] * $item['num']) . ' VND</td>
                        </tr>';
                    }
                    ?>

                    <tr>
                        <th colspan="4" style="text-align: right;">TỔNG TIỀN</th>
                        <th><?= number_format($total) ?> VND</th>
                    </tr>
                </table>
                <div style="display: flex; gap: 10px;margin-top: 80px;">
                    <a href="cart.php" class="btn btn-secondary"
                        style="border-radius:0px; font-size:22px; flex:1; display:flex; align-items:center; justify-content:center;">
                        QUAY LẠI
                    </a>
                    <button type="submit" class="btn btn-success" style="border-radius: 0px; font-size: 26px; flex: 2;">
                        THANH TOÁN</button>
                </div>
            </div>
        </div>
    </form>
</div>



<?php
require_once('layouts/footer.php');
?>