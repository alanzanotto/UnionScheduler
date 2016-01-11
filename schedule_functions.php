<?php

//Returns 0 or 1. 0 if PP is not available on Dayshift. 1 if it is.
function calculatePP_DS($link, $db, $emp_posted_position, $template_ID, $schedule_ID)
{

$pp_id = $emp_posted_position;//$pp_id is the posted position ID. 




//can alter this sql to use * and remove count, this will display the actual row entries. However this is not really needed for this function.
$sql_PP_DS_saved = "
SELECT count(sp.id_posted_position_requirement) total
FROM `".$db."`.`schedule_position` sp,
`".$db."`.`schedule_saved` ss
WHERE ss.id_schedule_position = sp.id 
AND ss.id_schedule = ".$schedule_ID ."
AND sp.id_posted_position_requirement = ". $pp_id ."
AND ss.shift = 0";
//echo "HERE IS THE PROBLEM::::::".$sql_PP_DS_saved;
$result_PP_DS_saved = $link->query($sql_PP_DS_saved);
$object_PP_DS_saved = $result_PP_DS_saved->fetch_assoc();
$PP_DS_saved = $object_PP_DS_saved['total'];



$sql_PP_DS_quantity = 
"
SELECT sum(quantity) quantity
FROM `".$db."`.`schedule_template_position_list_auto` stpl,
`".$db."`.`schedule_position`sp
WHERE 
stpl.id_schedule_position = sp.id 
AND stpl.id_template = ".$template_ID ." 
AND stpl.shift = 0 
AND sp.id_posted_position_requirement = ".$pp_id;
$result_PP_DS_quantity = $link->query($sql_PP_DS_quantity);
$object_PP_DS_quantity = $result_PP_DS_quantity->fetch_assoc();
$PP_DS_quantity = $object_PP_DS_quantity['quantity'];//pp_DS_quantity is the sum of jobs, that require said positions, that are required for night shift.


if ($PP_DS_saved < $PP_DS_quantity)
{
	return 1;//There is an available PP spot on Day Shift
}
else
{
	return 0;//THere is NOT and available PP spot on Day Shift
}
}//function calculatePP_DS($emp_posted_position)







//Retruns 0 or 1. 0 if PP is not available on Nightshift. 1 if it is.
function calculatePP_NS($link, $db, $emp_posted_position, $template_ID, $schedule_ID)
{

$pp_id = $emp_posted_position;//$pp_id is the posted position ID. 




//can alter this sql to use * and remove count, this will display the actual row entries. However this is not really needed for this function.
$sql_PP_NS_saved = "
SELECT count(sp.id_posted_position_requirement) total
FROM `".$db."`.`schedule_position` sp,
`".$db."`.`schedule_saved` ss
WHERE ss.id_schedule_position = sp.id 
AND ss.id_schedule = ".$schedule_ID ."
AND sp.id_posted_position_requirement = ". $pp_id ."
AND ss.shift = 1";
$result_PP_NS_saved = $link->query($sql_PP_NS_saved);
$object_PP_NS_saved = $result_PP_NS_saved->fetch_assoc();
$PP_NS_saved = $object_PP_NS_saved['total'];



$sql_PP_NS_quantity = 
"
SELECT sum(quantity) quantity
FROM `".$db."`.`schedule_template_position_list_auto` stpl,
`".$db."`.`schedule_position`sp
WHERE 
stpl.id_schedule_position = sp.id 
AND stpl.id_template = ".$template_ID ." 
AND stpl.shift = 1 
AND sp.id_posted_position_requirement = ".$pp_id;
$result_PP_NS_quantity = $link->query($sql_PP_NS_quantity);
$object_PP_NS_quantity = $result_PP_NS_quantity->fetch_assoc();
$PP_NS_quantity = $object_PP_NS_quantity['quantity'];//pp_ds_quantity is the sum of jobs, that require said positions, that are required for Night shift.
//echo "ECHO NS_QUANTITY: ".$PP_NS_quantity;
//echo "NS_Saved: ".$PP_NS_saved. " NS_QUANTITY: " .$PP_NS_quantity;

if ($PP_NS_quantity == NULL)
{
	return NULL;
}
else if ($PP_NS_saved < $PP_NS_quantity)
{
	return 1;//There is an available PP spot on Night Shift
}
else
{
	return 0;//THere is NOT and available PP spot on Night Shift
}
}//function calculatePP_NS($emp_posted_position)







