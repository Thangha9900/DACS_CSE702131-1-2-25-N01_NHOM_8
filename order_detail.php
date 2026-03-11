<?php
require_once('layouts/header.php');

$user = getUserToken();
if ($user == null) {
    header('Location: admin/authen/login.php');
    exit;
}

$sql = "SELECT u.* FROM User u 
        INNER JOIN Tokens t ON u.id = t.user_id 
        WHERE t.token = '{$_COOKIE['token']}' LIMIT 1";
$currentUser = executeResult($sql, true);

if ($currentUser == null) {
    header('Location: admin/authen/login.php');
    exit;
}

$userId = $currentUser['id'];
$orderId = getGet('id');

$sql = "SELECT * FROM Orders WHERE id = $orderId AND user_id = $userId";
$order = executeResult($sql, true);

if ($order == null) {
    echo '<div class="container" style="margin-top: 20px;">
        <div class="alert alert-danger">Không tìm thấy đơn hàng!</div>
    </div>';
    require_once('layouts/footer.php');
    exit;
}

$sql = "SELECT od.*, p.title, p.thumbnail FROM Order_Details od
        LEFT JOIN Product p ON p.id = od.product_id
        WHERE od.order_id = $orderId";
$items = executeResult($sql);

$statusMap = [
    0 => ['text' => 'Chờ xử lý', 'color' => 'warning', 'icon' => 'clock'],
    1 => ['text' => 'Đã phê duyệt', 'color' => 'success', 'icon' => 'check-circle'],
    2 => ['text' => 'Đã hủy', 'color' => 'danger', 'icon' => 'x-circle']
];

$currentStatus = $statusMap[$order['status']];
?>


<style>
    .container {
        max-width: 1300px;
    }
</style>
<div class="container" style="margin-top: 20px; margin-bottom: 40px;">
    <div class="row">
        <div class="col-md-12">
            <a href="my_orders.php" class="btn btn-outline-secondary btn-sm" style="margin-bottom: 20px;">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <h2 style="margin-bottom: 20px;">
                <i class="bi bi-receipt"></i> Chi Tiết Đơn Hàng #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header" style="background-color: #28a745; color: white;">
                    <h5 style="margin: 0;">Thông Tin Sản Phẩm</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th>STT</th>
                                <th>Hình Ảnh</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Giá</th>
                                <th>Số Lượng</th>
                                <th>Tổng Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $index = 0;
                            foreach ($items as $item) {
                                $imagePath = 'assets/photos/' . htmlspecialchars($item['thumbnail']);
                                echo '<tr>
                                    <td>' . (++$index) . '</td>
                                    <td>
                                        <img src="' . $imagePath . '" style="height: 80px; object-fit: cover;" alt="Product">
                                    </td>
                                    <td>' . $item['title'] . '</td>
                                    <td>' . number_format($item['price'], 0, ',', '.') . ' VND</td>
                                    <td class="text-center">' . $item['num'] . '</td>
                                    <td><strong>' . number_format($item['total_money'], 0, ',', '.') . ' VND</strong></td>
                                </tr>';
                            }
                            ?>
                            <tr style="background-color: #f8f9fa; font-weight: bold;">
                                <td colspan="5" class="text-right">TỔNG TIỀN:</td>
                                <td>
                                    <span style="font-size: 18px; color: #28a745;">
                                        <?= number_format($order['total_money'], 0, ',', '.') ?> VND
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header" style="background-color: #28a745; color: white;">
                    <h5 style="margin: 0;">Trạng Thái Đơn Hàng</h5>
                </div>
                <div class="card-body text-center">
                    <div style="font-size: 48px; margin: 20px 0;">
                        <i class="bi bi-<?= $currentStatus['icon'] ?>" style="color: #<?= ($currentStatus['color'] == 'success' ? '28a745' : ($currentStatus['color'] == 'danger' ? 'dc3545' : 'ffc107')) ?>"></i>
                    </div>
                    <h4>
                        <span class="badge badge-<?= $currentStatus['color'] ?>" style="font-size: 16px; padding: 10px 20px;">
                            <?= $currentStatus['text'] ?>
                        </span>
                    </h4>
                    <p style="color: #666; margin-top: 15px;">
                        <small>
                            <?php
                            if ($order['status'] == 0) {
                                echo "Đơn hàng của bạn đang chờ được xác nhận. Vui lòng chờ xử lý từ cửa hàng.";
                            } elseif ($order['status'] == 1) {
                                echo "Đơn hàng đã được phê duyệt. Sản phẩm sẽ được chuẩn bị giao hàng.";
                            } else {
                                echo "Đơn hàng đã bị hủy.";
                            }
                            ?>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <!-- Invoice Actions Card -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header" style="background-color: #28a745; color: white;">
                    <h5 style="margin: 0;">Hóa Đơn</h5>
                </div>
                <div class="card-body">
                    <p style="color: #666; margin-bottom: 15px; font-size: 14px;">
                        <i class="bi bi-info-circle"></i> Bạn có thể xem, in hoặc tải hóa đơn dưới dây.
                    </p>
                    <div style="display: grid; gap: 10px;">
                        <a href="invoice.php?id=<?= $orderId ?>" class="btn btn-info" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; border: none; border-radius: 4px; color: white; cursor: pointer; background-color: #17a2b8;">
                            <i class="bi bi-eye"></i> Xem Hóa Đơn
                        </a>
                        <button onclick="window.open('invoice.php?id=<?= $orderId ?>', '_blank'); window.print();" class="btn btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; border: none; border-radius: 4px; color: white; cursor: pointer; background-color: #007bff;">
                            <i class="bi bi-printer"></i> In Hóa Đơn
                        </button>
                        <button onclick="downloadInvoicePDF(<?= $orderId ?>)" class="btn btn-success" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; border: none; border-radius: 4px; color: white; cursor: pointer; background-color: #28a745;">
                            <i class="bi bi-download"></i> Tải PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background-color: #28a745; color: white;">
                    <h5 style="margin: 0;">Thông Tin Giao Hàng</h5>
                </div>
                <div class="card-body">
                    <table style="width: 100%; line-height: 30px;">
                        <tr>
                            <th style="text-align: left; width: 120px;">Tên:</th>
                            <td><?= htmlspecialchars($order['fullname']) ?></td>
                        </tr>
                        <tr>
                            <th style="text-align: left;">Email:</th>
                            <td><?= htmlspecialchars($order['email']) ?></td>
                        </tr>
                        <tr>
                            <th style="text-align: left;">Điện Thoại:</th>
                            <td><?= htmlspecialchars($order['phone_number']) ?></td>
                        </tr>
                        <tr>
                            <th style="text-align: left;">Địa Chỉ:</th>
                            <td><?= htmlspecialchars($order['address']) ?></td>
                        </tr>
                        <tr>
                            <th style="text-align: left;">Ngày Đặt:</th>
                            <td><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></td>
                        </tr>
                        <?php if (!empty($order['note'])): ?>
                        <tr>
                            <th style="text-align: left;">Ghi Chú:</th>
                            <td><?= htmlspecialchars($order['note']) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadInvoicePDF(orderId) {
    // Mở hóa đơn và gọi hàm in
    const invoiceWindow = window.open('invoice.php?id=' + orderId, '_blank');
    invoiceWindow.addEventListener('load', function() {
        setTimeout(() => {
            invoiceWindow.print();
        }, 500);
    });
}
</script>

<?php
require_once('layouts/footer.php');
?>
