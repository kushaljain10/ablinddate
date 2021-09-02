<?php
session_start();
include_once "config.php";
$userid = $_SESSION['userid'];
$query = mysqli_query($conn, "SELECT matches FROM users WHERE id = '{$userid}'");
$row = mysqli_fetch_assoc($query);
$matchesArray = json_decode($row['matches'], true);
$matchesList = implode("','", $matchesArray);
$query = mysqli_query($conn, "SELECT id, picture, name FROM users WHERE id IN ('$matchesList')");
$output = "";
if (mysqli_num_rows($query) == 0) {
    $output .= "<br><br><br><center class='font-weight-bold'>You don't have any matches yet.</center>";
} elseif (mysqli_num_rows($query) > 0) {
    include_once "matchesdata.php";
}
echo $output;

// TODO replace select * with specific queries everywhere