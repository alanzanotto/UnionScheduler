<?php
//Include database Connection Script
include 'db_connection.php';
include 'myClasses.php';

$mySchedule = new Schedule();
echo $mySchedule->load_schedule_setup_page2();


//Include database Termination Script
include 'db_disconnect.php';
?>