<?php

$fullname = $email = $msg = '';

if(!empty($_POST)){
    $email = getPost('email');
    $pwd = getPost('password');
    $pwd = getSecurityMD5($pwd);

    $sql = "select * from user where email = '$email' and password = '$pwd'";
    $userExist = executeResult($sql, true);
    if($userExist == null){
        $msg = 'Email hoac mat khau khong chinh xac';
    }else {
        $token = getSecurityMD5($userExist['email'].time());

        setcookie('token', $token, time() + 7*24*3600, '/');
        $created_at = date('Y-m-d H:i:s');
        
        $_SESSION['user'] = $userExist;
        
        $userId = $userExist['id'];
        $sql = "insert into tokens (user_id, token, created_at) 
        values ('$userId', '$token', '$created_at')";

        execute($sql);
        
        if ($userExist['role_id'] == 1) {
            header('Location: ../');
        } else {
            header('Location: ../../index.php');
        }
        die();
    }
    
}