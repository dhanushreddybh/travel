<?php
// Try different methods to get environment variables
function getEnvVar($name, $default = '') {
    // Try getenv
    $value = getenv($name);
    if ($value !== false && $value !== '') {
        return $value;
    }
    
    // Try $_ENV
    if (isset($_ENV[$name]) && $_ENV[$name] !== '') {
        return $_ENV[$name];
    }
    
    // Try $_SERVER
    if (isset($_SERVER[$name]) && $_SERVER[$name] !== '') {
        return $_SERVER[$name];
    }
    
    return $default;
}

$host = getEnvVar('MYSQLHOST', 'localhost');
$port = getEnvVar('MYSQLPORT', '3306');
$database = getEnvVar('MYSQLDATABASE', 'railway');
$username = getEnvVar('MYSQLUSER', 'root');
$password = getEnvVar('MYSQLPASSWORD', '');

// Create connection with port
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection with detailed error
if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed",
        "error" => $conn->connect_error,
        "host" => $host,
        "port" => $port,
        "database" => $database,
        "user" => $username
    ]));
}

// Set charset to utf8
$conn->set_charset("utf8mb4");
?>
