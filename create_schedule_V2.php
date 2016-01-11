<?php

//Retrieve POST Values
//RETRIEVE SCHEDULE SETUP INFORMATION
//SCHEDULE TEMPLATE
//SCHEDULE DATE
$schedule_date = $_POST["schedule_date"];
$schedule_template = $_POST["schedule_template"];
//echo $schedule_date;
//echo $schedule_template;

//Include database Connection Script
include 'db_connection.php';
include 'schedule_functions.php';


//CREATE A SCHEDULE...


//INSERT NEW SCHEDULE ENTRY newSchedule($date, $id_template)
//SELECT NEW SCHEDULE_ID...
$sql_newSchedule = 
"
INSERT INTO `".$db."`.`schedule` (`ID`, `ID_template`, `date`) VALUES (NULL, '". $schedule_template ."', '". $schedule_date ."');
";
$link->query($sql_newSchedule);

$sql_newSchedule_ID = "SELECT LAST_INSERT_ID()";
$result_newSchedule_ID = $link->query($sql_newSchedule_ID);
$row_schedule_ID = $result_newSchedule_ID->fetch_assoc();
$schedule_ID = $row_schedule_ID['LAST_INSERT_ID()'];
//echo $schedule_ID;



//START A LOOP AND INSERT PEOPLE INTO THE SCHEDULE....IN ORDER OF SENORITY.
$sql_employee = 
'SELECT * 
FROM `'.$db.'`.`employee` e, `'.$db.'`.`employee_shift_preference` esp 
WHERE e.`ID` = esp.`ID_employee` AND  esp.`available` = 1';
//echo $sql_employee;//debugging
$result_employee = $link->query($sql_employee);
//echo $result_employee;
while ($employee = $result_employee->fetch_assoc())
{
//CHECK TO SEE IF SCHEUDLE IS FULL.  IF FULL THEN SCHEDULE IS DONE.
//By looking at the how many positions are needed for the schedule and comparing that with how many have been scheduled, we can figure out whether or not the schedule is full.
//$num_positions_needed SQL QUERY


//By looking at the how many positions are needed for the schedule($num_positions_needed) and comparing that with how many have been scheduled($num_saved_positions_scheduled), 
//we can figure out whether or not the schedule is full.  In the event that it is, we break out of the loop.
if (isScheduleFull($link, $db, $schedule_ID, $schedule_template))
{//
break;//Terminate the while loop.  Schedule composition is completed.
}
else//schedule said employee
{
//ELSE...put the person onto the schedule.
//RETRIEVE A PERSONS INFORMATION
$emp_id = $employee['ID'];
$emp_senority = $employee['senority'];
$emp_first_name = $employee['first_name'];
$emp_last_name = $employee['last_name'];
$emp_shift = $employee['shift'];
$emp_non_rotational = $employee['non_rotational'];
$emp_posted_position = $employee['posted_position'];
//convert shift_preference(text) -> (bool) Day = 0 | Night = 1;
$emp_shift_num;
$emp_shift_num_opposite;
if ($emp_shift == "day")
{
$emp_shift_num = 0;
$emp_shift_num_opposite = 1;
}
else
{
$emp_shift_num = 1;
$emp_shift_num_opposite = 0;
}

//debugging
//echo "</br> ".$emp_id.", ". $emp_senority . ", ". $emp_first_name .", ". $emp_last_name .", ". $emp_shift .", ". $emp_non_rotational .", ". $emp_posted_position;



//RETRIEVE AVAILABLE SPOT INFORMATION
//RETRIEVE PP_DS Is PP available on DS.
//RETRIEVE PP_NS is PP available on NS.
$PP_DS = calculatePP_DS($link, $db, $emp_posted_position, $schedule_template, $schedule_ID);
//echo "PP_DS: ".$PP_DS ."</br>";
$PP_NS = calculatePP_NS($link, $db, $emp_posted_position, $schedule_template, $schedule_ID);
//echo "PP_NS: ".$PP_NS ."</br>";
//RETRIEVE SP IS AVAILABLE OR NOT.  
$SP = calculateSP($link, $db, $emp_shift, $schedule_template, $schedule_ID);
//echo "SP: ".$SP ."</br></br>";



//IF CHECK THROUGH 5 IF SCENARIOS IF (PP_DS && PP_NS & SP) THEN....

if (  (($PP_DS == 1) && ($PP_NS == 1) && ($SP == 1)) || 
	  (($PP_DS == 1) && ($PP_NS == 1) && ($SP == 0)) ||
	  (($PP_DS == 1) && ($PP_NS == NULL) && ($SP == 1)) )
	  
	  {
	  //echo "FIRST CASE: ";
	  //Give the emp their posted position on their shift preference.
	  //echo $emp_first_name ." got to this point";
	  $job_id = findAvailablePP_withShiftPreference($link, $db, $emp_id, $emp_posted_position, $emp_shift, $schedule_template, $schedule_ID);
	  //echo "jobid = ".$job_id;
	  $sql_add_toSchedule = "INSERT INTO `".$db."`.`schedule_saved` (`ID_schedule`, `ID_schedule_position`, `ID_employee`, `shift`) VALUES ('".$schedule_ID."', '".$job_id."', '".$emp_id."', '".$emp_shift_num."')";
	  $link->query($sql_add_toSchedule);
	  }
	  
	  
elseif ( (($PP_DS == 1) && ($PP_NS == 0) && ($SP == 1)) ||
		(($PP_DS == 1) && ($PP_NS == 0) && ($SP == 0)) ||
		(($PP_DS == 1) && ($PP_NS == NULL) && ($SP == 0)) ||
		(($PP_DS == 1) && ($PP_NS == NULL) && ($SP == 1))   )
		
		{
		//echo "SECOND CASE: ";
		//Give emp $PP_DS
		$emp_shift = "day";//We know only Dayshift PP is available.  Set preference to that.
		$emp_shift_num = 0;//only day shift PP available.
		$job_id = findAvailablePP_withShiftPreference($link, $db, $emp_id, $emp_posted_position, $emp_shift, $schedule_template, $schedule_ID);
		//echo "jobid = ".$job_id;
		$sql_add_toSchedule = "INSERT INTO `".$db."`.`schedule_saved` (`ID_schedule`, `ID_schedule_position`, `ID_employee`, `shift`) VALUES ('".$schedule_ID."', '".$job_id."', '".$emp_id."', '".$emp_shift_num."')";
		$link->query($sql_add_toSchedule);
		}

		
		
elseif (( ($PP_DS == 0) && ($PP_NS == 1) && ($SP == 1)) ||
		( ($PP_DS == 0) && ($PP_NS == 1) && ($SP ==0)) ||
		( ($PP_DS == NULL) && ($PP_NS == 1) && ($SP == 0) ) ||
		( ($PP_DS == NULL) && ($PP_NS == 1) && ($SP == 1) )   )
		{
		//echo "THIRD CASE: ";
		//Give PP_NS
		$emp_shift = "night";//We know only Dayshift PP is available.  Set preference to that.
		$emp_shift_num = 1;//only day shift PP available.
		$job_id = findAvailablePP_withShiftPreference($link, $db, $emp_id, $emp_posted_position, $emp_shift, $schedule_template, $schedule_ID);
		//echo "jobid = ".$job_id;
		$sql_add_toSchedule = "INSERT INTO `".$db."`.`schedule_saved` (`ID_schedule`, `ID_schedule_position`, `ID_employee`, `shift`) VALUES ('".$schedule_ID."', '".$job_id."', '".$emp_id."', '".$emp_shift_num."')";
		$link->query($sql_add_toSchedule);
		}
		
	
elseif ( ($PP_DS == 0) && ($PP_NS == 0) && ($SP ==1)) 
		{
		//echo "FORTH CASE: ";
		//Give SP as a sorter
		$emp_posted_position = "Sorter";
		$job_id = findAvailablePP_withShiftPreference($link, $db, $emp_id, $emp_posted_position, $emp_shift, $schedule_template, $schedule_ID);
		//echo "jobid = ".$job_id;
		$sql_add_toSchedule = "INSERT INTO `".$db."`.`schedule_saved` (`ID_schedule`, `ID_schedule_position`, `ID_employee`, `shift`) VALUES ('".$schedule_ID."', '".$job_id."', '".$emp_id."', '".$emp_shift_num."')";
		$link->query($sql_add_toSchedule);
		}

		
//Maybe add another elseif....$PP_DS == 0) && ($PP_NS == 0) && ($SP ==0) && PP="Sorter"..then they don't make it onto the schedule or it would go to trained & capable positions.

elseif ( ($PP_DS == 0) && ($PP_NS == 0) && ($SP ==0)) 
		{
		//echo "SIXTH CASE: ";
		//Give SP as a sorter if there is a spot on the schedule
		//check if sorters PP is full.
		if ( !isSortersFull($link, $db, $schedule_template, $schedule_ID) )
		{
		$emp_posted_position = "Sorter";
		$job_id = findAvailablePP_withShiftPreference($link, $db, $emp_id, $emp_posted_position, $emp_shift_num_opposite, $schedule_template, $schedule_ID);
		//echo "jobid = ".$job_id;
		$sql_add_toSchedule = "INSERT INTO `".$db."`.`schedule_saved` (`ID_schedule`, `ID_schedule_position`, `ID_employee`, `shift`) VALUES ('".$schedule_ID."', '".$job_id."', '".$emp_id."', '".$emp_shift_num_opposite."')";
		$link->query($sql_add_toSchedule);
		}
		else
		{
		//echo "Sorry!  No more sorters allowed on the schedule";
		}

		}//end elseif SIXTH CASE
else
{//fail..what just happened?
echo "SEVENTH CASE: ??????How did it get here??????";
//go to the next person in the list.  
//The schedule probably had very little amount of positions and specific 
//posted position requirement.  
}		




}//end ELSE(scheduling of said employee).

}//END LOOP

echo "<p><b>The Schedule has been created</b>...Click on Schedule Page to view it.</p>";

//Include database Termination Script
include 'db_disconnect.php';
?>