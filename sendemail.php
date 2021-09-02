<?php
session_start();
include_once "config.php";


function sendmail($to, $subject, $message)
{
    // To send HTML mail, the Content-type header must be set
    $headers = "From: A Blind Date <updates@ablinddate.online>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    mail($to, $subject, $message, $headers);
}