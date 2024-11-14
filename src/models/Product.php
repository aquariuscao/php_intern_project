<?php


class Product {
    public $id;
    public $name;
    public $status;
    public $image;

    public function __construct($id, $name, $status,$image) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->image=$image;
    }
    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status){
        $this->status = $status;
    }
    public function getImage(){
        return $this->image;
    }
    public function setImage($image){
        $this->image=$image;
    }







}