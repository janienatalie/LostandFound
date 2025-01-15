<?php
include '../database/config.php';
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log request untuk debugging
file_put_contents('debug.txt', date('Y-m-d H:i:s') . " Request received\n", FILE_APPEND);
file_put_contents('debug.txt', "POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);

$type = $_POST['type'] ?? 'Lost';
$campus = $_POST['campus'] ?? '';

// Log connection status
if ($conn->connect_error) {
    file_put_contents('debug.txt', "Connection failed: " . $conn->connect_error . "\n", FILE_APPEND);
    die(json_encode(['error' => 'Database connection failed']));
}

// Determine which table to query
$table = ($type === 'Lost') ? 'LostItems' : 'FoundItems';
$barangColumn = ($type === 'Lost') ? 'barang_hilang' : 'barang_ditemukan';
$tempatColumn = ($type === 'Lost') ? 'tempat_kehilangan' : 'tempat_menemukan';
$tanggalColumn = ($type === 'Lost') ? 'tanggal_kehilangan' : 'tanggal_menemukan';

// Build the query
$query = "SELECT $barangColumn as barang, 
                 lokasi_kampus, 
                 $tempatColumn as tempat, 
                 $tanggalColumn as tanggal, 
                 foto_barang 
          FROM $table";

// Add campus filter if specified
if (!empty($campus)) {
    $query .= " WHERE lokasi_kampus = ?";
}

$query .= " ORDER BY $tanggalColumn DESC";

// Log the query
file_put_contents('debug.txt', "Query: " . $query . "\n", FILE_APPEND);

try {
    $stmt = $conn->prepare($query);
    
    if (!empty($campus)) {
        $stmt->bind_param('s', $campus);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $items = $result->fetch_all(MYSQLI_ASSOC);
    
    // Log the results
    file_put_contents('debug.txt', "Results: " . print_r($items, true) . "\n", FILE_APPEND);
    
    echo json_encode($items);
} catch (Exception $e) {
    file_put_contents('debug.txt', "Error: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>