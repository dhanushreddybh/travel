<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$host = 'localhost';
$dbname = 'travel_booking';
$username = 'root';
$password = '';

// Check if user is admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

try {
    // Get statistics - using simple query instead of view
    $statsQuery = "SELECT 
        COUNT(*) as total_bookings,
        COALESCE(SUM(total_amount), 0) as total_revenue,
        COALESCE(SUM(num_travelers), 0) as total_travelers,
        COUNT(CASE WHEN DATE(booking_date) = CURDATE() THEN 1 END) as today_bookings,
        COALESCE(SUM(CASE WHEN DATE(booking_date) = CURDATE() THEN total_amount ELSE 0 END), 0) as today_revenue,
        COALESCE(AVG(total_amount), 0) as avg_booking_value
    FROM bookings";
    
    $statsStmt = $pdo->query($statsQuery);
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
    
    // Get all bookings
    $bookingsStmt = $pdo->query("SELECT * FROM bookings ORDER BY booking_date DESC");
    $bookings = $bookingsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'stats' => $stats,
        'bookings' => $bookings
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>