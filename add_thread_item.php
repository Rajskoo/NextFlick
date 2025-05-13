<?php
require_once 'functions.php';
require_once 'config.php';

header('Content-Type: application/json');

$content = $_POST['content'] ?? '';
$image_url = $_POST['image_url'] ?? '';
$date_idea_id = $_POST['date_idea_id'] ?? '';

if (empty($date_idea_id)) {
    echo json_encode(['success' => false, 'message' => 'Chýba ID nápadu']);
    exit;
}

// Validate image URL if provided
if (!empty($image_url)) {
    if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
        echo json_encode(['success' => false, 'message' => 'Neplatná URL adresa obrázka']);
        exit;
    }
    
    // Check if URL is accessible
    $headers = get_headers($image_url);
    if (!$headers || strpos($headers[0], '200') === false) {
        echo json_encode(['success' => false, 'message' => 'Obrázok nie je dostupný']);
        exit;
    }
}

try {
    $pdo = getDbConnection();
    
    // Check if the date idea exists and is open
    $stmt = $pdo->prepare("SELECT status FROM date_ideas WHERE id = ?");
    $stmt->execute([$date_idea_id]);
    $idea = $stmt->fetch();
    
    if (!$idea) {
        echo json_encode(['success' => false, 'message' => 'Nápad nebol nájdený']);
        exit;
    }
    
    if ($idea['status'] !== 'open') {
        echo json_encode(['success' => false, 'message' => 'Nápad je uzavretý']);
        exit;
    }
    
    // Rozlíšiť, či ide o text alebo obrázok
    $type = !empty($image_url) ? 'image' : 'text';
    $contentToInsert = !empty($image_url) ? $image_url : $content;
    $stmt = $pdo->prepare("
        INSERT INTO date_idea_thread (date_idea_id, content, type, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([
        $date_idea_id,
        $contentToInsert,
        $type
    ]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Chyba databázy']);
} 