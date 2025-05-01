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

// Get parameters
$id = $_POST['id'] ?? 0;
$rating = $_POST['rating'] ?? 0;
$rater = $_POST['rater'] ?? '';

// Validate ID
if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID filmu je povinné.']);
    exit;
}

// Update rating
$result = updateUserRating($conn, $id, $rating, $rater);

// Return result
echo json_encode($result); 