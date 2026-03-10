<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('utils/utility.php');
require_once('database/dbhelpere.php');

$user = getUserToken();
if ($user == null || $user['role_id'] == 1) {
    header('Location: admin/authen/login.php');
    die();
}

$msg = $fullname = $email = $phone_number = $address = '';

if (!empty($_POST)) {
    $fullname = getPost('fullname');
    $email = getPost('email');
    $phone_number = getPost('phone_number');
    $address = getPost('address');
    $password = getPost('password');

    if ($password != '') {
        $password = getSecurityMD5($password);
    }

    $updated_at = date("Y-m-d H:i:s");
    $id = $user['id'];

    $sql = "select * from User where email = '$email' and id <> $id";
    $userItem = executeResult($sql, true);
    if ($userItem != null) {
        $msg = "Email đã được đăng ký bởi người dùng khác, vui lòng kiểm tra lại!!!";
    } else {
        if ($password != '') {
            $sql = "update User set fullname = '$fullname', email = '$email', phone_number = '$phone_number',
            address = '$address', password = '$password',
            updated_at = '$updated_at' where id = $id";
        } else {
            $sql = "update User set fullname = '$fullname', email = '$email', phone_number = '$phone_number',
            address = '$address', updated_at = '$updated_at'
            where id = $id";
        }

        execute($sql);

        $_SESSION['user']['fullname'] = $fullname;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['phone_number'] = $phone_number;
        $_SESSION['user']['address'] = $address;

        $msg = "Cập nhật thông tin thành công!";
        header('Location: my_profile.php?success=1');
        die();
    }
}

$fullname = $user['fullname'];
$email = $user['email'];
$phone_number = $user['phone_number'];
$address = $user['address'];

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $msg = "Cập nhật thông tin thành công!";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="assets/photos/logo.jpg">
    <title>Chỉnh sửa thông tin cá nhân</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/profile.css">
</head>

<body>
    <?php require_once('layouts/header.php'); ?>

    <div class="container">
        <div class="profile-container">
            <h2><i class="bi bi-person-circle"></i> Chỉnh sửa thông tin cá nhân</h2>

            <?php if ($msg != '') { ?>
                <div class="success-message">
                    <?= $msg ?>
                </div>
            <?php } ?>

            <form method="post" onsubmit="return validateForm()">

                <div class="form-group">
                    <label>Họ và Tên</label>
                    <input required type="text"
                        class="form-control"
                        name="fullname"
                        value="<?= $fullname ?>">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input required type="email"
                        class="form-control"
                        name="email"
                        value="<?= $email ?>">
                </div>

                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="tel"
                        class="form-control"
                        name="phone_number"
                        value="<?= $phone_number ?>">
                </div>

                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text"
                        class="form-control"
                        name="address"
                        value="<?= $address ?>">
                </div>

                <div class="form-group">
                    <label>Mật khẩu mới</label>
                    <input type="password"
                        class="form-control"
                        id="pwd"
                        name="password"
                        minlength="6">
                </div>

                <div class="form-group">
                    <label>Xác minh mật khẩu</label>
                    <input type="password"
                        class="form-control"
                        id="confirmation_pwd"
                        minlength="6">
                </div>

                <button type="submit" class="btn btn-update">
                    <i class="bi bi-check-circle"></i> Cập nhật thông tin
                </button>
                <a href="index.php" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </form>
        </div>
    </div>

    <?php require_once('layouts/footer.php'); ?>

    <script>
        function validateForm() {
            var pwd = document.getElementById("pwd").value;
            var confirmation_pwd = document.getElementById("confirmation_pwd").value;

            if (pwd != '' || confirmation_pwd != '') {
                if (pwd !== confirmation_pwd) {
                    alert("Mật khẩu không khớp, vui lòng nhập lại");
                    return false;
                }
            }
            return true;
        }
    </script>
</body>

</html>