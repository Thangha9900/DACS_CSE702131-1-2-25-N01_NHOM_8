<?php
require_once('../../utils/utility.php');
require_once('../../database/dbhelpere.php');

if (!empty($_POST)) {
    $id = getPost('id');
    $title = getPost('title');
    $price = getPost('price');
    $discount = getPost('discount') ?: 0;
    $category_id = getPost('category_id');
    $description = $_POST['description'] ?? '';
    $description = fixSqlInject($description);
    
    $thumbnail = '';
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['thumbnail']['name'];
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($filetype, $allowed)) {
            die('Lỗi: Chỉ hỗ trợ ảnh JPG, PNG, GIF');
        }
        
        if ($_FILES['thumbnail']['size'] > 5 * 1024 * 1024) {
            die('Lỗi: Kích thước ảnh không được quá 5MB');
        }
        
        $new_filename = time() . '_' . rand(1000, 9999) . '.' . $filetype;
        $upload_path = '../../assets/photos/' . $new_filename;
        
        if (!is_dir('../../assets/photos')) {
            mkdir('../../assets/photos', 0777, true);
        }
        
        if (!move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_path)) {
            die('Lỗi: Không thể upload ảnh');
        }
        
        $thumbnail = $new_filename;
    }
    
    $updated_at = date("Y-m-d H:i:s");
    
    if ($id && $id > 0) {
        // Update
        if ($thumbnail) {
            $sql = "UPDATE Product SET title = '$title', price = $price, discount = $discount, 
                    category_id = $category_id, description = '$description', 
                    thumbnail = '$thumbnail', updated_at = '$updated_at' 
                    WHERE id = $id";
        } else {
            $sql = "UPDATE Product SET title = '$title', price = $price, discount = $discount, 
                    category_id = $category_id, description = '$description', 
                    updated_at = '$updated_at' 
                    WHERE id = $id";
        }
        execute($sql);
        header('Location: index.php');
        die();
    } else {
        // Insert
        if (!$thumbnail) {
            die('Lỗi: Vui lòng chọn ảnh sản phẩm');
        }
        
        $created_at = date("Y-m-d H:i:s");
        $sql = "INSERT INTO Product(category_id, title, price, discount, thumbnail, description, created_at, updated_at, deleted)
                VALUES ($category_id, '$title', $price, $discount, '$thumbnail', '$description', '$created_at', '$updated_at', 0)";
        execute($sql);
        header('Location: index.php');
        die();
    }
}
?>

