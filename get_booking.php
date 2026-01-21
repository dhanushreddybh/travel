<?php
// get_booking.php - Retrieve single booking by ID

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db_config.php';

if (isset($_GET['booking_id'])) {
    $booking_id = htmlspecialchars($_GET['booking_id']);
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = ?");
        $stmt->execute([$booking_id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($booking) {
            echo json_encode([
                'success' => true,
                'booking' => $booking
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Booking not found'
            ]);
        }
        
    } catch(PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Booking ID is required'
    ]);
}
?>