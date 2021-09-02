$(".collapse").collapse();

$("form").submit(function (event) {
  event.preventDefault();
  document.getElementById("response-error").innerHTML = "";
  answers = document.querySelectorAll("textarea");
  var numOfAnswers = 0;
  for (i = 0; i < answers.length; i++) {
    if (answers[i].value.length > 0) {
      numOfAnswers++;
    }
  }
  if (numOfAnswers < 7) {
    document.getElementById("response-error").innerHTML =
      "<h6 class='text-center'>Answer at least 7 questions!</h6>";
    return;
  }

  const form = document.querySelector("form");
  errorText = form.querySelector("#response-error");
  submitDiv = form.querySelector("#submit-div");

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/responseupdate.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        if (data === "success") {
          errorText.innerHTML =
            "<h6 class='text-center'>Response updated successfully!</h6>";
          submitDiv.innerHTML =
            "<a class='button' href='dashboard' style='color: #fff' >Go To Dashboard</a>";
          //   location.href = "dashboard";
        } else {
          errorText.innerHTML = "<h6 class='text-center'>" + data + "</h6>";
        }
      }
    }
  };
  let formData = new FormData(form);
  xhr.send(formData);
});
