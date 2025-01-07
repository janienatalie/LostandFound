<!-- File: includes/navbar.php -->
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
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
                        <a href="formfound.php">Form Penemuan</a>
                    </li>
                    <li class="<?php echo $currentPage == 'listofitems.php' ? 'active' : ''; ?>">
                        <a href="listofitems.php">Daftar Barang</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</body>
</html>