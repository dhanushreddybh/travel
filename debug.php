<?php
echo "<h2>Environment Variables:</h2>";
echo "<pre>";
echo "MYSQLHOST: " . getenv('MYSQLHOST') . "\n";
echo "MYSQLPORT: " . getenv('MYSQLPORT') . "\n";
echo "MYSQLDATABASE: " . getenv('MYSQLDATABASE') . "\n";
echo "MYSQLUSER: " . getenv('MYSQLUSER') . "\n";
echo "MYSQLPASSWORD: " . (getenv('MYSQLPASSWORD') ? '[SET]' : '[NOT SET]') . "\n";
echo "\n--- Alternative names ---\n";
echo "MYSQL_HOST: " . getenv('MYSQL_HOST') . "\n";
echo "MYSQL_PORT: " . getenv('MYSQL_PORT') . "\n";
echo "MYSQL_DATABASE: " . getenv('MYSQL_DATABASE') . "\n";
echo "MYSQL_USER: " . getenv('MYSQL_USER') . "\n";
echo "MYSQL_PASSWORD: " . (getenv('MYSQL_PASSWORD') ? '[SET]' : '[NOT SET]') . "\n";
echo "\n--- All Environment Variables ---\n";
print_r($_ENV);
echo "</pre>";
?>
