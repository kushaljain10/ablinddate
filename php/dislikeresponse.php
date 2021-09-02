<?php
include_once "config.php";
session_start();

if (isset($_POST['currid']) && isset($_POST['dislikedid'])) {
    $currId = $_POST['currid'];
    $dislikedId = $_POST['dislikedid'];

    $sql = mysqli_query($conn, "SELECT dislikes FROM users WHERE id = '{$currId}'");
    if (mysqli_num_rows($sql) > 0) {
        $currUser = mysqli_fetch_assoc($sql);

        $dislikes = json_decode($currUser['dislikes'], true);

        array_push($dislikes, $dislikedId);

        $dislikes = json_encode($dislikes);
        $dislikes = mysqli_real_escape_string($conn, $dislikes);
        $insert_query = mysqli_query($conn, "UPDATE users SET dislikes = '{$dislikes}' WHERE id = '{$currId}'");
        if (!$insert_query) {
            echo "Error in updating dislike details.";
        }
    } else {
        echo "Error in fetching user.";
    }
} else {
    header('location: index');
}