<?php
session_start();
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['userid']))
    header('location: index');

define('MyConst', TRUE);
$responsesPage = 1;
include_once "header.php";
include_once "php/config.php";

$userId = $_SESSION['userid'];
$currUserToT = $_SESSION['thisOrThatResponse'];

$rows   = array_map('str_getcsv', file('questions.csv'));
$questions    = array();
foreach ($rows as $row) {
    $questions[$row[0]] = $row[1];
}
unset($questions['id']);

$query = mysqli_query($conn, "SELECT id, likes, dislikes FROM users WHERE id = '{$userId}'");
if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    $viewedUsers = array_merge(json_decode($row['likes'], true), json_decode($row['dislikes'], true));
    $viewedUsersList = count($viewedUsers) != 0 ? implode("','", $viewedUsers) : "''";
} else {
    header('location: dashboard');
}


$sql = mysqli_query($conn, "SELECT id, age, gender, city, response, thisOrThatResponse, otherGender FROM users WHERE id NOT IN ('$viewedUsersList') AND NOT id = '{$userId}'");
$allUsers = array();
if (mysqli_num_rows($sql) > 0) {
    while ($row = mysqli_fetch_assoc($sql)) {
        if ($row['response'] != "" and $row['thisOrThatResponse'] != "") {
            $row['answers'] = json_decode($row['response'], true);
            array_push($allUsers, $row);
        }
    }
}
// Filters
$allCities = array_unique(array_column($allUsers, 'city'));
sort($allCities);

$sql = mysqli_query($conn, "SELECT filters from users WHERE id = '{$userId}'");
$row = mysqli_fetch_assoc($sql);
$dbFilters = json_decode($row['filters'], true);
$filterSet = count($dbFilters);

if (!isset($_POST['gender'])) {
    $genderFilter = isset($dbFilters['gender']) ? $dbFilters['gender'] : 'all';
    $minAge = isset($dbFilters['minAge']) ? $dbFilters['minAge'] : 16;
    $maxAge = isset($dbFilters['maxAge']) ? $dbFilters['maxAge'] : 50;
    $cityFilter = isset($dbFilters['city']) ? $dbFilters['city'] : 'all';
} else {
    $genderFilter = isset($_POST['gender']) ? $_POST['gender'] : 'all';
    $minAge = isset($_POST['minAge']) ? $_POST['minAge'] : 16;
    $maxAge = isset($_POST['maxAge']) ? $_POST['maxAge'] : 50;
    $cityFilter = isset($_POST['city']) ? $_POST['city'] : 'all';
}

$dbFilters['city'] = $cityFilter;
$dbFilters['gender'] = $genderFilter;
$dbFilters['minAge'] = $minAge;
$dbFilters['maxAge'] = $maxAge;

$dbFilters = json_encode($dbFilters);
$dbFilters = mysqli_real_escape_string($conn, $dbFilters);
$query = mysqli_query($conn, "UPDATE users SET filters = '{$dbFilters}' WHERE id = '{$userId}'");

// Filtering Responses
if ($cityFilter != 'all') {
    foreach ($allUsers as $key => $user) {
        if ($user['city'] != $cityFilter)
            unset($allUsers[$key]);
        if ($genderFilter != 'all') {
            if ($genderFilter == 'Other' && $user['otherGender'] == 'false')
                unset($allUsers[$key]);
            else if ($genderFilter != 'Other' && $user['gender'] != $genderFilter)
                unset($allUsers[$key]);
            if ($user['age'] < $minAge || $user['age'] > $maxAge)
                unset($allUsers[$key]);
        } else {
            if ($user['age'] < $minAge || $user['age'] > $maxAge)
                unset($allUsers[$key]);
        }
    }
} else {
    foreach ($allUsers as $key => $user) {
        if ($genderFilter != 'all') {
            if ($genderFilter == 'Other' && $user['otherGender'] == 'false')
                unset($allUsers[$key]);
            else if ($genderFilter != 'Other' && $user['gender'] != $genderFilter)
                unset($allUsers[$key]);
            if ($user['age'] < $minAge || $user['age'] > $maxAge)
                unset($allUsers[$key]);
        } else {
            if ($user['age'] < $minAge || $user['age'] > $maxAge)
                unset($allUsers[$key]);
        }
    }
}
$allUsers = array_values($allUsers);

