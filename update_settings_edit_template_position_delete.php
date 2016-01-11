<?php
//Include database Connection Script
include 'db_connection.php';

//retrive post variables
$template_ID = $_POST['template_ID'];
$position_ID = $_POST['position_ID'];
$position_shift = $_POST['position_shift'];
$position_quantity = $_POST['position_quantity'];


//delete the row
$sql_delete_row = 
"
DELETE FROM `".$db."`.`schedule_template_position_list`
WHERE ID = ".$position_ID;
$link->query($sql_delete_row);






//Include database Termination Script
include 'db_disconnect.php';
?>