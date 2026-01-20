<?php
// check_session.php - Check if user is logged in

session_start();
header('Content-Type: application/json; charset=utf-8');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => true,
        'logged_in' => true,
        'user' => [
            'user_id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'full_name' => $_SESSION['full_name'],
            'user_type' => $_SESSION['user_type']
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'logged_in' => false
    ]);
}
?>

