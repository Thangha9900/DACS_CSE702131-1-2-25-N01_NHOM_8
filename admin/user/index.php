<?php
$title = "Quản lý người dùng";
$baseUrl = "../";
require_once("../layouts/header.php");

$sql = "SELECT user.*, role.name AS role_name 
        FROM user 
        LEFT JOIN role ON user.role_id = role.id 
        WHERE user.deleted = 0";


$data = executeResult($sql);

?>
<div class="row">
    <div class="col-md-11">
        <h2>Quản lý người dùng</h2>

        <a href="editor.php">
            <button class="btn btn-success">
                Thêm tài khoản
            </button>
        </a>

        <table class="table table-bordered table-hover" style="margin-top: 20px;">
            <thead>
                <tr>
                    <td>STT</td>
                    <td>Họ và Tên</td>
                    <td>Email</td>
                    <td>SDT</td>
                    <td>Địa chỉ</td>
                    <td>Quyền</td>
                    <th style="width: 50px;"></th>
                    <th style="width: 50px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $idex = 0;
                foreach ($data as $item) {
                    $deleteButtonHtml = '';
                    // Chỉ hiển thị nút xóa nếu không phải Admin
                    if (strtolower($item['role_name']) !== 'admin') {
                        $deleteButtonHtml = '<button class="btn btn-danger" onclick="deleteUser(' . $item['id'] . ')">Xoa</button>';
                    } else {
                        $deleteButtonHtml = '<button class="btn btn-secondary" disabled title="Không thể xóa Admin">Xoa</button>';
                    }
                    
                    echo '<tr>
                            <td>' . (++$idex) . '</td>
                            <td>' . $item['fullname'] . '</td>
                            <td>' . $item['email'] . '</td>
                            <td>' . $item['phone_number'] . '</td>
                            <td>' . $item['address'] . '</td>
                            <td>' . $item['role_name'] . '</td>
                            <td style="width: 50px">
                                <a href="editor.php?id=' . $item['id'] . '">
                                    <button class="btn btn-warning">Sửa</button>
                                </a>
                            </td>
                            <td style="width: 50px;">
                            ' . $deleteButtonHtml . '
                            </td>
                        </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function deleteUser(id) {
        option = confirm('Bạn có chắc chắn muốn xoá tài khoản này không?')
        if(!option) return;

        $.post('form_api.php', {
            'id': id,
            'action': 'delete'
        }, function(data) {
            try {
                var response = JSON.parse(data);
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            } catch(e) {
                location.reload();
            }
        })
    }
</script>
<?php
require_once("../layouts/footer.php");
?>