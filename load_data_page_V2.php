<?php

//Include database Connection Script
include 'db_connection.php';


//Loop Through Employees table 1 by 1 and add there information to the page.

//sql to select * from employees. sort by ID ASC
$sql = "SELECT * FROM `".$db."`.`employee`";
//echo $sql;
$sql_results = $link->query($sql);


//Loop through employees

//List Begin
echo 
'
<ul data-role="listview" data-inset="true"  data-filter="true" data-split-icon="delete" data-split-theme="a">';

while($row = $sql_results->fetch_assoc())
{
//load variables from current entry.
$id = $row['ID'];
$senority = $row['senority'];
$first_name = $row['first_name'];
$last_name = $row['last_name'];

/* DEBUGGING INFO
echo $id;
echo $senority;
echo $first_name;
echo $last_name;
*/

//Retrieve the shift preference settings for the user.
$sql_shift_preference = 
"
SELECT * 
FROM `".$db."`.`employee_shift_preference`
WHERE `employee_shift_preference`.`ID_employee` = ". $id;

$results_shift_preference_results = $link->query($sql_shift_preference);
$row = $results_shift_preference_results->fetch_assoc();

$id_employee = $row['ID_employee'];
$available = $row['available'];
$shift = $row['shift'];
$posted_position = $row['posted_position'];

/* DEBUGGING INFO
echo $id_employee;
echo $available;
echo $shift;
echo $posted_position;
*/



/*<div>
<a class="ui-shadow ui-btn ui-corner-all ui-icon-delete ui-btn-icon-notext ui-btn-inline" data-rel="popup" data-position-to="window" data-transition="pop" href="#popupDeleteEmployee" 
onClick="update_delete_employee_popup('.$id_employee.', \'' .$first_name.'\' , \''.$last_name.'\')"></a></div>
*/
echo 
'
<li><a>
<h2>'.$first_name.' '.$last_name.'</h2>';

echo '<div id="label_block"><p id="label_shift">Shift</p>';
echo '<p id="label_available">Available</p>';
echo '<p id="label_posted_position">Posted Position</p></div>';
//echo '<a href="#popupInfo" data-rel="popup" data-transition="pop" class="my-tooltip-btn ui-btn ui-alt-icon ui-nodisc-icon ui-btn-inline ui-icon-info ui-btn-icon-notext" title="Learn more">Learn more</a>';

//************************SHIFT**************************
echo '<select name="flip-shift" id="flip-shift" data-role="flipswitch" data-mini="true" onchange="update_shift('. $id .',this.value)">';
//if statement will setup the input field to be correct based of database entry.
//write shift
//if else to add "selected" 
//..repeat with available, posted position, and non rotational.
if ($shift == 0)
{
//PRINT YES CASE
echo 
'<option value="0" selected="">Day</option>
<option value="1">Night</option>';
}
else
{
//PRINT NO CASE
echo 
'<option value="0">Day</option>
<option value="1" selected="">Night</option>';
}
echo '</select>';
//************************SHIFT END**********************





//************************AVAILABLE**********************
echo '<select name="flip-availability" id="flip-availability" data-role="flipswitch" data-mini="true" onchange="update_available('. $id .',this.value)">';
if ($available == "1")
{
//PRINT YES CASE
echo 
'
<option value="0">No</option>
<option value="1" selected="">Yes</option>
';
}
else
{
//PRINT NO CASE
echo 
'
<option value="0" selected="">No</option>
<option value="1">Yes</option>
';
}					
				
echo '</select>';

//************************AVAILABLE END******************










//************************POSTED POSITION****************
echo '<select name="select-postedPosition" id="select-postedPosition" data-mini="true" data-inline="true" onchange="update_posted_position('. $id .',this.value)">';
					
//loop through posted positions and add to select input.  Add special case if for the selected value.
//sql Select all Posted Positions.  For use later in the loop.

$sql_posted_positions_list_V2 = "SELECT * FROM `".$db."`.`posted_positions`";

//echo $sql_posted_positions_list_V2;
$result_posted_positions_list_results = $link->query($sql_posted_positions_list_V2);

while ($row_posted_position_list = $result_posted_positions_list_results->fetch_assoc())
{
//load current posted position value.
$posted_position_ID = $row_posted_position_list['ID'];
$posted_position_name = $row_posted_position_list['name'];
//Echo the entry.
echo '<option value="' . $posted_position_ID . '" ';
//if selected make this the value.
if ($posted_position == $posted_position_ID)
{
echo 'selected=""';
}

echo '>' . $posted_position_name . '</option>';
}
//end Loop		
	
echo '</select>';
//************************POSTED POSITION END************





echo '
</p>
</a>
<a data-rel="popup" data-position-to="window" data-transition="pop" href="#popupDeleteEmployee"  onClick="update_delete_employee_popup('.$id_employee.', \'' .$first_name.'\' , \''.$last_name.'\')">Delete User</a>
</li>
';


}//end while loop

echo '</ul>';

//Include database Termination Script
include 'db_disconnect.php';

?>
