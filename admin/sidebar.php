<?php
// Get current page name for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost and Found - Admin</title>
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        .sidebar-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 20%;
            height: 600px;
            background-color: #763996;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .logo-container {
            padding: 30px 20px;
            text-align: center;
        }

        .logo-container img {
            max-width: 80%;
            height: auto;
        }

        .menu-items {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .menu-item:hover {
            background: linear-gradient(0deg, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.1) 100%), #763996;
        }

        .menu-item.active {
            background: linear-gradient(0deg, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.3) 100%), #763996;
        }

        .menu-item img {
            height: 40px;
            width: auto;
            margin-right: 13px;
        }

        .menu-text {
            font-size: 16px;
            font-weight: 500;
        }

        .menu-logout {
            margin-top: auto;
            margin-bottom: 50px;
        }

        #mobile-notice {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: white;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        @media (max-width: 40rem) {
            #mobile-notice {
                display: flex;
            }
            .sidebar-container {
                display: none;
            }
        }

        .main-content {
            margin-left: 22%;
        }
    </style>
        <script>
    function confirmLogout() {
        if (confirm('Apakah Anda yakin ingin keluar?')) {
            window.location.href = 'logout.php';
        }
        return false;
    }
    </script>
</head>
<body>
    <!-- Mobile Notice -->
    <div id="mobile-notice">
        <h5>Admin panel doesn't show in mobile view</h5>
    </div>

    <!-- Sidebar -->
    <div class="sidebar-container">
        <nav class="sidebar-menu">
            <div class="logo-container">
                <img src="../image/logo2.png" alt="Lost and Found Logo">
            </div>
            
            <div class="menu-items">
                <a href="dashboard.php" class="menu-item <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                    <img src="../image/icon/dashboard.png" alt="Dashboard">
                    <span class="menu-text">Dasbor</span>
                </a>
                
                <a href="userdata.php" class="menu-item <?php echo ($current_page == 'userdata.php') ? 'active' : ''; ?>">
                    <img src="../image/icon/user.png" alt="User Data">
                    <span class="menu-text">Data Pengguna</span>
                </a>
                
                <a href="form.php" class="menu-item <?php echo ($current_page == 'form.php') ? 'active' : ''; ?>">
                    <img src="../image/icon/form.png" alt="Form">
                    <span class="menu-text">Formulir</span>
                </a>
                
                <a href="reports.php" class="menu-item <?php echo ($current_page == 'reports.php') ? 'active' : ''; ?>">
                    <img src="../image/icon/reports.png" alt="Reports">
                    <span class="menu-text">Laporan</span>
                </a>
                
                <a href="javascript:void(0)" onclick="confirmLogout()" class="menu-item menu-logout">
                    <img src="../image/icon/logout.png" alt="Logout">
                    <span class="menu-text">Keluar</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content Container -->
    <div class="main-content">
        <!-- Content of each page will go here -->
    </div>
</body>
</html>