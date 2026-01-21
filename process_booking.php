<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

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
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
        exit;
    }
    
    $destination = $data['destination'] ?? '';
    $package_amount = floatval($data['amount'] ?? 0);
    $full_name = $data['fullName'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $num_travelers = intval($data['travelers'] ?? 1);
    $payment_method = $data['paymentMethod'] ?? '';
    $total_amount = $package_amount * $num_travelers;
    
    $booking_id = 'WL' . date('Ymd') . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
    
    try {
        $stmt = $pdo->prepare("INSERT INTO bookings (booking_id, destination, package_amount, full_name, email, phone, num_travelers, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $booking_id,
            $destination,
            $package_amount,
            $full_name,
            $email,
            $phone,
            $num_travelers,
            $payment_method,
            $total_amount
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Booking confirmed successfully',
            'booking_id' => $booking_id,
            'data' => [
                'destination' => $destination,
                'travelers' => $num_travelers,
                'total_amount' => $total_amount,
                'email' => $email
            ]
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Booking failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}