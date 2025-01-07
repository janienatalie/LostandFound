SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS lostandfound;
CREATE DATABASE IF NOT EXISTS lostandfound;

DROP USER IF EXISTS 'lostandfound_user'@'%';
CREATE USER IF NOT EXISTS 'lostandfound_user'@'%' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, UPDATE, DELETE ON lostandfound.* TO 'lostandfound_user'@'%';
USE lostandfound;


-- Tabel untuk menyimpan data pengguna
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    npm VARCHAR(20) NOT NULL,
    nomor_telepon VARCHAR(15) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL -- Kata sandi akan disimpan dalam bentuk hash
);

INSERT INTO `users`(`id`, `nama`, `npm`, `nomor_telepon`, `username`, `password_hash`) VALUES (1,'kevin', '10122645', '08963818319343', 'kevin', 'kevin123');

-- Tabel untuk menyimpan data admin
CREATE TABLE Admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL -- Kata sandi admin juga disimpan dalam bentuk hash

);

INSERT INTO `admins`(`id`, `username`, `password_hash`) VALUES (1,'admin','admin123');

-- Tabel untuk menyimpan formulir kehilangan barang
CREATE TABLE LostItems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lokasi_kampus ENUM('Kampus E', 'Kampus D', 'Kampus G', 'Kampus H', 'Kampus F8'),
    barang_hilang VARCHAR(100) NOT NULL,
    tempat_kehilangan VARCHAR(255) NOT NULL,
    tanggal_kehilangan DATE NOT NULL,
    foto_barang VARCHAR(255), -- Lokasi atau nama file gambar barang
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

-- Tabel untuk menyimpan formulir penemuan barang
CREATE TABLE FoundItems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lokasi_kampus ENUM('Kampus E', 'Kampus D', 'Kampus G', 'Kampus H', 'Kampus F8')
    barang_ditemukan VARCHAR(100) NOT NULL,
    tempat_menemukan VARCHAR(255) NOT NULL,
    tanggal_menemukan DATE NOT NULL,
    foto_barang VARCHAR(255), -- Lokasi atau nama file gambar barang
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);