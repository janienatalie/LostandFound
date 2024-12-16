-- Database untuk Lost and Found Universitas
CREATE DATABASE LostAndFound;
USE LostAndFound;

-- Tabel untuk menyimpan data pengguna
CREATE TABLE users (
    npm VARCHAR(20) PRIMARY KEY, -- NPM sebagai primary key
    nama VARCHAR(100) NOT NULL,
    no_telepon VARCHAR(15) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Tabel untuk menyimpan data admin
CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Tabel untuk menyimpan formulir kehilangan barang
CREATE TABLE kehilangan (
    kehilangan_id INT AUTO_INCREMENT PRIMARY KEY,
    npm VARCHAR(20) NOT NULL, -- Relasi ke tabel users
    lokasi_kampus VARCHAR(100) NOT NULL,
    barang VARCHAR(100) NOT NULL,
    tempat VARCHAR(100) NOT NULL,
    tanggal DATE NOT NULL,
    foto_barang VARCHAR(255),
    FOREIGN KEY (npm) REFERENCES users(npm) -- Foreign key ke tabel users
);

-- Tabel untuk menyimpan formulir penemuan barang
CREATE TABLE penemuan (
    penemuan_id INT AUTO_INCREMENT PRIMARY KEY,
    npm VARCHAR(20) NOT NULL, -- Relasi ke tabel users
    lokasi_kampus VARCHAR(100) NOT NULL,
    barang VARCHAR(100) NOT NULL,
    tempat VARCHAR(100) NOT NULL,
    tanggal DATE NOT NULL,
    foto_barang VARCHAR(255),
    FOREIGN KEY (npm) REFERENCES users(npm) -- Foreign key ke tabel users
);

-- Tabel untuk menyimpan laporan barang oleh pengguna
CREATE TABLE UserReports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    jenis_laporan ENUM('Lost', 'Found') NOT NULL, -- Menentukan apakah laporan kehilangan atau penemuan
    lokasi_kampus VARCHAR(100) NOT NULL,
    barang VARCHAR(100) NOT NULL,
    tempat VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    foto_barang VARCHAR(255), -- Lokasi atau nama file gambar barang
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

-- Tabel untuk menyimpan laporan barang oleh admin
CREATE TABLE AdminReports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    jenis_laporan ENUM('Lost', 'Found') NOT NULL, -- Menentukan apakah laporan kehilangan atau penemuan
    lokasi_kampus VARCHAR(100) NOT NULL,
    barang VARCHAR(100) NOT NULL,
    tempat VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM('Pending', 'Resolved') DEFAULT 'Pending', -- Status laporan
    foto_barang VARCHAR(255), -- Lokasi atau nama file gambar barang
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES Admins(id) ON DELETE CASCADE
);

-- Tabel untuk melacak statistik dashboard admin
CREATE VIEW DashboardStats AS
SELECT 
    (SELECT COUNT(*) FROM LostItems) AS jumlah_kehilangan,
    (SELECT COUNT(*) FROM FoundItems) AS jumlah_penemuan,
    (SELECT COUNT(*) FROM Users) AS jumlah_pengguna;

-- Tabel untuk halaman data pengguna (mirip dengan Users)
CREATE VIEW UserData AS
SELECT 
    id AS nomor_urut,
    nama,
    username,
    npm,
    nomor_telepon
FROM Users;

-- Tabel untuk menampilkan semua formulir (kehilangan dan penemuan)
CREATE VIEW AllForms AS
SELECT 
    l.id AS nomor_urut,
    u.nama,
    u.npm,
    l.lokasi_kampus,
    l.barang,
    l.tempat_kehilangan AS tempat,
    l.tanggal_kehilangan AS tanggal,
    'Lost' AS jenis_laporan
FROM LostItems l
JOIN Users u ON l.user_id = u.id
UNION ALL
SELECT 
    f.id AS nomor_urut,
    u.nama,
    u.npm,
    f.lokasi_kampus,
    f.barang,
    f.tempat_menemukan AS tempat,
    f.tanggal_menemukan AS tanggal,
    'Found' AS jenis_laporan
FROM FoundItems f
JOIN Users u ON f.user_id = u.id;