<?php
$title = "Quản lý sản phẩm";
$baseUrl = "../";
require_once("../layouts/header.php");

$sql = "SELECT p.*, c.name AS category_name 
        FROM Product p 
        LEFT JOIN Category c ON p.category_id = c.id 
        WHERE p.deleted = 0
        ORDER BY p.id DESC";
$products = executeResult($sql);
?>

<div class="row">
    <div class="col-md-11">
        <h2>Quản Lý Sản Phẩm</h2>

        <a href="editor.php">
            <button class="btn btn-success" style="margin-bottom: 20px;">
                Thêm Sản Phẩm
            </button>
        </a>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 60px;">STT</th>
                    <th style="width: 150px;">Thumbnail</th>
                    <th style="width: 300px;">Tên Sản Phẩm</th>
                    <th style="width: 150px;">Giá</th>
                    <th style="width: 150px;">Danh Mục</th>
                    <th style="width: 80px;"></th>
                    <th style="width: 80px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $index = 0;
                foreach ($products as $item) {
                    echo '<tr>
                            <th>' . (++$index) . '</th>
                            <td>
                            <img src="../../assets/photos/' . $item['thumbnail'] . '" 
                            style="height:100px;width:100px;object-fit:cover;">
                            </td>
                            <td>' . $item['title'] . '</td>
                            <td>' . $item['price'] . '</td>
                            <td>' . $item['category_name'] . '</td>
                            <td style="width: 50px">
                                <a href="editor.php?id=' . $item['id'] . '">
                                    <button class="btn btn-warning">Sửa</button></a>
                            </td>
                            <td style="width: 50px">
                                <button onclick="deleteProduct(' . $item['id'] . ')"
                                    class="btn btn-danger">Xoá</button>
                            </td>
                        </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function deleteProduct(id) {
        if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')) {
            return;
        }

        $.post('form_api.php', {
            'id': id,
            'action': 'delete'
        }, function(data) {
            location.reload();
        });
    }
</script>

<?php
require_once("../layouts/footer.php");
?>