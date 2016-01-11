<?php
//Include database Connection Script
include 'db_connection.php';

//retrive post variables
$position_id = $_POST['position_id'];

$sql_update_schedule_position = 
"DELETE FROM `".$db."`.`schedule_position` 
WHERE `schedule_position`.`ID` = ".$position_id;

$link->query($sql_update_schedule_position);

//Include database Termination Script
include 'db_disconnect.php';
?>