// print_r($allAges);
?>
<link href="css/nouislider.css" rel="stylesheet">
<script>
var styleElem = document.head.appendChild(document.createElement("style"));
</script>
<input type="hidden" id="currid" value="<?php echo $userId; ?>" style="display: none"></input>
<div id="main-body" style="width: 100%;">
    <div class="container" id="inner-body">
        <div id="snackbar">Change filters below &darr;</div>
        <?php
        if (!empty($allUsers)) {
            $thisUserTot = json_decode($allUsers[0]['thisOrThatResponse'], true);

            $c = 0;
            foreach ($thisUserTot as $id => $ans) {
                if ($currUserToT[$id] == $ans)
                    $c++;
            }
        ?>
        <div id="carouselControls" class="carousel slide">
            <div class="carousel-inner">
                <div id="<?php echo $allUsers[0]['id']; ?>" class="carousel-item active">
                    <div id="info-button" class="mb-1" onclick="infoOverflow()"><span
                            id="age"><?php echo $allUsers[0]['age']; ?></span>, <span
                            id="gender"><?php echo $allUsers[0]['gender']; ?></span> | <span
                            id="city"><?php echo $allUsers[0]['city']; ?></span></div>
                    <div id="" class="text-center my-3">
                        <h5><?php echo "This or That Match: <strong>" . $c . "/10</strong>"; ?>
                        </h5>
                    </div>
                    <?php
                        foreach ($allUsers[0]['answers'] as $id => $answer) {
                        ?>
                    <blockquote class="blockquote mb-0">
                        <h5 class="gradient-text"><?php echo $questions[$id]; ?> </h5>
                        <p><?php echo $answer; ?></p>
                        <hr />
                    </blockquote>
                    <?php
                        }
                        ?>
                </div>
                <?php
                    for ($i = 1; $i < count($allUsers); $i++) {
                        $thisUserTot = json_decode($allUsers[$i]['thisOrThatResponse'], true);

                        $c = 0;
                        foreach ($thisUserTot as $id => $ans) {
                            if ($currUserToT[$id] == $ans)
                                $c++;
                        }
                    ?>
                <div id="<?php echo $allUsers[$i]['id']; ?>" class="carousel-item">
                    <div id="info-button" onclick="infoOverflow()"><span
                            id="age"><?php echo $allUsers[$i]['age']; ?></span>, <span
                            id="gender"><?php echo $allUsers[$i]['gender']; ?></span> | <span
                            id="city"><?php echo $allUsers[$i]['city']; ?></span></div>
                    <div id="" class="text-center my-3">
                        <h5><?php echo "This or That Match: <strong>" . $c . "/10</strong>"; ?>
                        </h5>
                    </div>
                    <?php
                            foreach ($allUsers[$i]['answers'] as $id => $answer) {
                            ?>
                    <blockquote class="blockquote mb-0">
                        <h5 class="gradient-text"><?php echo $questions[$id]; ?> </h5>
                        <p><?php echo $answer; ?></p>
                        <hr />
                    </blockquote>
                    <?php
                            }
                            ?>
                </div>
                <?php
                    }
                    ?>
            </div>
        </div>
    </div>
    <?php } else { ?>
    <div class="text-center" id="no-responses">
        <h3>No responses available.</h3>
        <p>You've viewed all the responses in your current filter settings.</p>
        <br>
        <p>Come back later and try again :)</p>
    </div>
    <?php } ?>
</div>
</div>

