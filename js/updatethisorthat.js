$("form").submit(function (event) {
  event.preventDefault();
  document.getElementById("response-error").innerHTML = "";

  const form = document.querySelector("form");
  errorText = form.querySelector("#response-error");
  submitDiv = form.querySelector("#submit-div");

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/thisorthatupdate.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        if (data === "success") {
          location.href = "dashboard";
        } else {
          errorText.innerHTML = "<h6 class='text-center'>" + data + "</h6>";
        }
      }
    }
  };
  let formData = new FormData(form);
  xhr.send(formData);
});
