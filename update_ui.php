<?php
include 'connect.php';
include 'functions.php';
$C = New Connection();
session_start();
check_logout();

$sql_user = 'SELECT * FROM users WHERE user_id="' . $_SESSION['user'] . '" LIMIT 1';
$result_user = $C->selectQuery($sql_user);
$fetch_user = $C->fetchArray($result_user);

$print_userlocation = $fetch_user["user_location"];

$sql_userlocation = 'SELECT user_id,user_location,user_position_x,user_position_y FROM users WHERE user_location="' . $print_userlocation . '"';
$fetch_userlocation = $C->selectQuery($sql_userlocation);
?>


<div class="fso-navigation">

    <?php
    $sql_territoriallocation = 'SELECT territorial_id,territorial_user_id,territorial_location,territorial_position_x,territorial_position_y FROM territorial WHERE territorial_location="' . $print_userlocation . '"';
    $fetch_territoriallocation = $C->selectQuery($sql_territoriallocation);
    while ($row_navterritorial = $C->fetchArray($fetch_territoriallocation)) {
        $row_navterritorial['territorial_position_x'] = (($row_navterritorial['territorial_position_x'] / 10 ) / 1.5);
        $row_navterritorial['territorial_position_y'] = (($row_navterritorial['territorial_position_y'] / 10 ) / 1.5);
        ?>

        <div style="
        <?php if ($fetch_user["user_id"] == $row_navterritorial["territorial_user_id"]) { ?>
                 background:green;
             <?php } else { ?>
                 background:red;
             <?php } ?>
             position:absolute;
             transform: translate(<?php echo ($row_navterritorial['territorial_position_x'] - 1.5); ?>px, <?php echo ($row_navterritorial['territorial_position_y'] - 1.5) ?>px);
             width:3.45px;
             height:3.45px;
             z-index:12;
             ">                 
        </div>

    <?php } ?>

    <?php
//    while ($row = $C->fetchArray($fetch_userlocation)) {
//        $row['user_position_x'] = (($row['user_position_x'] / 10 ) / 1.5);
//        $row['user_position_y'] = (($row['user_position_y'] / 10 ) / 1.5);
    ?>

    <!--        <div style="
                 background:white;
                 position:absolute;
                 transform: translate(<?php // echo ($row['user_position_x'] - 1.5);    ?>px, <?php // echo ($row['user_position_y'] - 1.5)    ?>px);
                 width:3px;
                 height:3px;
                 border-radius:50%;
                 z-index:12;
                 ">                 
            </div>-->

    <?php // } ?>

    <?php
    $sql_ratlocation = 'SELECT rat_id,rat_location,rat_position_x,rat_position_y FROM rats WHERE rat_location="' . $print_userlocation . '"';
    $fetch_ratlocation = $C->selectQuery($sql_ratlocation);

    while ($row = $C->fetchArray($fetch_ratlocation)) {
        $row['rat_position_x'] = (($row['rat_position_x'] / 10 ) / 1.5);
        $row['rat_position_y'] = (($row['rat_position_y'] / 10 ) / 1.5);
        ?>

        <div style="
             background:#dc3545;
             position:absolute;
             transform: translate(<?php echo ($row['rat_position_x'] - 1.5); ?>px, <?php echo ($row['rat_position_y'] - 1.5) ?>px);
             width:3px;
             height:3px;
             border-radius:50%;
             z-index:11;
             ">                 
        </div>

    <?php } ?>

    <?php
    $sql_npc = 'SELECT npc_id,npc_position_x,npc_position_y FROM npc WHERE npc_location="' . $print_userlocation . '"';
    $result_npc = $C->selectQuery($sql_npc);

    while ($row = $C->fetchArray($result_npc)) {
        $row['npc_position_x'] = (($row['npc_position_x'] / 10 ) / 1.5);
        $row['npc_position_y'] = (($row['npc_position_y'] / 10 ) / 1.5);
        ?>
        <div style="
        <?php if ($row['npc_id'] == 0) { ?>
                 background:yellow;
             <?php } else {
                 ?>
                 background:blue;
             <?php }
             ?>
             position:absolute;
             transform: translate(<?php echo ($row['npc_position_x'] - 1.5); ?>px, <?php echo ($row['npc_position_y'] - 1.5); ?>px);
             width:3px;
             height:3px;
             border-radius:50%;
             z-index:11;
             "></div>
             <?php
         }
         ?>

    <?php
    $sql = 'SELECT asteroid_position_x,asteroid_position_y FROM asteroids WHERE asteroid_location="' . $print_userlocation . '" AND asteroid_miningbyuserid=0';
    $result = $C->selectQuery($sql);

    while ($row = $C->fetchArray($result)) {

        $row['asteroid_position_x'] = (($row['asteroid_position_x'] / 10 ) / 1.5);
        $row['asteroid_position_y'] = (($row['asteroid_position_y'] / 10 ) / 1.5);
        ?>
        <div style="
             background:magenta;
             position:absolute;
             transform: translate(<?php echo ($row['asteroid_position_x'] - 1.5) ?>px, <?php echo ($row['asteroid_position_y'] - 1.5) ?>px);
             width:3px;
             height:3px;
             border-radius:50%;
             z-index:11;
             "></div>
             <?php
         }
         ?>

</div>

<div class="fso-chat">
    <iframe src="chat/index.php" style="position:absolute;border:0;padding:7px;" width="100%" height="100%"></iframe>
        </div>

<div class="fso-inventory">
    <?php
    $sql_inventory = 'SELECT * FROM inventory WHERE inventory_user_id="' . $_SESSION['user'] . '"';
    $select_inventory = $C->selectQuery($sql_inventory);
    while ($fetch_inventory = $C->fetchArray($select_inventory)) {
        $sql_items = 'SELECT * FROM items WHERE item_id="' . $fetch_inventory['inventory_item_id'] . '"LIMIT 1';
        $select_items = $C->selectQuery($sql_items);
        $fetch_items = $C->fetchArray($select_items);
        ?>
        <img src="assets/img/items/<?php echo $fetch_items['item_img']; ?>" width="30" height="30" alt="<?php echo $fetch_items['item_name']; ?>" style="margin:2px;">
    <?php } ?>
</div>

<div class="fso-topbar">
    <button onmouseup="play_on_click()" onmouseover="play_on_hover()"><img src="assets/img/galleon.png"> <?php echo $fetch_user["user_galleons"]; ?></button>
    <button onmouseup="play_on_click()" onmouseover="play_on_hover()"><img src="assets/img/teranium.png"> <?php echo $fetch_user["user_teranium"]; ?></button>
    <button onmouseup="play_on_click()" onmouseover="play_on_hover()"><img src="assets/img/titanium.png"> <?php echo $fetch_user["user_titanium"]; ?></button>
    <div class='fso-info' style=''>
        Solar system: <font color="lightgreen">ubana</font>
        <div class="fso-topbar_frameborder_bottom"></div>
    </div>

</div>

<?php
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
    $C->selectQuery('UPDATE users SET `user_lvl` = user_lvl+1 WHERE user_id=1');
}
?>

