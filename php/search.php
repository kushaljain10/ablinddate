<?php
session_start();
include_once "config.php";

$userid = $_SESSION['userid'];
$searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);

$sql = "SELECT matches FROM users WHERE id = '{$userid}'";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($query);
$matchesArray = json_decode($row['matches'], true);
$matchesList = implode("','", $matchesArray);

$sql = "SELECT * FROM users WHERE NOT id = '{$userid}' AND id IN ('$matchesList') AND (name LIKE '%{$searchTerm}%') ";
$output = "";
$query = mysqli_query($conn, $sql);
if (mysqli_num_rows($query) > 0) {
    include_once "data.php";
} else {
    $output .= 'No user found related to your search term';
}
echo $output;