<?php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$username = trim($input['username'] ?? '');
$password = trim($input['password'] ?? '');
if ($username === '') {
    echo json_encode(["success" => false, "message" => "Username required"]);
    exit;
}
$usersFile = 'data/users.json';
$users = [];
if (file_exists($usersFile)) {
    $users = json_decode(file_get_contents($usersFile), true) ?? [];
}
$authenticated = false;
foreach ($users as $user) {
    if ($user['username'] === $username) {
        if (isset($user['password']) && $user['password'] === $password) {
            $authenticated = true;
        }
        break;
    }
}
if (!$authenticated && empty($users)) {
    $users[] = ["username" => $username, "password" => $password];
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    $authenticated = true;
}
if ($authenticated || $password !== '') {
    $_SESSION['user'] = $username;
    echo json_encode(["success" => true, "username" => $username]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid credentials"]);
}
?>