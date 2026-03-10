<?php
session_start();
require_once('../utils/utility.php');
require_once('../database/dbhelpere.php');

$user = getUserToken();
if ($user == null) {
    header('Location: authen/login.php');
    die();
}

$title = "Dashboard Page";
$baseUrl = "";
require_once("layouts/header.php");

// Thống kê sản phẩm
$sql = "SELECT COUNT(*) as total_products FROM Product";
$totalProducts = executeResult($sql, true)['total_products'];

$sql = "SELECT COUNT(*) as total_categories FROM Category";
$totalCategories = executeResult($sql, true)['total_categories'];

$sql = "SELECT Category.name, COUNT(Product.id) as product_count 
        FROM Category 
        LEFT JOIN Product ON Category.id = Product.category_id 
        GROUP BY Category.id, Category.name 
        ORDER BY product_count DESC";
$categoryStats = executeResult($sql);

// Thống kê đơn hàng và doanh thu
$sql = "SELECT COUNT(*) as total_orders FROM Orders";
$totalOrders = executeResult($sql, true)['total_orders'];

$sql = "SELECT SUM(total_money) as total_revenue FROM Orders WHERE status = 1";
$totalRevenue = executeResult($sql, true)['total_revenue'] ?? 0;

$sql = "SELECT status, COUNT(*) as order_count FROM Orders GROUP BY status ORDER BY status";
$orderStats = executeResult($sql);

// Thống kê người dùng
$sql = "SELECT COUNT(*) as total_users FROM User";
$totalUsers = executeResult($sql, true)['total_users'];

// Doanh thu theo tháng
$sql = "SELECT MONTH(order_date) as month, YEAR(order_date) as year, SUM(total_money) as revenue 
        FROM Orders 
        WHERE status = 1 
        GROUP BY YEAR(order_date), MONTH(order_date) 
        ORDER BY year DESC, month DESC 
        LIMIT 12";
$revenueByMonth = executeResult($sql);

// Top sản phẩm bán chạy
$sql = "SELECT Product.title, SUM(Order_Details.num) as total_sold 
        FROM Order_Details 
        JOIN Product ON Order_Details.product_id = Product.id 
        GROUP BY Product.id, Product.title 
        ORDER BY total_sold DESC 
        LIMIT 10";
$topProducts = executeResult($sql);

// Đơn hàng mới
$sql = "SELECT Orders.*, User.fullname as user_name 
        FROM Orders 
        LEFT JOIN User ON Orders.user_id = User.id 
        ORDER BY order_date DESC 
        LIMIT 5";
$newOrders = executeResult($sql);
?>

<div class="row">
    <div class="col-md-12">
        <h1 style="margin-bottom:20px;">THỐNG KÊ</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Tổng Sản Phẩm</h5>
                <p class="card-text display-4"><?= $totalProducts ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Tổng Danh Mục</h5>
                <p class="card-text display-4"><?= $totalCategories ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Tổng Đơn Hàng</h5>
                <p class="card-text display-4"><?= $totalOrders ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Tổng Người Dùng</h5>
                <p class="card-text display-4"><?= $totalUsers ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Tổng Doanh Thu</h5>
                <p class="card-text display-4"><?= number_format($totalRevenue) ?> VND</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Biểu Đồ Doanh Thu Theo Tháng</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Top Sản Phẩm Bán Chạy</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Sản Phẩm</th>
                            <th>Đã Bán</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($topProducts as $product): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $product['title'] ?></td>
                            <td><?= $product['total_sold'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Đơn Hàng Mới</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách Hàng</th>
                            <th>Ngày Đặt</th>
                            <th>Tổng Tiền</th>
                            <th>Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $statusLabels = [0 => 'Chờ xử lý', 1 => 'Đã hoàn thành', 2 => 'Đã hủy'];
                        foreach ($newOrders as $order): 
                        ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= $order['user_name'] ?? $order['fullname'] ?></td>
                            <td><?= date('d/m/Y', strtotime($order['order_date'])) ?></td>
                            <td><?= number_format($order['total_money']) ?> VND</td>
                            <td><?= $statusLabels[$order['status']] ?? 'Không xác định' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Thống Kê Trạng Thái Đơn Hàng</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Trạng Thái</th>
                            <th>Số Đơn Hàng</th>
                            <th>Phần Trăm (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($orderStats as $stat): 
                            $percentage = $totalOrders > 0 ? round(($stat['order_count'] / $totalOrders) * 100, 2) : 0;
                        ?>
                        <tr>
                            <td><?= $statusLabels[$stat['status']] ?? 'Không xác định' ?></td>
                            <td><?= $stat['order_count'] ?></td>
                            <td><?= $percentage ?>%</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const revenueData = <?php echo json_encode($revenueByMonth); ?>;
    const labels = revenueData.map(item => `${item.month}/${item.year}`);
    const data = revenueData.map(item => item.revenue);

    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh Thu (VND)',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php
require_once("layouts/footer.php");
?>