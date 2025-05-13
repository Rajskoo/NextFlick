<?php
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $planned_date = !empty($_POST['planned_date']) ? $_POST['planned_date'] : null;
    
    if (empty($title)) {
        echo json_encode(['success' => false, 'message' => 'Názov nápadu je povinný']);
        exit;
    }
    
    $ideaId = addDateIdea($conn, $title, $planned_date);
    
    if ($ideaId) {
        echo json_encode(['success' => true, 'id' => $ideaId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Chyba pri pridávaní nápadu']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Neplatná metóda']);
} 