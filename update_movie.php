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

// Get movie ID and action from POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$action = $_POST['action'] ?? '';

// Validate ID
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Neplatné ID filmu.']);
    exit;
}

// Update movie status based on action
if ($action === 'watch') {
    $result = updateMovieStatus($conn, $id, true);
} elseif ($action === 'unwatch') {
    $result = updateMovieStatus($conn, $id, false);
} else {
    $result = ['success' => false, 'message' => 'Neplatná akcia.'];
}

// Return result
echo json_encode($result); 