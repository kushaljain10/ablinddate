<?php
session_start();

if (!isset($_SESSION['loggedin']))
    header("location: index");

if (!isset($_SESSION['profileUpdate']))
    header("location: profile");

define('MyConst', TRUE);
include_once "header.php";
include_once "php/config.php";

$rows   = array_map('str_getcsv', file('tot.csv'));
$header = array_shift($rows);
$tot    = array();
foreach ($rows as $row) {
    $tot[] = array_combine($header, $row);
}
?>
<script>
var styleElem = document.head.appendChild(document.createElement("style"));
</script>
<div class="container addresponse">
    <div class="text-center mt-4 mb-4">
        <h3 class="gradient-text font-weight-bold">This or That!</h3>
        <p>Choose one of the two options for each question given below. These answers will be used to give a hint about
            how much you match with the other person.</p>
    </div>
    <form action="">
        <?php
        foreach ($tot as $question) {
        ?>
        <div class="mb-4">
            <h5 class="text-center mt-4 font-weight-bolder py-3"><?php echo $question['question']; ?></h5>
            <div class="switches switch b2">
                <input type="checkbox" class="checkbox" id="<?php echo $question['id']; ?>"
                    value="<?php echo $question['id']; ?>" name="tot[]" />
                <div class="knobs d-flex" id="knob">
                    <span style="display: flex;"><?php echo $question['this']; ?></span>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
        <input type="checkbox" checked hidden name="tot[]" />
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
<script src="js/updatethisorthat.js"></script>
<?php
echo "<script>";
foreach ($tot as $question) {
    echo "
            styleElem.innerHTML += \"#" . $question['id'] . "+.knobs:after {content: '" . $question['that'] . "';}\";";
}
echo "</script>";
?>

</html>