<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['hint'])) {
    echo json_encode(["error" => "No active game or hint available."]);
    exit;
}

echo json_encode([
    "hint" => $_SESSION['hint']
]);
?>