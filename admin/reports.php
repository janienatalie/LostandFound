<?php        
include '../database/config.php';
include './sidebar.php';
    
// Update query untuk founditems
$sqlFound = "    
    SELECT u.nama, u.npm, f.lokasi_kampus, f.barang_ditemukan, f.tempat_menemukan, 
           f.tanggal_menemukan, f.foto_barang, 'Sudah Dikembalikan' as status   
    FROM founditems f    
    JOIN users u ON f.user_id = u.id where status = 'Sudah Dikembalikan'
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
            'tanggal' => date('d-m-Y', strtotime($row['tanggal_menemukan'])),
            'foto' => $row['foto_barang'],
            'status' => $row['status']    
        ];    
    }    
}  
    
// Update query untuk lostitems  
$sqlLost = "    
    SELECT u.nama, u.npm, l.lokasi_kampus, l.barang_hilang, l.tempat_kehilangan, 
           l.tanggal_kehilangan, l.foto_barang, 'Sudah Ditemukan' as status    
    FROM lostitems l    
    JOIN users u ON l.user_id = u.id where status = 'Sudah Ditemukan'    
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
            'foto' => $row['foto_barang'],    
            'status' => $row['status']    
        ];    
    }    
}   


    
$data = array_merge($foundData, $lostData);    
$dataJson = json_encode($data);   

// foreach($foundData as $item) {
//     echo "Found item foto: " . $item['foto'] . "<br>";
// }
// foreach($lostData as $item) {
//     echo "Lost item foto: " . $item['foto'] . "<br>";
// }
?> 
    
