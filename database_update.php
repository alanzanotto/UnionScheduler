<?php

//Include database Connection Script
include 'db_connection.php';


//grab all people in the database...
$sql = 'SELECT * FROM `'.$db.'`.`employee_shift_preference`';
$result = $link->query($sql);

//cycle through people
while ($row = $result->fetch_assoc())
{
	//get id of user
	$user_ID = $row['ID_employee'];

	//get posted position.
	$posted_position  = $row['posted_position'];
	//lookup ID of posted position
	$sqlpp = 
	'SELECT * FROM `'.$db.'`.`posted_positions` WHERE posted_position = "'.$posted_position.'"';
	echo $sqlpp;
	$resultpp = $link->query($sqlpp);
	$objectpp = $resultpp->fetch_assoc();
	$ppID = $objectpp['ID'];
	//insert ID into pp2
	$sql_insert = 
	'
	UPDATE `'.$db.'`.`employee_shift_preference`
	SET posted_position2 = '.$ppID.'
	WHERE ID_employee = '.$user_ID;
	$link->query($sql_insert);
	
}
//end cycle




//Include database Termination Script
include 'db_disconnect.php';
?>