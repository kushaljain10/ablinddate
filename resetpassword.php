<?php
session_start();

if (!isset($_SESSION['userid']) || !isset($_SESSION['email']))
    header('location: index');

define('MyConst', TRUE);
include_once "header.php";
include_once "php/config.php";

$userId = $_SESSION['userid'];
$email = $_SESSION['email'];

if (isset($_POST['password'])) {
    $password = $_POST['password'];
    $query = mysqli_query($conn, "SELECT id, email FROM users WHERE id = '{$userId}'");
    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
    }
    $password = md5($password);
    $query = mysqli_query($conn, "UPDATE users SET password = '{$password}' WHERE id = '{$userId}'");

    header('location: logout');
}

?>
<div class="container">
    <br>
    <br>
    <form method="post" id="password-form">
        <div class="form__group">
            <input type="password" placeholder="Enter Password" id="password" name="password" class="form-input" />
            <span class="error">
                <p id="password-error"></p>
            </span>
        </div>
        <div class="form__group">
            <input type="password" placeholder="Confirm Password" id="password2" class="form-input" />
            <span class="error">
                <p id="password2-error"></p>
            </span>
        </div>

        <button class="button mt-3" type="button" onclick="checkPassword()" id="reset-password-button">Reset
            Password</button>
</div>
</form>
<script>
function checkPassword() {
    error = 0;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("password2").value;
    if (
        password == "" ||
        password.length < 6 ||
        password.length > 32
    ) {
        error = 1;
        document.getElementById(
            "password-error"
        ).innerHTML = "Enter a valid password (6-32 characters)";
    } else if (password != confirmPassword) {
        error = 1;
        document.getElementById("password2-error").innerHTML =
            "The passwords do not match!";
    }
    if (error == 0) {
        document.getElementById('password-form').submit();
    }
} {
    var input = document.getElementById("password2");
    input.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("reset-password-button").click();
        }
    });
}
</script>
</body>

</html>