<!DOCTYPE html>        
<html lang="en">        
<head>        
    <meta charset="UTF-8">        
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />       
    <title>Lost & Found Laporan</title>    
    <style> 
        .dropdownlostandfound, .dropdowncampus {  
            border: 1px solid #763996; /* Border default */  
            padding: 10px; /* Padding untuk dropdown */  
            border-radius: 5px; /* Sudut melengkung */  
        }  
        
        .dropdownlostandfound.active, .dropdowncampus.active {  
            color: #763996; /* Warna teks saat aktif */  
        }  
        .item-image {
            max-width: auto;
            height: 110px;
            display: block;
            margin: 0 auto;
        }
    </style>    
    <link rel="stylesheet" href="./css/reports.css">        
    <link rel="stylesheet" href="../css/style.css">        
    <script>        

    
   
        const data = <?php echo $dataJson; ?>;      
        let filteredData = []; // Variabel global untuk data yang difilter  
  
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
                function formatTanggal(tanggal) {
                const bulanNama = [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];
                
                const [hari, bulan, tahun] = tanggal.split('-'); // Format tanggal 'YYYY-MM-DD'
                const namaBulan = bulanNama[parseInt(bulan, 10) - 1]; // Konversi bulan ke nama
                return `${hari} ${namaBulan} ${tahun}`; // Format tanggal dalam bahasa Indonesia
            }  
                filteredData.forEach((row, index) => {        
                    const tr = document.createElement('tr');        
                    tr.innerHTML = `        
                        <td>${index + 1}</td>        
                        <td>${row.nama}</td>        
                        <td>${row.npm}</td>        
                        <td>${row.kampus}</td>        
                        <td>${row.barang}</td>        
                        <td>${row.lokasi}</td>        
                        <td>${formatTanggal(row.tanggal)}</td>     
                        <td>${row.status}</td>    
                        <td style="min-width: 2200px; padding: 10px;">${row.foto ? `<img src="../uploads/ ${row.foto}" alt="Foto Barang" style="max-width: 100px;">` : 'Tidak ada foto'}</td>
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
                    this.classList.add('active'); // Tambahkan kelas active jika ada pilihan    
                } else {    
                    this.classList.remove('active'); // Hapus kelas active jika tidak ada pilihan    
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
        if (lostAndFoundDropdown) {
            lostAndFoundDropdown.addEventListener('change', () => {
          // Update color when value changes
          if (lostAndFoundDropdown.value) {
            lostAndFoundDropdown.style.color = '#763996';
            } else {
                lostAndFoundDropdown.style.color = '#c7c7c7';
            }
            fetchData();
          });
        }
        if (campusDropdown) {
          campusDropdown.style.color = '#c7c7c7';
            campusDropdown.addEventListener('change', () => {
                // Update color when value changes
                if (campusDropdown.value) {
                    campusDropdown.style.color = '#763996';
                } else {
                    campusDropdown.style.color = '#c7c7c7';
                }
                fetchData();
            });
        }        

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
                    <input        
                        type="search"        
                        id="query"        
                        name="q"        
                        placeholder="Cari..."        
                        aria-label="Search"        
                    />      
                    <i class="fa fa-search search-icon" aria-hidden="true"></i>  
                </div>        
            </div>        
        </div>        
    </form> 
    <div class="print-container">  
    <a class="print-button" id="printButton" href="#" target="_blank">    
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">  
            <path d="M19 8h-1V3a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v5H5a3 3 0 0 0-3 3v5a1 1 0 0 0 1 1h2v4a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-4h2a1 1 0 0 0 1-1v-5a3 3 0 0 0-3-3ZM8 4h8v4H8Zm8 16H8v-3h8Zm3-5h-2v-2H7v2H5v-4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1Z"/>  
        </svg>  
        Print  
    </a>      
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
                    <th>Foto</th>     
                </tr>        
            </thead>        
            <tbody>        
                <!-- Data akan diisi oleh JavaScript -->        
            </tbody>        
        </table>        
        
 
    </div>        
</div>        
</body>    
<script>
    function applyFilters() {      
        const lostFound = document.querySelector('[name="lost-found"]').value;      
        const campus = document.querySelector('[name="campus"]').value;      
        const query = document.querySelector('[name="q"]').value.toLowerCase();      

        filteredData = data.filter((row) => {      
            const matchesLostFound = !lostFound || lostFound === 'Semua' || 
                (lostFound === 'Lost' && row.status === 'Sudah Ditemukan') || 
                (lostFound === 'Found' && row.status === 'Sudah Dikembalikan');      
            const matchesCampus = !campus || row.kampus.toLowerCase().includes(campus.toLowerCase());      

            const searchWords = query.split(' ');      
            const allText = Object.values(row).join(' ').toLowerCase();      
            const matchesQuery = searchWords.every(word => word ? allText.includes(word) : true);      

            return matchesLostFound && matchesCampus && matchesQuery;      
        });      

        const tableBody = document.querySelector('tbody');      
        tableBody.innerHTML = '';      

        if (!filteredData ||filteredData.length === 0) {        
            tableBody.innerHTML = '<tr><td colspan="9" style="text-align:center;">Tidak ada data ditemukan</td></tr>';        
        } else {        
            function formatTanggal(tanggal) {
                const bulanNama = [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];
                
                const [hari, bulan, tahun] = tanggal.split('-'); // Format tanggal 'YYYY-MM-DD'
                const namaBulan = bulanNama[parseInt(bulan, 10) - 1]; // Konversi bulan ke nama
                return `${hari} ${namaBulan} ${tahun}`; // Format tanggal dalam bahasa Indonesia
            }
            filteredData.forEach((row, index) => {        
                const tr = document.createElement('tr');        
                tr.innerHTML = `        
                    <td>${index + 1}</td>        
                    <td>${row.nama}</td>        
                    <td>${row.npm}</td>        
                    <td>${row.kampus}</td>        
                    <td>${row.barang}</td>        
                    <td>${row.lokasi}</td>        
                    <td>${formatTanggal(row.tanggal)}</td>     
                    <td>${row.status}</td>
                    <td>
                        ${row.foto ? 
                            `<img src="../uploads/${row.foto}" class="item-image" alt="Foto Barang" 
                            onerror="console.log('Error loading image:', '../uploads/${row.foto}')" 
                            />`
                            : 
                            '<span>Tidak ada foto</span>'
                        }
                    </td>          
                `;        
                tableBody.appendChild(tr);        
            });        
        }    
    }

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