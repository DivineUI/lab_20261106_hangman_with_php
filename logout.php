<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["word"]) || $_SESSION["over"]) {
    http_response_code(400);
    echo json_encode(["error" => "No round in progress"]);
    exit;
}

if (!$_SESSION["hintUsed"]) {
    $_SESSION["hintUsed"] = true;
    $_SESSION["score"] = max(0, $_SESSION["score"] - 1);
}

echo json_encode(["hint" => $_SESSION["hint"], "score" => $_SESSION["score"]]);