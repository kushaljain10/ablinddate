<?php
while ($row = mysqli_fetch_assoc($query)) {
    $output .= '<div class="content d-flex align-items-center" style="position: relative;">
                    <a href="response?user_id=' . $row['id'] . '"> 
                        <div class="mb-2">
                            <img src="pictures/' . $row['picture'] . '" alt="">
                        </div>
                        <div class="details ml-2 d-flex justify-content-between">
                            <h5 class="gradient-text font-weight-bolder">' . $row['name'] . '</h5>
                            </div>
                            </a><div onclick="showUnmatch(' . $row['id'] . ', \'' . $row['name'] . '\')" class="mb-4 unmatch" data-toggle="modal" data-target="#UnmatchModal"
                            style="cursor: pointer">unmatch</div></div>';
}