<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function getDatabaseConnection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    try {
        $dsn = "mysql:host=" . DB_HOST;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Create database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
        $pdo->exec("USE " . DB_NAME);

        // Create users table
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                full_name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )'
        );

        // Create fees table
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS fees (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                month VARCHAR(20) NOT NULL,
                amount DECIMAL(10, 2) NOT NULL,
                due_date DATE NOT NULL,
                status VARCHAR(20) NOT NULL,
                FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
            )'
        );

        // Create user_profiles table (3rd table)
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS user_profiles (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL UNIQUE,
                phone VARCHAR(20),
                address TEXT,
                date_of_birth DATE,
                FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
            )'
        );

        return $pdo;
    }
    catch (PDOException $e) {
        die("Could not connect to the database $e");
    }
}