<div class="responses-button-bar fixed-bottom d-flex justify-content-around">
    <div onclick="disliked()" class="responses-button"><img class="responses-button-icon"
            src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnN2Z2pzPSJodHRwOi8vc3ZnanMuY29tL3N2Z2pzIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgeD0iMCIgeT0iMCIgdmlld0JveD0iMCAwIDM0OC4zMzMgMzQ4LjMzNCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTEyIDUxMiIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgY2xhc3M9IiI+PGc+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+Cgk8cGF0aCBkPSJNMzM2LjU1OSw2OC42MTFMMjMxLjAxNiwxNzQuMTY1bDEwNS41NDMsMTA1LjU0OWMxNS42OTksMTUuNzA1LDE1LjY5OSw0MS4xNDUsMCw1Ni44NSAgIGMtNy44NDQsNy44NDQtMTguMTI4LDExLjc2OS0yOC40MDcsMTEuNzY5Yy0xMC4yOTYsMC0yMC41ODEtMy45MTktMjguNDE5LTExLjc2OUwxNzQuMTY3LDIzMS4wMDNMNjguNjA5LDMzNi41NjMgICBjLTcuODQzLDcuODQ0LTE4LjEyOCwxMS43NjktMjguNDE2LDExLjc2OWMtMTAuMjg1LDAtMjAuNTYzLTMuOTE5LTI4LjQxMy0xMS43NjljLTE1LjY5OS0xNS42OTgtMTUuNjk5LTQxLjEzOSwwLTU2Ljg1ICAgbDEwNS41NC0xMDUuNTQ5TDExLjc3NCw2OC42MTFjLTE1LjY5OS0xNS42OTktMTUuNjk5LTQxLjE0NSwwLTU2Ljg0NGMxNS42OTYtMTUuNjg3LDQxLjEyNy0xNS42ODcsNTYuODI5LDBsMTA1LjU2MywxMDUuNTU0ICAgTDI3OS43MjEsMTEuNzY3YzE1LjcwNS0xNS42ODcsNDEuMTM5LTE1LjY4Nyw1Ni44MzIsMEMzNTIuMjU4LDI3LjQ2NiwzNTIuMjU4LDUyLjkxMiwzMzYuNTU5LDY4LjYxMXoiIGZpbGw9IiNmMjcxNzUiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIHN0eWxlPSIiIGNsYXNzPSIiPjwvcGF0aD4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8L2c+PC9zdmc+" />
    </div>
    <div onclick="liked()" class="text-center responses-button">
        <img class="responses-button-icon"
            src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnN2Z2pzPSJodHRwOi8vc3ZnanMuY29tL3N2Z2pzIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgeD0iMCIgeT0iMCIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMiA1MTIiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnPjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGc+PHBhdGggZD0ibTI0Ni4xMjIgNDc3LjI4OWMtMTQ0LjQxNy0xMjYuMzY3LTI0Ni4xMjItMTkzLjMwNC0yNDYuMTIyLTI5OS43NzQgMC04MC41MTMgNTcuNC0xNDYuNTE1IDEzNi0xNDYuNTE1IDU0LjU0NCAwIDk1LjAxNyAzMy40OTcgMTIwIDgxLjAxNSAyNC45ODEtNDcuNTE1IDY1LjQ1NC04MS4wMTUgMTIwLTgxLjAxNSA3OC42MDkgMCAxMzYgNjYuMDE1IDEzNiAxNDYuNTE1IDAgMTA2LjQ1Ny0xMDEuNTcyIDE3My4yOTEtMjQ2LjEyMiAyOTkuNzczLTUuNjU3IDQuOTQ5LTE0LjEgNC45NDktMTkuNzU2LjAwMXoiIGZpbGw9IiNlZjQyNmMiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIHN0eWxlPSIiPjwvcGF0aD48L2c+PC9nPjwvZz48L3N2Zz4=" />
    </div>
</div>
<div>
    <span id="filter-button" data-toggle="modal" data-target="#filtersModal"
        style="cursor: pointer"><a>Filters</a></span>
</div>

