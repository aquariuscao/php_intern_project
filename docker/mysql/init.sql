-- init.sql

-- Tạo cơ sở dữ liệu (nếu chưa tồn tại)
CREATE DATABASE IF NOT EXISTS mvc_php;

-- Sử dụng cơ sở dữ liệu vừa tạo
USE mvc_php;

-- Tạo bảng `product`
CREATE TABLE IF NOT EXISTS product (
                                       id INT AUTO_INCREMENT PRIMARY KEY,
                                       name VARCHAR(255) NOT NULL,
    status VARCHAR(50) NOT NULL,
    image VARCHAR(255)
    );

-- Chèn dữ liệu mẫu vào bảng `product`
INSERT INTO product (name, status, image) VALUES
                                       ('Iphone', 'in stock',''),
                                       ('Macbook', 'in stock',''),
                                       ('Samsung', 'out of stock',''),
                                    ('Headphone','in stock',''),
('Laptop','in stock','');
