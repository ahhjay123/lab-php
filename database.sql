CREATE DATABASE IF NOT EXISTS inventory_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventory_db;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sku VARCHAR(40) NOT NULL UNIQUE,
  name VARCHAR(120) NOT NULL,
  category_id INT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  quantity INT NOT NULL DEFAULT 0,
  reorder_level INT NOT NULL DEFAULT 5,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

INSERT IGNORE INTO categories (name) VALUES ('Office Supplies'), ('Electronics'), ('Groceries');

INSERT IGNORE INTO products (sku, name, category_id, price, quantity, reorder_level) VALUES
('OS-001', 'Ballpoint Pen (Box of 12)', 1, 120.00, 40, 10),
('OS-002', 'A4 Bond Paper (Ream)', 1, 260.00, 4, 10),
('EL-001', 'USB Flash Drive 32GB', 2, 350.00, 15, 5),
('GR-001', 'Instant Coffee 200g', 3, 185.50, 0, 6);
-- Create your admin account from register.php (first run).
