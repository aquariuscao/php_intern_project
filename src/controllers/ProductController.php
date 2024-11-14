<?php
session_start();
require_once './src/repositories/ProductRepository.php';
require_once __DIR__ . '/../models/Product.php';
require_once './src/core/Connection.php';
require_once './src/core/Controller.php';

use Core\Controller;

class ProductController extends Controller
{
    private $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }


    public function index()
    {

//        $statuses = $this->productRepository->getAllStatus();
        $data = $this->productRepository->getAllProducts();
        $params = [
            'keyword' => isset($_GET['keyword']) ? $_GET['keyword'] : '',
            'status' => isset($_GET['status']) ? $_GET['status'] : '',
            'sort_by' => isset($_GET['sort_by']) ? $_GET['sort_by'] : ''
        ];
        $products = [];
        foreach ($data as $product) {
            $products[] = new Product($product['id'], $product['name'], $product['status'], $product['image']);
        }

        $this->listPage($params);



    }


    public function checkImage(){
//        $imageName = null;
//        if ($_FILES['image']['error'] == UPLOAD_ERR_INI_SIZE || $_FILES['image']['error'] == UPLOAD_ERR_FORM_SIZE) {
//            $_SESSION['errorInput'] = "Tệp tải lên quá lớn! Vui lòng chọn tệp có kích thước nhỏ hơn 10MB.";
//            header('Location: /index.php?action=add');
//        } else {
//            if (isset($_FILES['image'])) {
//                if ($_FILES['image']['error'] === 0) {
//                    $image = $_FILES['image'];
//                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
//                    $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
//
//                    // Kiểm tra loại file
//                    if (!in_array($fileExtension, $allowedExtensions)) {
//                        $_SESSION['errorInput'] = "Chỉ chấp nhận file hình ảnh (jpg, jpeg, png, gif).";
//                        header('Location: /index.php?action=add');
//                        exit();
//                    } elseif ($image['size'] > 5 * 1024 * 1024) {
//                        $_SESSION['errorInput'] = "File quá lớn. Kích thước tối đa là 5MB.";
//                        header('Location: /index.php?action=add');
//                        exit();
//                    } else {
//                        // Upload file
//                        $imageName = $this->uploadImage($image);
//                        if (!$imageName) {
//                            $_SESSION['errorInput'] = "Không thể upload ảnh.";
//                            header('Location: /');
//                        }
//                    }
//                }
//            } else {
//                $imageName = " ";
//            }
//        }
//        return $imageName;

        $imageName=null;
        if (isset($_FILES['image'])) {
                if ($_FILES['image']['error'] === 0) {
                    $image = $_FILES['image'];
                    $imageName = $this->uploadImage($image);
                    if (!$imageName) {
                        $_SESSION['errorInput'] = "Không thể upload ảnh.";
                        header('Location: /');
                    }
                }
        }
        else{
            $imageName = " ";
        }
        return $imageName;
    }

    public function add()
    {
        $statuses = $this->productRepository->getAllStatus();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $status = $_POST['status'];

            $imageName=$this->checkImage();

                $product = $this->productRepository->addProduct(new Product(null, $name, $status, $imageName));

                if (!$product) {
                    $_SESSION['errorInput'] = "Thêm sản phẩm thất bại vì tên sản phẩm đã tồn tại!";
                    header('Location: /?action=add');
                    exit();
                } else {
                    header('Location: /');
                }
            }
        $this->view('add_product', ['statuses' => $statuses]);
    }


    public function uploadImage($image)
    {
        // Tạo thư mục với quyền ghi
        $uploadDir = './uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Tạo tên file duy nhất để tránh trùng lặp
        $imageName = uniqid() . '.' . strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $uploadFile = $uploadDir . $imageName;

        // Di chuyển file từ thư mục tạm thời vào thư mục uploads
        if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
            return $uploadFile; // Trả về đường dẫn của file
        } else {
            return null;
        }
    }

    public function edit()
    {
        $statuses = $this->productRepository->getAllStatus();
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $data = $this->productRepository->getProductById($id);
            $product = new Product($data['id'], $data['name'], $data['status'], $data['image']);


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $name = $_POST['name'];
                $status = $_POST['status'];

                if (isset($_POST['delete-image']) && $_POST['delete-image'] === 'true') {
                    // Nếu có, xóa ảnh cũ (xóa file ảnh khỏi server)
                    $imageName = null; // Không lưu ảnh nữa
                    $imagePath = $product->getImage();
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // Xóa ảnh
                    }
                }else {
                        $imageName = $this->checkImage();
                        if ($imageName === null || $imageName === " ") {
                            $imageName = $product->getImage();
                        }
                    }

                $productToEdit = new Product($id, $name, $status,$imageName);
                $count = $this->productRepository->editProduct($productToEdit);
                if (!$count) {
                    $_SESSION['errorInput'] = "Sửa sản phẩm thất bại vì trùng tên!";
                    header('Location:/ ');
                    exit();
                } else {
                    header('Location: /');
                }
            }
            $this->view('edit_product', ['product' => $product, 'statuses' => $statuses]);
        }
    }


    public function delete()
    {

        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $this->productRepository->deleteProduct($id);

            header('Location: /');
            exit();
        }
    }

    public
    function listPage($params)
    {

        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $statuses = $this->productRepository->getAllStatus();
        $limit = 5;


        $totalProducts = $this->productRepository->getTotalProduct($params);

        $totalPage = ceil($totalProducts/ $limit);


        $offset = ($currentPage - 1) * $limit;
        $data = $this->productRepository->getProductsByPage($offset, $limit, $params);

        $products = [];

        if($data) {
            foreach ($data as $product) {
                $products[] = new Product($product['id'], $product['name'], $product['status'], $product['image']);
            }
        }
        else {
            $_SESSION['errorInput'] = "Không tồn tại sản phẩm";
            }

        $this->view('list_products', [
            'products' => $products,
            'currentPage' => $currentPage,
            'totalPages' => $totalPage,
            'totalProducts'=>$totalProducts,
            'statuses' => $statuses,
            'params' => $params
        ]);
    }



}