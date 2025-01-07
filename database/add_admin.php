<?php
require_once 'database_connection.php'; 

try {
    $username = 'admin'; 
    $password = 'admin123'; 
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO Admins (username, password_hash) VALUES (?, ?)");
    $stmt->execute([$username, $passwordHash]);

    echo "Admin berhasil ditambahkan dengan username: $username dan password: $password";
} catch (Exception $e) {
    die('Gagal menambahkan admin: ' . $e->getMessage());
}
?>