<?php
require_once 'config.php';

try {
    // Drop existing tables
    $pdo->exec("DROP TABLE IF EXISTS projects");
    $pdo->exec("DROP TABLE IF EXISTS skills");
    $pdo->exec("DROP TABLE IF EXISTS messages");
    $pdo->exec("DROP TABLE IF EXISTS users");

    // Create tables
    $pdo->exec("
        CREATE TABLE projects (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(100) NOT NULL,
            description TEXT,
            image_url VARCHAR(255),
            technologies VARCHAR(255),
            github_link VARCHAR(255),
            featured BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE skills (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(50) NOT NULL,
            percentage INT NOT NULL,
            category VARCHAR(50) NOT NULL,
            icon_class VARCHAR(50)
        );

        CREATE TABLE messages (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    echo "Database tables created successfully!<br>";
    echo "Now visit init_data.php to initialize your portfolio data.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
