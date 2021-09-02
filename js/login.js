const registerHtml =
  '<p style="font-size: 14px;" class="px-1 text-center text-muted font-italic">Notifications will be sent to your email.</p><div class="form-group"><input type="email" placeholder="Email" id="register-email" class="form-input" /><span class="error"><p id="register-email-error"></p></span></div><div class="form-group"><input required type="password" placeholder="Password (6-32 characters long)" id="register-password" class="form-input" /><i class="fas fa-eye" id="eye1"></i><span class="error"><p id="register-password-error"></p></span></div><div class="form-group"><input required type="password" placeholder="Re-enter Password" id="register-password2" class="form-input" /><i class="fas fa-eye" id="eye2"></i><span class="error"><p id="register-password2-error"></p></span></div><button class="button mt-3" onclick="register()" type="button" id="register-button" class="gradient-text">Register</button><div class="text-center mt-3"><h6 onclick="switchToLogin()" id="click-to-login" class="gradient-text" style="cursor: pointer">Already registered? Click here!</h6>';

function switchToRegister() {
  document.getElementById("formdiv").innerHTML = registerHtml;

  var input = document.getElementById("register-password2");
  input.addEventListener("keyup", function (event) {
    if (event.keyCode === 13) {
      event.preventDefault();
      document.getElementsByClassName("button")[0].click();
    }
  });

  const pswrdField1 = document.querySelector("#register-password"),
    toggleIcon1 = document.querySelector("#eye1");

  toggleIcon1.onclick = () => {
    if (pswrdField1.type === "password") {
      pswrdField1.type = "text";
      toggleIcon1.classList.add("active");
    } else {
      pswrdField1.type = "password";
      toggleIcon1.classList.remove("active");
    }
  };

  const pswrdField2 = document.querySelector("#register-password2"),
    toggleIcon2 = document.querySelector("#eye2");

  toggleIcon2.onclick = () => {
    if (pswrdField2.type === "password") {
      pswrdField2.type = "text";
      toggleIcon2.classList.add("active");
    } else {
      pswrdField2.type = "password";
      toggleIcon2.classList.remove("active");
    }
  };
}

function loginCheck() {
  var error = 0;
  var email = document.getElementById("login-email").value;
  var password = document.getElementById("login-password").value;
  document.getElementById("login-password-error").innerHTML =
    document.getElementById("login-email-error").innerHTML = "";
  if (
    !email.match(
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    )
  ) {
    document.getElementById("login-email-error").innerHTML =
      "Please enter a valid email.";
    error = 1;
  }
  if (password.length < 6 || password.length > 32) {
    document.getElementById("login-password-error").innerHTML =
      "Enter a valid password.";
    error = 1;
  }
  return error;
}

function login() {
  var email = document.getElementById("login-email").value;
  var password = document.getElementById("login-password").value;
  if (loginCheck() == 0)
    $.ajax({
      type: "POST",
      url: "php/login.php",
      data: {
        email: email,
        password: password,
      },
      success: function (response) {
        if (response == "success") {
          window.location.href = "dashboard";
        } else {
          if (response == "Incorrect password.")
            document.getElementById("login-password-error").innerHTML =
              response;
          else
            document.getElementById("login-email-error").innerHTML = response;
        }
      },
    });
}
