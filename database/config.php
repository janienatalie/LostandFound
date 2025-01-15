<?php

$host = "localhost"; // Nama host database
$user = "root"; // Username database
$password = ""; // Password database
$database = "lostandfound2"; // Nama database

try {
    $conn = new mysqli($host, $user, $password, $database);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to handle Indonesian characters properly
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Database connection failed");
}
?>