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

// Process login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = validateInput($_POST['username']);
    $password = $_POST['password'];
    $userType = validateInput($_POST['user_type']);
    
    try {
        if ($userType === 'admin') {
            // Admin login
            $stmt = $conn->prepare("SELECT * FROM Admins WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                if ($password === $user['password_hash']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_type'] = 'admin';
                    
                    header("Location: ./admin/dashboard.php");
                    exit();
                } else {
                    echo "<script>alert('Password salah!');window.location.href = 'index.php';</script>";
                }
            } else {
                echo "<script>alert('Username admin tidak ditemukan!');window.location.href = 'index.php';</script>";
            }
            $stmt->close();
        } else {
            // Regular user login
            $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                if (password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_type'] = 'user';
                    $_SESSION['nama'] = $user['nama'];
                    
                    header("Location: ./user/home.php");
                    exit();
                } else {
                    echo "<script>alert('Password salah!');window.location.href = 'index.php';</script>";
                }
            } else {
                echo "<script>alert('Username tidak ditemukan!');window.location.href = 'index.php';</script>";
            }
            $stmt->close();
        }
    } catch(Exception $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
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
        <div class="image-container">
            <img src="./image/img-login.png" alt="Example Image">
        </div>

        <div class="form-section">
            <img src="./image/logo.png" alt="Example Image" class="image-logo">
            <h2 class="title">Masuk</h2>

            <!-- Button option -->
            <div style="display: flex">
                <button class="button-option-admin" id="button-option-admin">Admin</button>
                <button class="button-option-user active" id="button-option-user">Pengguna</button>
            </div>

            <!-- Field -->
            <form class="form-container" method="POST" action="" id="login-form">
                <input type="hidden" id="user_type" name="user_type" value="user">
                <p>Nama Pengguna</p>
                <input type="text" id="username" name="username" required>

                <p>Kata Sandi</p>
                <input type="password" id="password" name="password" required><br>

                <button type="submit" id="submit-button">Masuk</button>
            </form>

            <p class="info" id="signup-info">Belum punya akun? <a href="signup.php" style="text-decoration: underline; color: #1C07D2">Daftar</a></p>
        </div>
    </div>

    <script>
        const adminButton = document.getElementById('button-option-admin');
        const userButton = document.getElementById('button-option-user');
        const userTypeInput = document.getElementById('user_type');
        const signUpInfo = document.getElementById('signup-info');

        adminButton.addEventListener('click', () => {
            adminButton.classList.add('active');
            userButton.classList.remove('active');
            userTypeInput.value = 'admin';
            toggleSignUpInfo();
        });

        userButton.addEventListener('click', () => {
            userButton.classList.add('active');
            adminButton.classList.remove('active');
            userTypeInput.value = 'user';
            toggleSignUpInfo();
        });

        function toggleSignUpInfo() {
            if (adminButton.classList.contains('active')) {
                signUpInfo.style.display = 'none';
            } else {
                signUpInfo.style.display = 'block';
            }
        }

        toggleSignUpInfo(); // Initialize the state
    </script>
</body>
</html>