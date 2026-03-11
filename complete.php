<?php
require_once('layouts/header.php');

$orderId = getGet('id') ?? '';
?>

<style>
    .container {
        max-width: 1300px;
    }

    .success-container {
        text-align: center;
        padding: 40px 20px;
    }

    .success-icon {
        font-size: 80px;
        color: #28a745;
        margin-bottom: 20px;
    }

    .invoice-actions {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin: 30px 0;
        flex-wrap: wrap;
    }

    .invoice-actions a, .invoice-actions button {
        padding: 12px 30px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .invoice-actions .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .invoice-actions .btn-secondary:hover {
        background-color: #545b62;
    }

    .invoice-actions .btn-info {
        background-color: #17a2b8;
        color: white;
    }

    .invoice-actions .btn-info:hover {
        background-color: #138496;
    }

    .invoice-actions .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .invoice-actions .btn-primary:hover {
        background-color: #0056b3;
    }

    .invoice-actions .btn-success {
        background-color: #28a745;
        color: white;
    }

    .invoice-actions .btn-success:hover {
        background-color: #1e7e34;
    }
</style>

<div class="container" style="margin-top: 20px; margin-bottom: 40px;">
    <div class="row">
        <div class="col-md-12 success-container">
            <div class="success-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <h1 style="color: green; margin-bottom: 20px;">BẠN ĐÃ TẠO ĐƠN HÀNG THÀNH CÔNG!!!</h1>
            <h4>Cảm ơn quý khách đã đặt mua sản phẩm của chúng tôi.</h4>
            <h4>Đơn hàng của quý khách sẽ được nhân viên kiểm tra và giao hàng trong thời gian sớm nhất.</h4>
            <h4>Quý khách có thể xem lại lịch sử mua hàng trong phần "Đơn hàng của tôi".</h4>
            
            <?php if (!empty($orderId)): ?>
            <div style="margin: 30px 0; padding: 20px; background-color: #f8f9fa; border-radius: 4px; border-left: 4px solid #28a745;">
                <p style="color: #666; margin-bottom: 15px;"><i class="bi bi-info-circle"></i> <strong>Mã Đơn Hàng:</strong> #<?= str_pad($orderId, 5, '0', STR_PAD_LEFT) ?></p>
                <p style="color: #666; margin-bottom: 0;"><i class="bi bi-receipt"></i> Bạn có thể tải hoặc in hóa đơn từ các nút dưới đây:</p>
            </div>

            <div class="invoice-actions">
                <a href="invoice.php?id=<?= $orderId ?>" class="btn-info" target="_blank">
                    <i class="bi bi-eye"></i> Xem Hóa Đơn
                </a>
                <button onclick="printInvoice(<?= $orderId ?>)" class="btn-primary">
                    <i class="bi bi-printer"></i> In Hóa Đơn
                </button>
                <button onclick="downloadPDF(<?= $orderId ?>)" class="btn-success">
                    <i class="bi bi-download"></i> Tải PDF
                </button>
            </div>

            <div style="margin-bottom: 20px;">
                <a href="order_detail.php?id=<?= $orderId ?>" class="btn btn-info" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 4px; color: white;">
                    <i class="bi bi-eye"></i> Xem Chi Tiết Đơn Hàng
                </a>
            </div>
            <?php endif; ?>

            <div style="margin-top: 40px; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="my_orders.php" class="btn btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; padding: 12px 30px; border-radius: 4px; color: white;">
                    <i class="bi bi-list"></i> Đơn Hàng Của Tôi
                </a>
                <a href="index.php" class="btn btn-success" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; padding: 12px 30px; border-radius: 4px; color: white; font-size: 16px;">
                    <i class="bi bi-shop"></i> TIẾP TỤC MUA HÀNG
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function printInvoice(orderId) {
    const invoiceWindow = window.open('invoice.php?id=' + orderId, 'invoiceWindow');
    invoiceWindow.addEventListener('load', function() {
        setTimeout(() => {
            invoiceWindow.print();
        }, 500);
    });
}

function downloadPDF(orderId) {
    const invoiceWindow = window.open('invoice.php?id=' + orderId, 'invoiceWindow');
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