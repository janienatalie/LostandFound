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
    }

    .navbar nav ul li a::selection {
        display: none;
    }

    /* Style untuk tombol logout */
    .logout-btn {
        display: inline-block;
        background-color: #763996;
        color: #ff9934 !important; /* Override default link color */
        padding: 8px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        margin-left: 20px;
    }

    .logout-btn:hover {
        background-color: #ff9934;
        color: #763996 !important; /* Override default link color */
        text-decoration: none;
    }

    /* Remove the active underline style for logout button */
    .navbar nav ul li:last-child::after {
        display: none;
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
                        <button onclick="confirmLogout()" class="logout-btn">Keluar</button>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</body>
</html>