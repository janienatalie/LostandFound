<?php
include '../database/config.php';

// Enable error logging
error_log("Delete request received: " . file_get_contents('php://input'));

// Mengambil dan decode data JSON yang dikirim
$data = json_decode(file_get_contents('php://input'), true);

// Cek apakah ID dan tipe tabel dikirim
if (isset($data['id']) && isset($data['table'])) {
    $id = $data['id'];
    $table = $data['table'];
    
    try {
        // Pilih tabel berdasarkan parameter yang dikirim
        if ($table === 'lost') {
            // Verifikasi bahwa item ada di LostItems
            $checkSql = "SELECT id FROM LostItems WHERE id = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("i", $id);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            
            if ($result->num_rows > 0) {
                // Item ditemukan, lakukan penghapusan
                $deleteSql = "DELETE FROM LostItems WHERE id = ?";
                $deleteStmt = $conn->prepare($deleteSql);
                $deleteStmt->bind_param("i", $id);
                
                if ($deleteStmt->execute()) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Item berhasil dihapus dari LostItems'
                    ]);
                } else {
                    throw new Exception("Gagal menghapus dari LostItems");
                }
                $deleteStmt->close();
            } else {
                throw new Exception("Item tidak ditemukan di LostItems");
            }
            $checkStmt->close();
            
        } else if ($table === 'found') {
            // Verifikasi bahwa item ada di FoundItems
            $checkSql = "SELECT id FROM FoundItems WHERE id = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("i", $id);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            
            if ($result->num_rows > 0) {
                // Item ditemukan, lakukan penghapusan
                $deleteSql = "DELETE FROM FoundItems WHERE id = ?";
                $deleteStmt = $conn->prepare($deleteSql);
                $deleteStmt->bind_param("i", $id);
                
                if ($deleteStmt->execute()) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Item berhasil dihapus dari FoundItems'
                    ]);
                } else {
                    throw new Exception("Gagal menghapus dari FoundItems");
                }
                $deleteStmt->close();
            } else {
                throw new Exception("Item tidak ditemukan di FoundItems");
            }
            $checkStmt->close();
            
        } else {
            throw new Exception("Tipe tabel tidak valid");
        }
        
    } catch (Exception $e) {
        error_log("Error in deleteItem.php: " . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
    
} else {
    error_log("Missing required parameters in deleteItem.php");
    echo json_encode([
        'status' => 'error',
        'message' => 'ID dan tipe tabel harus disediakan'
    ]);
}

// Tutup koneksi database
$conn->close();
?>