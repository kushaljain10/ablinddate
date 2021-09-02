<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("location: index");
}
define('MyConst', TRUE);
include_once "header.php";
include_once "php/config.php";
?>
<script>
function showUnmatch(id, name) {
    document.getElementById("unmatchId").value = id;
    document.getElementById("unmatchName").innerHTML = name;
}

function unmatch() {
    let unmatchId = document.getElementById("unmatchId").value;
    let currId = document.getElementById("currId").value;
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/unmatch.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                location.reload();
            }
        }
    };
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("unmatchId=" + unmatchId + "&currId=" + currId);
}
</script>
<input type="hidden" id="currId" value="<?php echo $_SESSION['userid']; ?>" style="display: none;">
<div class="container">
    <section class="users">
        <div class="search">
            <span class="text">Click on a name to open the reponse</span>
            <input type="text" placeholder="Enter name to search...">
            <button><i class="fas fa-search"></i></button>
        </div>
        <div class="users-list">

        </div>
    </section>
</div>
<!-- Modal -->
<div class="modal fade" id="UnmatchModal" tabindex="-1" role="dialog" aria-labelledby="UnmatchModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="UnmatchModalLabel">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Once you unmatch with <span style="font-weight: bold" id="unmatchName"></span>, you cannot match with
                them
                again.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn gradient-button" onclick="unmatch()">Unmatch!</button>
            </div>
            <input type="hidden" id="unmatchId" value="" style="display: none">
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>
<script src="js/matches.js"></script>

</body>

</html>