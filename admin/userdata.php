<?php
// Konfigurasi database
$host = "localhost"; // Nama host database
$user = "root"; // Username database
$password = ""; // Password database
$database = "lostandfound"; // Nama database

// Membuat koneksi ke database
$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Query untuk mengambil data pengguna
$query = "SELECT id, nama, username, npm, nomor_telepon FROM Users ORDER BY nama";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <link rel="stylesheet" href="/admin/css/userdata.css" />
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="list-of-items">
        <div class="title">
            <h2>Data Pengguna</h2>
        </div>
        <div class="dropdown">
            <div class="controls-container">
                <div class="search-bar">
                    <input type="search" id="query" name="q" placeholder="Cari..." 
                           aria-label="Search through site content" />
                    <i class="fa fa-search search-icon" aria-hidden="true"></i>
                </div>
            </div>
        </div>
        <div class="listofitemstable">
            <table>
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
                    if (!$result) {
                        echo "<tr><td colspan='5' class='error'>Error: " . mysqli_error($koneksi) . "</td></tr>";
                    } else if (mysqli_num_rows($result) == 0) {
                        echo "<tr><td colspan='5' class='no-data'>Tidak ada data pengguna</td></tr>";
                    } else {
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['npm']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nomor_telepon']) . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include './footer.php';?>

    <script>
        const searchInput = document.getElementById("query");

        if (searchInput) {
            searchInput.addEventListener("input", debounce(() => {
                filterTable(searchInput.value.toLowerCase());
            }, 300));
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function filterTable(searchTerm) {
            const tableBody = document.querySelector(".listofitemstable tbody");
            const rows = tableBody.getElementsByTagName("tr");
            let hasResults = false;

            for (const row of rows) {
                let text = "";
                const cells = row.getElementsByTagName("td");

                for (let i = 0; i < cells.length; i++) {
                    text += cells[i].textContent.toLowerCase() + " ";
                }

                if (text.includes(searchTerm)) {
                    row.style.display = "";
                    hasResults = true;
                } else {
                    row.style.display = "none";
                }
            }

            if (!hasResults) {
                if (!document.getElementById("no-results-row")) {
                    const noResultsRow = document.createElement("tr");
                    noResultsRow.id = "no-results-row";
                    noResultsRow.innerHTML = '<td colspan="5" style="text-align: center;">Tidak ada hasil yang ditemukan</td>';
                    tableBody.appendChild(noResultsRow);
                }
            } else {
                const noResultsRow = document.getElementById("no-results-row");
                if (noResultsRow) {
                    noResultsRow.remove();
                }
            }
        }
    </script>
</body>
</html>