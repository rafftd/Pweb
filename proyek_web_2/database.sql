-- Buat database
CREATE DATABASE nusantara_cuisine;
USE nusantara_cuisine;

-- Tabel admin
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel kategori
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Menambahkan kategori
INSERT INTO categories (name, description) VALUES 
('Makanan', 'Berbagai jenis makanan'),
('Minuman', 'Berbagai jenis minuman');

-- Tabel makanan
CREATE TABLE dishes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    origin VARCHAR(100) NOT NULL,
    description TEXT,
    ingredients TEXT,
    cooking_time VARCHAR(50),
    difficulty VARCHAR(20),
    calories VARCHAR(50),
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Tabel gallery
CREATE TABLE gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dish_id INT,
    image VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dish_id) REFERENCES dishes(id) ON DELETE CASCADE
);

-- Insert sample admin
INSERT INTO admin (username, password) VALUES ('admin', '');
