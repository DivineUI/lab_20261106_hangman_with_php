<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["word"])) {
    http_response_code(400);
    echo json_encode(["error" => "No round in progress"]);
    exit;
}

$_SESSION["over"] = true;
echo json_encode(["word" => $_SESSION["word"], "score" => $_SESSION["score"]]);