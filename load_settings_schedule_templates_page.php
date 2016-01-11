<?php
//Include database Connection Script
include 'db_connection.php';
include 'myClasses.php';


$mySchedule = new Schedule();

echo $mySchedule->popup_new_template();

echo $mySchedule->list_view_templates();

echo $mySchedule->popup_edit_template();



//Include database Termination Script
include 'db_disconnect.php';
?>