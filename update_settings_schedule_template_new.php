<?php
//Include database Connection Script
include 'db_connection.php';

$new_template_name = $_POST['new_template_name'];
$new_template_replication = $_POST['new_template_replication'];


//If variable are set then create a new schedule
if ( $new_template_name != "" )
{
//echo "variables are set";

//Requesting a blank schedule entry
if ($new_template_replication == "blank")
	{
		$sql_new_schedule =
		"
		INSERT INTO `".$db."`.`schedule_template`
		(ID, name) VALUES (NULL, '".$new_template_name."')
		";
		$link->query($sql_new_schedule);
	}
else
	{
	//Create the schedule_template.  Retrieve new AUTO_INCREMENT value
		$sql_new_schedule =
		"
		INSERT INTO `".$db."`.`schedule_template`
		(ID, name) VALUES (NULL, '".$new_template_name."')
		";
		$link->query($sql_new_schedule);
		$new_schedule_ID = $link->insert_id;
	$sql_replication = 
	"
	INSERT INTO schedule_template_position_list (ID_template, ID_schedule_position, quantity, shift) 
	SELECT ".$new_schedule_ID.", stpl.id_schedule_position, stpl.quantity, stpl.shift 
	FROM `".$db."`.`schedule_template_position_list` stpl 
	WHERE stpl.id_template = ".$new_template_replication;
	$link->query($sql_replication);
	}



}//end If



//Include database Termination Script
include 'db_disconnect.php';
?>