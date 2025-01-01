<?php
// Koneksi ke database
$host = "localhost"; // Nama host database
$user = "root"; // Username database
$password = ""; // Password database
$database = "coba"; // Nama database

$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses jika formulir dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $nama = trim($_POST['name']);
    $npm = trim($_POST['npm']);
    $nomor_telepon = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);

    // Validasi data
    if (empty($nama) || empty($npm) || empty($nomor_telepon) || empty($username) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('Semua field harus diisi.');</script>";
    } elseif ($password !== $confirm_password) {
        echo "<script>alert('Password dan konfirmasi password tidak cocok.'); window.location.href = 'signup.php';</script>";
    } else {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk menyimpan data
        $sql = "INSERT INTO Users (nama, npm, nomor_telepon, username, password_hash) VALUES (?, ?, ?, ?, ?)";

        // Gunakan prepared statement untuk mencegah SQL Injection
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssss", $nama, $npm, $nomor_telepon, $username, $password_hash);

            // Eksekusi statement
            if ($stmt->execute()) {
                echo "<script>alert('Pendaftaran berhasil. Silakan login.'); window.location.href = 'index.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan: " . $stmt->error . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Terjadi kesalahan: " . $conn->error . "');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="./css/auth.css">
    <link rel="stylesheet" href="./css/style.css">


</head>
<body>
    <div class="auth-container">
        <!-- image -->
        <div class="image-container">
          <img src="./image/img-login.png" alt="Example Image">
        </div>
        <!-- end -->

        <!-- form -->
        <div class="form-section-sign-up">
            <img src="./image/logo.png" alt="Example Image" style="width: 50%">
            <h2 class='title'>Daftar Akun</h2>

            <!-- field -->
            <form action="signup.php" method="POST" class="form-container">

              <p>Nama</p>
              <input type="text" id="name" name="name">
                
              <p>NPM</p>
              <input type="text" id="npm" name="npm">
                
              <p>Nomor Telepon</p>
              <input type="number" id="phone" name="phone">
                
              <p>Nama Pengguna</p>
              <input type="text" id="username" name="username">

              <p>Kata Sandi</p>
              <input type="password" id="password" name="password">

              <p>Konfirmasi Kata Sandi</p>
             <input type="password" id="confirm-password" name="confirm-password"><br>

              <button type="submit" id='submit-buton'>Daftar</button>
            </form>

            <p class='info-signup' id='signup-info'>Sudah memiliki akun ? <a  href="index.php" style='text-decoration: underline; color: #1C07D2'>Masuk</a></p>
        </div>
        <!-- end -->
    </div>
</body>
</html>
