<?php
include '../database/config.php'; // Pastikan jalur ke config.php benar
session_start();
?>

<!DOCTYPE html>
<html lang="id">
  <head>
  <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Load style.css terlebih dahulu -->
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="./css/navbar.css" />
    <!-- Kemudian CSS spesifik halaman -->
    <!-- loading bar -->
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <link rel="stylesheet" href="./css/form.css" />
<script>
function validateFile(input) {
  const allowedTypes = ['image/jpeg','image/jpg'];
  const maxSize = 5 * 1024 * 1024; // 5MB in bytes
  const file = input.files[0];
  const validationMessage = document.getElementById('fileValidationMessage');
  
  // Reset styling dan pesan
  input.classList.remove('invalid');
  validationMessage.textContent = '';
  
  if (!file) {
    return;
  }

  // Validasi tipe file
  if (!allowedTypes.includes(file.type)) {
    input.classList.add('invalid');
    validationMessage.textContent = 'Hanya format JPG dan JPEG yang diperbolehkan.';
    input.value = ''; // Clear input
    return;
  }

  // Validasi ukuran file
  if (file.size > maxSize) {
    input.classList.add('invalid');
    validationMessage.textContent = 'File terlalu besar (maksimal 5MB).';
    input.value = ''; // Clear input
    return;
  }
}
</script>
    <title>Lost and Found - List of Items</title>
  </head>
  <body>
  <?php include './navbar.php';?>
    <div class="form-section" >
      <h2>Formulir Penemuan</h2>
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
            <option value="Kampus D">Kampus D</option>
            <option value="Kampus E">Kampus E</option>
            <option value="Kampus F8">Kampus F8</option>
            <option value="Kampus G">Kampus G</option>
            <option value="Kampus H">Kampus H</option>
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
        <input type="file" id="foto" name="foto" onchange="validateFile(this)" accept="image/jpeg,image/jpg,image/png,image/gif"/>
        <div id="fileValidationMessage" class="validation-message"></div>
      </div>

        <button type="submit">Kirim</button>
      </form>
    </div>
    <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $lokasi_kampus = $_POST['lokasi'];
    $barang_ditemukan = $_POST['barang'];
    $tempat_menemukan = $_POST['tempat'];
    $tanggal_menemukan = $_POST['tanggal'];
    $foto = $_FILES['foto'];

    if (empty($lokasi_kampus) || empty($barang_ditemukan) || empty($tempat_menemukan) || empty($tanggal_menemukan)) {
        die("Semua input wajib diisi.");
    }

    // Proses upload file
    $foto_barang = null;
    if (!empty($foto['name'])) {
        $target_dir = "../uploads/";

        // Memeriksa apakah folder uploads ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $foto_barang = $target_dir . time() . "_" . basename($foto["name"]);
        $image_file_type = strtolower(pathinfo($foto_barang, PATHINFO_EXTENSION));

        // Validasi jenis file
        // $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        //   if (!in_array($image_file_type, $allowed_types)) {
        //       die("Hanya format JPG, JPEG, PNG, dan GIF yang diperbolehkan.");
        //   }

        // Validasi ukuran file
          // if ($foto['size'] > 5000000) {
          //     die("File terlalu besar (maksimal 5MB).");
          // }

        // Pindahkan file ke folder tujuan
          if (!move_uploaded_file($foto["tmp_name"], $foto_barang)) {
              die("Gagal mengunggah file.");
          }
      
    

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO FoundItems (user_id, lokasi_kampus, barang_ditemukan, tempat_menemukan, tanggal_menemukan, foto_barang) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $lokasi_kampus, $barang_ditemukan, $tempat_menemukan, $tanggal_menemukan, $foto_barang);

    if ($stmt->execute()) {
        echo "<script>alert('Formulir berhasil dikirim.');</script>";
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
  }
  
}
  
    $conn->close();
    
    ?>
    <?php include './footer.php';?>

    
  </body>
  
</html>