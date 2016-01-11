<?php
//Include database Connection Script
include 'db_connection.php';



//select all the current schedule_positions in the database.
$sql_all_schedule_positions = 
"
SELECT *
FROM `".$db."`.`schedule_position`
ORDER BY  `ID` ASC
";
$result_all_schedule_positions = $link->query($sql_all_schedule_positions);


//Setup Posted Position Requirement Index.	
$sql_posted_positions_list_V2 = 
"
SELECT *
FROM `".$db."`.`posted_positions`
";
$result_posted_positions_list_V2 = $link->query($sql_posted_positions_list_V2);
$result_posted_positions_list_V2_1 = $link->query($sql_posted_positions_list_V2);




//display all the current schedule_positions in the database.
//output the results
echo 
'<ul data-role="listview">';

while ($row = $result_all_schedule_positions->fetch_assoc())
{
//Assign variables
$schedule_position_ID = $row['ID'];
$schedule_position_ID_posted_position_requirement = $row['ID_posted_position_requirement'];
$schedule_position_name = $row['name'];

//retrieve posted position name.
$sql_posted_position_name = '
SELECT name
FROM `'.$db.'`.`posted_positions`
WHERE ID = '.$schedule_position_ID_posted_position_requirement;
$result_posted_position_name = $link->query($sql_posted_position_name);
$object_posted_position_name = $result_posted_position_name->fetch_assoc();
$schedule_position_PP_name = $object_posted_position_name['name'];

echo '
	<li>
		<a href="#editPopupSchedulePosition" 
			onClick="update_edit_popup_schedule_position('.$schedule_position_ID.', \''.$schedule_position_name.'\', '.$schedule_position_ID_posted_position_requirement.')" 
			class="ui-icon-edit" data-rel="popup" 
			data-position-to="window" 
			data-transition="pop">'.$schedule_position_name.'
		</a>
	</li>';

}
echo '	</ul>';




//Echo the NEW POSITION POPUP	

echo '
	<div data-role="popup" id="newPopupSchedulePosition" data-theme="a" class="ui-corner-all">
    <form>
		<div style="padding: 5px 10px;">
		
		<h3 id="newPopupNewSchedulePositionHeader">New Position:<label></label></h3>
		
		<input type="text" name="text-basic" id="newPopupSchedulePositionName" value="" placeholder="Position Name (Optional Description)">

		<select name="select-choice-mini" id="newPopupSchedulePositionPPRequirement" data-mini="true" data-inline="true">
		<option value="">Posted Position</option>';

while ($row_posted_position_list = $result_posted_positions_list_V2->fetch_assoc())
{
//load current posted position value.
$posted_position_ID = $row_posted_position_list['ID'];
$posted_position_list = $row_posted_position_list['posted_position'];
$posted_position_name = $row_posted_position_list['name'];
//Echo the entry.
echo '<option value="' . $posted_position_ID . '" ';
echo '>' . $posted_position_name . '</option>';
}

		

echo '
		</select>
		
		
		</br><hr>
		<a href="#settings_positions_page" data-role="button" data-icon="check" data-inline="true" onClick="update_settings_edit_position_new()">Save</a>
		<a href="#settings_positions_page" data-role="button"  data-inline="true">Cancel</a>

		</div>
	</form>
	</div>
';






//Echo the EDIT POSITION POPUP	
echo '
	<div data-role="popup" id="editPopupSchedulePosition" data-theme="a" class="ui-corner-all">
    <form>
		<div style="padding: 5px 10px;">
		
		
		<h3 id="editPopupSchedulePositionHeader">Edit Position:<label></label></h3>
		<input type="hidden" name="schedule_id_hidden" id="editPopupSchedulePositionHidden" value="">
		<input type="text" name="text-basic" id="editPopupSchedulePositionName" value="" placeholder="Position Name (Optional Description)">

		<select name="select-choice-mini" id="editPopupSchedulePositionPPRequirement" data-mini="true" data-inline="true">
		<option value="">Posted Position</option>';
while ($row_posted_position_list = $result_posted_positions_list_V2_1->fetch_assoc())
{
//load current posted position value.
$posted_position_ID = $row_posted_position_list['ID'];
$posted_position_list = $row_posted_position_list['posted_position'];
$posted_position_name = $row_posted_position_list['name'];
//Echo the entry.
echo '<option value="' . $posted_position_ID . '" ';
//if selected make this the value.

echo '>' . $posted_position_name . '</option>';
}

echo '
		</select>
		</br><hr>
		<a href="#settings_positions_page" data-role="button" data-icon="check" data-inline="true" onClick="update_settings_edit_position_save()">Save</a>
		<a href="#settings_positions_page" data-role="button" data-icon="delete" data-inline="true" onClick="update_settings_edit_position_delete()">Delete</a>
		<a href="#settings_positions_page" data-role="button"  data-inline="true">Cancel</a>

		</div>
	</form>
	</div>
';

//Include database Termination Script
include 'db_disconnect.php';
?>
