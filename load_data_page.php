<?php

//Include database Connection Script
include 'db_connection.php';


//Loop Through Employees table 1 by 1 and add there information to the page.

//sql to select * from employees. sort by ID ASC
$sql = "SELECT * FROM `".$db."`.`employee`";
//echo $sql;
$sql_results = $link->query($sql);


//Loop through employees
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
$non_rotational = $row['non_rotational'];
$posted_position = $row['posted_position'];

/* DEBUGGING INFO
echo $id_employee;
echo $available;
echo $shift;
echo $posted_position;
*/


//echo "the data entry page information for the current user.
echo 
'

<div class="ui-grid-e">
			<div class="ui-block-a">
				<div class="ui-bar ui-bar-a " style="height:60px" >
				<a class="ui-shadow ui-btn ui-corner-all ui-icon-delete ui-btn-icon-notext ui-btn-inline" data-rel="popup" data-position-to="window" data-transition="pop" href="#popupDeleteEmployee" 
				onClick="update_delete_employee_popup('.$id_employee.', \'' .$first_name.'\' , \''.$last_name.'\')"></a>
				'. $first_name . ' '. $last_name .'
				</div>
			</div>
			
			
			<div class="ui-block-b">
				<div class="ui-bar ui-bar-a" style="height:60px">Shift</br>
					<select name="flip-shift" id="flip-shift" data-role="flipswitch" data-mini="true" onchange="update_shift('. $id .',this.value)">';
//if statement will setup the input field to be correct based of database entry.
//write shift
//if else to add "selected" 
//..repeat with available, posted position, and non rotational.
if ($shift == "day")
{
//PRINT YES CASE
echo 
'<option value="day" selected="">Day</option>
<option value="night">Night</option>';
}
else
{
//PRINT NO CASE
echo 
'<option value="day">Day</option>
<option value="night" selected="">Night</option>';
}

echo 
'				        
				    </select>
				</div>
			</div>
			
			
			<div class="ui-block-c">
				<div class="ui-bar ui-bar-a" style="height:60px">Available</br>
					<select name="flip-availability" id="flip-availability" data-role="flipswitch" data-mini="true" onchange="update_available('. $id .',this.value)">';
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
				
echo 
'				        
				    </select>
				</div>
			</div>
			

			<div class="ui-block-d">
				<div class="ui-bar ui-bar-a" style="height:60px">Posted Position</br>
					<select name="select-postedPosition" id="select-postedPosition" data-mini="true" data-inline="true" onchange="update_posted_position('. $id .',this.value)">';
					
//loop through posted positions and add to select input.  Add special case if for the selected value.
//sql Select all Posted Positions.  For use later in the loop.
$sql_posted_positions_list = 
'
SELECT DISTINCT `employee_shift_preference`.`posted_position`
FROM `'.$db.'`.`employee_shift_preference`
';
$sql_posted_positions_list_V2 = 
"
SELECT posted_position, name
FROM `".$db."`.`posted_positions`
";
echo $sql_posted_positions_list_V2;
$result_posted_positions_list_results = $link->query($sql_posted_positions_list_V2);

while ($row_posted_position_list = $result_posted_positions_list_results->fetch_assoc())
{
//load current posted position value.
$posted_position_list = $row_posted_position_list['posted_position'];
$posted_position_name = $row_posted_position_list['name'];
//Echo the entry.
echo '<option value="' . $posted_position_list . '" ';
//if selected make this the value.
if ($posted_position == $posted_position_list)
{
echo 'selected=""';
}

echo '>' . $posted_position_name . '</option>';
}
//end Loop		
	
echo '
					</select>
				</div>
			</div>
			
			
			<div class="ui-block-e">
				<div class="ui-bar ui-bar-a" style="height:60px">Non-Rotational</br>
					<select name="flip-nonrotational" id="flip-nonrotational" data-role="flipswitch" data-mini="true" onchange="update_non_rotational('. $id .',this.value)">';
if ($non_rotational == "1")
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


echo 
'					        
					</select>
				</div>
			</div>
			
		</div><!-- Grid e -->
';

//next person...
}//end while loop



//Include database Termination Script
include 'db_disconnect.php';

?>
