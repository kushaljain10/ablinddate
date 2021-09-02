<?php
session_start();
include_once "config.php";
include_once "sendemail.php";

if (isset($_POST['currid']) && isset($_POST['likedid'])) {
    $currId = $_POST['currid'];
    $likedId = $_POST['likedid'];

    $sql = mysqli_query($conn, "SELECT likes, matches FROM users WHERE id = '{$currId}'");
    if (mysqli_num_rows($sql) > 0) {
        $currUser = mysqli_fetch_assoc($sql);
        $sql = mysqli_query($conn, "SELECT name, likes, matches, likedby, email FROM users WHERE id = '{$likedId}'");
        if (mysqli_num_rows($sql) > 0) {
            $likedUser = mysqli_fetch_assoc($sql);

            $currUserLikes = json_decode($currUser['likes'], true);
            $currUserMatches = json_decode($currUser['matches'], true);

            $likedUserLikes = json_decode($likedUser['likes'], true);
            $likedUserMatches = json_decode($likedUser['matches'], true);
            $likedUserLikedBy = json_decode($likedUser['likedby'], true);

            array_push($currUserLikes, $likedId);
            array_push($likedUserLikedBy, $currId);

            if (in_array($currId, $likedUserLikes)) {
                array_push($currUserMatches, $likedId);
                array_push($likedUserMatches, $currId);
                $to = 'jain.kushal10@gmail.com';

                $subject = "You've got a match!";

                $message = "
                <html>
                <head>
                    <title>Title</title>
                </head>
                <body>
                    <h1>Woohoo!</h1>
                    <h2>Hey " . $likedUser['name'] . ", you've got a match!
                    <br>You've matched with " . $currUser['name'] . " &#10084;&#65039;
                    <br>Head over to <a href='ablinddate.online/matches'>A Blind Date</a> to start talking! :D</h2>
                    <br>
                    <h3>Regards,<br>
                    A Blind Date</h3>
                    <img style='width: 150px;' src='https://i.ibb.co/nLr9mh9/ABD-Logo.jpg' alt='ABD-Logo' border='0'>
                </body>
                </html>
                ";

                sendmail($likedUser['email'], $subject, $message);
            }

            $currUserLikes = json_encode($currUserLikes);
            $currUserLikes = mysqli_real_escape_string($conn, $currUserLikes);
            $currUserMatches = json_encode($currUserMatches);
            $currUserMatches = mysqli_real_escape_string($conn, $currUserMatches);
            $insert_query = mysqli_query($conn, "UPDATE users SET likes = '{$currUserLikes}', matches = '{$currUserMatches}' WHERE id = '{$currId}'");
            if (!$insert_query) {
                echo "Error in updating like details.";
            }

            $likedUserLikes = json_encode($likedUserLikes);
            $likedUserLikes = mysqli_real_escape_string($conn, $likedUserLikes);
            $likedUserLikedBy = json_encode($likedUserLikedBy);
            $likedUserLikedBy = mysqli_real_escape_string($conn, $likedUserLikedBy);
            $likedUserMatches = json_encode($likedUserMatches);
            $likedUserMatches = mysqli_real_escape_string($conn, $likedUserMatches);
            $insert_query = mysqli_query($conn, "UPDATE users SET likes = '{$likedUserLikes}', matches = '{$likedUserMatches}', likedby = '{$likedUserLikedBy}' WHERE id = '{$likedId}'");
            if (!$insert_query) {
                echo "Error in updating like details.";
            }
        } else {
            echo "Error in fetching liked user.";
        }
    } else {
        echo "Error in fetching user.";
    }
} else {
    header('location: index');
}