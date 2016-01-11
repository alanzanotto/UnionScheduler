<?php

//Include database Connection Script
include 'db_connection.php';


//div1
//SELECT all schedules created.  order by newest on top.
//make drop down menu.  value = schedule id, name/text = date of schedule.

$sql_created_schedules = 
"
SELECT ID, date
FROM `".$db."`.`schedule`
ORDER BY ID DESC 
LIMIT 15
";
$result_created_schedule = $link->query($sql_created_schedules);

//CHOOSE SCHEDULE
echo 
'
<div id="schedule_page_content_1">
<select name="select-choice-schedule_ID" id="select-choice-schedule_ID" onchange="update_displayed_schedule(this.value)">
<option value="">Select Schedule:</option>
';
//fill in the select
while ($row = $result_created_schedule->fetch_assoc())
{
$cs_ID = $row['ID'];
$cs_date = $row['date'];
echo 
'<option value="'.$cs_ID.'">'.$cs_date.'</option>';
}

echo 
'
</select>
</div>
';	

	
//div2.
//will be placeholder for all the schedule information that is retrieved.
//get_schedule.php [schedule_id]
//this script will simply just print all the users names from 
//schedule_saved where schedule_id = [schedule_id]
echo 
'
<div id="schedule_page_content_4">
	
</div>

';




//Include database Termination Script
include 'db_disconnect.php';

?>
