<!-- <?php

// include '../config.php';
// session_start();

// $usermail="";
// $usermail=$_SESSION['usermail'];
// if($usermail == true){

// }else{
//   header("location: http://localhost/hotelmanage_system/index.php");
// }

?> -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/admin.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <!-- loading bar -->
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="/css/style.css" />
    <!-- fontowesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
      integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <title>Lost and Found - Admin</title>
  </head>

  <body>
    <!-- mobile view -->
    <div id="mobileview">
        <h5>Admin panel doesn't show in mobile view</h4>
    </div>

    <!-- side bar -->
    <nav class="sidenav">
      <ul>
        <div class="logo">
          <img class="lostandfoundlogo" src="../image/logo2.png" alt="logo" />
        </div>
        <li class="pagebtn active">
          <img src="../image/icon/dashboard.png" />&nbsp&nbsp&nbsp Dasbor
        </li>
        <li class="pagebtn">
          <img src="..\image\icon\user.png" />&nbsp&nbsp&nbsp Data Pengguna
        </li>
        <li class="pagebtn">
          <img src="../image/icon/form.png" />&nbsp&nbsp&nbsp Formulir
        </li>
        <li class="pagebtn">
          <img src="../image/icon/reports.png" />&nbsp&nbsp&nbsp Laporan
        </li>
        <li class="pagebtnlogout">
          <img src="../image/icon/logout.png" />&nbsp&nbsp&nbsp Keluar
        </li>
      </ul>
    </nav>

    <!-- main section -->
    <div class="mainscreen">
      <iframe
        class="frames frame1 active"
        src="./dashboard.php"
        frameborder="0"
      ></iframe>
      <iframe
        class="frames frame2"
        src="./userdata.html"
        frameborder="0"
      ></iframe>
      <iframe
        class="frames frame3"
        src="./form.html"
        frameborder="0"
      ></iframe>
      <iframe class="frames frame4" src="./reports.html" frameborder="0"></iframe>
      <iframe class="frames frame4" src="./index.php" frameborder="0"></iframe>
    </div>
  </body>

  <script src="./javascript/script.js"></script>
</html>
