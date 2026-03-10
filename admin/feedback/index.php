<?php
$title = "Quản lý phản hồi";
$baseUrl = "../";
require_once("../layouts/header.php");

$sql = "SELECT * FROM feedback ORDER BY status asc ";


$data = executeResult($sql);
?>

<div class="row">
    <div class="col-md-11">
        <h2>Quản lý phản hồi</h2>


        <table class="table table-bordered table-hover" style="margin-top: 20px;">
            <thead>
                <tr>
                    <td>STT</td>
                    <td>Tên</td>
                    <td>Họ</td>
                    <td>SDT</td>
                    <td>Email</td>
                    <td>Chủ đề</td>
                    <td>Nội dung</td>
                    <td>Ngày gửi</td>
                    <th style="width: 120px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $idex = 0;
                foreach ($data as $item) {
                    $button = '';
                    if ($item['status'] == 0) {
                        $button = '<button onclick="markRead(' . $item['id'] . ', this)" class="btn btn-danger btn-sm">Đã Đọc</button>';
                    } else {
                        $button = '<label class="badge badge-success">Đã Đọc</label>';
                    }
                    echo '<tr>
                            <td>' . (++$idex) . '</td>
                            <td>' . $item['firstname'] . '</td>
                            <td>' . $item['lastname'] . '</td>
                            <td>' . $item['phone_number'] . '</td>
                            <td>' . $item['email'] . '</td>
                            <td>' . $item['subject_name'] . '</td>
                            <td>' . $item['note'] . '</td>
                            <td>' . $item['created_at'] . '</td>
                            <td style="width: 50px">' . $button . '</td>
                        </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function markRead(id, btn) {
        $.post('form_api.php', {
            'id': id,
            'action': 'mark'
        }, function(data) {
            $(btn).replaceWith('<label class="badge badge-success">Đã Đọc</label>');
        })
    }
</script>

<?php
require_once("../layouts/footer.php");
?>