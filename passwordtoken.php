<?php
session_start();
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['passwordToken']))
    header("location: index");
define('MyConst', TRUE);
include_once("header.php");
?>
<div class="container">
    <div class="alert alert-info mt-5 pb-0" style="width: 100%; word-wrap: break-word;" role="alert">
        <b>Password Token:</b> <input spellcheck="false" type="text"
            style=" width: 100%; border: none;background: rgba(0,0,0,0);" id="passwordtoken"
            value="<?php if (isset($_SESSION['passwordToken'])) echo $_SESSION['passwordToken']; ?>" />
        <p class="text-right" onclick="copyToken()" style="cursor: pointer;">Click here to copy</p>
    </div>
    <blockquote class="bd-callout" style="width: 100%; word-wrap: break-word;">
        Please keep this code safely. It will be used if you want to reset your password in the future.
    </blockquote>
    <button class="button mt-3" type="button" onclick="proceed()" id="proceed-button">Proceed to A blind
        Date</button>
</div>
<script>
function copyToken() {
    var copyText = document.getElementById("passwordtoken");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand('copy');
}

function proceed() {
    window.location.href = "profile";
}
</script>
</body>

</html>