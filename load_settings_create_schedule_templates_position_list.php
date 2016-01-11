<?php
//Include database Connection Script
include 'db_connection.php';
include 'myClasses.php';

//Retrieve POST Values
$template_ID = $_POST["requested_template_ID"];

if ( $template_ID == "" )
{
echo "Please Select a template to display...";
}
else
{
//echo "The template ID is set to: " . $template_ID;


//DISPLAY list view for a particular template
$mySchedule = new Schedule;
echo $mySchedule->list_view_scheduled_template($template_ID);



//DISPLAY new Popup
echo $mySchedule->popup_new_position_scheduled_template();



//DISPLAY edit popup 
echo $mySchedule->popup_edit_position_scheduled_template();


}//else




//Include database Termination Script
include 'db_disconnect.php';
?>