<div class="modal fade" id="filtersModal" tabindex="-1" role="dialog" aria-labelledby="filtersModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filtersModalTitle">Filter Responses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="d-flex flex-column align-items-center">
                        <div class="col-5 p-1">
                            <select class="form-control" id="genderSelect">
                                <option <?php if ($genderFilter == 'all') echo 'selected'; ?> value="all">Gender
                                </option>
                                <option <?php if ($genderFilter == "Female") echo 'selected'; ?> value="Female">
                                    Female
                                </option>
                                <option <?php if ($genderFilter == "Male") echo 'selected'; ?> value="Male">Male
                                </option>
                                <option <?php if ($genderFilter == 'Other') echo 'selected'; ?> value="Other">Other
                                </option>
                            </select>
                        </div>
                        <h6 class="mt-3 text-muted">Age:</h6>
                        <div id="slider" class="my-5" style="width: 80%;">
                        </div>
                        <div class="m-2">
                            <select class="form-control" id="citySelect">
                                <option <?php if ($cityFilter == 'all') echo 'selected'; ?> value="all" disabled hidden>
                                    City
                                </option>
                                <?php
                                foreach ($allCities as $city) {
                                ?>
                                <option <?php if ($cityFilter == $city) echo 'selected'; ?>
                                    value="<?php echo $city; ?>">
                                    <?php echo $city; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer" style="position: relative">
                <button type="button" onclick="filterUpdate()" class="btn gradient-button mx-auto">Save</button>
                <a onclick="clearFilters()"
                    style="position: absolute; border-bottom: #333 1px solid; cursor: pointer;">Clear Filters</a>
            </div>
            </form>
        </div>
    </div>
</div>

<form action="responses" method="POST" id="filterForm" style="display: none">
    <input type="hidden" id="genderFilter" name="gender" value="<?php echo $genderFilter; ?>" />
    <input type="hidden" id="minAge" name="minAge" value="<?php echo $minAge; ?>" />
    <input type="hidden" id="maxAge" name="maxAge" value="<?php echo $maxAge; ?>" />
    <input type="hidden" id="cityFilter" name="city" value="<?php echo $cityFilter; ?>" />
</form>
<?php // echo "<input type='hidden' id='allMinAge' value='".$allMinAge."'><input type='hidden' id='allMaxAge' value='".$allMaxAge."'>";
?>

<script src="js/nouislider.js"></script>
<script src="js/wnumb.js"></script>
<?php
if (!$filterSet) {
?>
<script>
var x = document.getElementById("snackbar");
x.className = "show";
setTimeout(function() {
    x.className = x.className.replace("show", "");
}, 2000);
</script>
<?php
}
?>
<script>
function responsePopup(x) {
    // Add the "show" class to DIV
    x.className = "show";

    // After 3 seconds, remove the show class from DIV
    setTimeout(function() {
        x.className = x.className.replace("show", "");
    }, 1000);
}

var slider = document.getElementById('slider');

noUiSlider.create(slider, {
    start: [document.getElementById('minAge').value, document.getElementById('maxAge').value],
    step: 1,
    connect: true,
    tooltips: true,
    range: {
        'min': 16,
        'max': 50
    },
    format: wNumb({
        decimals: 0
    })
});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>
<script>
function goToDashboard() {
    window.location.href = "dashboard"
}

function filterUpdate() {
    document.getElementById('genderFilter').value = document.getElementById('genderSelect').value;
    document.getElementById('minAge').value = document.getElementsByClassName('noUi-handle-lower')[0].getAttribute(
        'aria-valuetext');
    document.getElementById('maxAge').value = document.getElementsByClassName('noUi-handle-upper')[0].getAttribute(
        'aria-valuetext');
    document.getElementById('cityFilter').value = document.getElementById('citySelect').value;
    document.getElementById('filterForm').submit();
}

function clearFilters() {
    document.getElementById('genderFilter').value = 'all';
    document.getElementById('minAge').value = 16;
    document.getElementById('maxAge').value = 50;
    document.getElementById('cityFilter').value = 'all';
    document.getElementById('filterForm').submit();
}

function infoOverflow() {
    if (document.getElementById('info-button').style.textOverflow == 'ellipsis') {
        document.getElementById('info-button').style.textOverflow = 'initial';
        document.getElementById('info-button').style.overflow = 'initial';
        document.getElementById('info-button').style.whiteSpace = 'initial';
    } else {
        document.getElementById('info-button').style.textOverflow = 'ellipsis';
        document.getElementById('info-button').style.overflow = 'hidden';
        document.getElementById('info-button').style.whiteSpace = 'nowrap';
    }
}

function filterToggle() {
    nav = document.getElementById('filters');
    if (nav.style.display == 'flex')
        nav.style.display = 'none';
    else
        nav.style.display = 'flex';
}

//============================================================
//Next Response Function
function nextResponse() {
    ResponseItem = document.getElementsByClassName('carousel-item');
    if (ResponseItem.length == 0)
        return 1;
    if (ResponseItem[1] == null) {
        document.getElementsByClassName('carousel-inner')[0].innerHTML +=
            '<div class="carousel-item" id="no-responses"><div class="text-center" id="no-responses"><h3>No responses available.</h3><p>You\'ve viewed all the responses in your current filter settings.</p><br><p>Come back later and try again :)</p></div></div>';
        $('#carouselControls').carousel('next');
        $('#info-button').fadeTo(300, 0);
        $('#no-responses').removeClass('carousel-item');
        $('#no-responses').removeClass('active');
    } else {
        $('#carouselControls').carousel('next');
        setTimeout(function() {
            ResponseItem[0].remove();
        }, 500);
    }
}
//====================================================================
//Liked Function
function liked() {
    currentid = document.getElementById('currid').value;
    likedResponse = document.getElementsByClassName('active');
    if (likedResponse.length == 0)
        return 1;
    likepopup = document.getElementById('snackbar');
    likepopup.innerHTML = "Response Liked!"
    responsePopup(likepopup);
    $.ajax({
        type: "POST",
        url: 'php/likeresponse.php',
        data: {
            currid: currentid,
            likedid: likedResponse[0].id
        },
        success: function(response) {
            // console.log(response);
        }
    });
    nextResponse();
}
//====================================================================
//Disliked Function
function disliked() {
    currentid = document.getElementById('currid').value;
    dislikedResponse = document.getElementsByClassName('active');
    if (dislikedResponse.length == 0)
        return;
    dislikepopup = document.getElementById('snackbar');
    dislikepopup.innerHTML = "Response Disliked!"
    responsePopup(dislikepopup);
    $.ajax({
        type: "POST",
        url: 'php/dislikeresponse.php',
        data: {
            currid: currentid,
            dislikedid: dislikedResponse[0].id
        },
        success: function(response) {
            // console.log(response);
        }
    });
    nextResponse();
}
//====================================================================
</script>
</body>

</html>