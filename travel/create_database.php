<?php
// create_database.php - Run this file once to create the database and table

$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS travel_booking");
    $pdo->exec("USE travel_booking");
    
    // Create bookings table
    $sql = "CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        booking_id VARCHAR(50) UNIQUE NOT NULL,
        destination VARCHAR(100) NOT NULL,
        package_amount DECIMAL(10, 2) NOT NULL,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        num_travelers INT NOT NULL,
        payment_method VARCHAR(50) NOT NULL,
        total_amount DECIMAL(10, 2) NOT NULL,
        booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status VARCHAR(20) DEFAULT 'confirmed'
    )";
    
    $pdo->exec($sql);
    
    echo "Database and table created successfully!";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>