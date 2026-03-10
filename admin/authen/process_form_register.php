<?php

$fullname = $email = $msg = '';

if (!empty($_POST)) {
    $fullname = getPost('fullname');
    $email = getPost('email');
    $pwd = getPost('password');
    $confirmation_pwd = getPost('confirmation_pwd');
    $role_id = getPost('role_id');
    if (empty($fullname) || empty($email) || empty($pwd) || strlen($pwd) < 6 || empty($role_id)) {
    } else {
        $userExist = executeResult("select * from user where email = '$email'", true);
        if ($userExist != null) {
            $msg = 'Email da ton tai';
        } else {
            $created_at = $updated_at = date('Y-m-d H:i:s');
            // dung ma hoa 1 chieu md5
            $pwd = getSecurityMD5($pwd);

            $sql = "insert into User (fullname, email, password, role_id,created_at,updated_at,deleted) 
                values ('$fullname', '$email', '$pwd','$role_id', '$created_at', '$updated_at', 0)";
            execute($sql);
            header('Location: login.php');
            die();
        }
    }
}

