<?php
session_start();

$host = 'localhost';
$dbname = 'portfolio_db';
$username = 'root';
$password = '';

try {
    // First try to connect without specifying a database
    $db = new PDO("mysql:host=$host", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $db->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    
    // Then connect to the specific database
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create tables if they don't exist
    $db->exec("
        CREATE TABLE IF NOT EXISTS messages (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS projects (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(100) NOT NULL,
            description TEXT,
            image_url VARCHAR(255),
            technologies VARCHAR(255),
            github_link VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS skills (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(50) NOT NULL,
            percentage INT NOT NULL,
            category VARCHAR(50) NOT NULL,
            icon_class VARCHAR(50)
        );
        
        CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE
        )
    ");

    // Insert default admin user if not exists
    $stmt = $db->prepare("SELECT id FROM users WHERE username = 'admin' LIMIT 1");
    $stmt->execute();
    if (!$stmt->fetch()) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $db->exec("INSERT INTO users (username, password, email) VALUES ('admin', '$hashedPassword', 'admin@example.com')");
    }
    
} catch(PDOException $e) {
    // Log the error details
    error_log("Database connection error: " . $e->getMessage());
    
    // Show a user-friendly error message
    die("Database connection error. Please check your database configuration and try again.");
}

// Helper functions
function verify_db_connection() {
    global $db;
    try {
        $db->query("SELECT 1");
        return true;
    } catch (PDOException $e) {
        error_log("Database connection verification failed: " . $e->getMessage());
        return false;
    }
}

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function redirect($location) {
    header("Location: $location");
    exit();
}

function set_flash_message($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function get_flash_message() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return "<div class='alert alert-{$flash['type']}'>{$flash['message']}</div>";
    }
    return '';
}

// Make the database connection available globally
global $db;
?>
