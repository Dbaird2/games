<?php
$host = 'localhost';
$port = '5432'; // Default PostgreSQL port
$dbname = 'games';
$user = 'postgres';
$password = '12345';

// The Data Source Name (DSN) string
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    // Create a new PDO instance
    $dbh = new PDO($dsn, $user, $password);

    // Set PDO attributes for better error handling (recommended)
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // You can now perform database operations using $pdo, e.g., prepared statements

} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
}
