<?php
include '../database/config.php'; // Pastikan koneksi database benar

// Mengambil data yang dikirim dari frontend
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $id = $data['id']; // ID barang yang ingin dihapus
    $deleted = false; // Untuk mengecek apakah ada barang yang berhasil dihapus

    // Cek tabel LostItems
    $sqlLost = "SELECT id FROM LostItems WHERE id = ?";
    $stmtLost = $conn->prepare($sqlLost);
    $stmtLost->bind_param("i", $id);
    $stmtLost->execute();
    $resultLost = $stmtLost->get_result();

    if ($resultLost->num_rows > 0) {
        // Hapus dari LostItems jika ditemukan
        $deleteSql = "DELETE FROM LostItems WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $id);
        if ($deleteStmt->execute()) {
            $deleted = true;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus dari LostItems: ' . $deleteStmt->error]);
            exit;
        }
        $deleteStmt->close();
    }

    // Cek tabel FoundItems
    $sqlFound = "SELECT id FROM FoundItems WHERE id = ?";
    $stmtFound = $conn->prepare($sqlFound);
    $stmtFound->bind_param("i", $id);
    $stmtFound->execute();
    $resultFound = $stmtFound->get_result();

    if ($resultFound->num_rows > 0) {
        // Hapus dari FoundItems jika ditemukan
        $deleteSql = "DELETE FROM FoundItems WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $id);
        if ($deleteStmt->execute()) {
            $deleted = true;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus dari FoundItems: ' . $deleteStmt->error]);
            exit;
        }
        $deleteStmt->close();
    }

    // Cek apakah ada yang dihapus
    if ($deleted) {
        echo json_encode(['status' => 'success', 'message' => 'Barang berhasil dihapus']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID barang tidak ditemukan di kedua tabel']);
    }

    // Tutup statement dan koneksi
    $stmtLost->close();
    $stmtFound->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID barang tidak ditemukan dalam permintaan']);
}

$conn->close();
?>
