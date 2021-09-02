<?php
session_start();
$tokenError = '';

if (isset($_POST['password-token'])) {
    $passwordToken = $_POST['password-token'];
    $query = mysqli_query($conn, "SELECT id, email, passwordToken FROM users WHERE passwordToken = '{$passwordToken}'");
    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
        $_SESSION['userid'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        header('location: resetpassword');
    } else {
        $tokenError = "The token entered by you is invalid.";
    }
}

define('MyConst', TRUE);
include_once "header.php";
include_once "php/config.php";

?>

<br>
<br>
<div class="container">
    <form method="post" id="token-form">
        <input type="text" placeholder="Password Token" id="password-token" name="password-token" class="form-input" />
        <span class="error">
            <p id="token-error"><?php echo $tokenError; ?></p>
        </span>
        <blockquote class="bd-callout" style="width: 100%; word-wrap: break-word;">
            Enter the password token that you received during registration.
        </blockquote>
        <button class="button mt-3" type="button" id="verify-button" onclick="verifyToken()">Verify Token</button>
</div>
</form>
</div>
<script>
function verifyToken() {
    if (document.getElementById('password-token').value == '')
        document.getElementById('token-error').innerHTML = "Please enter a valid token.";
    else
        document.getElementById('token-form').submit();
}
</script>
</body>

</html>