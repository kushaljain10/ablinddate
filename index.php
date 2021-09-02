<?php
session_start();
if (isset($_SESSION['loggedin']))
    header("location: dashboard");
define('MyConst', TRUE);
include_once "header.php";
?>

<div class="container">
    <form class="form" id="formdiv">
        <div class="form-group">
            <input type="email" placeholder="Email" id="login-email" class="form-input" /><span class="error">
                <p id="login-email-error"></p>
            </span>
        </div>
        <div class="form-group">
            <input type="password" placeholder="Password" id="login-password" class="form-input" />
            <i class="fas fa-eye" id="eye1"></i>
            <span class="error">
                <p id="login-password-error"></p>
            </span>
        </div>
        <button onclick="login()" class="button mt-3" type="button" id="login-button">Log in</button>
        <div class="text-center mt-3">
            <h6 onclick="forgotPassword()" id="forgotpassword" class="gradient-text" style="cursor: pointer">
                Forgot
                password?</h6>
        </div>
        <div class="text-center mt-3">
            <h6 onclick="switchToRegister()" id="not-registered" class="gradient-text" style="cursor: pointer">
                Not
                registered yet? Click here!</h6>
        </div>
    </form>
</div><br><br>
<div class="text-center mt-3">
    <h6 id="not-registered" style="cursor: pointer">
        Follow us on Instagram
        <div>
            <a href="https:www.instagram.com/a.blind.date">
                <img class="mt-2" src="img/instagram-black.png" height="50px;" alt="">
            </a>
        </div>
    </h6>
    <div>
        <a href="/privacy-policy" id="privacy-policy">Privacy Policy</a>
    </div>
</div>
<script src="script.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="js/login.js"></script>
<script src="js/register.js"></script>
<script>
var input = document.getElementById("login-password");
input.addEventListener("keyup", function(event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        document.getElementsByClassName("button")[0].click();
    }
});

function forgotPassword() {
    window.location.href = "forgotpassword";
}

{
    const pswrdField = document.querySelector("#login-password"),
        toggleIcon = document.querySelector("#eye1");

    toggleIcon.onclick = () => {
        if (pswrdField.type === "password") {
            pswrdField.type = "text";
            toggleIcon.classList.add("active");
        } else {
            pswrdField.type = "password";
            toggleIcon.classList.remove("active");
        }
    };
}
</script>
</body>

</html>