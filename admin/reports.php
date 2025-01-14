<?php        
// Koneksi ke database    
$servername = "localhost";  
$username = "root";     
$password = "";  
$dbname = "lostandfound";   
    
// Membuat koneksi    
$conn = new mysqli($servername, $username, $password, $dbname);    
    
// Memeriksa koneksi    
if ($conn->connect_error) {    
    die("Koneksi gagal: " . $conn->connect_error);    
}    
    
// Mengambil data dari tabel founditems dengan join ke tabel users    
$sqlFound = "    
    SELECT u.nama, u.npm, f.lokasi_kampus, f.barang_ditemukan, f.tempat_menemukan, f.tanggal_menemukan     
    FROM founditems f    
    JOIN users u ON f.user_id = u.id    
";       
$resultFound = $conn->query($sqlFound);    
    
$foundData = [];    
if ($resultFound->num_rows > 0) {    
    while ($row = $resultFound->fetch_assoc()) {    
        $foundData[] = [    
            'nama' => $row['nama'],    
            'npm' => $row['npm'],    
            'kampus' => $row['lokasi_kampus'],    
            'barang' => $row['barang_ditemukan'],    
            'lokasi' => $row['tempat_menemukan'],    
            'tanggal' => date('d-m-Y', strtotime($row['tanggal_menemukan'])), // Format tanggal  
            'status' => 'Barang Ditemukan'    
        ];    
    }    
}  
    
// Mengambil data dari tabel lostitems dengan join ke tabel users    
$sqlLost = "    
    SELECT u.nama, u.npm, l.lokasi_kampus, l.barang_hilang, l.tempat_kehilangan, l.tanggal_kehilangan     
    FROM lostitems l    
    JOIN users u ON l.user_id = u.id    
";    
$resultLost = $conn->query($sqlLost);    
    
$lostData = [];    
if ($resultLost->num_rows > 0) {    
    while ($row = $resultLost->fetch_assoc()) {    
        $lostData[] = [    
            'nama' => $row['nama'],    
            'npm' => $row['npm'],    
            'kampus' => $row['lokasi_kampus'],    
            'barang' => $row['barang_hilang'],    
            'lokasi' => $row['tempat_kehilangan'],    
            'tanggal' => date('d-m-Y', strtotime($row['tanggal_kehilangan'])),    
            'status' => 'Belum Ditemukan'    
        ];    
    }    
}    
    
// Menggabungkan data    
$data = array_merge($foundData, $lostData);    
    
// Mengubah data menjadi JSON untuk digunakan di JavaScript    
$dataJson = json_encode($data);    
?>      
    
<!DOCTYPE html>        
<html lang="en">        
<head>        
    <meta charset="UTF-8">        
    <meta name="viewport" content="width=device-width, initial-scale=1.0">        
    <title>Lost & Found Laporan</title>    
    <style> 
        .dropdownlostandfound, .dropdowncampus {  
            border: 1px solid #6a1b9a; /* Border default */  
            padding: 10px; /* Padding untuk dropdown */  
            border-radius: 5px; /* Sudut melengkung */  
        }  
        
        .dropdownlostandfound.active, .dropdowncampus.active {  
            color: #6a1b9a; /* Warna teks saat aktif */  
        }  
    </style>    
    <link rel="stylesheet" href="./css/reports.css">        
    <link rel="stylesheet" href="../css/style.css">        
    <script>        
   
        const data = <?php echo $dataJson; ?>;      
        let filteredData = [];  
  
        function applyFilters() {      
            const lostFound = document.querySelector('[name="lost-found"]').value;      
            const campus = document.querySelector('[name="campus"]').value;      
            const query = document.querySelector('[name="q"]').value.toLowerCase();      
  
            filteredData = data.filter((row, index) => {      
                const matchesLostFound = !lostFound || lostFound === 'Semua' || (lostFound === 'Barang Ditemukan' && row.status === 'Barang Ditemukan') || (lostFound === 'Belum Ditemukan' && row.status === 'Belum Ditemukan');      
                const matchesCampus = !campus || row.kampus.toLowerCase().includes(campus.toLowerCase());      
  
                const searchWords = query.split(' ');      
                const allText = Object.values(row).join(' ').toLowerCase();      
                const matchesQuery = searchWords.every(word => word ? allText.includes(word) : true);      
  
                return matchesLostFound && matchesCampus && matchesQuery;      
            });      
  
            const tableBody = document.querySelector('tbody');      
            tableBody.innerHTML = '';      
  
            if (filteredData.length === 0) {        
                tableBody.innerHTML = '<tr><td colspan="8" style="text-align:center;">Tidak ada data ditemukan</td></tr>';        
            } else {        
                filteredData.forEach((row, index) => {        
                    const tr = document.createElement('tr');        
                    tr.innerHTML = `        
                        <td>${index + 1}</td>        
                        <td>${row.nama}</td>        
                        <td>${row.npm}</td>        
                        <td>${row.kampus}</td>        
                        <td>${row.barang}</td>        
                        <td>${row.lokasi}</td>        
                        <td>${row.tanggal}</td>     
                        <td>${row.status}</td>          
                    `;        
                    tableBody.appendChild(tr);        
                });        
            }    
        }      
      
        document.addEventListener('DOMContentLoaded', function() {        
            const form = document.querySelector('form');        
            form.addEventListener('input', applyFilters);        
            applyFilters(); // Inisialisasi filter saat halaman dimuat        
        
            // Add event listeners for dropdowns  
            const lostAndFoundDropdown = document.querySelector('.dropdownlostandfound');  
            const campusDropdown = document.querySelector('.dropdowncampus');  
        
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
            });  
        });  

    </script>        
</head>        
<body>        
<div class="content">        
    <div class="title">        
        <h2>Laporan</h2>        
    </div>        
    <form method="GET" action="">        
        <div class="dropdown">        
            <div class="controls-container">        
            <div class="dropdown-lost-and-found">            
                <select name="lost-found" class="dropdownlostandfound" id="dropdown">    
                    <option value="Belum Ditemukan">Kehilangan</option>            
                    <option value="Barang Ditemukan">Penemuan</option>            
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
                    <input        
                        type="search"        
                        id="query"        
                        name="q"        
                        placeholder="Cari..."        
                        aria-label="Search"        
                    />        
                </div>        
            </div>        
        </div>        
    </form>        
      
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
            </tbody>        
        </table>        
        <div class="print-container">  
    <a class="print-button" id="printButton" href="#" target="_blank">    
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">  
            <path d="M19 8h-1V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v5H5a3 3 0 0 0-3 3v5a1 1 0 0 0 1 1h2v4a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-4h2a1 1 0 0 0 1-1v-5a3 3 0 0 0-3-3ZM8 4h8v4H8Zm8 16H8v-3h8Zm3-5h-2v-2H7v2H5v-4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1Z"/>  
        </svg>  
        Print  
    </a>      
</div>  
 
    </div>        
</div>        
</body>    
<script>
    document.getElementById('printButton').addEventListener('click', function() {  
    const dataToPrint = encodeURIComponent(JSON.stringify(filteredData));  
    this.href = 'print_reports.php?data=' + dataToPrint;  
});  


document.querySelector('.dropdownlostandfound').addEventListener('change', function() {  
    if (this.value) {  
        this.classList.add('active'); // Tambahkan kelas active jika ada pilihan  
    } else {  
        this.classList.remove('active'); // Hapus kelas active jika tidak ada pilihan  
    }  
});  

</script>    
</html>