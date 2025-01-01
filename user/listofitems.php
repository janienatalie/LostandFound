<?php
$sql = "SELECT barang, lokasi_kampus, tempat, tanggal, foto_barang FROM kehilangan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/listofitems.css" />
    <!-- loading bar -->
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="../css/style.css" />
    <!-- fontowesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
      integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <title>Lost and Found - List of Items</title>
  </head>
  <body>
    <div class="list-of-items">
      <div class="title">
        <h2>Daftar Barang</h2>
      </div>
      <div class="dropdown">
        <div class="controls-container">
          <div class="dropdown-lost-and-found">
            <select
              name="lost-found"
              class="dropdownlostandfound"
              id="dropdown"
            >
              <option hidden value="" disabled selected>
                Kehilangan atau Penemuan
              </option>
              <option value="Lost">Kehilangan</option>
              <option value="Found">Penemuan</option>
            </select>
          </div>

          <div class="dropdown-campus">
            <select name="campus" class="dropdowncampus" id="dropdowncampus">
              <option hidden value="" disabled selected>Kampus</option>
              <option value="CampusE">Kampus E</option>
              <option value="CampusD">Kampus D</option>
              <option value="CampusG">Kampus G</option>
              <option value="CampusH">Kampus H</option>
              <option value="CampusF8">Kampus F8</option>
            </select>
          </div>

          <div class="search-bar">
            <!-- Ikon pencarian -->
            <input
              type="search"
              id="query"
              name="q"
              placeholder="Search..."
              aria-label="Search through site content"
            />
            <i class="fa fa-search search-icon" aria-hidden="true"></i>
          </div>
        </div>
      </div>
      <div class="listofitemstable" class="table-responsive-xl">
        <table>
          <thead>
            <tr>
              <th>Barang</th>
              <th>Kampus</th>
              <th>Lokasi</th>
              <th>Tanggal</th>
              <th>Foto Barang</th>
            </tr>
          </thead>
          <tbody>
          <?php
            if ($result->num_rows > 0) {
                // Output data untuk setiap baris
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['barang']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lokasi_kampus']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tempat']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
                    echo "<td>";
                    if (!empty($row['foto_barang'])) {
                        echo "<img src='uploads/" . htmlspecialchars($row['foto_barang']) . "' alt='Foto Barang' style='width:100px;'>";
                    } else {
                        echo "Tidak ada foto";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Tidak ada data ditemukan</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="frame">
        <p class="terms-and-conditions">
          Syarat dan Ketentuan Pengambilan Barang :<br />1. Untuk pengambilan
          barang dapat langsung menemui BM Gedung terkait dengan membawa Kartu
          Tanda Mahasiswa (KTM) <br />2. Jangka waktu penyimpanan barang hilang
          dan ditemukan dibatasi selama 30 hari kerja. <br />3. Jam operasional
          pengambilan:<br />  Senin - Jumat 09:00 – 17:00 <br />  Sabtu 09:00 –
          12:00
        </p>
      </div>
    </div>
    <footer class="footer">
      <p class="copyrights-lost">
        <span class="span">Copyrights © 2024 </span>
        <span class="text-wrapper-2">Lost and Found.</span>
        <span class="span">Kelompok 6.</span>
      </p>
    </footer>

    <script src="/user/js/script.js"></script>
  </body>
</html>
