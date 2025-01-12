<?php
include '../database/config.php';
include './sidebar.php';
?>

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
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <script>
      document.querySelectorAll(".dropdown").forEach((dropdown) => {
        const select = dropdown.querySelector("select");

        select.addEventListener("focus", () => {
          dropdown.classList.add("active");
        });

        select.addEventListener("blur", () => {
          dropdown.classList.remove("active");
        });
      });

      // Fungsi untuk melakukan pencarian
      function searchTable() {
        // Ambil nilai dari input pencarian dan ubah ke huruf kecil untuk pencocokan
        const searchValue = document
          .querySelector(".search-container input")
          .value.toLowerCase();

        // Ambil semua baris dalam tabel (kecuali header)
        const tableRows = document.querySelectorAll("table tbody tr");

        // Loop melalui setiap baris tabel
        tableRows.forEach((row) => {
          // Ambil teks dari kolom Items
          const itemsText = row.children[4].textContent.toLowerCase();

          // Cek apakah nilai pencarian ada di kolom Items
          if (itemsText.includes(searchValue)) {
            // Tampilkan baris jika cocok
            row.style.display = "";
          } else {
            // Sembunyikan baris jika tidak cocok
            row.style.display = "none";
          }
        });
      }

      // Fungsi untuk menangani tombol "Has Been Found"
      function markAsFound(button) {
        const row = button.closest("tr");
        row.style.backgroundColor = "#d4edda"; // Ubah warna baris sebagai tanda sudah ditemukan
        button.disabled = true; // Nonaktifkan tombol setelah ditekan
        button.textContent = "Found"; // Ubah teks tombol
      }

      // Fungsi untuk menangani tombol "Delete"
      function deleteRow(button) {
        const row = button.closest("tr");
        row.remove(); // Hapus baris dari tabel
      }

      // Fungsi untuk memfilter tabel berdasarkan status
      function filterTable(status) {
        const tableRows = document.querySelectorAll("table tbody tr");

        tableRows.forEach((row) => {
          if (row.getAttribute("data-status") === status || status === "") {
            row.style.display = "";
          } else {
            row.style.display = "none";
          }
        });
      }

      // Tambahkan event listener ke tombol pencarian
      document
        .querySelector(".search-container button")
        .addEventListener("click", searchTable);

      // Opsional: Tambahkan event listener ke input untuk pencarian langsung saat mengetik
      document
        .querySelector(".search-container input")
        .addEventListener("input", searchTable);

      // Tambahkan event listener ke tombol "Has Been Found" dan "Delete"
      document
        .querySelectorAll("table .action-buttons button")
        .forEach((button) => {
          if (button.textContent.includes("Has Been Found")) {
            button.addEventListener("click", () => markAsFound(button));
          } else if (button.textContent.includes("Delete")) {
            button.addEventListener("click", () => deleteRow(button));
          }
        });
    </script>
  </body>
</html>
