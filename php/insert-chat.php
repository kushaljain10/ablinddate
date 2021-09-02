<?php
session_start();
include_once "sendemail.php";
if (isset($_SESSION['userid'])) {
    include_once "config.php";
    $outgoing_id = $_SESSION['userid'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    if (!empty($message)) {
        $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES ('{$incoming_id}', '{$outgoing_id}', '{$message}')");

        $sql = mysqli_query($conn, "SELECT name FROM users WHERE id = '{$outgoing_id}'");
        if (mysqli_num_rows($sql) > 0) {
            $sender = mysqli_fetch_assoc($sql);
            $sql = mysqli_query($conn, "SELECT email FROM users WHERE id = '{$incoming_id}'");
            if (mysqli_num_rows($sql) > 0) {
                $receiver = mysqli_fetch_assoc($sql);
                $subject = "You've got a message!";

                $emailMessage = "
                <html>
                <head>
                    <title>Title</title>
                </head>
                <body>
                    <h2>Hey! You've got a new message from " . $sender['name'] . "!
                    <br>Head over to <a href='ablinddate.online'>A Blind Date</a> to reply! :D</h2>
                    <br>
                    <h3>Regards,<br>
                    <img style='width: 150px;' src='https://i.ibb.co/nLr9mh9/ABD-Logo.jpg' alt='ABD-Logo' border='0'>
                </body>
                </html>
                ";

                sendmail($receiver['email'], $subject, $emailMessage);
            }
        }
    }
} else {
    header("url: ../index");
    // TODO: check if no url value leads to index or not
}