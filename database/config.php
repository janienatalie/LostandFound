<?php

$host = "localhost"; // Nama host database
$user = "root"; // Username database
$password = ""; // Password database
$database = "lostandfound"; // Nama database

$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>