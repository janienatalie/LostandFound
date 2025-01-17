<?php
include '../database/config.php';

// Mengambil data yang dikirim dari frontend
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $id = $data['id']; // ID barang yang akan diperbarui statusnya

    // Update status barang menjadi "Sudah Ditemukan" pada tabel LostItems
    $updateSqlLost = "UPDATE LostItems SET status = 'Sudah Ditemukan' WHERE id = ?";
    $updateSqlFound = "UPDATE FoundItems SET status = 'Sudah Dikembalikan' WHERE id = ?";

    // Cek apakah data ada di tabel LostItems
    $stmtLost = $conn->prepare("SELECT id FROM LostItems WHERE id = ?");
    $stmtLost->bind_param("i", $id);
    $stmtLost->execute();
    $resultLost = $stmtLost->get_result();

    if ($resultLost->num_rows > 0) {
        $stmtUpdateLost = $conn->prepare($updateSqlLost);
        $stmtUpdateLost->bind_param("i", $id);

        if ($stmtUpdateLost->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui di tabel LostItems']);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status di tabel LostItems']);
            exit;
        }
    }

    // Cek apakah data ada di tabel FoundItems
    $stmtFound = $conn->prepare("SELECT id FROM FoundItems WHERE id = ?");
    $stmtFound->bind_param("i", $id);
    $stmtFound->execute();
    $resultFound = $stmtFound->get_result();

    if ($resultFound->num_rows > 0) {
        $stmtUpdateFound = $conn->prepare($updateSqlFound);
        $stmtUpdateFound->bind_param("i", $id);

        if ($stmtUpdateFound->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui di tabel FoundItems']);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status di tabel FoundItems']);
            exit;
        }
    }

    echo json_encode(['status' => 'error', 'message' => 'ID barang tidak ditemukan di kedua tabel']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
}

$conn->close();
?>
