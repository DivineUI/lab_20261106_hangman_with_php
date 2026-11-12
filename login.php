<?php
session_start();
header("Content-Type: application/json");

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

$text = @file_get_contents(__DIR__ . "/data/users.json");
$users = ($text === false) ? null : json_decode($text, true);
if ($users === null) {
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => "Cannot read the users file."]);
    exit;
}

if (isset($users[$username]) && $users[$username]["password"] === $password) {
    $_SESSION["username"] = $username;
    echo json_encode(["ok" => true, "username" => $username]);
} else {
    http_response_code(401);
    echo json_encode(["ok" => false, "message" => "Wrong username or password."]);
}