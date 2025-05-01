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

// Get movie ID and rating from POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

// Validate ID
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Neplatné ID filmu.']);
    exit;
}

// Update movie rating
$result = updateRating($conn, $id, $rating);

// Return result
echo json_encode($result); 