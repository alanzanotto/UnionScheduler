<?php
//Include database Connection Script
include 'db_connection.php';

//retrive post variables
$template_ID = $_POST['template_ID'];
$new_position_ID = $_POST['new_position_ID'];
$new_position_quantity = $_POST['new_position_quantity'];
$new_position_shift = $_POST['new_position_shift'];
$new_position_facility = $_POST['new_position_facility'];
$new_position_station = $_POST['new_position_station'];

/*
echo $template_ID;
echo $new_position_ID;
echo $new_position_quantity;
echo $new_position_shift;
*/

//Check to see if a position is already entered, if so don't add it.



$sql_insert = "
INSERT INTO `".$db."`.`schedule_template_position_list` 
(ID_template, ID_schedule_position, quantity, facility, station) 
VALUES (".$template_ID.", ".$new_position_ID.", ".$new_position_quantity.", ".$new_position_facility.", ".$new_position_station.")";
//echo $sql_insert;
$link->query($sql_insert);

//Include database Termination Script
include 'db_disconnect.php';
?>