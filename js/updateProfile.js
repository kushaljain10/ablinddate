function validateName() {
  input = document.getElementById("name").value.trim();
  if (!input.match(/^[a-zA-Z ]+$/) || input.length > 40) {
    document.getElementById("name-error").innerHTML =
      "Please enter a valid name.";
  } else {
    document.getElementById("name-error").innerHTML = "";
  }
}

function validateAge() {
  input = document.getElementById("age").value;
  if (input < 16 || input > 50) {
    document.getElementById("age-error").innerHTML = "Invalid age.";
  } else {
    document.getElementById("age-error").innerHTML = "";
  }
}
$("form").submit(function (event) {
  event.preventDefault();
  profilePhoto = document.getElementById("profile-photo");
  if (profilePhoto.value == "") {
    document.getElementById("name-error").innerHTML =
      "&#x2190; Please upload an image.";
  } else {
    document.getElementById("name-error").innerHTML = "";
  }
  userGender = document.getElementById("gender-select").value;
  if (userGender == "Other") {
    userGender = document.getElementById("gender-other").value;
  }
  document.getElementById("gender").value = userGender;
  if (userGender == "") {
    document.getElementById("gender-error").innerHTML =
      "Please enter a gender.";
  } else {
    document.getElementById("gender-error").innerHTML = "";
  }

  userName = document.getElementById("name").value;
  userAge = document.getElementById("age").value;
  userCity = document.getElementById("city").value;
  if (userCity == "") {
    document.getElementById("city-error").innerHTML = "Please choose a city.";
  } else {
    document.getElementById("city-error").innerHTML = "";
  }

  if (
    document.getElementById("name-error").innerHTML == "" &&
    document.getElementById("age-error").innerHTML == "" &&
    document.getElementById("gender-error").innerHTML == "" &&
    document.getElementById("city-error").innerHTML == ""
  ) {
    const form = document.querySelector("form");
    errorText = form.querySelector("#submit-error");

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/updateprofile.php", true);
    xhr.onload = () => {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          let data = xhr.response;
          if (data === "success") {
            location.href = "dashboard";
          } else {
            errorText.innerHTML = data;
          }
        }
      }
    };
    let formData = new FormData(form);
    xhr.send(formData);
  }
});
