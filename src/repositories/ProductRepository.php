<?php

require_once './src/models/Product.php';
require_once './src/core/Connection.php';
//require_once './src/Source.php';


class ProductRepository extends Connection
{


    public function getAllProducts()
    {
        if (!isset($this->pdo) || !$this->pdo instanceof PDO) {
            throw new Exception('Kết nối cơ sở dữ liệu chưa được thiết lập.');
        }
        $stmt = $this->pdo->prepare("SELECT * FROM product ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductByName($name)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM product WHERE name = :name');
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    public function getProductByIdAndName($id, $name)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM product WHERE name = :name AND id != :id');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM product WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function addProduct($product)
    {
        $check = $this->getProductByName($product->getName());
        if ($check) {
            return false;
        } else {
            $stmt = $this->pdo->prepare('INSERT INTO product (name, status, image) VALUES (:name, :status, :image)');

            $name = $product->getName();
            $status = $product->getStatus();
            $image = $product->getImage();

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':image', $image);

            $stmt->execute();
            return true;
        }
    }


    public function deleteProduct($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM product WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function editProduct($product)
    {
        $check = $this->getProductByIdAndName($product->getId(), $product->getName());
        if ($check) {
            return false;
        } else {
            $stmt = $this->pdo->prepare('UPDATE product SET name = :name, status = :status, image = :image WHERE id = :id');

            $id = $product->getId();
            $name = $product->getName();
            $status = $product->getStatus();
            $image = $product->getImage();

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':image', $image);
            $stmt->execute();
            return true;
        }
    }

    public function getAllStatus()
    {
        $stmt = $this->pdo->prepare('SELECT DISTINCT status FROM product ORDER BY status ASC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchProduct($keyword)
    {
        $keyword = "%" . $keyword . "%";
        $stmt = $this->pdo->prepare('SELECT * FROM product WHERE name LIKE :keyword OR id LIKE :keyword ORDER BY id ASC');
        $stmt->bindParam('keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function filterByStatus($status)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM product WHERE status = :status');
        $stmt->bindParam('status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getProductsByPage($offset, $limit, $params = [])
    {
        $query = "SELECT * FROM product";


        // Kiểm tra nếu có tham số tìm kiếm hoặc lọc
        if ( $params != null ) {
            if (isset($params['keyword']) && !empty($params['keyword'])) {
                $query .= " WHERE name LIKE :keyword";
            }
            if (isset($params['status']) && !empty($params['status'])) {
                $query .= empty($params['keyword']) ? " WHERE" : " AND";
                $query .= " status = :status";
            }
            if(isset($params['sort_by']) && !empty($params['sort_by'])){
                switch ($params['sort_by']) {
                    case 'name_asc':
                        $query .= " ORDER BY name ASC";
                        break;
                    case 'name_desc':
                        $query .= " ORDER BY name DESC";
                        break;
                    case 'status_asc':
                        $query .= " ORDER BY status ASC";
                        break;
                    case 'status_desc':
                        $query .= " ORDER BY status DESC";
                        break;
                }
            }
        }

        $query .= " LIMIT :offset, :limit";


        $stmt = $this->pdo->prepare($query);


        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $stmt->bindValue(':keyword', '%' . $params['keyword'] . '%');
        }
        if (isset($params['status']) && !empty($params['status'])) {
            $stmt->bindValue(':status', $params['status']);
        }
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalProduct($params)
    {

        $query = "SELECT COUNT(*) FROM product";
        // Kiểm tra nếu có tham số tìm kiếm hoặc lọc
        if ($params) {
            // Nếu có từ khóa tìm kiếm
            if (isset($params['keyword']) && !empty($params['keyword'])) {
                $query .= " WHERE name LIKE :keyword";
            }

            // Nếu có lọc theo trạng thái
            if (isset($params['status']) && !empty($params['status'])) {
                $query .= empty($params['keyword']) ? " WHERE" : " AND";
                $query .= " status = :status";
            }
        }

        // Chuẩn bị và thực thi câu truy vấn
        $stmt = $this->pdo->prepare($query);

        // Gắn tham số nếu có
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $stmt->bindValue(':keyword', '%' . $params['keyword'] . '%');
        }
        if (isset($params['status']) && !empty($params['status'])) {
            $stmt->bindValue(':status', $params['status']);
        }

        $stmt->execute();
        return $stmt->fetchColumn();

    }
}