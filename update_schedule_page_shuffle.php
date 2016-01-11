<?php
//Include database Connection Script
include 'db_connection.php';

//Retrieve POST Values
$schedule_ID = $_POST["schedule_ID"];
//$schedule_ID = 9;


//Get Template ID from schedule_ID
$sql_template_ID = 
"SELECT * FROM `".$db."`.`schedule` WHERE ID = ".$schedule_ID;
$result_template_ID = $link->query($sql_template_ID);
$object_template_ID = $result_template_ID->fetch_assoc();
$schedule_template_ID = $object_template_ID['ID_template'];

//*********************************************
//SETUP DAY ARRAY 
//*********************************************
$position_array_day = array();
//get sorter positions IDS
//get quantity of sorter positions
//create array of positions.

$sql_scheduled_employees = 
"
SELECT *
FROM `".$db."`.`schedule_saved`
WHERE ID_schedule = ".$schedule_ID."
AND shift = 0";
//go through day employees and create array.
//echo $sql_scheduled_employees;
$result_scheduled_employees = $link->query($sql_scheduled_employees);
while ($row = $result_scheduled_employees->fetch_assoc())
{
//Get the position ID
$position_ID = $row['ID_schedule_position'];

//Check to see if position ID is of sorter.
$sql_sorter_positions = 
"
SELECT * 
FROM `".$db."`.`schedule_position`
WHERE ID = ".$position_ID;
$result_sorter_positions = $link->query($sql_sorter_positions);
$object_sorter_positions = $result_sorter_positions->fetch_assoc();
$position_pp_requirement = $object_sorter_positions['ID_posted_position_requirement'];
$position_pp_on_call_exception = $object_sorter_positions['station'];
//18 is a sorter pp id.  so if it is a sorter, get the quantity and add to array.
//NOTE 12 and 4 is a preset value in station table.  This value has to change for each setup. 
//12 = ON CALL (WINFIELD)
// 4 = ON CALL (OLIVER)
if ($position_pp_requirement == 18 && $position_pp_on_call_exception != 12)
{
//echo $position_quantity;
array_push($position_array_day, $position_ID);

}//if ($position_pp_requirement == 18)
}//while ($row = $sql_scheduled_employees->fetch_assoc())
//echo "array day";
print_r(array_values($position_array_day));
//*********************************************
//SETUP DAY ARRAY END
//*********************************************




//*********************************************
//SETUP NIGHT ARRAY 
//*********************************************
$position_array_night = array();
//get sorter positions IDS
//get quantity of sorter positions
//create array of positions.

$sql_scheduled_employees = 
"
SELECT *
FROM `".$db."`.`schedule_saved`
WHERE ID_schedule = ".$schedule_ID."
AND shift = 1";
$result_scheduled_employees = $link->query($sql_scheduled_employees);
//go through day employees and create array.
while ($row = $result_scheduled_employees->fetch_assoc())
{
//Get the position ID
$position_ID = $row['ID_schedule_position'];

//Check to see if position ID is of sorter.
$sql_sorter_positions = 
"
SELECT * 
FROM `".$db."`.`schedule_position`
WHERE ID = ".$position_ID;
$result_sorter_positions = $link->query($sql_sorter_positions);
$object_sorter_positions = $result_sorter_positions->fetch_assoc();
$position_pp_requirement = $object_sorter_positions['ID_posted_position_requirement'];
$position_pp_on_call_exception = $object_sorter_positions['station'];
//18 is a sorter pp id.  so if it is a sorter, get the quantity and add to array.
//NOTE 12 and 4 is a preset value in station table.  This value has to change for each setup. 
//12 = ON CALL (WINFIELD)
// 4 = ON CALL (OLIVER)
if ($position_pp_requirement == 18 && $position_pp_on_call_exception != 12)
{
//add some fields to the array.
array_push($position_array_night, $position_ID);
}//if ($position_pp_requirement == 18)
}//while ($row = $sql_scheduled_employees->fetch_assoc())
//echo "night array";
//print_r(array_values($position_array_night));


//*********************************************
//SETUP NIGHT ARRAY END
//*********************************************




//shuffle them.
//1. Select the people w/ RAND()
//2. Delete the people from 1.
//3. Add back into table with a new job id.
//4. repeate for night shift.

//1.
$sql_employees_rand = 
"
SELECT * 
FROM `".$db."`.`schedule_saved` ss, `".$db."`.`schedule_position` sp
WHERE ss.ID_schedule_position = sp.ID
AND ID_schedule = ".$schedule_ID."
AND sp.ID_posted_position_requirement = 18
AND sp.station != 12
AND ss.shift = 0
ORDER BY RAND()
";
$result_employees_rand = $link->query($sql_employees_rand);
// 2 & 3.  Looping & deleting
$temp = 0;
while ($row = $result_employees_rand->fetch_assoc() )
{
$employee_ID = $row['ID_employee'];
$employee_schedule_position = $row['ID_schedule_position'];

$sql_delete_person =
"
DELETE FROM `".$db."`.`schedule_saved`
WHERE ID_schedule = ".$schedule_ID."
AND ID_employee = ".$employee_ID."
AND shift = 0
";
$link->query($sql_delete_person);

$sql_insert_person = 
"
INSERT INTO `".$db."`.`schedule_saved`(`ID_schedule`, `ID_schedule_position`, `ID_employee`, `shift`) 
VALUES (".$schedule_ID." , ".$position_array_day[$temp]." , ".$employee_ID." , 0)";
$link->query($sql_insert_person);
//echo $sql_insert_person;
$temp++;
}


//4.  do the same for night.
//1.
$sql_employees_rand = 
"
SELECT * 
FROM `".$db."`.`schedule_saved` ss, `".$db."`.`schedule_position` sp
WHERE ss.ID_schedule_position = sp.ID
AND ID_schedule = ".$schedule_ID."
AND sp.ID_posted_position_requirement = 18
AND sp.station != 12
AND ss.shift = 1
ORDER BY RAND()
";
$result_employees_rand = $link->query($sql_employees_rand);
// 2 & 3.  Looping & deleting
$temp = 0;
while ($row = $result_employees_rand->fetch_assoc() )
{
$employee_ID = $row['ID_employee'];
$employee_schedule_position = $row['ID_schedule_position'];

$sql_delete_person =
"
DELETE FROM `".$db."`.`schedule_saved`
WHERE ID_schedule = ".$schedule_ID."
AND ID_employee = ".$employee_ID."
AND shift = 1
";
$link->query($sql_delete_person);

$sql_insert_person = 
"
INSERT INTO `".$db."`.`schedule_saved`(`ID_schedule`, `ID_schedule_position`, `ID_employee`, `shift`) 
VALUES (".$schedule_ID." , ".$position_array_day[$temp]." , ".$employee_ID." , 1)";
$link->query($sql_insert_person);

$temp++;
}

//echo "got done";


//Include database Termination Script
include 'db_disconnect.php';
?>
