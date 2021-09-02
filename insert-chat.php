<?php
session_start();
if (isset($_SESSION['userid'])) {
    include_once "config.php";
    $outgoing_id = $_SESSION['userid'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    if (!empty($message)) {
        $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES ('{$incoming_id}', '{$outgoing_id}', '{$message}')");
    }
} else {
    header("url: ../index");
    // TODO: check if no url value leads to index or not
}