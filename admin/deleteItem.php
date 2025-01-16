<?php
include '../database/config.php'; // Pastikan koneksi ke database sudah benar

// Mengambil data yang dikirim dari frontend
$data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id'])) {
        $id = $data['id']; // ID barang yang ingin dihapus

        // Menentukan query untuk menghapus data berdasarkan ID
        // Cek status barang terlebih dahulu
        $sqlLost = "SELECT status FROM LostItems WHERE id = ?";
        $sqlFound = "SELECT status FROM FoundItems WHERE id = ?";    
        // Cek tabel LostItems
$stmtLost = $conn->prepare($sqlLost);
$stmtLost->bind_param("i", $id);
$stmtLost->execute();
$resultLost = $stmtLost->get_result();

if ($resultLost->num_rows > 0) {
    $row = $resultLost->fetch_assoc();
    $status = $row['status'];
    $deleteSql = "DELETE FROM LostItems WHERE id = ?";
}

// Cek tabel FoundItems
$stmtFound = $conn->prepare($sqlFound);
$stmtFound->bind_param("i", $id);
$stmtFound->execute();
$resultFound = $stmtFound->get_result();

if ($resultFound->num_rows > 0) {
    $row = $resultFound->fetch_assoc();
    $status = $row['status'];
    $deleteSql = "DELETE FROM FoundItems WHERE id = ?";
}

    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID barang tidak ditemukan']);
        }
        if (!$deleteStmt->execute()) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus barang: ' . $deleteStmt->error]);
            exit;
        }
        error_log("ID yang dikirim: " . $id);
error_log("Status yang ditemukan: " . $status);

$conn->close();
?>
