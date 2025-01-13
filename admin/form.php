<?php
include '../database/config.php';
include './sidebar.php';
?>

<html>
  <head>
    <title>List Of Items</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="./css/form.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    />
  </head>
  <body>
    <div class="container">
      <h2>Daftar Barang</h2>
      <div class="search-container">
        <div class="dropdown">
          <select>
            <option value="" disabled selected>Hilang atau Ditemukan</option>
            <option value="hilang">Hilang</option>
            <option value="ditemukan">Ditemukan</option>
          </select>
        </div>
        <div class="search-box">
          <input
            type="text"
            placeholder="Cari"
            id="searchInput"
            onkeyup="searchTable()"
          />
          <i class="fas fa-search"></i>
        </div>
      </div>
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NPM</th>
            <th>Kampus</th>
            <th>Item</th>
            <th>Lokasi</th>
            <th>Tanggal</th>
            <th></th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </body>
</html>