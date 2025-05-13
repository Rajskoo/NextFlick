<?php
require_once 'db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Delete the countdown
    $stmt = $conn->prepare("DELETE FROM countdowns WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to the main page
header('Location: index.php');
exit; 