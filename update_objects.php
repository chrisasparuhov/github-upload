<?php
include 'connect.php';
include 'functions.php';
$C = New Connection();
session_start();
check_logout();

$sql_user = 'SELECT user_id,user_location,user_exp,user_hp,user_lvl FROM users WHERE user_id="' . $_SESSION['user'] . '" LIMIT 1';
$result_user = $C->selectQuery($sql_user);
$fetch_user = $C->fetchArray($result_user);

$userexp = $fetch_user['user_exp'];
$userhp = $fetch_user['user_hp'];
$level = $fetch_user['user_lvl'];

$exp_prev = experience($level - 1);
$exp_goal = experience($level);

$theamounthofexpneeded = ($exp_goal - $userexp);
$theamounthofexpup = ($exp_goal - $exp_prev);
$thepercent_raw = number_format(($theamounthofexpneeded / $theamounthofexpup) * 100, 0);
$thepercent = number_format(($theamounthofexpneeded / $theamounthofexpup) * 100, 2);

if ($thepercent_raw < 0) {
    $thepercent_raw = "0";
}

if ($userexp >= experience(98)) {
    // Max lvl 99;
    $level = "MAX";
}

if ($userexp >= $exp_goal) {
    $C->selectQuery('UPDATE users SET `user_lvl`=user_lvl+1 WHERE user_id="' . $fetch_user['user_id'] . '"');
}