<div class="expbar" style="width:<?php echo ( 100 - $thepercent_raw); ?>%!important;">
    <div class='expbar-perc'><?php echo ( 100 - $thepercent_raw); ?>%</div>
</div>

<div class="fso-myplanet">
    <img class="warptome" onclick="play_on_click()" src="assets/img/planets/planet_id<?php echo $fetch_user['user_planet_id']; ?>.png" width="32" style='float:left;' > 
    <div style="float:left;padding-left:5px;">
        <button onmouseup="play_on_click()" onmouseover="play_on_hover()" onclick="play_open_window()"><?php echo $fetch_user['user_username']; ?> </button>
        <button data-toggle="modal" data-target="#itemcodex"  onmouseup="play_on_click()" onmouseover="play_on_hover()" onclick="play_open_window()">Item Codex</button>
        <button onclick="play_notenoughenergy()"onmouseup="play_on_click()" onmouseover="play_on_hover()" >Sound</button>
        <button class="pop-error" onclick="play_on_error()" onmouseup="play_on_click()" onmouseover="play_on_hover()">Error</button>
        <button onclick="play_game_music()" onmouseup="play_on_click()" onmouseover="play_on_hover()">Play Music</button>
        <button class="fso-button pop-achievement" onmouseup="play_on_click()" onmouseover="play_on_hover()" onclick="play_achievement()">pop</button>
        <button  class="pop-settings"   onmouseup="play_on_click()" onmouseover="play_on_hover()" onclick="play_open_window()">Settings</button>
        <button onclick="location.href = 'game.php?logout';" onmouseup="play_on_click()" onmouseover="play_on_hover()" >Logout</button>
    </div>
    <script>
        $(".warptome").click(function () {
            $("#region").animate({
                scrollLeft: (<?php echo $fetch_user['user_position_x']; ?> + 25) - screenx,
                scrollTop: (<?php echo $fetch_user['user_position_y']; ?> + 25) - screeny},
                    "slow", "swing");
        });
        $(".fso-achievement").hide();
        $(".pop-achievement").click(function () {
            $(".fso-achievement").fadeIn();
        });
        $(".close-achievement").click(function () {
            $(".fso-achievement").fadeOut();
        });
    </script>
</div>
<?php
$C->closeConnection();
