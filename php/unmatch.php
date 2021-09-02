<?php
include_once "config.php";
session_start();

if (isset($_POST['unmatchId']) && isset($_POST['currId'])) {
    $currId = $_POST['currId'];
    $unmatchId = $_POST['unmatchId'];

    $sql = mysqli_query($conn, "SELECT matches FROM users WHERE id = '{$currId}'");
    $sql2 = mysqli_query($conn, "SELECT matches FROM users WHERE id = '{$unmatchId}'");
    if (mysqli_num_rows($sql) > 0 && mysqli_num_rows($sql2) > 0) {
        $currUser = mysqli_fetch_assoc($sql);
        $unmatchedUser = mysqli_fetch_assoc($sql2);

        $matches = json_decode($currUser['matches'], true);
        $matches2 = json_decode($unmatchedUser['matches'], true);

        $delKey = array_search($unmatchId, $matches);
        unset($matches[$delKey]);

        $delKey = array_search($currId, $matches2);
        unset($matches2[$delKey]);

        $matches = json_encode($matches);
        $matches2 = json_encode($matches2);
        $matches = mysqli_real_escape_string($conn, $matches);
        $matches2 = mysqli_real_escape_string($conn, $matches2);

        $insert_query = mysqli_query($conn, "UPDATE users SET matches = '{$matches}' WHERE id = '{$currId}'");
        $insert_query2 = mysqli_query($conn, "UPDATE users SET matches = '{$matches2}' WHERE id = '{$unmatchId}'");
        if (!$insert_query || !$insert_query2) {
            echo "Error in updating unmatch details.";
        }
    } else {
        echo "Error in fetching user.";
    }
} else {
    header('location: index');
}