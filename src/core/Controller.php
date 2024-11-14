<?php
namespace Core;

class Controller {
    public function model($model, $subfolder = '') {
        $path = './src/models/' . ($subfolder ? $subfolder . '/' : '') . $model . '.php';
        if (file_exists($path)) {
            require_once $path;
            return new $model;
        } else {
            throw new Exception("Model file not found: " . $path);
        }
    }

    public function view($view, $data = []) {
        require_once './src/views/'. $view . '.php';
    }
}