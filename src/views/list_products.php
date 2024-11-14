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
    <!--    <link rel="stylesheet" href="../../public/css/style.css">-->
    <link rel="stylesheet" href="../../public/js/status.js">
    <title>Danh Sách Sản Phẩm</title>
    <style>
        img {
            width: 100px;
            height: auto;
            border: 1px solid rgba(0, 0, 0, 0.23);
            border-radius: 15px;
        }

        nav {
            display: flex;
            justify-content: center; /* Căn giữa theo chiều ngang */
        }
    </style>


</head>
<body>

<h5 class="ml-4 mt-2">
    <a href="/" class="link"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
</h5>

<!-- Form tìm kiếm và lọc -->
<div class="container mt-2">
    <h2 class="text-center mb-4">Product Management</h2>

    <div class="mb-3 text-left">
        <a href="/index.php?action=add" class="btn btn-success"><i class="fas fa-plus"></i> Add new</a>
    </div>


    <div >
            <form action="/" method="GET" class="row mb-4" role="search">

                <div class="col-md-4">
                    <input type="text" name="keyword" class="form-control"
                           value="<?php if (isset($_GET["keyword"])) echo($_GET["keyword"]); ?>"
                           placeholder="Search...">
                </div>
                <div class="col-md-4">
                    <select id="status" name="status" class="form-control">
                        <option value="">Choose status</option>
<!--                        --><?php //foreach ($data['statuses'] as $status): ?>
<!---->
<!--                            <option id="status_choosen"-->
<!--                                    value="--><?//= htmlspecialchars($status['status']) ?><!--">--><?//= htmlspecialchars($status['status']) ?><!--</option>-->
<!--                        --><?php //endforeach; ?>
                        <?php foreach ($data['statuses'] as $status): ?>
                            <option value="<?= htmlspecialchars($status['status']) ?>"
                                <?= isset($data['params']['status']) && $data['params']['status'] == $status['status'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($status['status']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="col-md-2">
                    <select name="sort_by" class="form-control">
                        <option value="">Sort By</option>
                        <option value="name_asc" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                        <option value="name_desc" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
                        <option value="status_asc" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'status_asc' ? 'selected' : '' ?>>Status (A-Z)</option>
                        <option value="status_desc" <?= isset($_GET['sort_by']) && $_GET['sort_by'] == 'status_desc' ? 'selected' : '' ?>>Status (Z-A)</option>

                    </select>
                </div>
                <div class="col md-2">
                    <input type="submit" value="Search" class="btn btn-primary">
                </div>
            </form>


    </div>

    <div class="mb-2">
        <?php
        echo "Total: " . $data['totalProducts']." products";
        ?>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="thead-dark">

        <tr>
            <th>ID</th>
                <th>
                    Name
                </th>
            <th>Status</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php foreach ($data['products'] as $product): ?>
            <tr>
                <td><?php echo htmlspecialchars($product->getId()); ?></td>
                <td><?php echo htmlspecialchars($product->getName()); ?></td>
                <td><?php echo htmlspecialchars($product->getStatus()); ?></td>

                <?php if ($product->getImage()) { ?>
                    <td>
                        <img src="<?php echo $product->getImage() ?>" alt="Product Image">
                    </td>
                <?php } else { ?>
                    <td><?php echo $product->getImage() ?></td>
                <?php } ?>
                <td>
                    <a class="btn btn-primary" href="/index.php?action=edit&id=<?php echo $product->getId(); ?>"
                       role="button">Edit</a>
                    <a class="btn btn-danger" href="/index.php?action=delete&id=<?php echo $product->getId(); ?>"
                       role="button" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Delete</a>

                </td>
            </tr>
        <?php endforeach; ?>



        </thead>
    </table>

    <?php
    if (isset($_SESSION['errorInput'])) {
        // Hiển thị thông báo lỗi
        echo '<p style="color: red;">' . $_SESSION['errorInput'] . '</p>';

        // Sau khi hiển thị, xóa thông báo lỗi khỏi session
        unset($_SESSION['errorInput']);
    }
    ?>
</div>

<nav aria-label="Page navigation example">
    <ul class="pagination">

        <?php if ($data['currentPage'] > 1) {?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $data['currentPage'] - 1; ?>">Previous</a>
            </li>
        <?php } else { ?>
            <li class="page-item disabled page-link">Previous</li>
        <?php } ?>



<?php
        $start = max(1, $data['currentPage'] - 2);
        $end = min($data['totalPages'], $data['currentPage'] + 2); ?>


        <?php for ($i = $start; $i <= $end; $i++) { ?>
            <li class="page-item <?= ($i == $data['currentPage'] ? 'active' : '') ?>">
                <a class="page-link" href="?page=<?= $i ?>&keyword=<?= urlencode($data['params']['keyword']); ?>&status=<?= urlencode($data['params']['status']); ?>&sort_by=<?= urlencode($data['params']['sort_by']); ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php } ?>

        <?php if ($data['currentPage'] < $data['totalPages']) { ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $data['currentPage'] + 1; ?>">Next</a>
            </li>
        <?php } else { ?>
            <li class="page-item disabled page-link">Next</li>
        <?php } ?>

    </ul>
</nav>

</body>
</html>