$asteroids_query = $C->selectQuery('SELECT *,UNIX_TIMESTAMP(`asteroid_expired`) AS `asteroid_expired` FROM asteroids
    LEFT JOIN items ON asteroids.asteroid_item_id = items.item_id
    WHERE asteroid_location="' . $fetch_user["user_location"] . '" AND asteroid_mined=0');

while ($row_asteroids = $C->fetchArray($asteroids_query)) {

    if ($row_asteroids['asteroid_expired'] < time() && $row_asteroids['asteroid_miningbyuserid'] > 0 && $row_asteroids['asteroid_mined'] == 0) {
        $C->selectQuery('UPDATE asteroids SET asteroid_mined=1 WHERE asteroid_expired < NOW() AND asteroid_miningbyuserid > 0');
    }
    ?>

    <div class="tile asteroid asteroid<?php echo $row_asteroids['asteroid_id']; ?>" onmouseover="play_beep()" onclick="play_openwindow()" style="
         transform: translate(<?php echo $row_asteroids['asteroid_position_x']; ?>px, <?php echo $row_asteroids['asteroid_position_y']; ?>px);
         background-image:url(assets/img/items/<?php echo $row_asteroids['item_img']; ?>);
         background-repeat: no-repeat;
         background-position:center center;
         background-size:32px 32px;  
         ">

        <div class="gridtitle asteroidtitle<?php echo $row_asteroids['asteroid_id']; ?>">
            &nbsp;<?php echo $row_asteroids['item_name']; ?>&nbsp;
            <div class="gridtitles">Mine</div>
        </div>

    </div>
    <script>
        $(function () {
            $(".asteroid<?php echo $row_asteroids['asteroid_id']; ?>").mouseover(function () {
                $(".asteroidtitle<?php echo $row_asteroids['asteroid_id']; ?>").show();
            });
            $(".asteroid<?php echo $row_asteroids['asteroid_id']; ?>").mouseout(function () {
                $(".asteroidtitle<?php echo $row_asteroids['asteroid_id']; ?>").hide();
            });
            $(".asteroid<?php echo $row_asteroids['asteroid_id']; ?>").click(function (e) {
                $("#region").animate({scrollLeft: (<?php echo $row_asteroids['asteroid_position_x']; ?> + 25) - screenx, scrollTop: (<?php echo $row_asteroids['asteroid_position_y']; ?> + 25) - screeny}, "slow", "swing");
            });
        });
    </script>

    <?php
}
?>

<?php
$npc_sql = 'SELECT * FROM npc WHERE npc_location="' . $fetch_user["user_location"] . '"';
$npc_query = $C->selectQuery($npc_sql);

while ($row_npc = $C->fetchArray($npc_query)) {
    ?>

    <div class="tile npc npc<?php echo $row_npc['npc_id']; ?>"  onmouseover="play_beep()" onclick="play_openwindow()" style="
         transform: translate(<?php echo $row_npc['npc_position_x']; ?>px, <?php echo $row_npc['npc_position_y']; ?>px);
         background-image:url(assets/img/npcs/<?php echo $row_npc['npc_img']; ?>);
         background-repeat: no-repeat;
         background-position:center center;
         background-size:95% 95%;

         <?php if ($row_npc['npc_warpable'] == 1) { ?>
             border-radius: 50%;
         <?php } ?>

         <?php if ($row_npc['npc_id'] == 0) { ?>
             background-size:150% 150%;
             border-radius: 50%;
             -webkit-animation: sun-glow 0.25s linear infinite;
             animation: sun-glow 0.25s  linear infinite;
         <?php } ?>
         ">

        <div class="gridtitle npctitle<?php echo $row_npc['npc_id']; ?>">
            &nbsp;<?php echo $row_npc['npc_name']; ?>&nbsp;
            <?php if ($row_npc['npc_warpable'] == 1) { ?>
                <div class="gridtitles">warp</div>
            <?php } ?>
            <?php if ($row_npc['npc_dockable'] == 1) { ?>
                <div class="gridtitles">dock</div>
            <?php } ?>
        </div>

    </div>

    <script>
        $(function () {

            $(".npc<?php echo $row_npc['npc_id']; ?>").mouseover(function () {
                $(".npctitle<?php echo $row_npc['npc_id']; ?>").show();
            });
            $(".npc<?php echo $row_npc['npc_id']; ?>").mouseout(function () {
                $(".npctitle<?php echo $row_npc['npc_id']; ?>").hide();
            });
            $(".npc<?php echo $row_npc['npc_id']; ?>").click(function (e) {
                $("#region").animate({scrollLeft: (<?php echo $row_npc['npc_position_x']; ?> + 25) - screenx, scrollTop: (<?php echo $row_npc['npc_position_y']; ?> + 25) - screeny}, "slow", "swing");
            });
        });
    </script>
    <?php
}
?>

<?php
$planets_query = $C->selectQuery('SELECT *,UNIX_TIMESTAMP(`user_protection`) AS `user_protection` FROM users WHERE user_location="' . $fetch_user["user_location"] . '"');

while ($row_planets = $C->fetchArray($planets_query)) {
    ?>

    <div class="tile planet planet<?php echo $row_planets['user_id']; ?>" style="
         transform: translate(<?php echo $row_planets['user_position_x']; ?>px, <?php echo $row_planets['user_position_y']; ?>px);
         background-image:url(assets/img/planets/planet_id1.png);
         background-repeat: no-repeat;
         background-position:center center;
         background-size:32px 32px;
         ">

        <?php
        if ($row_planets['user_protection'] > time()) {
            ?>
            <div class="orbit">
                <div class="belt1"></div>
                <div class="belt2"></div>
                <div class="belt3"></div>
            </div>
            <?php
        }
        ?>
        <div class="gridtitle planettitle<?php echo $row_planets['user_id']; ?>">
            &nbsp;<?php echo $row_planets['user_username']; ?>&nbsp;
            <?php if ($row_planets['user_protection'] > time()) { ?>
                <br> <font style="color:red">&nbsp;under protection &nbsp;</font>
            <?php } ?>
            <div class="gridtitles">info</div>

            <!--if session_id is me then dont show attack button-->
            <!--needs to be fixed if there is session-->

            <div class="gridtitles redtitles">attack</div>

        </div>
    </div>


    <script>
        $(function () {
            $(".planet<?php echo $row_planets['user_id']; ?>").mouseover(function () {
                $(".planettitle<?php echo $row_planets['user_id']; ?>").show();
            });
            $(".planet<?php echo $row_planets['user_id']; ?>").mouseout(function () {
                $(".planettitle<?php echo $row_planets['user_id']; ?>").hide();
            });
            $(".planet<?php echo $row_planets['user_id']; ?>").click(function () {
                $("#region").animate({scrollLeft: (<?php echo $row_planets['user_position_x']; ?> + 25) - screenx, scrollTop: (<?php echo $row_planets['user_position_y']; ?> + 25) - screeny}, "slow", "swing");
            });
        });
    </script>
<?php }
?>

<?php
$territorial_sql = 'SELECT * FROM territorial WHERE territorial_location="' . $fetch_user["user_location"] . '"';
$territorial_query = $C->selectQuery($territorial_sql);

while ($row_territorial = $C->fetchArray($territorial_query)) {
    ?>

    <div class="tile territorial territorial<?php echo $row_territorial['territorial_id']; ?>" onclick="play_openwindow()" style="
         transform: translate(<?php echo $row_territorial['territorial_position_x']; ?>px, <?php echo $row_territorial['territorial_position_y']; ?>px);

         <?php
         if ($row_territorial['territorial_user_id'] != $_SESSION['user']) {
             ?>
             box-shadow: inset 0px 0px 35px 2px rgba(255,0,0,0.15);
             <?php
         } else {
             ?>
             box-shadow: inset 0px 0px 35px 2px rgba(64,255,0,0.15);
             <?php
         }
         ?>

         ">
    </div>
<?php }
?>

<?php
$rats_query = $C->selectQuery('SELECT * FROM rats
    LEFT JOIN items ON rats.rat_ship_id = items.item_id
    WHERE rat_location="' . $fetch_user["user_location"] . '"');

while ($row_rats = $C->fetchArray($rats_query)) {
   
    ?>
    <div class="tile rats rat<?php echo $row_rats['rat_id']; ?>" onmouseover="play_beep()" onclick="play_openwindow()" style="
         transform: translate(<?php echo $row_rats['rat_position_x']; ?>px, <?php echo $row_rats['rat_position_y']; ?>px);
         background-image:url(assets/img/items/<?php echo $row_rats['item_img']; ?>);
         background-repeat: no-repeat;
         background-position:center center;
         background-size:32px 32px;
         ">
        <div class="gridtitle rattitle<?php echo $row_rats['rat_id']; ?>">   &nbsp;<?php echo $row_rats['item_name']; ?>&nbsp;</div>
    </div>

    <script>
        $(function () {
            $(".rat<?php echo $row_rats['rat_id']; ?>").mouseover(function () {
                $(".rattitle<?php echo $row_rats['rat_id']; ?>").show();
            });
            $(".rat<?php echo $row_rats['rat_id']; ?>").mouseout(function () {
                $(".rattitle<?php echo $row_rats['rat_id']; ?>").hide();
            });
            $(".rat<?php echo $row_rats['rat_id']; ?>").click(function () {
                $("#region").animate({scrollLeft: (<?php echo $row_rats['rat_position_x']; ?> + 25) - screenx, scrollTop: (<?php echo $row_rats['rat_position_y']; ?> + 25) - screeny}, "slow", "swing");
            });
        });
    </script>
    <?php
}
$C->closeConnection();