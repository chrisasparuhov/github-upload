<?php
include 'connect.php';
include 'functions.php';
$C = New Connection();
session_start();
check_logout();

$sql_commands = 'SELECT * FROM commands WHERE command_done=0';
$query_commands = $C->selectQuery($sql_commands);

// Function to calculate distance 
function distance($x1, $y1, $x2, $y2) {

    // Calculating distance 
    return sqrt(pow($x2 - $x1, 2) +
            pow($y2 - $y1, 2) * 1.0);
}
?> 

<div class="command_lines"></div>

<?php
while ($fetch_commands = $C->fetchArray($query_commands)) {
    ?>

    <div class="command_line"
         style="
         position:absolute;
         z-index:11;
         font-size:9px;
         color:grey;
         left:<?php echo $fetch_commands['command_position_x']; ?>;
         top:<?php echo ($fetch_commands['command_position_y'] + 50); ?>;
         "
         >
             DISTANCE:<br><?php echo (round(distance(($fetch_commands['command_position_x'] + 25), ($fetch_commands['command_position_y'] + 25), ($fetch_commands['command_target_x'] + 25), ($fetch_commands['command_target_y'] + 25)))* 1000); ?> KM
    </div>

    <script type="text/javascript">
        $(function () {
            $('.command_lines').line(<?php echo ($fetch_commands['command_position_x'] + 25); ?>, <?php echo ($fetch_commands['command_position_y'] + 25); ?>, <?php echo ($fetch_commands['command_target_x'] + 25); ?>, <?php echo ($fetch_commands['command_target_y'] + 25); ?>, {color: "cyan", zindex: 2, style: "dotted", stroke: 1});
        });
    </script>
    <?php
}
$C->closeConnection();