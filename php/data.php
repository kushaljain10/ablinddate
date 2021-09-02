<?php

while ($row = mysqli_fetch_assoc($query)) {
    $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = '{$row['id']}'
                OR outgoing_msg_id = '{$row['id']}') AND (outgoing_msg_id = '{$userid}' 
                OR incoming_msg_id = '{$userid}') ORDER BY msg_id DESC LIMIT 1";
    $query2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($query2);
    (mysqli_num_rows($query2) > 0) ? $result = $row2['msg'] : $result = "No messages yet";
    (strlen($result) > 25) ? $msg =  substr($result, 0, 25) . '...' : $msg = $result;
    if (isset($row2['outgoing_msg_id'])) {
        ($userid == $row2['outgoing_msg_id']) ? $you = "You: " : $you = "";
    } else {
        $you = "";
    }
    ($userid == $row['id']) ? $hid_me = "hide" : $hid_me = "";

    $output .= '<a href="chat?user_id=' . $row['id'] . '">
                    <div class="content d-flex">
                    <div class="">
                        <img src="pictures/' . $row['picture'] . '" alt="">
                    </div>
                    <div class="details mt-1 ml-2">
                        <span class="gradient-text font-weight-bolder">' . $row['name'] . '</span>
                        <p>' . $you . $msg . '</p>
                    </div>
                    </div>
                </a>';
}