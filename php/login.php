<?php
session_start();
include_once "config.php";
if (array_key_exists('email', $_POST) && array_key_exists('password', $_POST)) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if (!empty($email) && !empty($password)) {
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
        if (mysqli_num_rows($sql) > 0) {
            $row = mysqli_fetch_assoc($sql);
            $user_pass = md5($password);
            $enc_pass = $row['password'];
            if ($user_pass === $enc_pass) {
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = "$email";
                $_SESSION['userid'] = $row["id"];
                if ($row['profileUpdate'] == 1) {
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['age'] = $row['age'];
                    $_SESSION['city'] = $row['city'];
                    $_SESSION['gender'] = $row['gender'];
                    $_SESSION['otherGender'] = $row['otherGender'];
                    $_SESSION['picture'] = $row['picture'];
                    $_SESSION['profileUpdate'] = $row['profileUpdate'];
                }
                if ($row['responseUpdate'] == 1) {
                    $_SESSION['responseUpdate'] = $row['responseUpdate'];
                    $_SESSION['responseResponse'] = json_decode($row['responseResponse'], true);
                }
                if ($row['thisOrThatUpdate'] == 1) {
                    $_SESSION['thisOrThatUpdate'] = $row['thisOrThatUpdate'];
                    $_SESSION['thisOrThatResponse'] = json_decode($row['thisOrThatResponse'], true);
                }
                echo "success";
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "This email does not exist.";
        }
    } else {
        echo "All input fields are required!";
    }
} else {
    header('location: index');
}