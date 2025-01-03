<!DOCTYPE html>
<html lang="en">
  <head>
    <head>
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="/user/css/form.css" />
      <!-- loading bar -->
      <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
      <link rel="stylesheet" href="../css/style.css" />
      <!-- fontowesome -->
      <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
      />
      <title>Lost and Found - List of Items</title>
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
            <li><a href="#">Form Kehilangan</a></li>
            <li><a href="#" class="active">Form Penemuan</a></li>
            <li><a href="#">Daftar Barang</a></li>
          </ul>
        </nav>
      </div>
      </header> -->

      <h2>Form Penemuan</h2>
      <p>
        Formulir ini digunakan untuk melaporkan barang yang Anda temukan di
        kampus. Jika Anda menemukan barang yang hilang, isilah informasi di
        bawah ini dengan benar dan jelas agar pemiliknya dapat menemukannya
        kembali.
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
            >Barang yang ditemukan <span class="required">*</span></label
          >
          <input type="text" id="barang" name="barang" required />
        </div>
        <div class="form-group">
          <label for="tempat"
            >Tempat Menemukan <span class="required">*</span></label
          >
          <input type="text" id="tempat" name="tempat" required />
        </div>
        <div class="form-group">
          <label for="tanggal"
            >Tanggal Menemukan <span class="required">*</span></label
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
    // Menangkap data dari form
    $lokasi_kampus = $_POST['lokasi'];
    $barang_ditemukan = $_POST['barang'];
    $tempat_ditemukan = $_POST['tempat'];
    $tanggal_ditemukan = $_POST['tanggal'];
    $foto = $_FILES['foto'];

    // Validasi input
    if (empty($lokasi_kampus) || empty($barang_ditemukan) || empty($tempat_ditemukan) || empty($tanggal_ditemukan)) {
        die("Semua input wajib diisi.");
    }

    // Proses upload file
    $foto_ditemukan = null;
    if (!empty($foto['name'])) {
        $target_dir = "uploads/";

        // Memeriksa apakah folder uploads ada
        if (!is_dir($target_dir)) {
            // Membuat folder jika belum ada
            mkdir($target_dir, 0777, true);
        }

        $foto_ditemukan = $target_dir . time() . "_" . basename($foto["name"]);
        $image_file_type = strtolower(pathinfo($foto_ditemukan, PATHINFO_EXTENSION));

        // Validasi jenis file
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($image_file_type, $allowed_types)) {
            die("Hanya format JPG, JPEG, PNG, dan GIF yang diperbolehkan.");
        }

        // Validasi ukuran file
        if ($foto['size'] > 5000000) {  // Maksimal 5MB
            die("File terlalu besar (maksimal 5MB).");
        }

        // Pindahkan file ke folder tujuan
        if (!move_uploaded_file($foto["tmp_name"], $foto_ditemukan)) {
            die("Gagal mengunggah file.");
        }
    }

    // Simpan data ke dalam database
    $stmt = $conn->prepare("INSERT INTO form_found (lokasi_kampus, barang_ditemukan, tempat_ditemukan, tanggal_ditemukan, foto_ditemukan) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $lokasi_kampus, $barang_ditemukan, $tempat_ditemukan, $tanggal_ditemukan, $foto_ditemukan);

    if ($stmt->execute()) {
      echo "<script>alert('Formulir berhasil disimpan.');</script>";;
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>