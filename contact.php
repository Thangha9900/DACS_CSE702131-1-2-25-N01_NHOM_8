<?php
require_once('layouts/header.php');

$message = '';
if(!empty($_POST)) {
    $first_name = getPost('first_name');
    $last_name = getPost('last_name');
    $email = getPost('email');
    $phone_number = getPost('phone');
    $subject_name = getPost('subject_name');
    $note = getPost('note');
    $created_at = $updated_at = date('Y-m-d H:i:s');

    $sql = "insert into FeedBack(firstname, lastname, email, phone_number, subject_name, note, status, created_at, updated_at) values('$first_name', '$last_name', '$email', '$phone_number', '$subject_name', '$note', 0, '$created_at', '$updated_at')";

    execute($sql);
    $message = 'Gửi phản hồi thành công! Cảm ơn bạn đã liên hệ.';
}
?>

<style>
    .container {
        max-width: 1300px;
    }
</style>
<div class="container" style="margin-top: 20px; margin-bottom: 20px;">
    <?php
    if(!empty($message)) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            ' . $message . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>';
    }
    ?>
    <form method="post">
        <div class="row">
            <div class="col-md-6">
                <h3>Thông tin liên hệ</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input required="true" type="text" class="form-control" id="usr" name="first_name" placeholder="Nhập tên">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input required="true" type="text" class="form-control" id="usr" name="last_name" placeholder="Nhập họ">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input required="true" type="email" class="form-control" id="email" name="email" placeholder="Nhập email">
                </div>
                <div class="form-group">
                    <input required="true" type="tel" class="form-control" id="phone" name="phone" placeholder="Nhập sdt">
                </div>
                <div class="form-group">
                    <input required="true" type="text" class="form-control" id="subject_name" name="subject_name" placeholder="Nhập chủ đề">
                </div>
                <div class="form-group">
                    <label for="pwd">Nội Dung:</label>
                    <textarea class="form-control" rows="3" name="note"></textarea>
                </div>

                <button type="submit" class="btn btn-success" style="border-radius: 0px; font-size: 26px; width: 100%;">
                        GỬI PHẢN HỒI</button>

            </div>
            <div class="col-md-6">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7451.497045507533!2d105.744309425354!3d20.962611811056572!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313452efff394ce3%3A0x391a39d4325be464!2zVHLGsOG7nW5nIMSQ4bqhaSBI4buNYyBQaGVuaWthYQ!5e0!3m2!1svi!2s!4v1772872184595!5m2!1svi!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    <?php if(!empty($message)) { ?>
        document.querySelector('form').reset();
        setTimeout(function() {
            document.querySelector('.alert').style.display = 'none';
        }, 5000);
    <?php } ?>
</script>

<?php
require_once('layouts/footer.php');
?>