<?php
session_start();
include './database/config.php';

// Function to validate input
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and validate form data
    $nama = validateInput($_POST['name']);
    $npm = validateInput($_POST['npm']);
    $phone = validateInput($_POST['phone']);
    $username = validateInput($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Initialize error array
    $isError = false;
    
    // Validate required fields
    // if (empty($nama)) {
    //     echo "<script>alert('Nama harus diisi!');</script>";
    //     $isError = true;
    // }
    // if (empty($npm)) {
    //     echo "<script>alert('NPM harus diisi!');</script>";
    //     $isError = true;
    // }
    // if (empty($phone)) {
    //     echo "<script>alert('Nomor Telepon harus diisi!');</script>";
    //     $isError = true;
    // }
    // if (empty($username)) {
    //     echo "<script>alert('Username harus diisi!');</script>";
    //     $isError = true;
    // }
    // if (empty($password)) {
    //     echo "<script>alert('Password harus diisi!');</script>";
    //     $isError = true;
    // }

    // Validate password match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Password tidak cocok!');</script>";
        $isError = true;
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        echo "<script>alert('Username sudah digunakan!');</script>";
        $isError = true;
    }
    $stmt->close();

    // If no errors, proceed with registration
    if (!$isError) {
        try {
            // Hash the password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement
            $stmt = $conn->prepare("INSERT INTO Users (nama, npm, nomor_telepon, username, password_hash) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nama, $npm, $phone, $username, $passwordHash);
            
            // Execute statement with form data
            if ($stmt->execute()) {
                // Set success message
                echo "<script>
                    alert('Registrasi berhasil! Silahkan login.');
                    window.location.href = 'index.php';
                </script>";
                exit();
            } else {
                echo "<script>alert('Registrasi gagal: " . $stmt->error . "');</script>";
            }
            $stmt->close();

        } catch(Exception $e) {
            echo "<script>alert('Registrasi gagal: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}

// Close database connection
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
              <input type="text" id="phone" name="phone">
                
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