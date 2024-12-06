<?php
$isFirstLogin = true;  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/auth.css">

</head>
<body>
    <div class="auth-container">
        <!-- image -->
        <div>
          <img src="../../assets/img/img-login.png" alt="Example Image" class="image-container">
        </div>
        <!-- end -->

        <!-- form -->
        <div class="form-section">
            <img src="../../assets/img/logo-gundar.svg" alt="Example Image" style="width: 50%">
            <h2 class='title'>Sign Up Account</h2>

            <!-- field -->
            <form class="form-container">
              <p>Name</p>
              <input type="text" id="name" name="name">
                
              <p>NPM</p>
              <input type="text" id="npm" name="npm">
                
              <p>Phone Number</p>
              <input type="number" id="phone" name="phone">
                
              <p>username</p>
              <input type="text" id="username" name="username">

              <p>password </p>
              <input type="password" id="password" name="password"><br>

              <p>Confirm Password </p>
             <input type="password" id="confirm-password" name="confirm-password"><br>

              <button type="submit" id='submit-buton'>Sign Up</button>
            </form>

            <p class='info' id='signup-info'>Have an account ? <a  href="/auth/sign-in" style='text-decoration: underline; color: #1C07D2'>Login</a></p>
        </div>
        <!-- end -->
    </div>
</body>
</html>
