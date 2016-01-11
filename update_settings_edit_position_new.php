<?php
//Include database Connection Script
include 'db_connection.php';

//retrive post variables
$new_position_name = $_POST['new_position_name'];
$new_pp_requirement = $_POST['new_pp_requirement'];


$sql_update_schedule_position = "

INSERT INTO `".$db."`.`schedule_position` (`ID`, `ID_posted_position_requirement`, `name`) 
VALUES (NULL, '".$new_pp_requirement."', '".$new_position_name."')";

$link->query($sql_update_schedule_position);

//Include database Termination Script
include 'db_disconnect.php';
?>