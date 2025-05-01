<?php
require_once 'config.php';

// Create database connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to utf8
    $conn->set_charset("utf8mb4");
    
    // Check if the movies table exists
    $result = $conn->query("SHOW TABLES LIKE 'movies'");
    if ($result->num_rows == 0) {
        // Table doesn't exist, create it
        $sql = "CREATE TABLE IF NOT EXISTS movies (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            year INT(4) NULL,
            genre VARCHAR(100) NULL,
            description TEXT NULL,
            image_url VARCHAR(255) NULL,
            rating INT(1) DEFAULT 0,
            rating_mia INT(1) DEFAULT 0,
            rating_tomino INT(1) DEFAULT 0,
            watched TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        if (!$conn->query($sql)) {
            die("Error creating table: " . $conn->error);
        }
    } else {
        // Check if image_url column exists, if not add it
        $result = $conn->query("SHOW COLUMNS FROM movies LIKE 'image_url'");
        if ($result->num_rows == 0) {
            $sql = "ALTER TABLE movies ADD COLUMN image_url VARCHAR(255) NULL AFTER description";
            if (!$conn->query($sql)) {
                die("Error adding image_url column: " . $conn->error);
            }
        }
        
        // Check if rating_mia column exists, if not add it
        $result = $conn->query("SHOW COLUMNS FROM movies LIKE 'rating_mia'");
        if ($result->num_rows == 0) {
            $sql = "ALTER TABLE movies ADD COLUMN rating_mia INT(1) DEFAULT 0 AFTER rating";
            if (!$conn->query($sql)) {
                die("Error adding rating_mia column: " . $conn->error);
            }
        }
        
        // Check if rating_tomino column exists, if not add it
        $result = $conn->query("SHOW COLUMNS FROM movies LIKE 'rating_tomino'");
        if ($result->num_rows == 0) {
            $sql = "ALTER TABLE movies ADD COLUMN rating_tomino INT(1) DEFAULT 0 AFTER rating_mia";
            if (!$conn->query($sql)) {
                die("Error adding rating_tomino column: " . $conn->error);
            }
        }
    }
    
} catch (Exception $e) {
    // If database does not exist, create it
    if (strpos($e->getMessage(), "Unknown database") !== false) {
        $tempConn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        
        // Create database
        $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        if ($tempConn->query($sql) === TRUE) {
            // Create movies table
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $conn->set_charset("utf8mb4");
            
            $sql = "CREATE TABLE IF NOT EXISTS movies (
                id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                year INT(4) NULL,
                genre VARCHAR(100) NULL,
                description TEXT NULL,
                image_url VARCHAR(255) NULL,
                rating INT(1) DEFAULT 0,
                rating_mia INT(1) DEFAULT 0,
                rating_tomino INT(1) DEFAULT 0,
                watched TINYINT(1) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            if (!$conn->query($sql)) {
                die("Error creating table: " . $conn->error);
            }
        } else {
            die("Error creating database: " . $tempConn->error);
        }
        
        $tempConn->close();
    } else {
        die("Connection failed: " . $e->getMessage());
    }
} 