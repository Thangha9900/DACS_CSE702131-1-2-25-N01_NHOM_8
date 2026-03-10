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

$sql = "SELECT * FROM Orders WHERE user_id = $userId ORDER BY order_date DESC";
$orders = executeResult($sql);

$statusMap = [
    0 => ['text' => 'Chờ xử lý', 'color' => 'warning'],
    1 => ['text' => 'Đã phê duyệt', 'color' => 'success'],
    2 => ['text' => 'Đã hủy', 'color' => 'danger']
];
?>

<style>
    .container {
        max-width: 1300px;
    }
</style>

<div class="container" style="margin-top: 20px; margin-bottom: 40px;">
    <div class="row">
        <div class="col-md-12">
            <h2 style="margin-bottom: 30px;">
                <i class="bi bi-bag-check"></i> Lịch Sử Mua Hàng
            </h2>

            <?php if ($orders && count($orders) > 0): ?>
                <table class="table table-bordered table-hover">
                    <thead style="background-color: #28a745; color: white;">
                        <tr>
                            <th>STT</th>
                            <th>Mã Đơn</th>
                            <th>Ngày Đặt</th>
                            <th>Tổng Tiền</th>
                            <th>Trạng Thái</th>
                            <th style="width: 120px;">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $index = 0;
                        foreach ($orders as $order) {
                            $status = $statusMap[$order['status']];
                            echo '<tr>
                                <td>' . (++$index) . '</td>
                                <td>#' . str_pad($order['id'], 5, '0', STR_PAD_LEFT) . '</td>
                                <td>' . date('d/m/Y H:i', strtotime($order['order_date'])) . '</td>
                                <td><strong>' . number_format($order['total_money'], 0, ',', '.') . ' VND</strong></td>
                                <td>
                                    <span class="badge badge-' . $status['color'] . '">
                                        ' . $status['text'] . '
                                    </span>
                                </td>
                                <td>
                                    <a href="order_detail.php?id=' . $order['id'] . '" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle"></i> Bạn chưa có đơn hàng nào. 
                    <a href="index.php">Mua sắm ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once('layouts/footer.php');
?>
