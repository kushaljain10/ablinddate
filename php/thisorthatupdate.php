<?php
session_start();
include_once "config.php";

if (!isset($_POST['tot'])) {
    echo "Couldn't update answers. Try again.";
}

$rows   = array_map('str_getcsv', file('../tot.csv'));
$header = array_shift($rows);
$questions    = array();
foreach ($rows as $row) {
    $questions[] = array_combine($header, $row);
}

$answers = array();
foreach ($questions as $question) {
    $choice = $_POST['tot'];
    if (in_array($question['id'], $_POST['tot'])) {
        $answer = $question['that'];
    } else {
        $answer = $question['this'];
    }
    $answers += array($question['id'] => $answer);
}

$email = $_SESSION['email'];
$answersJson = json_encode($answers);
$answersJson = mysqli_real_escape_string($conn, $answersJson);
$sql = mysqli_query($conn, "SELECT id FROM users WHERE email = '{$email}'");
if (mysqli_num_rows($sql) > 0) {
    $_SESSION['thisOrThatResponse'] = $answers;
    $_SESSION['thisOrThatQuestions'] = $questions;
    $_SESSION['thisOrThatUpdate'] = 1;
    $update_query = mysqli_query($conn, "UPDATE users SET thisOrThatUpdate = 1, thisOrThatResponse = '{$answersJson}' WHERE email = '{$email}'");
    if ($update_query) {
        echo "success";
    }
} else {
    echo "This email does not exist.";
}

// TODO validate entry in all scripts