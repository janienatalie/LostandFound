<?php
include '../database/config.php';
include './sidebar.php';
session_start();

// $usermail="";
// $usermail=$_SESSION['usermail'];
// if($usermail == true){

// }else{
//   header("location: http://localhost/lostandfound/index.php");
// }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/dashboard.css" />
    <link rel="stylesheet" href="/css/" />
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
    <div class="titlepage">
      <h2>Dasbor</h2>
    </div>

    <div class="rectangle">
      <h2>Jumlah Barang yang Hilang</h2>
      <h3>28</h3>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="81"
        height="78"
        viewBox="0 0 81 78"
        fill="none"
      >
        <path
          d="M53.2486 68.334H48.8987C47.7451 68.334 46.6387 67.895 45.8229 67.1136C45.0071 66.3322 44.5488 65.2724 44.5488 64.1673V22.5007C44.5488 21.3956 45.0071 20.3358 45.8229 19.5544C46.6387 18.773 47.7451 18.334 48.8987 18.334H74.9982C76.1518 18.334 77.2582 18.773 78.074 19.5544C78.8898 20.3358 79.3481 21.3956 79.3481 22.5007V30.834"
          stroke="white"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
        <path
          d="M66.2984 18.3337V5.83366C66.2984 4.72859 65.8401 3.66878 65.0243 2.88738C64.2085 2.10598 63.1021 1.66699 61.9485 1.66699H5.39971C4.24604 1.66699 3.13963 2.10598 2.32386 2.88738C1.5081 3.66878 1.0498 4.72859 1.0498 5.83366V55.8337C1.0498 56.9387 1.5081 57.9985 2.32386 58.7799C3.13963 59.5613 4.24604 60.0003 5.39971 60.0003H44.5488M70.6483 76.667V76.7087M70.6483 64.167C72.5983 64.161 74.4899 63.5286 76.0203 62.371C77.5507 61.2133 78.6314 59.5973 79.0895 57.7817C79.5476 55.9661 79.3565 54.0557 78.547 52.3564C77.7374 50.657 76.3561 49.267 74.6241 48.4087C72.8936 47.5595 70.9141 47.2963 69.0075 47.6617C67.101 48.0271 65.3796 48.9996 64.1234 50.4212M57.5986 22.5003H66.2984"
          stroke="white"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
      </svg>
    </div>
    <div class="rectangle">
      <h2>Jumlah Barang yang Ditemukan</h2>
      <h3>10</h3>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="81"
        height="74"
        viewBox="0 0 81 74"
        fill="none"
      >
        <path
          d="M44.5488 49.584V22.5007C44.5488 21.3956 45.0071 20.3358 45.8229 19.5544C46.6387 18.773 47.7451 18.334 48.8987 18.334H74.9982C76.1518 18.334 77.2582 18.773 78.074 19.5544C78.8898 20.3358 79.3481 21.3956 79.3481 22.5007V39.1673"
          stroke="white"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
        <path
          d="M66.2984 18.3337V5.83366C66.2984 4.72859 65.8401 3.66878 65.0243 2.88738C64.2086 2.10598 63.1021 1.66699 61.9485 1.66699H5.39971C4.24604 1.66699 3.13963 2.10598 2.32386 2.88738C1.5081 3.66878 1.0498 4.72859 1.0498 5.83366V55.8337C1.0498 56.9387 1.5081 57.9985 2.32386 58.7799C3.13963 59.5613 4.24604 60.0003 5.39971 60.0003H35.849M57.5986 22.5003H66.2984M53.2487 64.167L61.9485 72.5003L79.3481 55.8337"
          stroke="white"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
      </svg>
    </div>
    <div class="rectangle">
      <h2>Jumlah Pengguna</h2>
      <h3>28</h3>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="90"
        height="86"
        viewBox="0 0 90 86"
        fill="none"
      >
        <mask
          id="mask0_511_1514"
          style="mask-type: luminance"
          maskUnits="userSpaceOnUse"
          x="0"
          y="0"
          width="90"
          height="86"
        >
          <path
            d="M23.4492 63.8333C29.4552 63.8333 34.324 59.1696 34.324 53.4167C34.324 47.6637 29.4552 43 23.4492 43C17.4433 43 12.5745 47.6637 12.5745 53.4167C12.5745 59.1696 17.4433 63.8333 23.4492 63.8333Z"
            fill="#555555"
            stroke="white"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            d="M66.9482 63.8333C72.9542 63.8333 77.823 59.1696 77.823 53.4167C77.823 47.6637 72.9542 43 66.9482 43C60.9423 43 56.0735 47.6637 56.0735 53.4167C56.0735 59.1696 60.9423 63.8333 66.9482 63.8333Z"
            fill="#555555"
            stroke="white"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            d="M45.1987 22.1663C51.2047 22.1663 56.0735 17.5026 56.0735 11.7497C56.0735 5.99671 51.2047 1.33301 45.1987 1.33301C39.1928 1.33301 34.324 5.99671 34.324 11.7497C34.324 17.5026 39.1928 22.1663 45.1987 22.1663Z"
            fill="#555555"
            stroke="white"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            d="M1.69971 84.666C1.69971 73.1598 11.437 63.8327 23.4492 63.8327C35.4615 63.8327 45.1988 73.1598 45.1988 84.666C45.1988 73.1598 54.936 63.8327 66.9483 63.8327C78.9605 63.8327 88.6978 73.1598 88.6978 84.666M66.9483 42.9993C66.9483 31.4931 57.211 22.166 45.1988 22.166C33.1865 22.166 23.4492 31.4931 23.4492 42.9993"
            stroke="white"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </mask>
        <g mask="url(#mask0_511_1514)">
          <path d="M-7 -7H97.3977V93H-7V-7Z" fill="white" />
        </g>
      </svg>
    </div>
  </body>

  <script src="./javascript/script.js"></script>
</html>
