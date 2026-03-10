<?php
require_once('../../utils/utility.php');
require_once('../../database/dbhelpere.php');

if (!empty($_POST)) {
    $id = getPost('id');
    $name = getPost('name');

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Tên danh mục không được để trống']);
        die();
    }

    if ($id && $id > 0) {
        // Update
        $sql = "UPDATE Category SET name = '$name' WHERE id = $id";
        execute($sql);
        echo json_encode(['success' => true, 'message' => 'Cập nhật thành công']);
        die();
    } else {
        // Check if category exists
        $sql = "SELECT * FROM Category WHERE name = '$name'";
        $categoryItem = executeResult($sql, true);
        
        if ($categoryItem == null) {
            // Insert
            $sql = "INSERT INTO Category(name) VALUES ('$name')";
            execute($sql);
            echo json_encode(['success' => true, 'message' => 'Thêm danh mục thành công']);
            die();
        } else {
            echo json_encode(['success' => false, 'message' => 'Danh mục này đã tồn tại']);
            die();
        }
    }
}
?>
