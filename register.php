<?php
// register.php - Handle User Registration

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
    
    $username = trim($data['username'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';
    $full_name = trim($data['full_name'] ?? '');
    $phone = trim($data['phone'] ?? '');
    
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
        exit;
    }
    
    try {
        // Check if username or email exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'Username or email already exists']);
            exit;
        }
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, phone, user_type) VALUES (?, ?, ?, ?, ?, 'user')");
        $stmt->execute([$username, $email, $hashed_password, $full_name, $phone]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful! Please login.'
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Registration failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
