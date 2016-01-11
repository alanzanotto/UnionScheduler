<?php
//Include database Connection Script
include 'db_connection.php';

//retrive post variables
$position_id = $_POST['position_id'];
$new_position_name = $_POST['new_position_name'];
$new_pp_requirement = $_POST['new_pp_requirement'];


$sql_update_schedule_position = "
UPDATE `".$db."`.`schedule_position` 
SET 
`ID_posted_position_requirement` = ".$new_pp_requirement.", 
`name` = '".$new_position_name."' 
WHERE `schedule_position`.`ID` = ".$position_id;

$link->query($sql_update_schedule_position);

//Include database Termination Script
include 'db_disconnect.php';
?>