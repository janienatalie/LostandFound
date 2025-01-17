<?php
include '../database/config.php';
header('Content-Type: application/json');

try {
    // Mengambil data yang dikirim dari frontend
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']) || !isset($data['status'])) {
        throw new Exception('Data tidak lengkap');
    }

    $id = $data['id'];
    $originalStatus = $data['status'];
    
    // Tentukan status baru dan tabel berdasarkan status original
    if ($originalStatus === 'Lost') {
        $newStatus = 'Sudah Ditemukan';
        $table = 'LostItems';
    } else if ($originalStatus === 'Found') {
        $newStatus = 'Sudah Dikembalikan';
        $table = 'FoundItems';
    } else {
        throw new Exception('Status tidak valid');
    }

    // Prepare statement untuk update
    $sql = "UPDATE $table SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Gagal mempersiapkan query: ' . $conn->error);
    }

    // Bind parameter dan execute
    $stmt->bind_param("si", $newStatus, $id);
    
    if ($stmt->execute()) {
        // Cek apakah ada baris yang terpengaruh
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => "Status berhasil diperbarui di tabel $table"
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => "Data dengan ID tersebut tidak ditemukan di tabel $table"
            ]);
        }
    } else {
        throw new Exception('Gagal mengeksekusi query: ' . $stmt->error);
    }

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
?>