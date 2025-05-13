<?php
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Chýbajúce ID položky']);
        exit;
    }
    
    // Get the thread item to check if it's an image
    $sql = "SELECT type, content FROM date_idea_thread WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // If it's an image, delete the file
        if ($row['type'] === 'image' && file_exists($row['content'])) {
            unlink($row['content']);
        }
        
        // Delete the thread item
        $sql = "DELETE FROM date_idea_thread WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Chyba pri mazaní položky']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Položka nebola nájdená']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Neplatná metóda']);
} 