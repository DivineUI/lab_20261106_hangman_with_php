<?php
session_start();
header('Content-Type: application/json');
$word = $_SESSION['word'];
$masked = $_SESSION['masked'];
$wrong = $_SESSION['wrong'];
$maxWrong = $_SESSION['maxWrong'];
$guessed = $_SESSION['guessed'] ?? [];

if (!isset($_SESSION['word']) || ($_SESSION['over'] ?? false)) {
    http_response_code(400);
    echo json_encode(["error" => "No active game."]);
    exit;
}
$letter = isset($_POST['letter']) ? strtolower(trim($_POST['letter'])) : '';
if (!preg_match('/^[a-z]$/', $letter)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid letter."]);
    exit;
}

// Refuse a letter that was already guessed
if (in_array($letter, $guessed)) {
    http_response_code(400);
    echo json_encode(["error" => "Already guessed"]);
    exit;
}

$guessed[] = $letter;
$_SESSION['guessed'] = $guessed;

// Reveal the letter, and give 1 point for each position revealed
$letterFound = false;
$maskedArray = str_split($masked);
for ($i = 0; $i < strlen($word); $i++) {
    if (strtolower($word[$i]) === $letter) {
        $maskedArray[$i] = $word[$i];
        $letterFound = true;
        $_SESSION['score']++;
    }
}
$masked = implode('', $maskedArray);
$_SESSION['masked'] = $masked;

// A wrong letter counts against the player
if (!$letterFound) {
    $wrong++;
    $_SESSION['wrong'] = $wrong;
}

// Is the round over?
$won = ($masked === $word);
$lost = (!$won && $wrong >= $maxWrong);
$over = $won || $lost;
$_SESSION['over'] = $over;

echo json_encode([
    "masked" => $lost ? $word : $masked,   // reveal the word on a loss
    "correct" => $letterFound,
    "wrong" => $wrong,
    "maxWrong" => $maxWrong,
    "score" => $_SESSION['score'],
    "over" => $over,
    "won" => $won,
    "word" => $over ? $word : null
]);