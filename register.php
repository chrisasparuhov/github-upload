<?php
include 'connect.php';
include 'functions.php';
$C = New Connection();
session_start();
check_login();

if (isset($_POST['btn-signup'])) {
    $ip = $_SERVER['REMOTE_ADDR'];

    $uname = trim($_POST['uname']);
    $email = trim($_POST['email']);
    $upass = trim($_POST['pass']);

    $uname = strip_tags($uname);
    $email = strip_tags($email);
    $upass = strip_tags($upass);

// password encrypt using SHA256();
    $password = hash('sha256', $upass);

// check email exist or not
    $query = "SELECT user_email FROM users WHERE user_email='" . $email . "'";
    $query_ip = "SELECT user_ip FROM users WHERE user_ip='" . $ip . "'";
    $query_username = "SELECT user_username FROM users WHERE user_username='" . $uname . "'";
    $result = $C->selectQuery($query);
    $result_ip = $C->selectQuery($query_ip);
    $result_username = $C->selectQuery($query_username);

    $count = $C->fetchNum($result); // if email not found then proceed
    $count_ip = $C->fetchNum($result_ip); // if email not found then proceed
    $count_username = $C->fetchNum($result_username); // if email not found then proceed

    if ($count_username > 0) {
        $errTyp = "warning";
        $errMSG = "Username already in use";
    } else {
        if ($count_ip > 2) {
            $errTyp = "warning";
            $errMSG = "Sorry, you can only have 2 accounts on your network";
        } else {

            if ($count > 0) {
                $errTyp = "warning";
                $errMSG = "Sorry, email already in use";
            } else {
                $protection = (time() + $GLOBALS['protectiontime']);
                $x_y = [];
                for ($i = 250; $i < ($GLOBALS['mapsize'] - 250); $i += $GLOBALS['tilesize']) {
                    $x_y[] = $i;
                }

                $position_x = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
                $position_y = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];

                function check_number($position_x, $position_y) {
                    global $position_x;
                    global $position_y;
                    $C = New Connection();
                    $x_y = [];
                    for ($i = 250; $i < ($GLOBALS['mapsize'] - 250); $i += $GLOBALS['tilesize']) {
                        $x_y[] = $i;
                    }

                    $query_spotavailablerat = "SELECT rat_position_x,rat_position_y FROM rats WHERE rat_position_x='" . $position_x . "' AND rat_position_y='" . $position_y . "'";
                    $result_spotrat= $C->selectQuery($query_spotavailablerat);
                    $spotavailablerat_num = $C->fetchNum($result_spotrat);
                    if ($spotavailablerat_num > 0) {
                        $position_x = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
                        $position_y = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
                        check_number($position_x, $position_y);
                    }

                    $query_spotavailableasteroid = "SELECT asteroid_position_x,asteroid_position_y FROM asteroids WHERE asteroid_position_x='" . $position_x . "' AND asteroid_position_y='" . $position_y . "'";
                    $result_spotasteroid = $C->selectQuery($query_spotavailableasteroid);
                    $spotavailableasteroid_num = $C->fetchNum($result_spotasteroid);
                    if ($spotavailableasteroid_num > 0) {
                        $position_x = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
                        $position_y = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
                        check_number($position_x, $position_y);
                    }

                    $query_spotavailable = "SELECT user_position_x,user_position_y FROM users WHERE user_position_x='" . $position_x . "' AND user_position_y='" . $position_y . "'";
                    $result_spot = $C->selectQuery($query_spotavailable);
                    $spotavailable_num = $C->fetchNum($result_spot);
                    if ($spotavailable_num > 0) {
                        $position_x = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
                        $position_y = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
                        check_number($position_x, $position_y);
                    }

                    $query_spotavailablenpc = "SELECT npc_position_x,npc_position_y FROM npc WHERE npc_position_x='" . $position_x . "' AND npc_position_y='" . $position_y . "'";
                    $resultnpc_spot = $C->selectQuery($query_spotavailablenpc);
                    $spotavailablenpc_num = $C->fetchNum($resultnpc_spot);
                    if ($spotavailablenpc_num > 0) {
                        $position_x = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
                        $position_y = $x_y[rand(0, ($GLOBALS['tilesize'] - 1))];
                        check_number($position_x, $position_y);
                    }
                }

                check_number($position_x, $position_y);

                $C->selectQuery("INSERT INTO users(user_username, user_email, user_password, user_protection, user_position_x, user_position_y,user_ip) VALUES('$uname', '$email', '$password', FROM_UNIXTIME('$protection'), '$position_x', '$position_y', '$ip')");

                $user_id = $C->fetchInsertId();

                $C->selectQuery("INSERT INTO territorial(territorial_position_x, territorial_position_y,territorial_user_id) VALUES('$position_x', '$position_y', '$user_id')");
                $C->selectQuery("INSERT INTO transactions(transaction_amount,transaction_user_id,transaction_txt) VALUES('5000','$user_id','start bonus')");
                $done_at = (time() + 10);
                $C->selectQuery("INSERT INTO request(request_type,request_user_id,request_done) VALUES('newplayer','$user_id',FROM_UNIXTIME('$done_at'))");

                $res = $C->selectQuery($query);

                if ($res) {
                    $errTyp = "success";
                    $errMSG = "Successfully registered, you may login now";
                } else {
                    $errTyp = "danger";
                    $errMSG = "Sorry, something went wrong";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $GLOBALS['page_title']; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link href="<?php echo $GLOBALS['url_path']; ?>assets/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $GLOBALS['url_path']; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <script src="<?php echo $GLOBALS['url_path']; ?>assets/js/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="<?php echo $GLOBALS['url_path']; ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
        <?php include 'loading.php'; ?>
    </head>
    <body onload="play_index_music();">
        <style>
            body {
                background:url(<?php echo $GLOBALS['url_path']; ?>assets/img/fso-bg-stars.gif) 200% 200% repeat;
                /* Full height */
                height: 100%;
            }
        </style>

        <div id="update"></div>

        <div class="fso-register boxbg">
            <img src="<?php echo $GLOBALS['url_path']; ?>assets/img/fso-logo.png" style="position:absolute;bottom:154px;left:50%;transform: translate(-50%,0);">
            <div class="fso-title">Register</div>
            <div class="fso-login_frameborder_top"></div>

            <?php
            if (isset($errMSG)) {
                ?>

                <div class="alert alert-<?php echo $errTyp; ?>" style="position:fixed;left:220px;">
                    <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>

                <?php
            }
            ?>
            <form method="post" autocomplete="off">
                <input type="text" name="uname" class="form-control" placeholder="Enter Username" required autocomplete="off" style="width:96%;margin:2%;" />
                <input type="email" name="email" class="form-control" placeholder="Enter Your Email" required  autocomplete="off" style="width:96%;margin:2%;margin-top:0px!important;" />
                <input type="password" name="pass" class="form-control" placeholder="Enter Password" required autocomplete="off" style="width:96%;margin:2%;margin-top:-1px!important;"/>

                <center><button type="submit" name="btn-signup" style="width:96%;margin:2%;margin-top:0px!important;">register</button></center>
            </form>

            <div class="fso-login_frameborder_bottom"></div>
        </div>

        <button class="boxbg-green" onclick="location.href = '<?php echo $GLOBALS['url_path']; ?>'" type="button" style="border:1px solid green!important;color:lightgreen!important;background-image: linear-gradient(black, green);position:fixed;bottom:25px;left:50%;transform: translate(-50%,0);">Play fso</button>


        <script>
            $(function () {
                play_indexmusic();
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
include 'sound.php';
