<?php
include '../database/config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found - Gunadarma University</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="./css/navbar.css" />
    <!-- <link rel="stylesheet" href="../css/footer.css" /> -->
    <link rel="stylesheet" href="./css/home.css">
</head>
<body>
<?php include './navbar.php';?>

    <!-- Hero Section -->
    <section class="hero">
        <img src="../image/gundarhd.png" alt="Building Image" class="hero-image">
    </section>
            <div class="header">
                Universitas Gunadarma Lost And Found
                <div class="line"></div>
            </div>
            <div class="container">
        <div class="text-section">
            <p>
                Website Lost and Found Universitas Gunadarma membantu mahasiswa memulihkan barang-barang yang hilang di kampus. 
                Pengguna dapat melaporkan dan mencari barang yang hilang atau ditemukan, berbagi detail seperti deskripsi dan 
                lokasi untuk menyederhanakan pemulihan. Platform ini membina komunitas kampus yang mendukung dengan membuat 
                lebih mudah dan cepat untuk menyatukan kembali orang-orang dengan barang-barang mereka.
            </p>
        </div>
        <div class="image-section">
            <img src="../image/gundar_build.png" alt="Gunadarma University Building">
        </div>
    </div>

    <section class="complaint-service">
        <div class="title-container">
            <h1 class="title">Layanan Pengaduan Barang Hilang dan Ditemukan</h1>
            <div class="underline"></div>
        </div>

    <!-- Procedures Section -->
    <section class="procedures-container">
        <!-- Lost Items Procedure -->
        <div class="procedure-card">
            <h2>Prosedur Oprasional</h2>
            <h3 class="lost-items">Barang Hilang</h3>
            
        <p class="paragraf">Jika Anda kehilangan barang di Universitas Gunadarma, ikuti langkah-langkah berikut:</p> 
        <ol> 
            <li>Jika merasa kehilangan, ingat kapan terakhir kali Anda bersama barang tersebut.</li> 
            <li>Laporkan dengan mengisi formulir berikut.</li> 
            <li>Isi formulir secara rinci, termasuk nama barang, lokasi, dan waktu terakhir kali terlihat agar 
                Admin dapat menghubungi Anda lebih lanjut.</li> 
            <li>Tunggu informasi selanjutnya jika barang tersebut ditemukan.</li> 
        </ol>
        </div>

        <!-- Found Items Procedure -->
        <div class="procedure-card">
            <h2>Prosedur Oprasional</h2>
            <h3 class="find-items">Menemukan Barang</h3>
            <p class="paragraf">Jika Anda menemukan barang hilang di kawasan Universitas Gunadarma, ikuti langkah-langkah berikut:</p>
            <ol>
                <li>Jika Anda menemukan barang yang hilang, amankan barang tersebut.</li>
                <li>Laporkan dengan mengisi formulir berikut.</li>
                <li>Isi formulir dengan lengkap, meliputi nama barang, lokasi, dan waktu ditemukannya untuk memudahkan Admin menghubungi Anda lebih lanjut.</li>
                <li>Setelah dilaporkan melalui link tersebut, mohon kesediaannya untuk menyerahkan barang tersebut ke gedung Kampus Lost & Found.</li>
            </ol>
        </div>
        </section>
    </main>
    <?php include './footer.php';?>
</body>
</html>
