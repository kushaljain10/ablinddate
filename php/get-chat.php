<?php
session_start();
if (isset($_SESSION['userid'])) {
    include_once "config.php";
    $outgoing_id = $_SESSION['userid'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output = "";
    $sql = "SELECT * FROM messages LEFT JOIN users ON users.id = messages.outgoing_msg_id
    WHERE (outgoing_msg_id = '{$outgoing_id}' AND incoming_msg_id = '{$incoming_id}')
    OR (outgoing_msg_id = '{$incoming_id}' AND incoming_msg_id = '{$outgoing_id}') ORDER BY msg_id";
    $query = mysqli_query($conn, $sql);
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            if ($row['outgoing_msg_id'] === $outgoing_id) {
                $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>' . $row['msg'] . '</p>
                                </div>
                                </div>';
            } else {
                $output .= '<div class="chat incoming">
                                <div class="details">
                                    <p>' . $row['msg'] . '</p>
                                </div>
                                </div>';
            }
            $lastRow = $row;
        }
    } else {
        $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
    }
    if ($lastRow['outgoing_msg_id'] != $outgoing_id) {
        $msgId = $lastRow['outgoing_msg_id'];
        $insert_query = mysqli_query($conn, "UPDATE messages SET seen = 1 WHERE outgoing_msg_id = '{$msgId}'");
    } else {
        if ($lastRow['seen'] == 1) {
            $output .= '<div style="position: absolute; right: 22px; margin-top: -20px; color: #777">seen</div>';
        }
    }
    echo $output;
} else {
    header("url: ../index");
}