<?php
include 'connect.php';
$C = New Connection();
session_start();
?>

<div class="fso-status boxbg">

    <center>
        <?php
        $query = $C->selectQuery("SELECT user_id FROM users where user_deleted=0");
        $result = $C->fetchNum($query);

        $query_online = $C->selectQuery("SELECT user_online FROM `users` WHERE user_online=1");
        $getonlinenum = $C->fetchNum($query_online);

        if ($getonlinenum > 0) {
            $onlinecolor = "green";
        } else {
            $onlinecolor = "#40c0fb";
        }

        echo "<font color='#40c0fb'> " . $result . "</font> players registered /  <font color='" . $onlinecolor . "'>" . $getonlinenum . "</font> ONLINE";
        ?>
    </center>

    <div class='fso-info' style="position:absolute;right:20px;top:5px;right:15px;width:100%;text-align:right;font-size:10px!important;"><font color="lightgreen">online</font></div>

    <div class='fso-info' style="position:absolute;left:20px;top:5px;text-align:left;width:100%;font-size:10px!important;"> <font color="#40c0fb">22:00</font></font></div>
</div>

<?php
$C->closeConnection();


