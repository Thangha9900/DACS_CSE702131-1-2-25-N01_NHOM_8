<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../utils/utility.php';
require_once __DIR__ . '/../../database/dbhelpere.php';

$user = getUserToken();
if($user != null) {
    $token = getCookie('token');
    $id = $user['id'];

    $sql = "delete from Tokens where user_id = '$id' and token = '$token'";
    execute($sql);

    setcookie('token', '', time() - 100, '/');
}

session_destroy();
header('Location: ../../index.php');
die();

session_destroy();
header('Location: ' . '/webbanhang/admin/authen/login.php');
exit();