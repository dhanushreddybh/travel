<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$host = 'localhost';
$dbname = 'travel_booking';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    $user = trim($data['username'] ?? '');
    $pass = $data['password'] ?? '';
    
    if (empty($user) || empty($pass)) {
        echo json_encode(['success' => false, 'message' => 'Username and password required']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND is_active = 1");
        $stmt->execute([$user]);
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($userRow && password_verify($pass, $userRow['password'])) {
            $updateStmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
            $updateStmt->execute([$userRow['user_id']]);
            
            $_SESSION['user_id'] = $userRow['user_id'];
            $_SESSION['username'] = $userRow['username'];
            $_SESSION['user_type'] = $userRow['user_type'];
            $_SESSION['full_name'] = $userRow['full_name'];
            
            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'user_id' => $userRow['user_id'],
                    'username' => $userRow['username'],
                    'full_name' => $userRow['full_name'],
                    'email' => $userRow['email'],
                    'user_type' => $userRow['user_type']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Login failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}