<?php
$title = "Thong tin chi tiet dơn hang";
$baseUrl = "../";
require_once("../layouts/header.php");

$orderId = getGet('id');

$sql = "select Order_Details.*, Product.title,
     Product.thumbnail from Order_Details left join Product on 
     Product.id = Order_Details.product_id where 
     Order_Details.order_id = $orderId";

$data = executeResult($sql);

$sql = "select * from Orders where id = $orderId";
$orderItem = executeResult($sql, true);
?>

<div class="row">
    <div class="col-md-11">
        <h2>Chi tiết đơn hàng</h2>
    </div>

    <div class="col-md-8">

        <table class="table table-bordered table-hover" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Thumbnail</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Giá</th>
                    <th>Số Lượng</th>
                    <th>Tổng Giá</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $index = 0;
                foreach ($data as $item) {
                    echo '<tr>
                            <th>' . (++$index) . '</th>
                            <td><img src="../../assets/photos/' . htmlspecialchars($item['thumbnail']) . '" style="height: 120px"/></td>
                            <td>' . $item['title'] . '</td>
                            <td>' . number_format($item['price']) . '  VND</td>
                            <td>' . $item['num'] . '</td>
                            <td>' . number_format($item['total_money']) . '  VND</td>
                        </tr>';
                }
                ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th>TỔNG TIỀN</th>
                    <th><?= number_format($orderItem['total_money']) ?>  VND</th>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <table class="table table-bordered table-hover" style="margin-top: 20px;">
            <tr>
                <th>Họ & Tên: </th>
                <td><?= $orderItem['fullname'] ?></td>
            </tr>
            <tr>
                <th>Email: </th>
                <td><?= $orderItem['email'] ?></td>
            </tr>
            <tr>
                <th>Địa Chỉ: </th>
                <td><?= $orderItem['address'] ?></td>
            </tr>
            <tr>
                <th>Phone: </th>
                <td><?= $orderItem['phone_number'] ?></td>
            </tr>
        </table>
    </div>

</div>


<?php
require_once("../layouts/footer.php");
?>