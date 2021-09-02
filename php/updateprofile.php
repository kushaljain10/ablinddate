<?php
session_start();

include_once "config.php";
$name = mysqli_real_escape_string($conn, $_POST['name']);
$age = mysqli_real_escape_string($conn, $_POST['age']);
$gender = mysqli_real_escape_string($conn, $_POST['gender']);
if ($gender != "Male" && $gender != 'Female') {
    $otherGender = 'true';
} else {
    $otherGender = 'false';
}

function make_thumb_jpeg($src, $dest, $desired_width)
{

    $source_image = imagecreatefromjpeg($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);

    $desired_height = floor($height * ($desired_width / $width));

    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

    imagejpeg($virtual_image, $dest);
}

function make_thumb_png($src, $dest, $desired_width)
{

    $source_image = imagecreatefrompng($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);

    $desired_height = floor($height * ($desired_width / $width));

    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

    imagepng($virtual_image, $dest);
}

$city = mysqli_real_escape_string($conn, $_POST['city']);
if (!empty($name) && !empty($age) && !empty($gender) && !empty($city)) {
    $email = $_SESSION['email'];
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
    if (mysqli_num_rows($sql) > 0) {
        $img_name = $_FILES['profile-photo']['name'];
        $img_type = $_FILES['profile-photo']['type'];
        $tmp_name = $_FILES['profile-photo']['tmp_name'];

        $img_explode = explode('.', $img_name);
        $img_ext = end($img_explode);

        $extensions = ["jpeg", "png", "jpg"];
        if (in_array($img_ext, $extensions) === true) {
            $types = ["image/jpeg", "image/jpg", "image/png"];
            if (in_array($img_type, $types) === true) {
                $time = time();
                $new_img_name = $time . $img_name;
                $info = getimagesize($tmp_name);
                if ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg') {
                    make_thumb_jpeg($tmp_name, '../pictures/' . $new_img_name, 300);
                } else if ($info['mime'] == 'image/png') {
                    make_thumb_png($tmp_name, '../pictures/' . $new_img_name, 300);
                }
                $update_query = mysqli_query($conn, "UPDATE users SET name = '{$name}', gender = '{$gender}', otherGender = '{$otherGender}', age = {$age}, city = '{$city}', picture = '{$new_img_name}', profileUpdate = 1 WHERE email = '{$email}'");
                if ($update_query) {
                    $_SESSION['name'] = $name;
                    $_SESSION['age'] = $age;
                    $_SESSION['city'] = $city;
                    $_SESSION['gender'] = $gender;
                    $_SESSION['otherGender'] = $otherGender;
                    $_SESSION['profileUpdate'] = 1;
                    $sql2 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                    if (mysqli_num_rows($sql2) > 0) {
                        $result = mysqli_fetch_assoc($sql2);
                        if ($result['responseUpdate'] == 0) {
                            header('url: ../addresponse');
                        } else {
                            $_SESSION['responseUpdate'] = $result['responseUpdate'];
                        }
                        echo "success";
                    } else {
                        echo "Something went wrong. Please try again!";
                    }
                } else {
                    echo "Something went wrong. Please try again!";
                }
            } else {
                echo "Please upload an image file - jpeg, png, jpg";
            }
        } else {
            echo "Please upload an image file - jpeg, png, jpg";
        }
    } else {
        echo "This email does not exist.";
    }
} else {
    echo "All input fields are required!";
}