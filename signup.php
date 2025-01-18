<?php
session_start();
include './database/config.php';

function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = validateInput($_POST['name']);
    $npm = validateInput($_POST['npm']);
    $phone = validateInput($_POST['phone']);
    $username = validateInput($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    $isError = false;
    
    if ($password !== $confirmPassword) {
        echo "<script>alert('Password tidak cocok!');</script>";
        $isError = true;
    }

    // Check both username and npm uniqueness
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM Users WHERE username = ? OR npm = ?");
    $stmt->bind_param("ss", $username, $npm);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        // Double check which one exists
        $stmt = $conn->prepare("SELECT username, npm FROM Users WHERE username = ? OR npm = ?");
        $stmt->bind_param("ss", $username, $npm);
        $stmt->execute();
        $result = $stmt->get_result();
        $existingUser = $result->fetch_assoc();
        
        if ($existingUser['username'] === $username) {
            echo "<script>alert('Username sudah digunakan!');</script>";
        }
        if ($existingUser['npm'] === $npm) {
            echo "<script>alert('NPM sudah terdaftar!');</script>";
        }
        $isError = true;
    }
    $stmt->close();

    if (!$isError) {
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO Users (nama, npm, nomor_telepon, username, password_hash) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nama, $npm, $phone, $username, $passwordHash);
            
            if ($stmt->execute()) {
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
    <style>
        .input-error {
            border-color: #ff0000 !important;
        }
        .error-message {
            color: #ff0000;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        .input-success {
            border-color: #763996 !important;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="image-container">
            <img src="./image/img-login.png" alt="Example Image">
        </div>

        <div class="form-section-sign-up">
            <img src="./image/logo.png" alt="Example Image" style="width: 50%">
            <h2 class='title'>Daftar Akun</h2>

            <form action="signup.php" method="POST" class="form-container" id="signupForm">
                <p>Nama</p>
                <input type="text" id="name" name="name" required>
                
                <p>NPM</p>
                <input type="text" id="npm" name="npm" required maxlength="8">
                <div id="npm-error" class="error-message"></div>
                
                <p>Nomor Telepon</p>
                <input type="text" id="phone" name="phone" required>
                
                <p>Nama Pengguna</p>
                <input type="text" id="username" name="username" required>
                <div id="username-error" class="error-message"></div>

                <p>Kata Sandi</p>
                <input type="password" id="password" name="password" required>

                <p>Konfirmasi Kata Sandi</p>
                <input type="password" id="confirm-password" name="confirm-password" required>
                <div id="password-error" class="error-message"></div>

                <button type="submit" id='submit-button' class="signup-button">Daftar</button>
            </form>

            <p class='info-signup' id='signup-info'>Sudah memiliki akun ? <a href="index.php" style='text-decoration: underline; color: #1C07D2'>Masuk</a></p>
        </div>
    </div>

    <script>
        let typingTimer;
        const doneTypingInterval = 500; // 500ms delay

        // Function to check availability
        async function checkAvailability(type, value) {
            try {
                const formData = new FormData();
                formData.append('type', type);
                formData.append('value', value);

                const response = await fetch('check_availability.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                const input = document.getElementById(type);
                const errorDiv = document.getElementById(`${type}-error`);

                if (data.exists) {
                    input.classList.add('input-error');
                    input.classList.remove('input-success');
                    errorDiv.style.display = 'block';
                    errorDiv.textContent = data.message;
                    return false;
                } else {
                    input.classList.remove('input-error');
                    input.classList.add('input-success');
                    errorDiv.style.display = 'none';
                    return true;
                }
            } catch (error) {
                console.error('Error:', error);
                return false;
            }
        }



        document.getElementById('npm').addEventListener('input', function() {
        clearTimeout(typingTimer);
        const errorDiv = document.getElementById('npm-error');
        
        if (this.value.length !== 8) {
            this.classList.add('input-error');
            this.classList.remove('input-success');
            errorDiv.style.display = 'block';
            errorDiv.textContent = 'NPM harus terdiri dari 8 karakter';
        } else {
            this.classList.remove('input-error');
            this.classList.add('input-success');
            errorDiv.style.display = 'none';
            // Cek ketersediaan NPM hanya jika panjangnya sudah 8
            if (this.value) {
                typingTimer = setTimeout(() => checkAvailability('npm', this.value), doneTypingInterval);
            }
        }
    });

        // Add event listeners for real-time validation
        document.getElementById('username').addEventListener('input', function() {
            clearTimeout(typingTimer);
            if (this.value) {
                typingTimer = setTimeout(() => checkAvailability('username', this.value), doneTypingInterval);
            }
        });

        document.getElementById('npm').addEventListener('input', function() {
            clearTimeout(typingTimer);
            if (this.value) {
                typingTimer = setTimeout(() => checkAvailability('npm', this.value), doneTypingInterval);
            }
        });

        // Password confirmation validation
        document.getElementById('confirm-password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const errorDiv = document.getElementById('password-error');
            
            if (this.value !== password) {
                this.classList.add('input-error');
                this.classList.remove('input-success');
                errorDiv.style.display = 'block';
                errorDiv.textContent = 'Password tidak cocok';
            } else {
                this.classList.remove('input-error');
                this.classList.add('input-success');
                errorDiv.style.display = 'none';
            }
        });

        // Form submission handling
        document.getElementById('signupForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const npm = document.getElementById('npm').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            // Check both username and npm availability
            const isUsernameAvailable = await checkAvailability('username', username);
            const isNpmAvailable = await checkAvailability('npm', npm);
            
            // Check password match
            const passwordsMatch = password === confirmPassword;
            
            if (!isUsernameAvailable || !isNpmAvailable || !passwordsMatch) {
                return false;
            }

            // If all validations pass, submit the form
            this.submit();
        });
    </script>
</body>
</html>