<?php
include '../database/config.php';
include './sidebar.php';

// Query untuk mengambil data barang hilang
$sqlLost = "
    SELECT u.nama, u.npm, l.lokasi_kampus, l.barang_hilang as item, 
           l.tempat_kehilangan as lokasi, l.tanggal_kehilangan as tanggal,
           l.foto_barang as foto, l.status as status, l.id as lost_id
    FROM LostItems l
    JOIN Users u ON l.user_id = u.id 
    where status = 'Lost'
";

// Query untuk mengambil data barang ditemukan
$sqlFound = "
    SELECT u.nama, u.npm, f.lokasi_kampus, f.barang_ditemukan as item,
           f.tempat_menemukan as lokasi, f.tanggal_menemukan as tanggal,
           f.foto_barang as foto, f.status as status, f.id as found_id
    FROM FoundItems f
    JOIN Users u ON f.user_id = u.id 
    WHERE f.status = 'Found' 
";

// Gabungkan kedua query
$sql = "$sqlLost UNION ALL $sqlFound";
$result = $conn->query($sql);

// Array untuk menyimpan semua data
$allData = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $allData[] = $row;
    }
}

// Konversi data ke JSON untuk digunakan di JavaScript
$dataJson = json_encode($allData);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang Lost & Found</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <link rel="stylesheet" href="./css/form.css" />
    <style>
        .dropdownlostandfound option:disabled {
            color: #763996;
        }
        .dropdownlostandfound {
            color: #763996;
        }
        .btn-kelola {
            padding: 10px 15px;
            margin: 3px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            width: 140px;
            font-size: 15px;
        }
        .btn-sudah-ditemukan {
            background-color: #5CB85C;
            color: white;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="list-of-items">
        <div class="title">
            <h2>Daftar Barang</h2>
        </div>
        <form method="GET" action="">
            <div class="dropdown">
                <div class="controls-container">
                    <div class="dropdown-lost-and-found">
                        <select name="lost-found" class="dropdownlostandfound" id="dropdown">
                            <option hidden value="" disabled>Kehilangan atau Penemuan</option>
                            <option value="Lost">Kehilangan</option>
                            <option value="Found">Penemuan</option>
                        </select>
                    </div>
                    <div class="dropdown-campus">          
                        <select name="campus" class="dropdowncampus" id="dropdowncampus">          
                            <option value="">Kampus</option>          
                            <option value="E">Kampus E</option>          
                            <option value="D">Kampus D</option>          
                            <option value="G">Kampus G</option>          
                            <option value="H">Kampus H</option>          
                            <option value="F8">Kampus F8</option>          
                        </select>          
                    </div>
                    <div class="search-bar">
                        <input type="search" 
                               id="query" 
                               name="q" 
                               placeholder="Cari (nama/npm/kampus/barang/lokasi)..." 
                               aria-label="Search through site content" />
                        <i class="fa fa-search search-icon" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </form>
        <div class="listofitemstable">
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
                        <th>Foto</th>
                        <th>Kelola</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
    // Data dari PHP
    const allData = <?php echo $dataJson; ?>;
    
    // Fungsi untuk format tanggal dalam bahasa Indonesia
    function formatTanggalIndo(dateString) {
        const date = new Date(dateString);
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Intl.DateTimeFormat('id-ID', options).format(date);
    }

    // Fungsi untuk mencari di semua kolom
    function searchTable() {
        const searchInput = document.getElementById('query');
        const searchQuery = searchInput.value.toLowerCase().trim();
        const tableRows = document.querySelectorAll('.listofitemstable tbody tr');

        tableRows.forEach(row => {
            // Ambil semua sel dalam baris kecuali kolom foto dan tombol kelola
            const cells = Array.from(row.getElementsByTagName('td')).slice(0, -2);
            
            // Gabungkan teks dari semua sel
            const rowText = cells.map(cell => cell.textContent.toLowerCase()).join(' ');
            
            // Tampilkan baris jika mengandung teks pencarian
            if (rowText.includes(searchQuery)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        updateNoResultsMessage();
    }

    // Fungsi untuk menerapkan semua filter
    function applyFilters() {
        const searchQuery = document.getElementById('query').value.toLowerCase().trim();
        const lostFound = document.querySelector('.dropdownlostandfound').value;
        const campus = document.querySelector('.dropdowncampus').value;
        
        const tableRows = document.querySelectorAll('.listofitemstable tbody tr');
        let hasVisibleRows = false;

        tableRows.forEach(row => {
            const cells = Array.from(row.getElementsByTagName('td')).slice(0, -2);
            const rowText = cells.map(cell => cell.textContent.toLowerCase()).join(' ');
            
            // Filter berdasarkan pencarian
            const matchesSearch = searchQuery === '' || rowText.includes(searchQuery);
            
            // Filter berdasarkan lost/found
            const rowStatus = row.getAttribute('data-status');
            const matchesStatus = !lostFound || rowStatus === lostFound;
            
            // Filter berdasarkan kampus
            const kampusCell = cells[3];
            const matchesCampus = !campus || (kampusCell && kampusCell.textContent.includes(campus));
            
            // Tampilkan baris jika memenuhi semua filter
            if (matchesSearch && matchesStatus && matchesCampus) {
                row.style.display = '';
                hasVisibleRows = true;
            } else {
                row.style.display = 'none';
            }
        });

        updateNoResultsMessage();
    }

    // Fungsi untuk menampilkan pesan tidak ada hasil
    function updateNoResultsMessage() {
        const tbody = document.querySelector('.listofitemstable tbody');
        const visibleRows = document.querySelectorAll('.listofitemstable tbody tr[style=""]').length;
        const noResultsRow = document.getElementById('no-results-row');
        const searchQuery = document.getElementById('query').value.trim(); // Tambah ini
        
        if (searchQuery && visibleRows === 0) {
            if (!noResultsRow) {
                const tr = document.createElement('tr');
                tr.id = 'no-results-row';
                tr.innerHTML = '<td colspan="9" style="text-align: center;">Tidak ada hasil yang ditemukan</td>';
                tbody.appendChild(tr);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }

    // Fungsi untuk menampilkan data di tabel
    function updateTableDisplay(data) {
        const tbody = document.querySelector('.listofitemstable tbody');
        tbody.innerHTML = '';
        
        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" style="text-align: center;">Tidak ada data</td></tr>';
            return;
        }
        
        data.forEach((row, index) => {
            const tr = document.createElement('tr');
            tr.setAttribute('data-id', row.lost_id || row.found_id);
            tr.setAttribute('data-status', row.status);
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${row.nama}</td>
                <td>${row.npm}</td>
                <td>${row.lokasi_kampus}</td>
                <td>${row.item}</td>
                <td>${row.lokasi}</td>
                <td>${formatTanggalIndo(row.tanggal)}</td>
                <td>${row.foto ? 
                    `<img src="../uploads/${row.foto}" class="item-image" alt="Foto Barang" 
                     onerror="this.onerror=null;this.src='../assets/no-image.png';" />` : 
                    '<span>Tidak ada foto</span>'}</td>
                <td>
                    <button class="btn-kelola btn-sudah-ditemukan" onclick="markFound(${row.lost_id || row.found_id}, '${row.status}')">
                        Barang Sudah Dikembalikan
                    </button>
                    <button class="btn-kelola btn-delete" onclick="deleteItem(${row.lost_id || row.found_id}, '${row.status === 'Lost' ? 'lost' : 'found'}')">
                        Delete
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('query');
        const lostFoundDropdown = document.querySelector('.dropdownlostandfound');
        const campusDropdown = document.querySelector('.dropdowncampus');
        
        // Event listener untuk search dengan debounce
        let searchTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                applyFilters();
            }, 300);
        });
        
        // Event listeners untuk dropdowns
        lostFoundDropdown.addEventListener('change', applyFilters);
        campusDropdown.addEventListener('change', applyFilters);
        
        // Mencegah form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
        });

        // Inisialisasi tampilan awal
        updateTableDisplay(allData);
        applyFilters();
    });

    // Fungsi untuk menghapus item
    function deleteItem(id, tableType) {
        if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
            fetch('deleteItem.php', {
                method: 'POST',
                body: JSON.stringify({ id: id, table: tableType }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Barang berhasil dihapus!');
                    location.reload();
                } else {
                    alert('Gagal menghapus barang: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus data');
            });
        }
    }

    // Fungsi untuk mengupdate status barang
    function markFound(id, status) {
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (status === 'Lost' || status === 'Found') {
            fetch('updateStatus.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    id: id,
                    status: status,
                    table: status === 'Lost' ? 'LostItems' : 'FoundItems'
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    alert('Status berhasil diperbarui!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Gagal mengupdate status'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate status');
            });
        } else {
            alert('Status barang sudah ditemukan atau tidak valid.');
        }
    }
    </script>
</body>
</html>