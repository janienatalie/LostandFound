<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins&display=swap");
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lost & Found - Gunadarma University</title>
    <link rel="stylesheet" href="./css/form.css" />
    <link rel="stylesheet" href="/css/style.css" />
  </head>
  <body>
    <div class="form-section">
      <!-- Header Section -->
      <!-- <header>
      <div class="navbar">
        <div class="logo">
          <img
            src="/image/logo.png"
            alt="Lost & Found Logo"
            class="logo-image"
          />
        </div>
        <nav>
          <ul>
            <li><a href="#">Beranda</a></li>
            <li><a href="#" class="active">Form Kehilangan</a></li>
            <li><a href="#">Form Penemuan</a></li>
            <li><a href="#">Daftar Barang</a></li>
          </ul>
        </nav>
      </div>
    </header> -->

      <h2>Form Kehilangan</h2>
      <p>
        Formulir ini digunakan untuk melaporkan barang-barang yang hilang di
        lingkungan kampus. Jika Anda kehilangan barang, isilah informasi di
        bawah ini dengan benar dan jelas.
      </p>
      <form
        action=""
        method="POST"
        enctype="multipart/form-data"
        class="form-laporan"
      >
        <div class="form-group">
          <label for="Lokasi"
            >Lokasi Kampus <span class="required">*</span></label
          >
          <select id="lokasi" name="lokasi" required>
            <option value="">Pilih Lokasi Kampus</option>
            <option value="Kampus A">Kampus D</option>
            <option value="Kampus B">Kampus E</option>
            <option value="Kampus F4">Kampus F4</option>
            <option value="Kampus F8">Kampus F8</option>
            <option value="Kampus C">Kampus G</option>
            <option value="Kampus D">Kampus H</option>
          </select>
        </div>

        <div class="form-group">
          <label for="barang"
            >Barang yang Hilang <span class="required">*</span></label
          >
          <input type="text" id="barang" name="barang" required />
        </div>
        <div class="form-group">
          <label for="tempat"
            >Tempat Kehilangan <span class="required">*</span></label
          >
          <input type="text" id="tempat" name="tempat" required />
        </div>
        <div class="form-group">
          <label for="tanggal"
            >Tanggal Kehilangan <span class="required">*</span></label
          >
          <input type="date" id="tanggal" name="tanggal" required />
        </div>
        <div class="form-group">
          <label for="foto">Foto Barang </label>
          <input type="file" id="foto" name="foto" />
        </div>

        <button type="submit">Kirim</button>
      </form>
      <!-- Footer Section -->
      <!-- <footer>
      <p>Copyright &copy; <b>Lost And Found.</b> Kelompok 6.</p>
    </footer> -->
    </div>
  </body>
</html>

<?php

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lokasi_kampus = $_POST['lokasi'];
    $barang_hilang = $_POST['barang'];
    $tempat_hilang = $_POST['tempat'];
    $tanggal_hilang = $_POST['tanggal'];
    $foto = $_FILES['foto'];

    // Validasi input
    if (empty($lokasi_kampus) || empty($barang_hilang) || empty($tempat_hilang) || empty($tanggal_hilang)) {
        die("Semua input wajib diisi.");
    }

    // Proses upload file
    $foto_hilang = null;
    if (!empty($foto['name'])) {
    $target_dir = "uploads/";

    // Memeriksa apakah folder uploads ada
    if (!is_dir($target_dir)) {
        // Membuat folder jika belum ada
        mkdir($target_dir, 0777, true);
    }

    $foto_hilang = $target_dir . time() . "_" . basename($foto["name"]);
    $image_file_type = strtolower(pathinfo($foto_hilang, PATHINFO_EXTENSION));

        // Validasi jenis file
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($image_file_type, $allowed_types)) {
            die("Hanya format JPG, JPEG, PNG, dan GIF yang diperbolehkan.");
        }

        // Validasi ukuran file
        if ($foto['size'] > 5000000) {  
            die("File terlalu besar (maksimal 5MB).");
        }

        // Pindahkan file ke folder tujuan
        if (!move_uploaded_file($foto["tmp_name"], $foto_hilang)) {  
            die("Gagal mengunggah file.");
        }   
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO form_lost (lokasi_kampus, barang_hilang, tempat_hilang, tanggal_hilang, foto_hilang) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $lokasi_kampus, $barang_hilang, $tempat_hilang, $tanggal_hilang, $foto_hilang);  // Menggunakan $foto_hilang untuk path foto

    if ($stmt->execute()) {
       echo "<script>alert('Formulir berhasil dikirim.');</script>";;
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>