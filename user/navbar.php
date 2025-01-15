<?php
$currentPage = basename($_SERVER['PHP_SELF']);

// Logout logic
if (isset($_GET['logout'])) {
    session_start();
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    .navbar {
        position: fixed;
        top: 0;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 50px;
        background: transparent;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .navbar .logo .logo-image {
        height: 50px;
        width: auto;
    }

    .navbar nav ul {
        list-style: none;
        display: flex;
        gap: 20px;
        align-items: center;
        margin: 0;
        padding: 0;
    }

    .navbar nav ul li {
        position: relative;
    }

    .navbar nav ul li a {
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        color: #763996;
        transition: all 0.3s ease;
    }

    /* Style untuk hover */
    .navbar nav ul li a:hover {
        color: #ff9934;
        text-decoration: none;
    }

    /* Style untuk menu active */
    .navbar nav ul li.active a {
        color: #ff9934;
    }

    /* Garis bawah untuk menu active */
    .navbar nav ul li.active::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -8px;
        width: 100%;
        height: 2px;
        background-color: #ff9934;
    }

    /* Style untuk navbar saat di-scroll */
    .navbar-scrolled {
        background-color: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar nav ul li a::selection {
        display: none;
    }

    /* Style untuk semua button di navbar */
    button.nav-button {
        background-color: #763996;
        color: #ff9934;
        padding: 8px 20px;
        border: 2px solid #763996;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-block;
        outline: none;
        text-decoration: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        height: 40px; /* Tetapkan tinggi spesifik */
        line-height: 1; /* Untuk vertical alignment teks */
        margin: 0; /* Reset margin */
        white-space: nowrap; /* Mencegah text wrapping */
        min-width: 100px; /* Lebar minimum */
        font-family: inherit; /* Memastikan font konsisten */
    }  

    button.nav-button:hover {
        background-color: transparent;
        color: #763996;
        border: 2px solid #763996;
        text-decoration: none;
    }

    /* Remove the active underline style for button container */
    .navbar nav ul li:last-child::after {
        display: none;
    }

    .navbar nav ul li:last-child {
        display: flex;
        align-items: center;
        margin: 0;
        padding: 0;
        height: auto;
    }

    /* Reset potensial conflicting styles */
    .navbar nav ul li button.nav-button * {
        box-sizing: border-box;
    }

    .navbar button.nav-button:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(118, 57, 150, 0.3);
    }
    </style>

    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });

        // Confirmation before logout
        function confirmLogout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                window.location.href = '?logout=true';
            }
        }
    </script>
</head>
<body>
<<<<<<< HEAD
    <!-- Header Section -->
    <header class="navbar">
  <div class="logo">
    <img src="logo.png" alt="Lost & Found Logo" class="logo-image" />
  </div>
  <nav>
    <ul>
      <li><a href="#" class="nav-link">Beranda</a></li>
      <li><a href="#" class="nav-link">Formulir Kehilangan</a></li>
      <li><a href="#" class="nav-link">Form Penemuan</a></li>
      <li><a href="#" class="nav-link">Daftar Barang</a></li>
    </ul>
  </nav>
</header>
=======
    <header>
        <div class="navbar">
            <div class="logo">
                <a href="home.php">
                    <img src="../image/logo.png" alt="Lost & Found Logo" class="logo-image">
                </a>
            </div>
            <nav>
                <ul>
                    <li class="<?php echo $currentPage == 'home.php' ? 'active' : ''; ?>">
                        <a href="home.php">Beranda</a>
                    </li>
                    <li class="<?php echo $currentPage == 'formlost.php' ? 'active' : ''; ?>">
                        <a href="formlost.php">Formulir Kehilangan</a>
                    </li>
                    <li class="<?php echo $currentPage == 'formfound.php' ? 'active' : ''; ?>">
                        <a href="formfound.php">Formulir Penemuan</a>
                    </li>
                    <li class="<?php echo $currentPage == 'listofitems.php' ? 'active' : ''; ?>">
                        <a href="listofitems.php">Daftar Barang</a>
                    </li>
                    <li>
                        <button onclick="confirmLogout()" class="nav-button">Keluar</button>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</body>
</html>
>>>>>>> fc82328d596223ec76fa8d2885a7eee64cf1eb0f
