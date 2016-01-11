<?php
//Include database Connection Script
include 'db_connection.php';

//retrive post variables
$template_ID = $_POST['template_ID'];
$position_ID = $_POST['position_ID'];
$new_position_quantity = $_POST['new_position_quantity'];
//$new_position_shift = $_POST['new_position_shift'];
$new_position_facility = $_POST['new_position_facility'];
$new_position_station = $_POST['new_position_station'];

/*
echo $template_ID;
echo $position_ID;
echo $new_position_quantity;
echo $new_position_shift;
*/

$sql_update = '
UPDATE `'.$db.'`.`schedule_template_position_list` 
SET quantity = '.$new_position_quantity.', facility = '.$new_position_facility.', station = '.$new_position_station.' 
WHERE ID = '.$position_ID;
echo $sql_update;
$link->query($sql_update);

//Include database Termination Script
include 'db_disconnect.php';
?>