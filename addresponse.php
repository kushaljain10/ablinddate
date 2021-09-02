<?php
session_start();

if (!isset($_SESSION['loggedin']))
    header("location: index");

if (!isset($_SESSION['profileUpdate']))
    header("location: profile");

if (!isset($_SESSION['thisOrThatUpdate']))
    header("location: thisorthat");

if (isset($_SESSION['responseUpdate'])) {
    $prevPage = "dashboard";
}

define('MyConst', TRUE);
include_once "header.php";
include_once "php/config.php";

$rows   = array_map('str_getcsv', file('questions.csv'));
$questions    = array();
foreach ($rows as $row) {
    $questions[$row[0]] = $row[1];
}
unset($questions['id']);

$sql = mysqli_query($conn, "SELECT response FROM users WHERE email = '" . $_SESSION['email'] . "'");
if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);
    if ($row['response'] != "") {
        $answers = json_decode($row['response'], true);
    }
}
?>

<div class="container addresponse">
    <div class="text-center mt-4 mb-4">
        <h3 class="gradient-text font-weight-bold">Your Response</h3>
        <p>Answer at least 7 questions.<br>Choose those which tell the most about you.</p>
    </div>
    <form action="">
        <div class="accordion" id="questions">
            <?php
            foreach ($questions as $id => $question) {
            ?>
            <div class="card">
                <div class="card-header pl-3 pr-5" id="question1" data-toggle="collapse"
                    data-target="#col<?php echo $id; ?>"
                    <?php if (isset($answers[$id])) echo "style='border-bottom-color: #0288D1'"; ?>>
                    <h6 class="mb-0 gradient-text" <?php if (isset($answers[$id])) echo "style='color: #0288D1'"; ?>>
                        <?php echo $question; ?>
                    </h6>
                    <i class="fas fa-angle-down" id="angle"></i>
                </div>

                <div id="col<?php echo $id; ?>" class="<?php if (!isset($answers[$id])) echo "collapse"; ?> show"
                    aria-labelledby="question1" data-parent="#questions">
                    <div class="card-body">
                        <textarea <?php if (isset($answers[$id])) echo "style='border-color: #0288D1'"; ?>
                            onkeyup="changeColor(this)" maxlength="255" class="form-control" name="<?php echo $id; ?>"
                            id="<?php echo $id; ?>" cols="30"
                            rows="5"><?php if (isset($answers[$id])) {
                                                                                                                                                                                                                                                            echo $answers[$id];
                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                        ?></textarea>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <span class="error">
            <p id="response-error"></p>
        </span>
        <div class="text-center pb-5" id="submit-div">
            <button type="submit" class="button" id="submit-button">Submit</button>
        </div>
    </form>
</div>
</body>
<?php include_once "footer.php"; ?>
<script src="js/updateresponse.js"></script>
<script>
function changeColor(e) {
    mainDiv = ((e.parentElement).parentElement).previousElementSibling;
    if (e.value.length > 0) {
        mainDiv.style.borderBottomColor = "#0288D1";
        mainDiv.children[0].style.color = "#0288D1";
        e.style.borderColor = "#0288D1"
    } else {
        mainDiv.style.borderBottomColor = "#ef426c";
        mainDiv.children[0].style.color = "#ef426c";
        e.style.borderColor = "#EF426C";
    }
    console.log();
}
</script>

</html>