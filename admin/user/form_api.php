<?php
session_start();
require_once('../../utils/utility.php');
require_once('../../database/dbhelpere.php');

$user = getUserToken();
if($user == null) {
    die();
}

if(!empty($_POST)) {
    $action = getPost('action');

    switch ($action) {
        case 'delete':
            deleteUser();
            break;
    }
}

function deleteUser() {
    $id = getPost('id');
    
    $sql = "SELECT user.*, role.name AS role_name 
            FROM user 
            LEFT JOIN role ON user.role_id = role.id 
            WHERE user.id = $id";
    $user = executeResult($sql, true);
    
    if ($user && strtolower($user['role_name']) === 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Không thể xóa tài khoản Admin!']);
        return;
    }
    
    $updated_at = date("Y-m-d H:i:s");
    $sql = "update User set deleted = 1, updated_at = '$updated_at'
        where id = $id";
    execute($sql);
    echo json_encode(['status' => 'success', 'message' => 'Xóa tài khoản thành công!']);
}