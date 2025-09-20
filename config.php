<?php
require __DIR__ . '/vendor/autoload.php';

// Load .env locally (won’t crash if missing, e.g. on Render)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Read DB settings from environment variables
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? 3306;
$db   = $_ENV['DB_NAME'] ?? 'invoice_manager';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? 'mysql4Lynn!';

// Build DSN string
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

try {
    // Connect with SSL (Aiven requires SSL mode)
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/ca.pem', // Download from Aiven and place in project root
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
