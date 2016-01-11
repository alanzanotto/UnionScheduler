<?php
//Include database Connection Script
include 'db_connection.php';
include 'myClasses.php';
//Retrieve POST Values
$new_schedule_value = $_POST["new_schedule_value"];

$mySchedule = new Schedule();
//$mySchedule->display_schedule_winfield_layout($new_schedule_value);

$mySchedule->generateExcelSchedule($new_schedule_value);

//Include database Termination Script
include 'db_disconnect.php';

?>