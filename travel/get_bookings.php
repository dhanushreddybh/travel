<?php
// get_bookings.php - Retrieve all bookings (for admin panel)

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db_config.php';

try {
    $stmt = $pdo->query("SELECT * FROM bookings ORDER BY booking_date DESC");
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'bookings' => $bookings
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>