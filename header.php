<?php
if (!defined('MyConst')) {
    die('Direct access not permitted');
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>A Blind Date</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="img/favicon-32x32.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header class="text-center pt-3 pb-4 sticky-top">
        <?php
        if (isset($_SESSION['responseUpdate']) && isset($_SESSION['thisOrThatUpdate'])) {
        ?>
        <a <?php if (isset($responsesPage)) echo "href='dashboard'";
                else echo "onclick='window.history.back()'" ?> class="back-icon-header"><i
                class="fas fa-arrow-left"></i></a>
        <?php } ?>
        <a href="dashboard"><img class="header-logo" src="img/logo.png" /></a>
    </header>