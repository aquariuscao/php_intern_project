<?php


require_once './config/config.php';
require_once './src/controllers/ProductController.php';
require_once './src/core/Controller.php';
require_once './src/core/Connection.php';


$controller = new ProductController();


// Lấy hành động từ query string
//$action = isset($_GET['action']) ? $_GET['action'] : 'index';
if(isset($_GET['action'])){
    $action=$_GET['action'];
}else{
    $action='';
}
// Gọi phương thức tương ứng trong controller
switch ($action) {
    case 'add':
        $controller->add();
        break;
    case 'delete':
        $controller->delete();
        break;
    case 'edit':
        $controller->edit();
        break;
    default:
        $controller->index();
        break;
}
