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
            deleteCategory();
            break;
    }
}

function deleteCategory() {
    $id = getPost('id');
    $sql = "DELETE FROM Category WHERE id = $id";
    execute($sql);
}
?>
