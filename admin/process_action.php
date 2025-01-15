<?php
header('Content-Type: application/json');

// Koneksi database
$host = "localhost";
$user = "root";
$password = "";
$database = "lostandfound2";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (mysqli_connect_errno()) {
    die(json_encode(['success' => false, 'message' => 'Koneksi database gagal']));
}

$action = $_POST['action'] ?? '';
$id = intval($_POST['id'] ?? 0);
$type = $_POST['type'] ?? '';

$table = ($type == 'Lost') ? 'LostItems' : 'FoundItems';

if ($action === 'markFound') {
    // Logika untuk menandai barang sebagai ditemukan
    $query = "UPDATE $table SET status = 'found' WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $success = mysqli_stmt_execute($stmt);
    
    echo json_encode(['success' => $success]);
} elseif ($action === 'delete') {
    $query = "DELETE FROM $table WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $success = mysqli_stmt_execute($stmt);
    
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'message' => 'Aksi tidak valid']);
}