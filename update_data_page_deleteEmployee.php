<?php

//Include database Connection Script
include 'db_connection.php';

$employee_ID = $_POST['employee_ID'];


//Delete User From Employee Preferences 
$sql_delete_employee_preferences = 
"DELETE FROM `".$db."`.`employee_shift_preference` 
WHERE `employee_shift_preference`.`ID_employee` = ".$employee_ID;

$link->query($sql_delete_employee_preferences);

//Delete User From Employee Table
$sql_delete_employee = 
"DELETE FROM `".$db."`.`employee` 
WHERE `employee`.`ID` = ".$employee_ID;

$link->query($sql_delete_employee);


//Include database Termination Script
include 'db_disconnect.php';

?>
