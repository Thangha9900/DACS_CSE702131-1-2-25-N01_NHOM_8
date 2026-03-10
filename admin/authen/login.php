<?php
    session_start();
    require_once('../../utils/utility.php');
    require_once('../../database/dbhelpere.php');
    require_once('process_form_login.php');

    $user = getUserToken();
    if($user != null){
        header('Location: ../');
        die();
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>ĐĂNG NHẬP</title>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="image/logo.png">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body style="background-color: #f2f4f7;">
    <div class="container">
        <div style="width: 450px; margin: 100px auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">

            <h3 class="text-center mb-3">ĐĂNG NHẬP</h3>

            <h6 class="text-center" style="color: red; min-height:20px;">
                <?= $msg ?>
            </h6>

            <form method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input required type="email"
                        class="form-control"
                        name="email"
                        value="<?= $email ?>">
                </div>

                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input required type="password"
                        class="form-control"
                        name="password"
                        minlength="6">
                </div>

                <p>
                    <a href="register.php">Đăng ký tài khoản mới</a>
                </p>

                <button class="btn btn-success btn-block">
                    ĐĂNG NHẬP
                </button>
            </form>

        </div>
    </div>
</body>

</html>