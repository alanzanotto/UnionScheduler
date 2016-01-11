<?php
//Include database Connection Script
include 'db_connection.php';


//PART 1 --- FORM
//echo the start of the form and the text box entry
echo 
'
<form id="settings_positions_page_form" action="submit_new_position()">
<div class="ui-field-contain">
<label for="text-name">Position Name:</label>
	<input type="text" name="text-name" id="text-name" value="">
</div>';

//echo out the PP R list.
$sql_pp_r = 
'
SELECT ID, name
FROM `'.$db.'`.`posted_positions`
';
$result_pp_r = $link->query($sql_pp_r);


echo '
<div class="ui-field-contain">
    <label for="select-pp_id">Posted Position Requirement:</label>
    <select name="select-pp_id" id="select-pp_id">
        <option value="select">Select</option>';

//PP
while ($row = $result_pp_r->fetch_assoc())
{
$PP_ID = $row['ID'];
$PP_name = $row['name'];
echo '<option value="'.$PP_ID.'">'.$PP_name.'</option>';
}


echo '
    </select>
</div>
';
	
echo '
<div class="ui-field-contain">
<label for="slider-non_rotational">Non Rotational:</label>
<select name="slider2" id="slider-non_rotational" data-role="slider">
    <option value="0">No</option>
    <option value="1">Yes</option>
</select>
</div>
	<a href="#" class="ui-shadow ui-btn ui-corner-all" onclick="submit_new_position(document.getElementById(\'text-name\').value, document.getElementById(\'select-pp_id\').value, document.getElementById(\'slider-non_rotational\').value)">Submit Form</a>
	</form>
';



























//PART 2 --- LIST


//select all the current schedule_positions in the database.
$sql_all_schedule_positions = 
"
SELECT *
FROM `".$db."`.`schedule_position`
ORDER BY  `ID` ASC
";
$result_all_schedule_positions = $link->query($sql_all_schedule_positions);

//display all the current schedule_positions in the database.



echo 
'
<table data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke">
<thead>
<tr>
<th data-priority="3">ID</th>
<th data-priority="2">Posted Position Requirement</th>
<th data-priority="1">Name</th>
<th data-priority="4">Non Rotational</th>
</tr>
</thead>
<tbody>';


//output the results
while ($row = $result_all_schedule_positions->fetch_assoc())
{
//Assign variables
$schedule_position_ID = $row['ID'];
$schedule_position_ID_posted_position_requirement = $row['ID_posted_position_requirement'];
$schedule_position_name = $row['name'];
$schedule_position_non_rotational = $row['non_rotational'];
$schedule_position_non_rotational_english;

if ($schedule_position_non_rotational == 0)
$schedule_position_non_rotational_english = "No";
else
$schedule_position_non_rotational_english = "Yes";

//retrieve posted position name.
$sql_posted_position_name = '
SELECT name
FROM `'.$db.'`.`posted_positions`
WHERE ID = '.$schedule_position_ID_posted_position_requirement;
$result_posted_position_name = $link->query($sql_posted_position_name);
$object_posted_position_name = $result_posted_position_name->fetch_assoc();
$schedule_position_PP_name = $object_posted_position_name['name'];


echo 
'
<tr>
<th>'.$schedule_position_ID.'</th>
<td>'.$schedule_position_PP_name.'</td>
<td>'.$schedule_position_name.'</td>
<td>'.$schedule_position_non_rotational_english.'</td>
</tr>
';

}

echo '
     </tbody>
   </table>
';




//Include database Termination Script
include 'db_disconnect.php';
?>
