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
        <div class="form-section">
            <img src="./image/logo.png" alt="Example Image" class="image-logo">
            <h2 class='title'>Masuk</h2>

            <!-- button option -->
            <div style='display: flex'> 
                <button class='button-option-admin <?php echo $isFirstLogin ? 'active' : ''; ?>' id='button-option-admin'>Admin</button>
                <button class='button-option-user' id='button-option-user'>Pengguna</button>
            </div>
            <!--  -->

            <!-- field -->
            <form class="form-container" method="POST" action="server/auth/sign-in.php" id="login-form">
              <p>Nama Pengguna</p>
              <input type="text" id="username" name="username">
                
              <p>Kata Sandi </p>
              <input type="password" id="password" name="password"><br>
                
              <button type="submit" id='submit-buton'>Masuk</button>
            </form>
                
            <p class='info' id='signup-info'>Belum punya akun ? <a  href="signup.php" style='text-decoration: underline; color: #1C07D2'>Daftar</a></p>
        </div>
        <!-- end -->
    </div>

    <!-- script -->
    <script>
        document.getElementById('button-option-admin').addEventListener('click', function() {
            this.classList.add('active'); // action set active class
            document.getElementById('button-option-user').classList.remove('active');
            toggleSignUpInfo();  // call the function to toggle sign-up info visibility
        });

        document.getElementById('button-option-user').addEventListener('click', function() {
            this.classList.add('active'); // action set active class
            document.getElementById('button-option-admin').classList.remove('active');
            toggleSignUpInfo();  // call the function to toggle sign-up info visibility
        });

        function toggleSignUpInfo() {
            const adminButton = document.getElementById('button-option-admin');
            const signUpInfo = document.getElementById('signup-info');

            if (adminButton.classList.contains('active')) {
                signUpInfo.style.display = 'none';  // hide sign-up info when 'Admin' button is active
            } else {
                signUpInfo.style.display = 'block';  // show sign-up info when 'User' button is active
            }
        }

        toggleSignUpInfo();
        
        // script login 
        document.getElementById('button-option-admin').addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('button-option-user').classList.remove('active');
            document.getElementById('user_type').value = 'admin'; // Set user_type to admin
            toggleSignUpInfo();
        });

        document.getElementById('button-option-user').addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('button-option-admin').classList.remove('active');
            document.getElementById('user_type').value = 'user'; // Set user_type to user
            toggleSignUpInfo();
        });

        // Prevent default form submission and handle via JavaScript
        document.getElementById('login-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            const response = await fetch(this.action, {
                method: this.method,
                body: formData,
            });
            
            const result = await response.json();
                window.location.href = '/lostandfound/dashboard.php'; 


            if (result.success) {
                window.location.href = '/lostandfound/dashboard.php'; 
            } else {
                alert(result.message); 
            }
        });
    </script>

    
</body>
</html>
