const loginHtml =
  '<div class="form-group"><input type="email" placeholder="Email" id="login-email" class="form-input" /><span class="error"><p id="login-email-error"></p></span></div><div class="form-group"><input type="password" placeholder="Password" id="login-password" class="form-input" /><i class="fas fa-eye" id="eye1"></i><span class="error"><p id="login-password-error"></p></span></div><button onclick="login()" class="button mt-3" type="button" id="login-button">Log in</button><div class="text-center mt-3"><h6 onclick="forgotPassword()" id="forgotpassword" class="gradient-text" style="cursor: pointer">Forgot password?</h6></div><div class="text-center mt-3"><h6 onclick="switchToRegister()" id="not-registered" class="gradient-text" style="cursor: pointer">Not registered yet? Click here!</h6>';

function switchToLogin() {
  document.getElementById("formdiv").innerHTML = loginHtml;

  var input = document.getElementById("login-password");
  input.addEventListener("keyup", function (event) {
    if (event.keyCode === 13) {
      event.preventDefault();
      document.getElementsByClassName("button")[0].click();
    }
  });

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

function registerCheck() {
  var error = 0;
  var email = document.getElementById("register-email").value;
  var password = document.getElementById("register-password").value;
  var confirmPassword = document.getElementById("register-password2").value;
  document.getElementById("register-password-error").innerHTML =
    document.getElementById("register-password2-error").innerHTML =
    document.getElementById("register-email-error").innerHTML =
      "";
  if (
    !email.match(
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    )
  ) {
    document.getElementById("register-email-error").innerHTML =
      "Please enter a valid email.";
    error = 1;
  }
  if (password.length < 6 || password.length > 32) {
    error = 1;
    document.getElementById("register-password-error").innerHTML =
      "Enter a valid password (6-32 characters long)";
  } else if (password != confirmPassword) {
    error = 1;
    document.getElementById("register-password2-error").innerHTML =
      "The passwords do not match!";
  }
  return error;
}

function register() {
  var email = document.getElementById("register-email").value;
  var password = document.getElementById("register-password").value;
  if (registerCheck() == 0)
    $.ajax({
      type: "POST",
      url: "php/register.php",
      data: {
        email: email,
        password: password,
      },
      success: function (response) {
        if (response == "success") {
          // alert(JSON.stringify(JSON.parse(response)));
          window.location.href = "passwordtoken";
        } else {
          document.getElementById("register-email-error").innerHTML = response;
        }
      },
    });
}
