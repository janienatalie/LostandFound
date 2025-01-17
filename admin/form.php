<?php
include '../database/config.php';
include './sidebar.php';

// Function untuk format tanggal
function formatTanggal($date) {
    return date('d F Y', strtotime($date));
}

// Query untuk mengambil data barang hilang
$sqlLost = "
    SELECT u.nama, u.npm, l.lokasi_kampus, l.barang_hilang as item, 
           l.tempat_kehilangan as lokasi, l.tanggal_kehilangan as tanggal,
           l.foto_barang as foto, 'Lost' as status, l.id as lost_id
    FROM LostItems l
    JOIN Users u ON l.user_id = u.id where status = 'Lost'
";

// Query untuk mengambil data barang ditemukan
$sqlFound = "
    SELECT u.nama, u.npm, f.lokasi_kampus, f.barang_ditemukan as item,
           f.tempat_menemukan as lokasi, f.tanggal_menemukan as tanggal,
           f.foto_barang as foto, 'Found' as status, f.id as found_id
    FROM FoundItems f
    JOIN Users u ON f.user_id = u.id where status = 'Found'
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
        /* Gaya untuk mengubah warna placeholder dropdown */
        .dropdownlostandfound option:disabled {
            color: #9b59b6; /* Warna ungu untuk placeholder */
        }

        /* Gaya untuk mengubah warna dropdown ketika nilai terpilih adalah 'Kehilangan' */
        .dropdownlostandfound {
            color: #9b59b6; /* Warna ungu pada teks pilihan */
        }

        /* Gaya untuk tombol kelola */
        .btn-kelola {
            padding: 5px 10px;
            margin: 3px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }

        .btn-sudah-ditemukan {
            background-color: #28a745; /* Hijau */
            color: white;
        }

        .btn-belum-ditemukan {
            background-color: #ffc107; /* Kuning */
            color: white;
        }

        .btn-delete {
            background-color: #dc3545; /* Merah */
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
                    <input type="search" id="query" name="q" placeholder="Cari..." 
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
                        <th>Item</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>Foto</th>
                        <th>Kelola</th> <!-- Kolom Kelola -->
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
    // Terima data dari PHP
    const allData = <?php echo $dataJson; ?>;
    let filteredData = allData;

    // Fungsi untuk format tanggal dalam bahasa Indonesia
    function formatTanggalIndo(dateString) {
        const date = new Date(dateString);
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Intl.DateTimeFormat('id-ID', options).format(date);
    }

    
    function applyFilters() {
    const lostFound = document.querySelector('[name="lost-found"]').value;      
    const campus = document.querySelector('[name="campus"]').value;
    const query = document.querySelector('[name="q"]').value.toLowerCase();      
    const tableBody = document.querySelector('tbody');
    
    // Filter data berdasarkan kampus
    filteredData = allData.filter((row) => {
        const matchesLostFound = !lostFound || lostFound === 'Semua' || 
            (lostFound === 'Lost' && row.status === 'Lost') || 
            (lostFound === 'Found' && row.status === 'Found');
        // Jika tidak ada kampus yang dipilih, tampilkan semua
        // Atau jika kampus cocok dengan data (case-insensitive)
        const matchesCampus = !campus || row.lokasi_kampus.toLowerCase().includes(campus.toLowerCase());
        const searchWords = query.split(' ');      
        const allText = Object.values(row).join(' ').toLowerCase();      
        const matchesQuery = searchWords.every(word => word ? allText.includes(word) : true);

        return matchesLostFound && matchesCampus && matchesQuery;
    });

    // Update tampilan tabel
    tableBody.innerHTML = '';

    if (!filteredData || filteredData.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="9" style="text-align:center;">Tidak ada data ditemukan</td></tr>';
    } else {
        filteredData.forEach((row, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${row.nama}</td>
                <td>${row.npm}</td>
                <td>${row.lokasi_kampus}</td>
                <td>${row.item}</td>
                <td>${row.lokasi}</td>
                <td>${formatTanggalIndo(row.tanggal)}</td>
                <td>
                    ${row.foto ? 
                        `<img src="../uploads/${row.foto}" class="item-image" alt="Foto Barang" 
                        onerror="console.log('Error loading image:', '../uploads/${row.foto}')" 
                        />`
                        : 
                        '<span>Tidak ada foto</span>'
                    }
                </td>
                <td>
                    <button class="btn-kelola btn-sudah-ditemukan" onclick="markFound(${row.lost_id || row.found_id}, '${row.status}')">
                        Barang Sudah Ditemukan
                    </button>
                    <button class="btn-kelola btn-delete" onclick="deleteItem(${row.lost_id || row.found_id})">
                        Delete
                    </button>
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }
}

    // Event listener untuk dropdown kampus
    // document.addEventListener('DOMContentLoaded', function() => {
    //     const campusDropdown = document.querySelector('.dropdowncampus');
    //     campusDropdown.addEventListener('change', applyFilters);
        
    //     // Inisialisasi filter saat halaman dimuat
    //     applyFilters();
    // });

      
    function updateTable() {
    const tableBody = document.querySelector('tbody');
    tableBody.innerHTML = '';
    const lostFoundFilter = document.querySelector('.dropdownlostandfound').value;
    const searchQuery = document.querySelector('#query').value.toLowerCase();

    // Filter data berdasarkan kriteria
    filteredData = allData.filter(item => {
        const matchesType = !lostFoundFilter || item.status === lostFoundFilter;
        const matchesSearch = Object.values(item).some(value => 
            value && value.toString().toLowerCase().includes(searchQuery)
        );
        return matchesType && matchesSearch;
    });

    // Tampilkan data yang sudah difilter
    if (filteredData.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="9" class="no-data">Tidak ada data ditemukan</td></tr>';
    } else {
        filteredData.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.setAttribute('data-id', item.lost_id || item.found_id); // Menambahkan ID sebagai data-id
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${item.nama}</td>
                <td>${item.npm}</td>
                <td>${item.lokasi_kampus}</td>
                <td>${item.item}</td>
                <td>${item.lokasi}</td>
                <td>${formatTanggalIndo(item.tanggal)}</td>
                <td>
                    ${item.foto ? 
                        `<img src="../uploads/${item.foto}" class="item-image" alt="Foto Barang" 
                        onerror="console.log('Error loading image:', '../uploads/${item.foto}')" 
                        />`
                        : 
                        '<span>Tidak ada foto</span>'
                    }
                </td>
                <td>
                    <button class="btn-kelola btn-sudah-ditemukan" onclick="markFound(${item.lost_id || item.found_id}, '${item.status}')">Barang Sudah Ditemukan</button>
                    <button class="btn-kelola btn-delete" onclick="deleteItem(${item.lost_id || item.found_id})">Delete</button>
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }
}


function markFound(id, status) {
    if (status === 'Lost' || status === 'Found') {
        fetch('updateStatus.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id }) // Mengirimkan ID barang
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === 'success') {
                alert('Status berhasil diperbarui!');
                location.reload();

                // Menghapus item yang sudah diperbarui dari filteredData
                filteredData = filteredData.filter(item => item.lost_id !== id && item.found_id !== id);

                updateTable();


                // Menghapus baris data yang sesuai dari tabel
                removeRowFromTable(id);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch((error) => console.error('Error:', error));
    } else {
        alert('Status barang sudah ditemukan atau tidak valid.');
    }
}

    // Fungsi untuk menghapus baris berdasarkan ID
    function removeRowFromTable(id) {
        // Menghapus baris berdasarkan ID yang terhubung dengan data-id
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (row) {
            row.remove(); // Menghapus baris yang sesuai
        }
    }

    // Fungsi untuk menghapus item
    function deleteItem(id) {
        const confirmation = confirm("Apakah Anda yakin ingin menghapus data ini?");
        if (confirmation) {
            // Kirim permintaan ke server untuk menghapus item dari database
            fetch('deleteItem.php', {
                method: 'POST',
                body: JSON.stringify({ id }),
                headers: {
                    'Content-Type': 'application/json',
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === 'success') {
                        alert('Barang berhasil dihapus!');
                        location.reload(); // Refresh halaman setelah penghapusan berhasil
                    } else {
                        alert('Gagal menghapus barang: ' + data.message);
                    }
                })
                .catch((error) => console.error('Error:', error));
        }
    }


    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');        
        form.addEventListener('input', applyFilters);  
        applyFilters();

        const lostAndFoundDropdown = document.querySelector('.dropdownlostandfound');
        // const searchInput = document.querySelector('#query');
        const campusDropdown = document.querySelector('.dropdowncampus'); 
        applyFilters();
        
        lostAndFoundDropdown.addEventListener('change', function() {
            if (this.value) {
                this.classList.add('active');
            } else {
                this.classList.remove('active');
            }
        });

        campusDropdown.addEventListener('change', function() {
            if (this.value) {  
                this.classList.add('active');  
            } else {  
                this.classList.remove('active');  
            } 
        })



        // lostFoundSelect.value = "Lost";

        // lostFoundSelect.addEventListener('change', updateTable);
        // searchInput.addEventListener('input', updateTable);

        // Initial table update
        updateTable();
    });
    </script>

    
</body>
</html>
