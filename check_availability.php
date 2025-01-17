<?php
include './database/config.php';
header('Content-Type: application/json');

if (isset($_POST['type']) && isset($_POST['value'])) {
    $type = $_POST['type'];
    $value = $_POST['value'];
    
    try {
        if ($type === 'username') {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM Users WHERE username = ?");
        } else if ($type === 'npm') {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM Users WHERE npm = ?");
        } else {
            echo json_encode(['error' => 'Invalid type']);
            exit;
        }
        
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        echo json_encode([
            'exists' => $row['count'] > 0,
            'message' => $row['count'] > 0 ? 
                ($type === 'username' ? 'Username sudah digunakan' : 'NPM sudah terdaftar') : 
                'tersedia'
        ]);
        
        $stmt->close();
    } catch(Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    
    $conn->close();
}
?>