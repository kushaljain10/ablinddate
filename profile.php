<?php
session_start();
if (!isset($_SESSION['loggedin']))
    header("location: index");

if (isset($_SESSION['profileUpdate'])) {
    $name = $_SESSION['name'];
    $age = $_SESSION['age'];
    $city = $_SESSION['city'];
    $gender = $_SESSION['gender'];
    $picture = $_SESSION['picture'];
} else {
    $name = "";
    $age = "";
    $city = "";
    $gender = "";
    $picture = "";
}
define('MyConst', TRUE);
include_once "header.php";
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<div class="container">

    <div class="pb-3">
        <div class="text-center mt-5 mb-4">
            <h3 class="gradient-text">Update Your Profile</h3>
            <p style="font-size: 14px;">Your picture will be shown to matches only.</p>
        </div>
        <form id="update-profile-form" method="post">
            <div class="row">
                <div class="col-3 form-group text-center">
                    <img src="<?php if ($picture != "") echo "pictures/" . $picture;
                                else echo "img/placeholder-image.png"; ?>" onclick="updatePhoto()" id="profile-display"
                        alt="">
                    <input type="file" name="profile-photo" accept="image/*" onchange="displayImage(this)"
                        id="profile-photo" style="display: none">
                </div>
                <div class="col-9 form-group">
                    <input type="text" required class="form-control mt-3" id="name" name="name" placeholder="Name"
                        onfocusout="validateName()" <?php if ($name != "") echo 'value="' . $name . '"'; ?>>
                    <span <?php if (isset($_SESSION['picture'])) echo 'style="display: none;"'; ?> class="error2">
                        <p id="name-error"></p>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-4" style="width: 25%">
                    <input type="number" max="50" min="16" required class="form-control" id="age" name="age"
                        placeholder="Age" onfocusout="validateAge()"
                        <?php if ($age != "") echo 'value="' . $age . '"'; ?>>
                    <span class="error2">
                        <p id="age-error"></p>
                    </span>
                </div>
                <div class="form-group col-8">
                    <select id="gender-select" name="gender-select" class="form-control"
                        onchange='checkGender(this.value);' required>
                        <option disabled selected hidden value="">Gender</option>
                        <option <?php if ($gender == "Female") echo 'selected'; ?> value="Female">Female</option>
                        <option <?php if ($gender == "Male") echo 'selected'; ?> value="Male">Male</option>
                        <option <?php if ($gender != "Male" && $gender != "Female" && $gender != "") echo 'selected'; ?>
                            value="Other">
                            Other</option>
                    </select>
                    <input class="form-control mt-2" placeholder="Gender" type="text" name="gender-other"
                        id="gender-other"
                        style='display:<?php if ($gender != "Male" && $gender != "Female" && $gender != "") echo "block;' value='" . $gender;
                                                                                                                                            else echo 'none'; ?>' />
                    <span class="error2">
                        <p id="gender-error"></p>
                    </span>
                    <input type="hidden" name="gender" id="gender" value="<?php echo $gender; ?>"
                        style="display: none"></span>
                </div>
            </div>

            <div class="form-group mb-5">
                <select id="city" name="city" class="form-control selectpicker" data-live-search="true" title="City"
                    required>
                    <option selected disabled hidden value="">Select City</option>
                    <?php
                    if (($handle = fopen("cities.csv", "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 100, ",")) !== FALSE) {
                            echo '<option';
                            if ($data[0] == $city) echo ' selected';
                            echo ' value ="' . $data[0] . '" data-tokens="' . $data[0] . '">' . $data[0] . '</option>';
                        }
                        fclose($handle);
                    }
                    ?>
                </select>
                <span class="error2">
                    <p id="city-error"></p>
                </span>
            </div>
            <div class="text-center">
                <button type="submit" class="button" id="submit-button">Submit</button>
            </div>
            <span class="error2">
                <p id="submit-error"></p>
            </span>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="js/profilephoto.js"></script>
<script src="js/updateProfile.js"></script>
<script>
$(function() {
    $('my-select').selectpicker();
});

function checkGender(val) {
    var element = document.getElementById('gender-other');
    if (val == 'Other')
        element.style.display = 'block';
    else
        element.style.display = 'none';
}
</script>
</body>

</html>