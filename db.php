<?php
$host = 'db';  // Docker service name for the database
$db   = 'checkout';
$user = 'checkoutuser';
$pass = 'checkpass';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // return rows as assoc arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                   // use native prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo "Database connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
