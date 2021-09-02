<?php
session_start();

include_once "config.php";
$userid = $_SESSION['userid'];
$sql = "SELECT matches FROM users WHERE id = '{$userid}'";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($query);
$matchesArray = json_decode($row['matches'], true);
$matchesList = implode("','", $matchesArray);
$query = mysqli_query($conn, "SELECT * FROM users WHERE id IN ('$matchesList')");
$output = "";
if (mysqli_num_rows($query) == 0) {
    $output .= "<br><br><br><center class='font-weight-bold'>No users are available for chat.</center>";
} elseif (mysqli_num_rows($query) > 0) {
    include_once "data.php";
}
echo $output;

// TODO replace select * with specific queries everywhere
// TODO show chats in recent order