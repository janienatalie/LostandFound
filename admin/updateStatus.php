<?php
include '../database/config.php'; // Pastikan koneksi ke database sudah benar

// Mengambil data yang dikirim dari frontend
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['status'])) {
    $id = $data['id']; // ID barang
    $status = $data['status']; // Status baru (Lost / Found)

    // Menentukan tabel dan kolom mana yang akan diperbarui
    if ($status == 'Found') {
        $sql = "UPDATE FoundItems SET status = 'Found' WHERE id = ?";
    } elseif ($status == 'Lost') {
        $sql = "UPDATE LostItems SET status = 'Lost' WHERE id = ?";
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Status tidak valid']);
        exit;
    }

    // Menyiapkan dan menjalankan query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // Bind ID dengan parameter query

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Status barang telah diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status barang']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
}

$conn->close();
?>
