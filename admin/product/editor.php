<?php
$title = "Them/Sua san pham";
$baseUrl = "../";
require_once("../layouts/header.php");

$id = $title_product = $price = $discount = $category_id = $description = $thumbnail = '';
require_once('form_save.php');

$id = getGet('id');
if ($id != '' && $id > 0) {
    $sql = "SELECT * FROM Product WHERE id = '$id' AND deleted = 0";
    $productItem = executeResult($sql, true);
    if ($productItem != null) {
        $title_product = $productItem['title'];
        $price = $productItem['price'];
        $discount = $productItem['discount'];
        $category_id = $productItem['category_id'];
        $description = $productItem['description'];
        $thumbnail = $productItem['thumbnail'];
    } else {
        $id = 0;
    }
} else {
    $id = 0;
}

$sql = "SELECT * FROM Category ORDER BY name ASC";
$categories = executeResult($sql);
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <h2><?= ($id > 0) ? 'Sửa' : 'Thêm' ?> Sản Phẩm</h2>
        
        <div class="panel panel-primary" style="margin-top: 20px; padding: 20px;">
            <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                
                <div class="form-group">
                    <label>Tên Sản Phẩm:</label>
                    <input required type="text" 
                           class="form-control" 
                           name="title" 
                           value="<?= htmlspecialchars($title_product) ?>">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Giá (VND):</label>
                            <input required type="number" 
                                   class="form-control" 
                                   name="price" 
                                   value="<?= $price ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Giảm Giá (VND):</label>
                            <input type="number" 
                                   class="form-control" 
                                   name="discount" 
                                   value="<?= $discount ?>"
                                   min="0">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Danh Mục Sản Phẩm:</label>
                    <select class="form-control" name="category_id" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php
                        foreach ($categories as $cat) {
                            $selected = ($category_id == $cat['id']) ? 'selected' : '';
                            echo '<option value="' . $cat['id'] . '" ' . $selected . '>' . htmlspecialchars($cat['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nội Dung:</label>
                    <textarea class="form-control" 
                              id="description" 
                              name="description" 
                              rows="10"><?= htmlspecialchars($description) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Thumbnail (Ảnh):</label>
                    <div style="margin-bottom: 10px;">
                        <input type="file" 
                               class="form-control" 
                               id="thumbnail" 
                               name="thumbnail" 
                               accept="image/*"
                               <?= ($id == 0) ? 'required' : '' ?>>
                        <small style="color: #999;">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 5MB</small>
                    </div>
                    <?php if ($thumbnail): ?>
                    <div style="margin-bottom: 15px;">
                        <label>Ảnh hiện tại:</label><br>
                        <img src="../../assets/photos/<?= htmlspecialchars($thumbnail) ?>" 
                             style="max-width: 200px; max-height: 200px;">
                    </div>
                    <?php endif; ?>
                </div>

                <input type="hidden" name="id" value="<?= $id ?>">

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-success">
                        <?= ($id > 0) ? 'Cập Nhật' : 'Lưu Sản Phẩm' ?>
                    </button>
                    <a href="index.php" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description', {
        toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
            { name: 'paragraph', items: ['BulletedList', 'NumberedList', '-', 'Blockquote'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'insert', items: ['Table', 'Image'] },
            { name: 'styles', items: ['Styles', 'Format'] }
        ],
        height: 300
    });
</script>

<?php
require_once("../layouts/footer.php");
?>
