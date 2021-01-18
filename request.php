<?php
include 'connect.php';
include 'functions.php';
$C = New Connection();
session_start();
check_logout();

$sql_delrequest = 'SELECT * FROM request WHERE request_deleted=1';
$result_delrequest = $C->selectQuery($sql_delrequest);
$num_delrequest = $C->fetchNum($result_delrequest);
while ($fetch_delrequest = $C->fetchArray($result_delrequest)) {
    $C->selectQuery("DELETE FROM request WHERE request_deleted=1");
}
$sql_request = 'SELECT *,UNIX_TIMESTAMP(`request_done`) AS `request_done` FROM request WHERE request_deleted=0';
$result_request = $C->selectQuery($sql_request);
$num_request = $C->fetchNum($result_request);
if (!$num_request == 0) {
    while ($fetch_request = $C->fetchArray($result_request)) {
        if ($fetch_request['request_type'] == "newplayer") {
            if ($fetch_request['request_done'] < (time())) {
                $C->selectQuery("UPDATE request SET request_deleted=1 WHERE request_id='" . $fetch_request['request_id'] . "'");
            }
            ?>
            <script>
                console.log("Request: <?php echo $fetch_request['request_type']; ?>");
                $("#update_objects").load("update_objects.php");
                $("#update_ui").load("update_ui.php");
            </script>
            <?php
        }
    }
} else {
    ?>
    <script>
        console.log("Nothing to request");
    </script>
    <?php
}
$C->closeConnection();