//Retruns 0 or 1.  0 if there is no spot available on SP, 1 if there is a spot available on SP.  The Spot looks at sorter positions.
function calculateSP($link, $db, $emp_shift_preference, $template_ID, $schedule_ID)
{


//see how many sorters have been put on the schedule
$sql_SP_saved = "
SELECT count(sp.id_posted_position_requirement) total
FROM `".$db."`.`schedule_position` sp,
`".$db."`.`schedule_saved` ss
WHERE ss.id_schedule_position = sp.id 
AND ss.id_schedule = ".$schedule_ID ."
AND sp.id_posted_position_requirement = 18
AND ss.shift = ".$emp_shift_preference;
//echo $sql_SP_saved;
$result_SP_saved = $link->query($sql_SP_saved);
$object_SP_saved = $result_SP_saved->fetch_assoc();
$SP_saved = $object_SP_saved['total'];


//see how many sorters are allowed onto the schedule.
$sql_SP_quantity = 
"
SELECT sum(quantity) quantity
FROM `".$db."`.`schedule_template_position_list_auto` stpl,
`".$db."`.`schedule_position`sp
WHERE 
stpl.id_schedule_position = sp.id 
AND stpl.id_template = ".$template_ID ." 
AND stpl.shift = ". $emp_shift_preference ." 
AND sp.id_posted_position_requirement = 18";
$result_SP_quantity = $link->query($sql_SP_quantity);
$object_SP_quantity = $result_SP_quantity->fetch_assoc();
$SP_quantity = $object_SP_quantity['quantity'];


//if the amount of sorters currently on the schedule is less than the amount of sorters allowed.  Then SP = 1.  So there shift preference is then available.
if ($SP_saved < $SP_quantity)
	{
		return 1;//there is a spot available.
	}
	else
	{
		return 0;//there is not a spot available.
	}
	
}//function calculateSP($emp_posted_position)






//Retruns 0 or 1.  0 if there is no spot available on SP, 1 if there is a spot available on SP.  The Spot looks at sorter positions.
function isSortersFull($link, $db, $template_ID, $schedule_ID)
{

//see how many sorters have been put on the schedule
$sql_sorters_saved = "
SELECT count(sp.id_posted_position_requirement) total
FROM `".$db."`.`schedule_position` sp,
`".$db."`.`schedule_saved` ss
WHERE ss.id_schedule_position = sp.id 
AND ss.id_schedule = ".$schedule_ID ."
AND sp.id_posted_position_requirement = 18";
//echo $sql_SP_saved;
$result_sorters_saved = $link->query($sql_sorters_saved);
$object_sorters_saved = $result_sorters_saved->fetch_assoc();
$sorters_saved = $object_sorters_saved['total'];


//see how many sorters are allowed onto the schedule.
$sql_sorters_quantity = 
"
SELECT sum(quantity) quantity
FROM `".$db."`.`schedule_template_position_list_auto` stpl,
`".$db."`.`schedule_position`sp
WHERE 
stpl.id_schedule_position = sp.id 
AND stpl.id_template = ".$template_ID ."  
AND sp.id_posted_position_requirement = 18";
$result_sorters_quantity = $link->query($sql_sorters_quantity);
$object_sorters_quantity = $result_sorters_quantity->fetch_assoc();
$sorters_quantity = $object_sorters_quantity['quantity'];

//echo "sorters saved = " .$sorters_saved ;
//echo " | sorters quantity = " .$sorters_quantity;

//if the amount of sorters currently on the schedule is equivalent to the amount of sorters allowed, then isSortersFull = 1 (true).
if ( ($sorters_saved == $sorters_quantity) || $sorters_quantity == "")
	{
		
		return true;//the amount of sorters on the schedule HAS been reached.
	}
	else
	{
		return false;//the amount of sorters on the schedule has NOT been reached.
	}
	
}//isSortersFull($link, $template_ID, $schedule_ID)





//finds a job_id in the stpl_auto table and returns that ID.  This is basically what a person will be scheduled as.
function findAvailablePP_withShiftPreference($emp_id, $emp_posted_position, $emp_shift_preference, $template_ID, $schedule_ID)
{
//Include database Connection Script
include 'db_connection.php';



$sql = "
SELECT stpla.ID, stpla.ID_template, stpla.ID_schedule_position, stpla.quantity,
stpla.shift, stpla.facility, stpla.station, stpla.scheduled, 
sp.ID_posted_position_requirement, sp.name
FROM `".$db."`.`schedule_template_position_list_auto` stpla,
`".$db."`.`schedule_position` sp
WHERE stpla.ID_template = ".$template_ID ."
AND stpla.ID_schedule_position = sp.ID
AND sp.ID_posted_position_requirement = ".$emp_posted_position. "
AND stpla.shift = ". $emp_shift_preference. "
AND stpla.scheduled = 0
";
//echo $sql;
$result = $link->query($sql);
$object = $result->fetch_assoc();


return $object['ID'];
//Include database Termination Script
include 'db_disconnect.php';
}


