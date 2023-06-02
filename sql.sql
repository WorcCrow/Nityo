CREATE DATABASE productsdb;

USE productsdb;

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  unit VARCHAR(100) NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  expiry_date DATE NOT NULL,
  inventory INT NOT NULL,
  image VARCHAR(255) NOT NULL
);