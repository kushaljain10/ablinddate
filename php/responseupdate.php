<?php
session_start();
include_once "config.php";

$rows   = array_map('str_getcsv', file('../questions.csv'));
$questions    = array();
foreach ($rows as $row) {
    $questions[$row[0]] = $row[1];
}
unset($questions['id']);
$answers = array();
foreach ($questions as $id => $question) {
    $answers += array($id => $_POST[$id]);
}
$numOfAnswers = 0;
foreach ($answers as $key => $answer) {
    if (empty($answer)) {
        unset($answers[$key]);
    }
}
if (count($answers) >= 7) {
    $email = $_SESSION['email'];
    $answersJson = json_encode($answers);
    $answersJson = mysqli_real_escape_string($conn, $answersJson);
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
    if (mysqli_num_rows($sql) > 0) {
        $_SESSION['response'] = $answers;
        $_SESSION['responseQuestions'] = $questions;
        $_SESSION['responseUpdate'] = 1;
        $update_query = mysqli_query($conn, "UPDATE users SET responseUpdate = 1, response = '{$answersJson}' WHERE email = '{$email}'");
        if ($update_query) {
            echo "success";
        }
    } else {
        echo "This email does not exist.";
    }
} else {
    echo "Answer at least 7 questions!";
}