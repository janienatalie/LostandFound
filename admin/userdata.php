<?php
include '../database/config.php';
include './sidebar.php';

// Query untuk mengambil semua data user
$query = "SELECT * FROM Users ORDER BY id ASC";
$result = mysqli_query($conn, $query);

// Cek apakah query berhasil
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Data Pengguna</title>   
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="./css/userdata.css"> 
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

      <div class="table-wrapper">

      <table class="userTable">
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
          <?php 
          if (mysqli_num_rows($result) > 0) {
              $no = 1;
              while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr>";
                  echo "<td>" . $no . "</td>";
                  echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['npm']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['nomor_telepon']) . "</td>";
                  echo "</tr>";
                  $no++;
              }
          } else {
              echo "<tr><td colspan='5' style='text-align: center;'>Tidak ada data pengguna</td></tr>";
          }
          ?>
        </tbody>
      </table>
      </div>
    </div>

    <script>
    function searchTable() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toLowerCase();
        table = document.getElementById("userTable");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) { // Start from 1 to skip header row
            tr[i].style.display = "none"; // Hide row by default
            td = tr[i].getElementsByTagName("td");
            for (j = 0; j < td.length; j++) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = ""; // Show row if match found
                    break; // No need to check other columns if match found
                }
            }
        }
    }
    </script>
  </body>
</html>