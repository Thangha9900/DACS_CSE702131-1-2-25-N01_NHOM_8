<?php
$title = "Quan ly danh muc san pham";
$baseUrl = "../";
require_once("../layouts/header.php");

$sql = "SELECT * FROM Category";
$categories = executeResult($sql);
?>

<div class="row">
    <div class="col-md-11">
        <h2>Quản Lý Danh Mục Sản Phẩm</h2>
        
        <div class="panel panel-primary" style="margin-top: 20px; padding: 20px;">
            <h5>Tên Danh Mục:</h5>
            <div style="display: flex; gap: 10px;">
                <input type="text" 
                       class="form-control" 
                       id="category_name" 
                       placeholder="Nhập tên danh mục"
                       style="max-width: 300px;">
                <button class="btn btn-success" onclick="addCategory()">Lưu</button>
            </div>
            <h6 style="color:red; min-height:20px; margin-top: 10px;">
                <span id="msg"></span>
            </h6>
        </div>

        <table class="table table-bordered table-hover" style="margin-top: 20px;">
            <thead>
                <tr>
                    <td>STT</td>
                    <td>Tên Danh Mục</td>
                    <th style="width: 50px;"></th>
                    <th style="width: 50px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $index = 0;
                foreach ($categories as $item) {
                    echo '<tr>
                            <td>' . (++$index) . '</td>
                            <td>' . $item['name'] . '</td>
                            <td style="width: 50px;">
                                <button class="btn btn-warning btn-sm" onclick="editCategory(' . $item['id'] . ', \'' . htmlspecialchars($item['name']) . '\')">Sửa</button>
                            </td>
                            <td style="width: 50px;">
                                <button class="btn btn-danger btn-sm" onclick="deleteCategory(' . $item['id'] . ')">Xóa</button>
                            </td>
                        </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    var editingId = null;

    function addCategory() {
        var categoryName = document.getElementById('category_name').value;
        
        if (categoryName.trim() === '') {
            alert('Vui lòng nhập tên danh mục');
            return;
        }

        $.post('form_save.php', {
            'id': editingId,
            'name': categoryName
        }, function(data) {
            var response = JSON.parse(data);
            if (response.success) {
                document.getElementById('category_name').value = '';
                editingId = null;
                location.reload();
            } else {
                alert(response.message);
            }
        });
    }

    function editCategory(id, name) {
        document.getElementById('category_name').value = name;
        editingId = id;
        document.getElementById('category_name').focus();
    }

    function deleteCategory(id) {
        if (!confirm('Bạn có chắc chắn muốn xóa danh mục này không?')) {
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