<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("location: index");
}
define('MyConst', TRUE);
include_once "header.php";
include_once "php/config.php";
?>

<body>
    <div class="container">
        <section class="users">
            <div class="search">
                <span class="text">Select an user to start chat</span>
                <input type="text" placeholder="Enter name to search...">
                <button><i class="fas fa-search"></i></button>
            </div>
            <div class="users-list">

            </div>
        </section>
    </div>
    <?php include_once "footer.php"; ?>
    <script src="js/chats.js"></script>

</body>

</html>