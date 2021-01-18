<?php

include 'connect.php';
include 'functions.php';
$C = New Connection();

$C->selectQuery('DELETE FROM rats WHERE rat_expired < NOW()');

$rats_randomamount = (1 - 1);
$rats = array(3);
$x_y = [];
for ($i = 250; $i < ($GLOBALS['mapsize'] - 250); $i += $GLOBALS['tilesize']) {
    $x_y[] = $i;
}

function check_number($position_x, $position_y) {
    global $position_x;
    global $position_y;
    $C = New Connection();
    $x_y = [];
    for ($i = 250; $i < ($GLOBALS['mapsize'] - 250); $i += $GLOBALS['tilesize']) {
        $x_y[] = $i;
    }
    $query_asteroidselect = "SELECT asteroid_position_x,asteroid_position_y FROM asteroids WHERE asteroid_position_x='" . $position_x . "' AND asteroid_position_y='" . $position_y . "'";
    $result_asteroidselect = $C->selectQuery($query_asteroidselect);
    $asteroidselect_num = $C->fetchNum($result_asteroidselect);
    if ($asteroidselect_num > 0) {
        $position_x = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
        $position_y = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
        check_number($position_x, $position_y);
    }
    $query_salvageselect = "SELECT salvage_position_x,salvage_position_y FROM salvagesites WHERE salvage_position_x='" . $position_x . "' AND salvage_position_y='" . $position_y . "'";
    $result_salvageselect = $C->selectQuery($query_salvageselect);
    $salvageselect_num = $C->fetchNum($result_salvageselect);
    if ($salvageselect_num > 0) {
        $position_x = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
        $position_y = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
        check_number($position_x, $position_y);
    }
    $npc_sql = "SELECT npc_position_x,npc_position_y FROM npc WHERE npc_position_x='" . $position_x . "' AND npc_position_y='" . $position_y . "'";
    $npc_query = $C->selectQuery($npc_sql);
    $npc_num = $C->fetchNum($npc_query);
    if ($npc_num > 0) {
        $position_x = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
        $position_y = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
        check_number($position_x, $position_y);
    }
    $user_sql = "SELECT user_position_x,user_position_y FROM users WHERE user_position_x='" . $position_x . "' AND user_position_y='" . $position_y . "'";
    $user_query = $C->selectQuery($user_sql);
    $user_num = $C->fetchNum($user_query);
    if ($user_num > 0) {
        $position_x = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
        $position_y = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
        check_number($position_x, $position_y);
    }
    $rat_sql = "SELECT rat_position_x,rat_position_y FROM rats WHERE rat_position_x='" . $position_x . "' AND rat_position_y='" . $position_y . "'";
    $rat_query = $C->selectQuery($rat_sql);
    $rat_num = $C->fetchNum($rat_query);
    if ($rat_num > 0) {
        $position_x = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
        $position_y = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
        check_number($position_x, $position_y);
    }
}

for ($amount = 1; $amount <= $GLOBALS['rat_spawns']; $amount++) {
    $position_x = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
    $position_y = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
    check_number($position_x, $position_y);
    $C->selectQuery('INSERT INTO rats 
    (rat_location, rat_ship_id,rat_position_x,rat_position_y)
    VALUES 
    ("ubana", "' . $rats[rand(0, $rats_randomamount)] . '", "' . $position_x . '", "' . $position_y . '")');
}

$C->closeConnection();
