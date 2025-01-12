<?php
include '../database/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Assuming user is logged in and user_id is stored in session
    $lokasi_kampus = $_POST['lokasi'];
    $barang_hilang = $_POST['barang'];
    $tempat_kehilangan = $_POST['tempat'];
    $tanggal_kehilangan = $_POST['tanggal'];
    $foto = $_FILES['foto'];

    // Validasi input
    if (empty($lokasi_kampus) || empty($barang_hilang) || empty($tempat_kehilangan) || empty($tanggal_kehilangan)) {
        die("Semua input wajib diisi.");
    }

    // Proses upload file
    $foto_barang = null;
    if (!empty($foto['name'])) {
        $target_dir = "uploads/";

        // Memeriksa apakah folder uploads ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $foto_barang = $target_dir . time() . "_" . basename($foto["name"]);
        $image_file_type = strtolower(pathinfo($foto_barang, PATHINFO_EXTENSION));

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
        if (!move_uploaded_file($foto["tmp_name"], $foto_barang)) {
            die("Gagal mengunggah file.");
        }
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO LostItems (user_id, lokasi_kampus, barang_hilang, tempat_kehilangan, tanggal_kehilangan, foto_barang) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $lokasi_kampus, $barang_hilang, $tempat_kehilangan, $tanggal_kehilangan, $foto_barang);

    if ($stmt->execute()) {
        echo "<script>alert('Formulir berhasil dikirim.');</script>";
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>


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
    <link rel="stylesheet" href="../css/style.css" />
  </head>
  <body>
  <?php include './navbar.php';?>
    <div class="form-section">
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
            <option value="Kampus D">Kampus D</option>
            <option value="Kampus E">Kampus E</option>
            <option value="Kampus F8">Kampus F8</option>
            <option value="Kampus G">Kampus G</option>
            <option value="Kampus H">Kampus H</option>
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
    </div>
    <?php include './footer.php';?>
  </body>
</html>
