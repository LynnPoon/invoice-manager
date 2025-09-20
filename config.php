<?php
require __DIR__ . '/vendor/autoload.php';

// Load .env locally (won’t crash if missing on Render)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Read DB settings from environment variables
$host     = $_ENV['DB_HOST'] ?? 'localhost';
$port     = $_ENV['DB_PORT'] ?? 3306;
$db       = $_ENV['DB_NAME'] ?? 'invoice_manager';
$username = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';

// Build DSN string
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

try {
    // Connect with SSL (Aiven requires SSL mode)
    $pdo = new PDO($dsn, $username, $password, [
        PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/ca.pem', // Download from Aiven and place in project root
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}
