<?php
//Include database Connection Script
include 'db_connection.php';


//Retrieve POST values
$new_position_name = $_POST["new_position_name"];
$new_position_PP_ID = $_POST['new_position_PP_ID'];
$new_position_NR = $_POST['new_position_NR'];


if($new_position_name != "")
{
//INSERT query
$sql_add_position =
'
INSERT INTO `'.$db.'`.`schedule_position` (`ID`, `ID_posted_position_requirement`, `name`, `non_rotational`) 
VALUES (NULL, "'.$new_position_PP_ID.'", "'.$new_position_name.'", "'.$new_position_NR.'");
';
//FIRE
$link->query($sql_add_position);
}

//Include database Termination Script
include 'db_disconnect.php';
?>