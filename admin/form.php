<!---buat nampilin data di formhtml-->

<?php
include 'config.php';

header('Content-Type: application/json');

$query = "SELECT i.*, u.name, u.npm FROM items i
          JOIN signup u ON i.userID = u.userID
          ORDER BY i.date DESC";
$result = mysqli_query($conn, $query);

$items = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
}

echo json_encode($items);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>List Of Items</title>
    <link rel="stylesheet" href="/admin/css/form.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    />
  </head>
  <body>
    <div class="container">
      <h2>Daftar Barang</h2>
      <div class="search-container">
        <div class="dropdown-lost-and-found">
          <select name="lost-found" class="dropdownlostandfound" id="dropdown">
            <option hidden value="" disabled selected>
              Kehilangan atau Penemuan
            </option>
            <option value="Lost">Kehilangan</option>
            <option value="Found">Penemuan</option>
          </select>
        </div>
        <div class="search-box">
          <input
            type="text"
            placeholder="Cari..."
            id="searchInput"
            onkeyup="searchTable()"
          />
          <i class="fas fa-search"></i>
        </div>
      </div>
      <div class="listofitemstable table-responsive-xl">
        <table id="itemTable">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>NPM</th>
              <th>Kampus</th>
              <th>Item</th>
              <th>Lokasi</th>
              <th>Tanggal</th>
              <th>Foto Barang</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <!-- Data akan dimasukkan di sini secara dinamis -->
          </tbody>
        </table>
      </div>
    </div>

    <script>
      // Fungsi untuk mengambil data barang dari server
      function fetchItems() {
        fetch("get_items.php")
          .then((response) => response.json())
          .then((data) => {
            const tbody = document.getElementById("tableBody");
            tbody.innerHTML = "";

            data.forEach((item, index) => {
              const row = document.createElement("tr");
              row.setAttribute("data-status", item.status);
              row.innerHTML = `
                <td>${index + 1}</td>
                <td>${item.name}</td>
                <td>${item.npm}</td>
                <td>${item.campus}</td>
                <td>${item.item_name}</td>
                <td>${item.location}</td>
                <td>${item.date}</td>
                <td><img src="${
                  item.image_url
                }" alt="Item Image" style="max-width: 100px;"></td>
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
        const searchValue = document
          .getElementById("searchInput")
          .value.toLowerCase();
        const rows = document.querySelectorAll("#tableBody tr");

        rows.forEach((row) => {
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(searchValue) ? "" : "none";
        });
      }

      // Fungsi untuk memfilter berdasarkan status (Lost/Found)
      function filterByDropdown() {
        const selectedValue = document.getElementById("dropdown").value;
        const rows = document.querySelectorAll("#tableBody tr");

        rows.forEach((row) => {
          const status = row.getAttribute("data-status");
          if (selectedValue === "" || status === selectedValue) {
            row.style.display = "";
          } else {
            row.style.display = "none";
          }
        });
      }

      // Event listeners
      document.addEventListener("DOMContentLoaded", fetchItems);
      document
        .getElementById("dropdown")
        .addEventListener("change", filterByDropdown);
      document
        .getElementById("searchInput")
        .addEventListener("input", searchTable);
    </script>
  </body>
</html>
