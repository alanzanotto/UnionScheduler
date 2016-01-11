<?php


//Retrieve POST Values
$user_id = $_POST["new_user_id"];
$posted_position_value = $_POST["new_posted_position_value"];

//Include database Connection Script
include 'db_connection.php';


//Write SQL to Perform Database Operation 
$sql = " 
UPDATE `".$db."`.`employee_shift_preference` SET `posted_position` = '". $posted_position_value ."' WHERE `employee_shift_preference`.`ID_employee` = ".$user_id;

$link->query($sql);


//Include database Termination Script
include 'db_disconnect.php';


?>