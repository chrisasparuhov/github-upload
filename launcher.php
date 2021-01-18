<?php
include 'functions.php';
include 'sound.php';
include 'connect.php';
$C = New Connection();
session_start();

if (isset($_SESSION['user']) != "") {
    echo "<script>top.window.location = 'game.php'</script>";
    die;
}

if (isset($_POST['btn-login'])) {

    $email = $_POST['email'];
    $upass = $_POST['pass'];

    $email = strip_tags(trim($email));
    $upass = strip_tags(trim($upass));

    $password = hash('sha256', $upass); // password hashing using SHA256

    $res = $C->selectQuery("SELECT user_id, user_username, user_password FROM users WHERE user_email='" . $email . "'");

    $row = $C->fetchArray($res);

    $count = $C->fetchNum($res); // if uname/pass correct it returns must be 1 row

    if ($count == 1 && $row['user_password'] == $password) {
        $_SESSION['user'] = $row['user_id'];
        $C->selectQuery('UPDATE users SET user_online=1 WHERE user_id="' . $_SESSION['user'] . '"');
        echo "<script>top.window.location = 'game.php'</script>";
        die;
    } else {
        $errMSG = "Wrong Credentials, Try again...";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>FutureSpace Online</title>
        <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/planet.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="assets/css/contextual.css">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <script src="assets/js/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="assets/js/jquery-migrate-1.4.1.min.js" type="text/javascript"></script>
        <script src="assets/js/scrollview.js" types"text/javascript"></script>
        <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/js/contextual.js"></script>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    </head>
    <body>

        <iframe src="index.php" width="890" height='580' border='0' style='border:1px solid #0a1d26;position:absolute;left:50%;top:50%; transform: translate(-50%,-50%);'></iframe>
        
        
        <style>
            body {
                /*background:url(../img/fso-bg-stars.gif) 200% 200% repeat;*/
                
                 background:url(../img/fso-bg-stars.gif) repeat;
                
                /* Full height */
                height: 100%;
                
            }
        </style>

    </body>
</html>
