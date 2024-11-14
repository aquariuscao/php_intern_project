<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>-->
    <!--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>-->
    <link rel="stylesheet" href="../../public/css/style.css">
    <title>Edit Product</title>
    <style>
        img {
            width: 200px;
            height: auto;
            border: 1px solid #000000; /* Viền đen 5px */
            border-radius: 15px;
        }
    </style>
</head>
<body>
<?php if (empty($data)): ?>
    <p>No products found.</p>
<?php else: ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Edit Product</h2>


    <form action="?action=edit&id=<?= $data['product']->getId() ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= $data['product']->getName() ?>"
                   required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" value="<?= $data['product']->getStatus() ?>">
                <?php foreach ($data['statuses'] as $status): ?>
                    <option value="<?= htmlspecialchars($status['status']) ?>"
                        <?= $data['product']->getStatus() == $status['status'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($status['status']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <?php if ($data['product']->getImage()) { ?>
                <img src="<?php echo $data['product']->getImage() ?>" alt="Product Image" id="productImageElement">
                <button type="button" class="btn btn-danger" id="deleteImageBtn" onclick="deleteImage()">Delete Image
                </button>
                <input type="hidden" name="delete-image" id="delete-image-input" value="false">
            <?php } else { ?>
                <td><?php echo $data['product']->getImage() ?></td>
            <?php } ?>
            <input type="file" accept="image/png, image/jpeg" class="form-control-file mt-2" id="image" name="image"
                   onchange="checkFileSize()">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="index.php" class="btn btn-secondary ml-2">Back</a>
        <?php endif; ?>
    </form>
</div>
</body>
<script>
    function checkFileSize() {
        var fileInput = document.getElementById("image");
        var file = fileInput.files[0];  // Lấy file đầu tiên trong danh sách

        // Kiểm tra kích thước file (tính bằng byte)
        if (file && file.size > 5 * 1024 * 1024) { // 5MB
            alert("Tệp quá lớn. Kích thước tối đa là 1MB.");
            fileInput.value = ''; // Xóa file đã chọn
        }
    }

    function deleteImage() {

        var imageElement = document.getElementById('productImageElement');
        var deleteBtn = document.getElementById('deleteImageBtn');
        var imageInput = document.getElementById('image');

        // Ẩn ảnh
        if (imageElement) {
            imageElement.style.display = 'none';
            imageInput.value = '';  // Xóa giá trị ảnh trong trường input hidden
        }

        // Ẩn nút "Hủy ảnh"
        if (deleteBtn) {
            deleteBtn.style.display = 'none';
            document.getElementById('delete-image-input').value = 'true';
        }
    }
</script>
</html>
