<?php
include 'connect.php';
include 'functions.php';
$C = New Connection();
session_start();
check_login();
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
        <?php
        if (isset($_POST['btn-login'])) {
            $uname = $_POST['uname'];
            $upass = $_POST['pass'];
            $uname = strip_tags(trim($uname));
            $upass = strip_tags(trim($upass));
            $password = hash('sha256', $upass); // password hashing using SHA256
            $res = $C->selectQuery("SELECT user_id, user_username, user_password FROM users WHERE user_username='" . $uname . "'");
            $row = $C->fetchArray($res);
            $count = $C->fetchNum($res); // if uname/pass correct it returns must be 1 row
            if ($count == 1 && $row['user_password'] == $password) {
                $_SESSION['user'] = $row['user_id'];
                $C->selectQuery('UPDATE users SET user_online=1,user_lastlog=NOW() WHERE user_id="' . $_SESSION['user'] . '"');
                echo "<script>top.window.location = '" . $GLOBALS['url_path'] . "game.php'</script>";
                die;
            } else {
                $errMSG = "Wrong Credentials, Try again...";
            }
        }
        ?>

        <style>
            body {
                background:url(<?php echo $GLOBALS['url_path']; ?>assets/img/fso-bg-stars.gif) repeat,black;
            }
        </style>

        <div id="update"></div>

        <!-- Modal -->
        <div class="modal fade" id="itemcodex" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

            <div class="modal-dialog" role="document">
                <div class="fso-modal_frameborder_top"></div>
                <div class="modal-content">
                    <div class="fso-title">Item Codex</div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                                        <img src="<?php echo $GLOBALS['url_path']; ?>assets/img/items/<?php echo $row["item_img"]; ?>">
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

        <div class="fso-login boxbg">
            <img src="<?php echo $GLOBALS['url_path']; ?>assets/img/fso-logo.png" style="position:absolute;bottom:140px;left:50%;transform: translate(-50%,0);">
            <div class="fso-title">Logins</div>
            <div class="fso-login_frameborder_top"></div>

            <?php
            if (isset($errMSG)) {
                ?>
                <script>
                    $(function () {
                        play_on_error();
                    });
                </script>
                <div class="alert alert-danger" style="position:fixed;left:220px;color:red;">
                    <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>

                <?php
            }
            ?>
            <form method="post" autocomplete="off">
                <input type="text" name="uname" placeholder="Username" required autocomplete="off" style="width:96%;margin:2%;"/><br>
                <input type="password" name="pass" placeholder="Password"  required autocomplete="off" style="width:96%;margin:2%;margin-top:0px;"/>
                <center><button type="submit" name="btn-login" style="width:96%;margin:2%;margin-top:-1px!important;" onmouseup="play_on_click()" onmouseover="play_on_hover()">Play</button></center>
            </form>

            <button data-toggle="modal" data-target="#itemcodex" style="position:fixed;bottom:-60px;width:173px; z-index:11;" onmouseup="play_on_click()" onmouseover="play_on_hover()" >Item Codex</button>
            <button style="position:fixed;bottom:-90px;width:173px; z-index:11;" onmouseup="play_on_click()" onmouseover="play_on_hover()" >Updates</button>
            <button class="boxbg-green" onmouseup="play_on_click()" onmouseover="play_on_hover()" onclick="location.href = '<?php echo $GLOBALS['url_path']; ?>register.php'" style="border:1px solid green!important;color:lightgreen!important;background-image: linear-gradient(black, green);position:fixed;left:50%;bottom:-120px;width:173px;transform: translate(-50%,0);">REGISTER FREE ACCOUNT</button>


            <div class="fso-login_frameborder_bottom"></div>
        </div>


        <script>
            $(function () {
                play_index_music();
                $('input').attr('autocomplete', 'off');
                $("#update").load("<?php echo $GLOBALS['url_path']; ?>update_serverstatus.php");
                setInterval(function () {
                    $("#update").load("<?php echo $GLOBALS['url_path']; ?>update_serverstatus.php");
                }, 10000);
            });
        </script>

    </body>
</html>

<?php
$C->closeConnection();
