<?php
session_start();
if (!isset($_SESSION['loggedin']))
    header("location: index");

if (!isset($_GET['user_id']))
    header("location: index");

define('MyConst', TRUE);
include_once "header.php";
include_once "php/config.php";

$responseId = $_GET['user_id'];
$userId = $_SESSION['userid'];

$query = mysqli_query($conn, "SELECT matches FROM users WHERE id = '{$userId}'");
$row = mysqli_fetch_assoc($query);
$matches = json_decode($row['matches'], true);
if (!in_array($responseId, $matches))
    header("location: index");


$rows   = array_map('str_getcsv', file('questions.csv'));
$questions    = array();
foreach ($rows as $row) {
    $questions[$row[0]] = $row[1];
}
unset($questions['id']);

$rows   = array_map('str_getcsv', file('tot.csv'));
$header = array_shift($rows);
$tot    = array();
foreach ($rows as $row) {
    $tot[] = array_combine($header, $row);
}

$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '{$responseId}'");
if (mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_assoc($query);
}

?>
<div class="response-header" style="width: 100%;">
    <div class="d-flex align-items-center px-3 pt-2">
        <img class="response-image" src="pictures/<?php echo $user['picture']; ?>" alt="">
        <div class="">
            <h3 class="ml-3 font-weight-bolder m-0"><?php echo $user['name']; ?></h3>
            <h5 class="ml-3">
                <?php echo $user['age'] . ', ' . $user['gender'] . ' | ' . $user['city']; ?>
                </h3>
        </div>
        <hr class="response-hr">
    </div>
    <div>
        <button class="tablink" onclick="openPage('response', this, '#ef426c')" id="defaultOpen">Response</button>
        <button class="tablink" onclick="openPage('tot', this, '#ef426c')">This or That</button>
    </div>
</div>
<div id="response" class="tabcontent">
    <?php
    $answers = json_decode($user['response'], true);
    foreach ($answers as $id => $answer) {
    ?>
    <blockquote class="blockquote mb-0">
        <h5 class="gradient-text"><?php echo $questions[$id]; ?> </h5>
        <p><?php echo $answer; ?></p>
        <hr />
    </blockquote>
    <?php
    }
    ?>
</div>
<div id="tot" class="tabcontent">
    <?php
    $totAnswers = json_decode($user['thisOrThatResponse'], true);
    foreach ($tot as $question) {
    ?>
    <blockquote class="blockquote mb-0">
        <h5 class="gradient-text"><?php echo $question['question']; ?> </h5>
        <p><?php echo $totAnswers[$question['id']]; ?></p>
        <hr />
    </blockquote>
    <?php
    }
    ?>
</div>
<script>
function openPage(pageName, elmnt, color) {
    // Hide all elements with class="tabcontent" by default */
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Remove the background color of all tablinks/buttons
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].style.backgroundColor = "";
        tablinks[i].style.color = "#000";
    }

    // Show the specific tab content
    document.getElementById(pageName).style.display = "block";

    // Add the specific color to the button used to open the tab content
    elmnt.style.backgroundColor = color;
    elmnt.style.color = '#fff';
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>