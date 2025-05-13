<?php
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Chýbajúce ID nápadu']);
        exit;
    }
    
    if (closeDateIdea($conn, $id)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Chyba pri uzatváraní nápadu']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Neplatná metóda']);
} 