<?php
   include '../database/config.php';
   include './sidebar.php';

// Cek jika ada request AJAX
if (isset($_POST['ajax']) && $_POST['ajax'] === 'true') {
    $type = isset($_POST['type']) ? $_POST['type'] : 'Lost';
    
    $table = ($type == 'Lost') ? 'LostItems' : 'FoundItems';
    $itemColumn = ($type == 'Lost') ? 'barang_hilang' : 'barang_ditemukan';
    $locationColumn = ($type == 'Lost') ? 'tempat_kehilangan' : 'tempat_menemukan';
    $dateColumn = ($type == 'Lost') ? 'tanggal_kehilangan' : 'tanggal_menemukan';
    
    $query = "SELECT $table.*, Users.nama, Users.npm 
              FROM $table 
              JOIN Users ON $table.user_id = Users.id 
              ORDER BY $dateColumn DESC";
    
    $result = mysqli_query($koneksi, $query);
    $items = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = [
                'nama' => $row['nama'],
                'npm' => $row['npm'],
                'kampus' => $row['lokasi_kampus'],
                'item' => ($type == 'Lost') ? $row['barang_hilang'] : $row['barang_ditemukan'],
                'lokasi' => ($type == 'Lost') ? $row['tempat_kehilangan'] : $row['tempat_menemukan'],
                'tanggal' => $row['tanggal_kehilangan'] ?? $row['tanggal_menemukan'],
                'foto' => $row['foto_barang']
            ];
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($items);
    exit;
}

// Default ke 'Lost' jika bukan request AJAX
$type = 'Lost';
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <link rel="stylesheet" href="./css/form.css" />
</head>
<body>
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
                        <th>NPM</th>
                        <th>Kampus</th>
                        <th>Item</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>Foto Barang</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
    <script>
        const lostFoundDropdown = document.getElementById("dropdown");
        const searchInput = document.getElementById("query");

        // Fungsi untuk memuat data
        async function fetchData() {
            try {
                const formData = new FormData();
                formData.append('type', lostFoundDropdown.value);
                formData.append('ajax', 'true');

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                updateTable(data);
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Fungsi untuk memperbarui tabel
        function updateTable(data) {
    const tbody = document.querySelector('.listofitemstable tbody');
    tbody.innerHTML = '';

    if (!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align: center;">Tidak ada data</td></tr>';
        return;
    }

    data.forEach((item, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${escapeHtml(item.nama)}</td>
            <td>${escapeHtml(item.npm)}</td>
            <td>${escapeHtml(item.kampus)}</td>
            <td>${escapeHtml(item.item)}</td>
            <td>${escapeHtml(item.lokasi)}</td>
            <td>${formatDate(item.tanggal)}</td>
            <td>${item.foto ? `<img src="${escapeHtml(item.foto)}" alt="Foto Barang" style="max-width: 100px;">` : 'Tidak ada foto'}</td>
            <td>
                <button class="has-been-found-btn" onclick="markAsFound(${item.id})" style="background-color: #763996; color: white; border: none; padding: 8px 12px; border-radius: 6px; margin-bottom: 5px; cursor: pointer; width: 100%;">Has Been Found</button>
                <button class="delete-btn" onclick="deleteItem(${item.id})" style="background-color: #763996; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; width: 100%;">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    if (searchInput.value) {
        filterTable(searchInput.value.toLowerCase());
    }
}

        // Fungsi untuk format tanggal
        function formatDate(dateString) {
            if (!dateString) return "";
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }

        // Fungsi untuk escape HTML
        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        // Fungsi debounce
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
            const tableBody = document.querySelector(".listofitemstable tbody");
            const rows = tableBody.getElementsByTagName("tr");
            let hasResults = false;

            for (const row of rows) {
                let text = "";
                const cells = row.getElementsByTagName("td");

                for (let i = 0; i < cells.length - 1; i++) {
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
                    noResultsRow.innerHTML = '<td colspan="8" style="text-align: center;">Tidak ada hasil yang ditemukan</td>';
                    tableBody.appendChild(noResultsRow);
                }
            } else {
                const noResultsRow = document.getElementById("no-results-row");
                if (noResultsRow) {
                    noResultsRow.remove();
                }
            }
        }

        // Event listener untuk dropdown
        if (lostFoundDropdown) {
            lostFoundDropdown.addEventListener('change', () => {
                fetchData();
                if (lostFoundDropdown.value) {
                    lostFoundDropdown.style.color = '#763996';
                } else {
                    lostFoundDropdown.style.color = '#c7c7c7';
                }
            });
        }

        // Event listener untuk pencarian
        if (searchInput) {
            searchInput.addEventListener("input", debounce(() => {
                filterTable(searchInput.value.toLowerCase());
            }, 300));
        }

        // Load data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            if (lostFoundDropdown) {
                lostFoundDropdown.value = 'Lost';
                lostFoundDropdown.dispatchEvent(new Event('change'));
            }
        });

        // Fungsi untuk menandai barang sudah ditemukan
async function markAsFound(itemId) {
    if (!confirm('Apakah Anda yakin ingin menandai barang ini sebagai ditemukan?')) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'markFound');
        formData.append('id', itemId);
        formData.append('type', lostFoundDropdown.value);

        const response = await fetch('process_action.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        if (result.success) {
            alert('Barang berhasil ditandai sebagai ditemukan!');
            fetchData(); // Menyegarkan tabel
        } else {
            alert('Gagal menandai barang sebagai ditemukan: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menandai barang');
    }
}

// Fungsi untuk menghapus barang
async function deleteItem(itemId) {
    if (!confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', itemId);
        formData.append('type', lostFoundDropdown.value);

        const response = await fetch('process_action.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        if (result.success) {
            alert('Barang berhasil dihapus!');
            fetchData(); // Menyegarkan tabel
        } else {
            alert('Gagal menghapus barang: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus barang');
    }
}
    </script>
</body>
</html>