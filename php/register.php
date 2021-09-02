<?php
session_start();
//TODO : password check for special characters
require_once "config.php";

if (array_key_exists('email', $_POST) && array_key_exists('password', $_POST)) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if (!empty($email) && !empty($password)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
            if (mysqli_num_rows($sql) > 0) {
                echo "This email already exists!";
            } else {
                $ran_id = rand(time(), 100000000);
                $encrypt_pass = md5($password);
                $token = sha1(mt_rand(1, 90000) . 'SALT');
                $emptyArray = json_encode(array());
                $insert_query = mysqli_query($conn, "INSERT INTO users (id, email, password, passwordToken, likes, likedby, matches, dislikes, filters) VALUES ('{$ran_id}', '{$email}', '{$encrypt_pass}', '{$token}', '{$emptyArray}', '{$emptyArray}', '{$emptyArray}', '{$emptyArray}', '{$emptyArray}')");
                if ($insert_query) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['email'] = "$email";
                    $_SESSION['passwordToken'] = "$token";
                    $_SESSION['userid'] = "$ran_id";
                    echo "success";
                } else {
                    echo "Something went wrong. Please try again!";
                }
            }
        } else {
            echo "This is not a valid email.";
        }
    } else {
        echo "All input fields are required!";
    }
} else {
    header('location: index');
}