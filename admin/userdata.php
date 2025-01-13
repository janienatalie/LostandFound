<?php
include '../database/config.php';
include './sidebar.php';
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Data Pengguna</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="./css/userdata.css">    
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    />
  </head>
  <body>
    <div class="container">
      <h2>Data Pengguna</h2>
      <div class="search-box">
        <input
          type="text"
          placeholder="Cari"
          id="searchInput"
          onkeyup="searchTable()"
        />
        <i class="fas fa-search"></i>
      </div>
      <table id="userTable">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Nama Pengguna</th>
            <th>NPM</th>
            <th>Nomor Handphone</th>
          </tr>
        </thead>
        <tbody>
          <!-- Data pengguna akan ditambahkan di sini -->
        </tbody>
      </table>
    </div>
    <script>
      function searchTable() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("userTable");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
          td = tr[i].getElementsByTagName("td")[1];
          if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
            } else {
              tr[i].style.display = "none";
            }
          }
        }
      }
    </script>
  </body>
</html>
