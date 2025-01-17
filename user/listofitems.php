<?php
include '../database/config.php';
session_start();
?>

<!DOCTYPE html>
<html>
  <head>
      <!-- Style font di-embed langsung -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
      <!-- Multiple CSS files yang mungkin konflik -->
      <link rel="stylesheet" href="../css/style.css" />
      <link rel="stylesheet" href="./css/navbar.css" />
      <link rel="stylesheet" href="./css/listofitems.css" />
  </head>
  <body>
  <?php include 'navbar.php'; ?>
    <div class="list-of-items">
      <div class="title">
        <h2>Daftar Barang</h2>
      </div>
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
              <option hidden value="" disabled selected>Kampus</option>
              <option value="Kampus E">Kampus E</option>
              <option value="Kampus D">Kampus D</option>
              <option value="Kampus G">Kampus G</option>
              <option value="Kampus H">Kampus H</option>
              <option value="Kampus F8">Kampus F8</option>
            </select>
          </div>
          <div class="search-bar">
            <!-- Ikon pencarian -->
            <input
              type="search"
              id="query"
              name="q"
              placeholder="Cari..."
              aria-label="Search through site content"
            />
            <i class="fa fa-search search-icon" aria-hidden="true"></i>
          </div>
        </div>
      </div>
      <div class="listofitemstable">
        <table>
          <thead>
            <tr>
              <th>Barang</th>
              <th>Kampus</th>
              <th>Lokasi</th>
              <th>Tanggal</th>
              <th>Foto</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data will be populated by JavaScript -->
          </tbody>
        </table>
      </div>
      <div class="frame">
        <p class="terms-and-conditions">
          Syarat dan Ketentuan Pengambilan Barang :<br />1. Untuk pengambilan
          barang dapat langsung menemui BM Gedung terkait dengan membawa Kartu
          Tanda Mahasiswa (KTM) <br />2. Jangka waktu penyimpanan barang hilang
          dan ditemukan dibatasi selama 30 hari kerja. <br />3. Jam operasional
          pengambilan:<br />  Senin - Jumat 09:00 – 17:00 <br />  Sabtu 09:00 –
          12:00
        </p>
      </div>
    </div>
    <?php include './footer.php';?>
    <!-- Tambahkan script langsung di sini -->
    <script>
        console.log('Script starting...');
        
        // Get references to the dropdowns
        const lostFoundDropdown = document.getElementById('dropdown');
        const campusDropdown = document.getElementById('dropdowncampus');

        window.addEventListener('DOMContentLoaded', () => {
       if (lostFoundDropdown) {
          lostFoundDropdown.value = 'Lost';
          // Trigger change event to update styles
          lostFoundDropdown.dispatchEvent(new Event('change'));
          fetchData();
      }
      });
        
        if (!lostFoundDropdown || !campusDropdown) {
            console.error('Dropdowns not found!');
        } else {
            console.log('Dropdowns found successfully');
        }

        // Set default value for lost/found dropdown to "Lost"
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded');
            if (lostFoundDropdown) {
                lostFoundDropdown.value = 'Lost';
                console.log('Set default value to Lost');
                fetchData();
            }
        });

        // Add event listeners for both dropdowns
        if (lostFoundDropdown) {
          lostFoundDropdown.addEventListener('change', () => {
          // Update color when value changes
          if (lostFoundDropdown.value) {
            lostFoundDropdown.style.color = '#763996';
            } else {
                lostFoundDropdown.style.color = '#c7c7c7';
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

        async function fetchData() {
            try {
                console.log('Fetching data...');
                const type = lostFoundDropdown.value;
                const campus = campusDropdown.value || '';
                
                console.log('Type:', type);
                console.log('Campus:', campus);

                // Get current URL path
                const currentPath = window.location.pathname;
                const basePath = currentPath.substring(0, currentPath.lastIndexOf('/') + 1);
                const fetchUrl = basePath + 'fetch_items.php';
                
                console.log('Fetching from:', fetchUrl);

                const response = await fetch(fetchUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `type=${type}&campus=${campus}`
                });

                console.log('Response received:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Data received:', data);
                
                updateTable(data);
            } catch (error) {
                console.error('Error in fetchData:', error);
            }
        }

        function updateTable(data) {
            console.log('Updating table with data:', data);
            const tbody = document.querySelector('.listofitemstable tbody');
            
            if (!tbody) {
                console.error('Table body not found!');
                return;
            }
            
            tbody.innerHTML = '';

            if (!data || data.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="5" style="text-align: center;">Tidak ada data</td>';
                tbody.appendChild(row);
                return;
            }

            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.barang || ''}</td>
                    <td>${item.lokasi_kampus || ''}</td>
                    <td>${item.tempat || ''}</td>
                    <td>${formatDate(item.tanggal) || ''}</td>
                    <td>${item.foto_barang ? `<img src="${item.foto_barang}" alt="Foto Barang" style="max-width: 100px;">` : 'Tidak ada foto'}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            try {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            } catch (error) {
                console.error('Error formatting date:', error);
                return dateString;
            }
        }

        console.log('Script loaded completely');

        // Tambahkan setelah deklarasi variabel di bagian atas script
const searchInput = document.getElementById('query');

// Tambahkan event listener untuk search input
if (searchInput) {
    searchInput.addEventListener('input', debounce(() => {
        filterTable(searchInput.value.toLowerCase());
    }, 300));
}

// Fungsi debounce untuk menghindari terlalu banyak pemanggilan fungsi
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

// Fungsi untuk memfilter tabel
function filterTable(searchTerm) {
    const tableBody = document.querySelector('.listofitemstable tbody');
    const rows = tableBody.getElementsByTagName('tr');
    let hasResults = false;

    for (const row of rows) {
        let text = '';
        const cells = row.getElementsByTagName('td');
        
        // Mengumpulkan teks dari setiap sel kecuali kolom foto
        for (let i = 0; i < cells.length - 1; i++) {
            text += cells[i].textContent.toLowerCase() + ' ';
        }

        if (text.includes(searchTerm)) {
            row.style.display = '';
            hasResults = true;
        } else {
            row.style.display = 'none';
        }
    }

    // Jika tidak ada hasil yang ditemukan
    if (!hasResults) {
        if (!document.getElementById('no-results-row')) {
            const noResultsRow = document.createElement('tr');
            noResultsRow.id = 'no-results-row';
            noResultsRow.innerHTML = '<td colspan="5" style="text-align: center;">Tidak ada hasil yang ditemukan</td>';
            tableBody.appendChild(noResultsRow);
        }
    } else {
        const noResultsRow = document.getElementById('no-results-row');
        if (noResultsRow) {
            noResultsRow.remove();
        }
    }
}

// Modifikasi fungsi updateTable yang sudah ada
function updateTable(data) {
    console.log('Updating table with data:', data);
    const tbody = document.querySelector('.listofitemstable tbody');
    
    if (!tbody) {
        console.error('Table body not found!');
        return;
    }
    
    tbody.innerHTML = '';

    if (!data || data.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = '<td colspan="5" style="text-align: center;">Tidak ada data</td>';
        tbody.appendChild(row);
        return;
    }

    data.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.barang || ''}</td>
            <td>${item.lokasi_kampus || ''}</td>
            <td>${item.tempat || ''}</td>
            <td>${formatDate(item.tanggal) || ''}</td>
            <td>${item.foto_barang ? `<img src="${item.foto_barang}" alt="Foto Barang" style="max-width: 100px;">` : 'Tidak ada foto'}</td>
        `;
        tbody.appendChild(row);
    });

    // Terapkan filter pencarian jika ada
    if (searchInput && searchInput.value) {
        filterTable(searchInput.value.toLowerCase());
    }
}
    </script>
  </body>
</html>