<!---buat nampilin data di userdatahtml-->

<?php
include 'config.php';

header('Content-Type: application/json');

$query = "SELECT userID, name, username, npm, phone FROM signup";
$result = mysqli_query($conn, $query);

$users = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}

echo json_encode($users);
mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
  <head>
    <title>Data Pengguna</title>
    <link rel="stylesheet" href="/admin/css/userdata.css" />
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
          placeholder="Cari..."
          id="searchInput"
          onkeyup="searchTable()"
        />
        <i class="fas fa-search"></i>
      </div>

      <div class="listofitemstable table-responsive-xl">
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
          <tbody id="tableBody">
            <!-- Data akan dimasukkan di sini secara dinamis -->
          </tbody>
        </table>
      </div>
    </div>

    <script>
      // Fungsi untuk mengambil data pengguna dari server
      function fetchUsers() {
        fetch("get_users.php")
          .then((response) => response.json())
          .then((data) => {
            const tbody = document.getElementById("tableBody");
            tbody.innerHTML = "";

            data.forEach((user, index) => {
              const row = document.createElement("tr");
              row.innerHTML = `
                <td>${index + 1}</td>
                <td>${user.name}</td>
                <td>${user.username}</td>
                <td>${user.npm}</td>
                <td>${user.phone}</td>
              `;
              tbody.appendChild(row);
            });
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }

      // Fungsi untuk mencari data dalam tabel
      function searchTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toUpperCase();
        const table = document.getElementById("userTable");
        const tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
          let matchFound = false;
          const td = tr[i].getElementsByTagName("td");

          // Periksa kolom Nama, Nama Pengguna, dan NPM (indeks 1, 2, dan 3)
          for (let j = 1; j <= 3; j++) {
            const cell = td[j];
            if (cell) {
              const txtValue = cell.textContent || cell.innerText;
              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                matchFound = true;
                break;
              }
            }
          }

          tr[i].style.display = matchFound ? "" : "none";
        }
      }

      // Panggil fetchUsers saat halaman dimuat
      document.addEventListener("DOMContentLoaded", fetchUsers);
    </script>
  </body>
</html>
