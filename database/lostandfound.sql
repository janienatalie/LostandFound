SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS lostandfound;
CREATE DATABASE IF NOT EXISTS lostandfound;

DROP USER IF EXISTS 'lostandfound_user'@'%';
CREATE USER IF NOT EXISTS 'lostandfound_user'@'%' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, UPDATE, DELETE ON lostandfound.* TO 'lostandfound_user'@'%';
USE lostandfound;

-- Table structure for admin login --
CREATE TABLE `adm_login` (
  `admID` int NOT NULL AUTO_INCREMENT,
  `adm_usn` varchar(50) NOT NULL,
  `adm_password` varchar(255) NOT NULL,
  PRIMARY KEY (`admID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table `adm_login` --
INSERT INTO `adm_login` (`admID`, `adm_usn`, `adm_password`) VALUES
(1, 'admin', 'admin123');

-- Table structure for user signup --
CREATE TABLE `signup` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `npm` varchar(8) NOT NULL UNIQUE,
  `phone` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table `signup` --
INSERT INTO `signup` (`userID`, `name`, `npm`, `phone`, `username`, `password`) VALUES
(1, 'janie', '10122654', '089630770165', 'janie123', 'janie');


-- Table structure for form_lost --
CREATE TABLE form_lost (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lokasi_kampus ENUM('Kampus E', 'Kampus D', 'Kampus G', 'Kampus H', 'Kampus F8'),
    barang_hilang VARCHAR(255) NOT NULL,
    tempat_hilang TEXT,
    tanggal_hilang DATE,
    foto_hilang MEDIUMBLOB
);

-- Table structure for form_found --
CREATE TABLE form_found (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lokasi_kampus ENUM('Kampus E', 'Kampus D', 'Kampus G', 'Kampus H', 'Kampus F8'),
    barang_ditemukan VARCHAR(255) NOT NULL,
    tempat_ditemukan TEXT,
    tanggal_ditemukan DATE,
    foto_ditemukan MEDIUMBLOB
);

-- Commit transaction --
COMMIT;


