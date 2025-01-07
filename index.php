<?php
include './database/config.php';
session_start(); // Start the session
$isFirstLogin = true;

// Proses jika formulir dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $user_type = trim($_POST['user_type']);

    // Validasi data
    if (empty($username) || empty($password)) {
        echo "<script>alert('Harap isi semua kolom.');</script>";
    } else {
        // Query berdasarkan user_type
        if ($user_type === 'admin') {
            $query = "SELECT id, username, password_hash FROM Admins WHERE username = ?";
        } else {
            $query = "SELECT id, username, password_hash FROM users WHERE username = ?";
        }

        // Prepare and execute the query
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user_type;

            echo "<script>alert('Berhasil Login.'); window.location.href = './user/home.php';</script>";
        } else {
            echo "<script>alert('Nama pengguna atau kata sandi tidak valid.'); window.location.href = 'index.php';</script>";;
        }
            
        $stmt->close();
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
        <div class="image-container">
            <img src="./image/img-login.png" alt="Example Image">
        </div>

        <div class="form-section">
            <img src="./image/logo.png" alt="Example Image" class="image-logo">
            <h2 class="title">Masuk</h2>

            <!-- Button option -->
            <div style="display: flex">
                <button class="button-option-admin <?php echo $isFirstLogin ? 'active' : ''; ?>" id="button-option-admin">Admin</button>
                <button class="button-option-user" id="button-option-user">Pengguna</button>
            </div>

            <!-- Field -->
            <form class="form-container" method="POST" action="" id="login-form">
                <input type="hidden" id="user_type" name="user_type" value="admin">
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
