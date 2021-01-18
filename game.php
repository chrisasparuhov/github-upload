<?php
include 'connect.php';
include 'functions.php';
$C = New Connection();
session_start();
check_logout();

$sql_user = 'SELECT * FROM users WHERE user_id="' . $_SESSION['user'] . '"';
$result_user = $C->selectQuery($sql_user);
$fetch_user = $C->fetchArray($result_user);

if (isset($_GET['logout'])) {
    $C = New Connection();
    $C->selectQuery('UPDATE users SET user_online=0 WHERE user_id="' . $_SESSION['user'] . '"');
    session_destroy();
    unset($_SESSION['user']);
    echo "<script>top.window.location = '" . $GLOBALS['url_path'] . "'</script>";
    die;
}

$npc_sql = 'SELECT * FROM npc WHERE npc_location="' . $fetch_user["user_location"] . '" AND npc_warpable=0 AND !npc_id=0';
$npc_query = $C->selectQuery($npc_sql);

$print_userlocation = $fetch_user["user_location"];
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $GLOBALS['page_title']; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="<?php echo $GLOBALS['url_path']; ?>assets/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $GLOBALS['url_path']; ?>assets/css/planet.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $GLOBALS['url_path']; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <script src="<?php echo $GLOBALS['url_path']; ?>assets/js/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="<?php echo $GLOBALS['url_path']; ?>assets/js/jquery.line.js" type="text/javascript"></script>
        <script src="<?php echo $GLOBALS['url_path']; ?>assets/js/scrollview.js" type="text/javascript"></script>
        <script src="<?php echo $GLOBALS['url_path']; ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
        <?php include 'loading.php'; ?>
    </head>
    <body>
        <?php include 'sound.php'; ?>

        <!-- Modal -->
        <div class="modal fade" id="itemcodex" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

            <div class="modal-dialog" role="document">
                <div class="fso-modal_frameborder_top"></div>
                <div class="modal-content">
                    <div class="fso-title">Item Codex</div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onmouseover="play_on_hover()" onclick="play_on_click()">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <div class="modal-body">

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <center><p class="sub" style="margin-bottom:8px;">All items</p></center>
                                    <div class="row" style="overflow: scroll!important;max-height:500px!important;">

                                        <?php
                                        $item_query = $C->selectQuery("SELECT * FROM items WHERE item_type!='blueprint'");

                                        while ($row = $C->fetchArray($item_query)) {
                                            ?>
                                            <div class="col-md-6">
                                                <div class="index_item" style="width:100%">

                                                    <div class="index_item_img">
                                                        <img src="assets/img/items/<?php echo $row["item_img"]; ?>">
                                                    </div>

                                                    <div class="index_item_name ">
                                                        <?php echo $row["item_name"]; ?>
                                                    </div>
                                                </div>



                                            </div>


                                        <?php } ?>

                                    </div>

                                </div>
                            </div>


                        </div>                                       
                    </div>                                       

                </div>
                <div class="fso-modal_frameborder_bottom"></div>
            </div>

        </div>


        <div id="request"></div>
        <div id="update_ui"></div>

        <div class="fso-achievement">
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <center><h1>Achievement Unlocked</h1>
                <h2>My first catch</h2><br>
                <h3>Rewards:</h3>

                <div class="item">
                    <div class="item_img">
                        <img src="assets/img/items/blueprint.png">
                    </div>
                </div>

                <br>
                <button class="close-achievement" onmouseover="play_on_hover()" onclick="play_open_window()">Close</button>
            </center>
        </div>

        <div class="fso_error">
            errorerror
            <button class="close-error" onmouseover="play_on_hover()" onclick="play_open_window()">Close</button>
        </div>

        <div class="yourplanet">
            <div class="theplanet">
                <div class="texture"></div>
            </div>
        </div>

        <div id="region">

            <script>
                var theWidth = document.getElementById('region').offsetWidth;
                var theHeight = document.getElementById('region').offsetHeight;
                var screenx = theWidth / 2;
                var screeny = theHeight / 2;
            </script>
            <div id="region_drag">

                <!--                <div class="tiles">
                <?php // for ($amount = 50; $amount < ($mapsize - 450); $amount++) { ?>
                                        <div class="tile"></div>
                <?php
//                    }
                ?>
                                </div>-->

                <div id="update_objects"></div>
                <div id="update_command"></div>

            </div>
        </div>

        <script>
            $("#region").scrollview({
                grab: "assets/img/cursor_default.png",
                grabbing: "assets/img/cursor_select.png"
            });
        </script>


        <script>
            $(function () {
                play_game_music();
                $("#region").animate({
                    scrollLeft: (<?php echo $fetch_user['user_position_x']; ?> + 25) - screenx,
                    scrollTop: (<?php echo $fetch_user['user_position_y']; ?> + 25) - screeny},
                        "slow", "swing");
                $("#request").load("request.php");

                $("#update_objects").load("update_objects.php");
                $("#update_ui").load("update_ui.php");
                $("#update_command").load("update_command.php");

                setInterval(function () {
                    $("#request").load("request.php");
                }, 10000);

                $(document).keydown(function (event) {
                    if (event.ctrlKey == true && (event.which == '61' || event.which == '107' || event.which == '173' || event.which == '109' || event.which == '187' || event.which == '189')) {
                        event.preventDefault();
                    }
                });

                $(window).bind('mousewheel DOMMouseScroll', function (event) {
                    if (event.ctrlKey == true) {
                        event.preventDefault();
                    }
                });

                $(".mute_bgmusic1").click(function () {
                    $("#bgmusic1").prop('muted', !$("#bgmusic1").prop('muted'));
                });

                $(".showplanet").click(function () {
                    $(".yourplanet").fadeIn();
                });

                $(".showmap").click(function () {
                    $(".yourplanet").fadeOut();
                });

                $(".fso_error").hide();
                $(".pop-error").click(function () {
                    $(".fso_error").fadeIn();
                });

                $(".close-error").click(function () {
                    $(".fso_error").fadeOut();
                });

                $(".fso-achievement").hide();
                $(".pop-achievement").click(function () {
                    $(".fso-achievement").fadeIn();
                });
                
                $(".close-achievement").click(function () {
                    $(".fso-achievement").fadeOut();
                });
            });
        </script>

    </body>
</html>
<?php
$C->closeConnection();
