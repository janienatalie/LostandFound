<?php
$isFirstLogin = true;  
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
            <form class="form-container">
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
