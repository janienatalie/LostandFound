<?php
session_start();
require_once '../../database/database_connection.php'; // Sesuaikan path ke file database_connection.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $userType = trim($_POST['user_type']); // Either 'user' or 'admin'

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
        exit;
    }

    // Query based on user type
    if ($userType === 'admin') {
        $query = "SELECT id, username, password_hash FROM Admins WHERE username = ?";
    } else {
        $query = "SELECT id, username, password_hash FROM Users WHERE username = ?";
    }

    // Prepare and execute the query
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_type'] = $userType;

        echo json_encode(['success' => true, 'message' => 'Login successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

if (!in_array($userType, ['admin', 'user'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid user type.']);
    exit;
}

