<?php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['loggedin'])) {
    header("location: index");
}
if (!isset($_SESSION['profileUpdate'])) {
    header("location: profile");
}
if (!isset($_SESSION['responseUpdate'])) {
    header("location: addresponse");
}
if (!isset($_SESSION['thisOrThatUpdate'])) {
    header("location: thisorthat");
}

$userId = $_SESSION['userid'];
$sql = mysqli_query($conn, "SELECT matches FROM users WHERE id = '{$userId}'");
if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);
    $matches = json_decode($row['matches'], true);
}

define('MyConst', TRUE);
include_once "header.php";

?>
<div class="container dashboard">
    <div>
        <h3 class="gradient-text text-center pt-4" style="white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;">
            Hello,
            <?php echo $_SESSION['name'] ?></h3>
    </div>
    <div class="d-flex justify-content-around mt-5">
        <button class="pushable">
            <a href="responses">
                <span class="front d-flex flex-column justify-content-between">
                    <i class="m-3 fa fa-2x fa-search"></i>
                    <h6 class="">View Responses</h6>
                </span>
            </a>
        </button>
        <button class="pushable">
            <a href="matches">
                <span class="front d-flex flex-column justify-content-between">
                    <i class="m-3 fa fa-2x fa-heart"></i>
                    <h6 class="">Matches <?php if (count($matches) > 0) { ?><span
                            id="matchesCount"><?php echo count($matches); ?></span><?php } ?></h6>
                </span>
            </a>
        </button>
    </div>
    <div class="d-flex justify-content-around mt-5">
        <button class="pushable">
            <a href="chats">
                <span class="front d-flex flex-column justify-content-between">
                    <i class="m-3 fa fa-2x fa-comments"></i>
                    <h6 class="">Chats <?php if (count($matches) > 0) { ?><span
                            id="matchesCount"><?php echo count($matches); ?></span><?php } ?> </h6>
                </span>
            </a>
        </button>
        <button class="pushable">
            <a href="addresponse">
                <span class="front d-flex flex-column justify-content-between">
                    <i class="m-3 fa fa-2x fa-user"></i>
                    <h6 class="">Update Response</h6>
                </span>
            </a>
        </button>
    </div>
    <br>
    <br>
    <br>
    <div class="text-center">
        <a href="logout">
            <h4>Log Out</h4>
        </a>
    </div>
</div>
</body>

</html>