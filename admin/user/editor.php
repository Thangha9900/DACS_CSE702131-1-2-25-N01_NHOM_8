<?php
$title = "Thêm người dùng";
$baseUrl = "../";
require_once("../layouts/header.php");

$id = $msg = $fullname = $email = $phone_number = $address = $role_id = '';
require_once('form_save.php');

$id = getGet('id');
if ($id != '' && $id > 0) {
    $sql = "select * from User where id = '$id'";
    $userItem = executeResult($sql, true);
    if ($userItem != null) {
        $fullname = $userItem['fullname'];
        $email = $userItem['email'];
        $phone_number = $userItem['phone_number'];
        $address = $userItem['address'];
        $role_id = $userItem['role_id'];
    } else {
        $id = 0;
    }
} else {
    $id = 0;
}

$sql = "select * from Role";
$roleItems = executeResult($sql);

?>

<div class="row justify-content-center">
    <div class="col-md-11">
        <h2>Thêm người dùng</h2>
        <div class="panel panel-primary">

            <h6 style="color:red; min-height:20px;">
                <?= $msg ?>
            </h6>

            <form method="post" onsubmit="return validateForm()">

                <div class="form-group">
                    <label>Họ và Tên</label>
                    <input required type="text"
                        class="form-control"
                        name="fullname"
                        value="<?= $fullname ?>">
                </div>
<input type="text" name="id" value="<?=$id?>" hidden="true">

                <div class="form-group">
                    <label for="usr">Role:</label>
                    <select class="form-control" name="role_id" id="role_id" required="true">
                        <option value="">-- Chọn --</option>
                        <?php
                        foreach ($roleItems as $role) {
                            echo '<option value="' . $role['id'] . '">' . $role['name'] . '</option>
                ';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input required type="email"
                        class="form-control"
                        name="email"
                        value="<?= $email ?>">
                </div>
                <div class="form-group">
                    <label for="phone_number">SDT:</label>
                    <input required="true" type="tel" class="form-control" id="phone_number" name="phone_number" value="<?= $phone_number ?>">
                </div>
                <div class="form-group">
                    <label for="address">Địa Chỉ:</label>
                    <input required="true" type="text" class="form-control" id="address" name="address" value="<?= $address ?>">
                </div>

                <div class="form-group">
                    <label for="pwd">Mật Khẩu:</label>
                    <input <?= ($id > 0) ? '' : 'required="true"' ?> type="password" class="form-control" id="pwd" name="password" minlength="6">
                </div>

                <div class="form-group">
                    <label for="confirmation_pwd">Xác Minh Mật Khẩu:</label>
                    <input <?= ($id > 0) ? '' : 'required="true"' ?> type="password" class="form-control" id="confirmation_pwd" minlength="6">
                </div>

                <button class="btn btn-success">
                    Đăng ký
                </button>
            </form>

        </div>

    </div>
</div>
<script>
    function validateForm() {
        var pwd = document.getElementById("pwd").value;
        var confirmation_pwd = document.getElementById("confirmation_pwd").value;

        if (pwd !== confirmation_pwd) {
            alert("Mật khẩu không khớp, vui lòng nhập lại");
            return false;
        }
        return true;
    }
</script>
<?php
require_once("../layouts/footer.php");
?>