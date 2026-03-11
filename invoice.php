<?php
require_once('database/dbhelpere.php');
require_once('utils/utility.php');

// Check if user is logged in
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
    echo '<div class="alert alert-danger" style="margin: 20px;">Không tìm thấy đơn hàng!</div>';
    exit;
}

$sql = "SELECT od.*, p.title, p.thumbnail FROM Order_Details od
        LEFT JOIN Product p ON p.id = od.product_id
        WHERE od.order_id = $orderId";
$items = executeResult($sql);

$isPrint = getGet('print') == 'yes';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Đơn Hàng #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/invoice.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
    <div class="invoice-wrapper">
        <div class="no-print">
            <button class="btn btn-print" onclick="window.print()">
                <i class="bi bi-printer"></i> In Hóa Đơn
            </button>
            <button class="btn btn-download" onclick="downloadPDF()">
                <i class="bi bi-download"></i> Tải Hóa Đơn (PDF)
            </button>
            <a href="order_detail.php?id=<?= $orderId ?>" class="btn btn-back">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>

        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h2><i class="bi bi-bag-check"></i> HỆ THỐNG BÁN HÀNG</h2>
                <p><strong>Công Ty:</strong> Web Bán Hàng Online</p>
                <p><strong>Địa Chỉ:</strong> 123 đường abc, Hà Nội</p>
                <p><strong>Điện Thoại:</strong> 1900 1234</p>
                <p><strong>Email:</strong> support@webshop.vn</p>
            </div>
            <div class="invoice-title">
                <h1>HÓA ĐƠN BÁN HÀNG</h1>
                <p class="invoice-number">
                    Số HĐ: <strong><?= str_pad($order['id'], 8, '0', STR_PAD_LEFT) ?></strong>
                </p>
                <div class="invoice-number">
                    <strong>Ngày Lập:</strong> <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?>
                </div>
            </div>
        </div>

        <!-- Customer and Order Info -->
        <div class="invoice-content">
            <div class="row-info">
                <div class="info-section">
                    <h3>Thông Tin Khách Hàng</h3>
                    <p><span class="label">Tên:</span> <?= htmlspecialchars($order['fullname']) ?></p>
                    <p><span class="label">Email:</span> <?= htmlspecialchars($order['email']) ?></p>
                    <p><span class="label">Điện Thoại:</span> <?= htmlspecialchars($order['phone_number']) ?></p>
                    <p><span class="label">Địa Chỉ:</span> <?= htmlspecialchars($order['address']) ?></p>
                </div>

                <div class="info-section">
                    <h3>Thông Tin Đơn Hàng</h3>
                    <p><span class="label">Mã Đơn:</span> <?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></p>
                    <p><span class="label">Ngày Đặt:</span> <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></p>
                    <p><span class="label">Trạng Thái:</span> 
                        <?php
                        $statusText = '';
                        if ($order['status'] == 0) $statusText = 'Chờ Xử Lý';
                        elseif ($order['status'] == 1) $statusText = 'Đã Phê Duyệt';
                        else $statusText = 'Đã Hủy';
                        echo $statusText;
                        ?>
                    </p>
                    <?php if (!empty($order['note'])): ?>
                    <p><span class="label">Ghi Chú:</span> <?= htmlspecialchars($order['note']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Products Table -->
            <table class="products-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">STT</th>
                        <th style="width: 10%;"></th>
                        <th style="width: 40%;">Tên Sản Phẩm</th>
                        <th style="width: 15%; text-align: right;">Giá / SP</th>
                        <th style="width: 10%; text-align: center;">Số Lượng</th>
                        <th style="width: 15%; text-align: right;">Tổng Giá</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 0;
                    foreach ($items as $item) {
                        $imagePath = 'assets/photos/' . htmlspecialchars($item['thumbnail']);
                        echo '<tr>
                            <td>' . (++$index) . '</td>
                            <td class="text-center"><img src="' . $imagePath . '" alt="Product" class="product-image" onerror="this.src=\'assets/photos/placeholder.jpg\'"></td>
                            <td>' . htmlspecialchars($item['title']) . '</td>
                            <td class="text-right">' . number_format($item['price'], 0, ',', '.') . ' VND</td>
                            <td class="text-center">' . $item['num'] . '</td>
                            <td class="text-right"><strong>' . number_format($item['total_money'], 0, ',', '.') . ' VND</strong></td>
                        </tr>';
                    }
                    ?>
                </tbody>
            </table>

            <!-- Summary -->
            <div class="summary-section">
                <div class="summary-box">
                    <div class="summary-row">
                        <span class="label">Subtotal:</span>
                        <span><?= number_format($order['total_money'], 0, ',', '.') ?> VND</span>
                    </div>
                    <div class="summary-row">
                        <span class="label">Phí Vận Chuyển:</span>
                        <span>Miễn Phí</span>
                    </div>
                    <div class="summary-row">
                        <span class="label">Thuế (0%):</span>
                        <span>0 VND</span>
                    </div>
                    <div class="summary-row total">
                        <span class="label">Tổng Cộng:</span>
                        <span><?= number_format($order['total_money'], 0, ',', '.') ?> VND</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-section">
            <div class="footer-item">
                <h4>Ghi Chú</h4>
                <p>Cảm ơn quý khách đã mua hàng tại cửa hàng của chúng tôi. Nếu có vấn đề gì, vui lòng liên hệ với chúng tôi.</p>
            </div>
            <div class="footer-item">
                <h4>Điều Kiện Thanh Toán</h4>
                <p>Thanh toán khi nhận hàng (COD)<br>
                Bảo hành theo quy định của nhà sản xuất</p>
            </div>
            <div class="footer-item">
                <h4>Chữ Ký Cửa Hàng</h4>
                <div class="signature"></div>
                <p style="color: #999; font-size: 12px;">(Ký tên và dấu)</p>
            </div>
        </div>
    </div>

    <script>
        function downloadPDF() {
            const element = document.querySelector('.invoice-wrapper');
            const orderId = '<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
            
            const opt = {
                margin: [10, 10, 10, 10],
                filename: 'HoaDon_' + orderId + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
            };

            html2pdf().set(opt).from(element).save();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Nếu là iframe payment, tự động focus vào nút download
            if (window.parent !== window) {
                console.log('Invoice đang chạy trong iframe');
            }
        });
    </script>
</body>
</html>
