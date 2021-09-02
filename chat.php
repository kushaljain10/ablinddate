<?php
session_start();
if (!isset($_SESSION['loggedin']))
    header("location: index");

if (!isset($_GET['user_id']))
    header("location: index");

include_once "php/config.php";
?>

<!DOCTYPE html>
<html>

<head>
    <title>A Blind Date</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
</head>

<div style=" margin: 0 auto;">
    <section class="chat-area">
        <header class="fixed-top">
            <?php

            $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);

            $userId = $_SESSION['userid'];
            $query = mysqli_query($conn, "SELECT matches FROM users WHERE id = '{$userId}'");
            $row = mysqli_fetch_assoc($query);
            $matches = json_decode($row['matches'], true);
            if (!in_array($user_id, $matches))
                header("location: index");

            $sql = mysqli_query($conn, "SELECT name, picture FROM users WHERE id = '{$user_id}'");
            if (mysqli_num_rows($sql) > 0) {
                $row = mysqli_fetch_assoc($sql);
            } else {
                header("location: chats");
            }
            ?>
            <a onclick="window.history.back()" class="back-icon"><i class="fas fa-arrow-left"></i></a>
            <img class="chat-image" src="pictures/<?php echo $row['picture']; ?>" alt="">
            <div class="details">
                <span><?php echo $row['name'] ?></span>
            </div>
        </header>
        <div class="d-flex flex-column align-self-stretched">
            <div class="chat-box">

            </div>
            <form action="#" class="typing-area fixed-bottom">
                <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
                <input type="text" name="message" class="input-field" placeholder="Type a message here..."
                    autocomplete="off">
                <button><i class="fab fa-telegram-plane"></i></button>
            </form>
        </div>
    </section>
</div>

<script src="js/chat.js"></script>

</body>

</html>