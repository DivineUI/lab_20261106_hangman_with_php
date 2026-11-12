
<?php
session_start();
header("Content-Type: application/json");
$level = "easy";

if (isset($_POST["level"])) {
    $level = $_POST["level"];
} elseif (isset($_GET["level"])) {
    $level = $_GET["level"];
}

$file = file_get_contents("data/words.json");
$words = json_decode($file, true);

if (!isset($words[$level])) {

    echo json_encode([
        "error" => "Unknown level"
    ]);

    exit;
}

$list = $words[$level];
$randomNumber = array_rand($list);
$item = $list[$randomNumber];

$_SESSION["word"] = $item["word"];
$_SESSION["hint"] = $item["hint"];
$_SESSION["guessed"] = array();
$_SESSION["wrong"] = 0;
$_SESSION["score"] = 0;
$_SESSION["hintUsed"] = false;
$_SESSION["over"] = false;

$maskedWord = str_repeat("_", strlen($item["word"]));

echo json_encode([
    "masked" => $maskedWord,
    "wrong" => 0,
    "maxWrong" => 6,
    "score" => 0
]);

?>