//takes a job_id and saves it into the schedule and sets that position to be unavailable for the next person.(scheduled column in stpl_auto, 0=unscheduled, 1=scheduled)
function schedule_a_person($schedule_ID, $template_ID, $job_id, $emp_id, $emp_shift, $job_facility, $job_station)
{
//Include database Connection Script
include 'db_connection.php';


//grab the ID_schedule_postion
$ID_schedule_position = findIDSchedulePosition($job_id);

//insert it to schedule saved.
$sql_add_toSchedule = "INSERT INTO `".$db."`.`schedule_saved` (`ID_schedule`, `ID_schedule_position`, `ID_employee`, `shift`, `facility`, `station`) VALUES (".$schedule_ID.", ".$ID_schedule_position.", ".$emp_id.", ".$emp_shift.", ".$job_facility. ", ". $job_station.")";
$link->query($sql_add_toSchedule);

//UPDATE stpl_auto to have this job_id scheduled column set to 1 so it wont be used anymore for other people.
$sql_hide_from_auto = "
UPDATE `".$db."`.`schedule_template_position_list_auto`
SET scheduled = 1
WHERE ID = ".$job_id;
$link->query($sql_hide_from_auto);

//Include database Termination Script
include 'db_disconnect.php';
}


function findFacility($job_id)
{
//Include database Connection Script
include 'db_connection.php';

$sql = '
SELECT *
FROM `'.$db.'`.`schedule_template_position_list_auto`
WHERE ID = '. $job_id;
$result = $link->query($sql);
$object = $result->fetch_assoc();
$facility_id = $object['facility'];


//Include database Termination Script
include 'db_disconnect.php';
return $facility_id;
}




function findStation($job_id)
{
//Include database Connection Script
include 'db_connection.php';

$sql = '
SELECT *
FROM `'.$db.'`.`schedule_template_position_list_auto`
WHERE ID = '. $job_id;
$result = $link->query($sql);
$object = $result->fetch_assoc();
$station_id = $object['station'];

//Include database Termination Script
include 'db_disconnect.php';
return $station_id;
}


function findIDSchedulePosition($job_id)
{
//Include database Connection Script
include 'db_connection.php';

$sql = '
SELECT *
FROM `'.$db.'`.`schedule_template_position_list_auto`
WHERE ID = '. $job_id;
$result = $link->query($sql);
$object = $result->fetch_assoc();
$ID_schedule_position = $object['ID_schedule_position'];

//Include database Termination Script
include 'db_disconnect.php';
return $ID_schedule_position;
}


function isScheduleFull($link, $db, $schedule_ID, $template_ID)
{
//CHECK TO SEE IF SCHEUDLE IS FULL.  IF FULL THEN SCHEDULE IS DONE.
//By looking at the how many positions are needed for the schedule and comparing that with how many have been scheduled, we can figure out whether or not the schedule is full.
//$num_positions_needed SQL QUERY
$sql_num_positions_needed = 
"
SELECT SUM(quantity) num_positions_needed FROM `".$db."`.`schedule_template_position_list_auto` WHERE id_template = ".$template_ID;
$result_num_positions_needed = $link->query($sql_num_positions_needed);
//echo $sql_num_positions_needed;
$object_num_positions_needed = $result_num_positions_needed->fetch_assoc();
$num_positions_needed = $object_num_positions_needed['num_positions_needed'];
//echo " number of positoins needed: " .$num_positions_needed;

//num of positions in the schedule
$sql_num_saved_positions_scheduled = 
"
SELECT COUNT(ID_schedule) num_saved_positions_scheduled 
FROM `".$db."`.`schedule_saved`
WHERE `schedule_saved`.`ID_schedule` = ". $schedule_ID;
$result_num_saved_positions_scheduled = $link->query($sql_num_saved_positions_scheduled);
$object_num_saved_positions_scheduled = $result_num_saved_positions_scheduled->fetch_assoc();
$num_saved_positions_scheduled = $object_num_saved_positions_scheduled['num_saved_positions_scheduled'];
//echo "  num_saved_positions_scheduled: ".$num_saved_positions_scheduled;

if ($num_saved_positions_scheduled < $num_positions_needed)
	{
		return 0;//Scheudle is not full.
	}
	else
	{
		return 1;//Scheudle is full.
	}

}//function isScheduleFull($schedule_ID, $template_ID)



?>