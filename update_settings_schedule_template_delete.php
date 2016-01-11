<?php
//Include database Connection Script
include 'db_connection.php';
include 'myClasses.php';

$template_ID = $_POST['template_ID'];

$mySchedule = new Schedule();
$mySchedule->schedule_template_delete($template_ID);


//Include database Termination Script
include 'db_disconnect.php';
?>