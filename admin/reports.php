<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lost & Found laporan</title>
<link rel="stylesheet" href="reports.css">
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    />
</head>
<body>
<div class="content">
        <h2>Laporan</h2>
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

    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NPM</th>
                    <th>Kampus</th>
                    <th>Barang</th>
                    <th>Lokasi</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Ardinda Nur</td>
                    <td>10122207</td>
                    <td class="kampus">E</td>
                    <td>Dompet</td>
                    <td>Masjid Kampus E</td>
                    <td>18 Nov 2024</td>
                    <td>Sudah Ditemukan</td>
                </tr>

            </tbody>
        </table>
        <div class="print-container">
            <a class="print-button" href="#" onclick="printPage()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19 8h-1V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v5H5a3 3 0 0 0-3 3v5a1 1 0 0 0 1 1h2v4a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-4h2a1 1 0 0 0 1-1v-5a3 3 0 0 0-3-3ZM8 4h8v4H8Zm8 16H8v-3h8Zm3-5h-2v-2H7v2H5v-4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1Z"/>
                </svg>
                Print
            </a>
        </div>
    </div>
</div>
</body>
</html>
