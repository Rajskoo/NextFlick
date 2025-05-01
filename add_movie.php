<?php
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['success' => false, 'message' => 'Neplatná požiadavka.']);
    exit;
}

// Get movie data from POST
$movieData = [
    'title' => $_POST['title'] ?? '',
    'year' => $_POST['year'] ?? null,
    'genre' => $_POST['genre'] ?? '',
    'description' => $_POST['description'] ?? '',
    'image_url' => $_POST['image_url'] ?? ''
];

// Add movie to database
$result = addMovie($conn, $movieData);

// Return result
echo json_encode($result); 