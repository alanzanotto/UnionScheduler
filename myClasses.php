<?php
class Schedule
{


//Retrieve an html list of all the facility IDs/names in ID order.
public function drop_down_menu_facility_list()
{
include 'db_connection.php';


$sql_facility_IDs = "SELECT * FROM `".$db."`.`schedule_facility` Order by ID";
$result_facility_IDs = $link->query($sql_facility_IDs);

$menu = "";
while ($row = $result_facility_IDs->fetch_assoc())
{
$menu = $menu . '<option value="'.$row['ID'].'">'.$row['name'].'</option>';
}

include 'db_disconnect.php';
return $menu;
}//function all_Facility_IDs_with_Names





//Retrieve an html list of all the stations IDs/Names in ID order
public function drop_down_menu_station_list()
{
include 'db_connection.php';

$sql_station_info = "SELECT * FROM `".$db."`.`schedule_station` Order by ID";
$result_station_info = $link->query($sql_station_info);

$menu = "";
while ($row = $result_station_info->fetch_assoc())
{
$menu = $menu . '<option value="'.$row['ID'].'">'.$row['name'].'</option>';
}

include 'db_disconnect.php';
return $menu;
}





//Creates a collapsible list view for a the requested schedule template
public function list_view_scheduled_template($template_ID)
{
include 'db_connection.php';
$mySchedule = new Schedule();

$list_view =  
'
<div data-inset="false">
		<ul data-role="listview">';
		
//RETREIVE TEMPLATE SCHEDULE POSITIONS
$sql_template_position_list_day = '
SELECT * 
FROM `'.$db.'`.`schedule_template_position_list`
WHERE ID_template = '.$template_ID;
$result_template_position_list_day = $link->query($sql_template_position_list_day);

while ($row = $result_template_position_list_day->fetch_assoc())
{
$stpl_ID = $row['ID'];
$stpl_ID_schedule_position = $row['ID_schedule_position'];
$stpl_quantity = $row['quantity'];
$stpl_facility_ID = $row['facility'];
$stpl_station_ID = $row['station'];
$stpl_facility_index = $mySchedule->get_index_for_facility($stpl_facility_ID);
$stpl_station_index = $mySchedule->get_index_for_station($stpl_station_ID);
//retrieve position name from schedule position table
$sql_position_name = '
SELECT name
FROM `'.$db.'`.`schedule_position`
WHERE ID = '.$stpl_ID_schedule_position;
$result_position_name = $link->query($sql_position_name);
$object_position_name = $result_position_name->fetch_assoc();
$stpl_position_name = $object_position_name['name'];

$list_view = $list_view . '
	<li>
		<a href="#popupEditPosition" 
			onClick="update_popup_edit_position('.$stpl_ID.', \''.$stpl_position_name.'\', '.$stpl_quantity.', '.$stpl_facility_index.', '.$stpl_station_index.')" 
			class="ui-icon-edit" data-rel="popup" 
			data-position-to="window" 
			data-transition="pop">'.$stpl_position_name.'<span class="ui-li-count">'.$stpl_quantity.' </span>
		</a>
	</li>';
	
		
}//while 

$list_view = $list_view . '	</ul>
</div><!-- /collapsible -->';



include 'db_disconnect.php';
return $list_view;
}



//Create the NEW popup menu for adding a new position to a template.  This is found in the load_settings_create_schedule_templates_position_list file.  
//On the settings_create_schedule_templates_page
public function popup_new_position_scheduled_template()
{
include 'db_connection.php';


//Echo the NEW POSITION POPUP
$popup =  '
	<div data-role="popup" id="popupNewPosition" data-theme="a" class="ui-corner-all">
    <form>
		<div style="padding: 5px 10px;">
		<h3>New Position for Template:</h3>
			<select name="select-choice-newPosition" id="select-choice-newPosition">
			<option value="">Choose Position</option>';
			
//RETRIEVE POSITIONS  FROM DATABSE
$sql_schedule_position = 
"
SELECT ID, name
FROM `".$db."`.`schedule_position`
";
$result_schedule_position = $link->query($sql_schedule_position);
//ECHO POSITION INTO SELECT LIST

while ($row = $result_schedule_position->fetch_assoc())
{
$position_ID = $row['ID'];
$position_name = $row['name'];


$popup = $popup . '<option value="'.$position_ID.'">'.$position_name.'</option>';
}		
$popup = $popup . '</select>
			<label for="slider-fill">Quantity:</label>
			<input type="range" name="slider-fill" id="slider-fill_newPosition" value="1" min="1" max="50" data-highlight="true">';

		/*	
		<fieldset data-role="controlgroup" data-type="horizontal" >
		    <legend>Shift:</legend>
		        <input type="radio" name="radio-choice_newPosition" id="radio-choice-day" value="0" checked="checked">
		        <label for="radio-choice-day">Day</label>
		        <input type="radio" name="radio-choice_newPosition" id="radio-choice-night" value="1">
		        <label for="radio-choice-night">Night</label>
		</fieldset>';*/

$mySchedule = new Schedule;		
$menu_facility = $mySchedule->drop_down_menu_facility_list();
$popup = $popup .	'<select name="select-choice-newFacility" id="select-choice-newFacility">';
$popup = $popup . $menu_facility;
$popup = $popup .	'</select>';


$popup = $popup . '<select name="select-choice-newStation" id="select-choice-newStation">';
$menu_stations = $mySchedule->drop_down_menu_station_list();
$popup = $popup . $menu_stations;	
$popup = $popup . '</select>';

$popup = $popup . '
		</br><hr>
		<a href="#settings_create_schedule_templates_page" data-role="button" data-icon="check" data-inline="true" onClick="update_settings_edit_template_position_new()">Save</a>
		<a href="#settings_create_schedule_templates_page" data-role="button"  data-inline="true">Cancel</a>

		</div>
	</form>
	';



include 'db_disconnect.php';
return $popup;
}




//Create the EDIT popup menu for EDITING a position on the template.  This is found in the load_settings_create_schedule_templates_position_list file.  
//On the settings_create_schedule_templates_page
public function popup_edit_position_scheduled_template()
{
//Echo the EDIT POSITION POPUP	
$edit_popup =  '
	<div data-role="popup" id="popupEditPosition" data-theme="a" class="ui-corner-all">
    <form>
		<div style="padding: 5px 10px;">
		<h3 id="editPositionHeader">Edit Position:<label></label></h3>
			 <input type="hidden" id="hiddenPositionID"  value="">
			<label for="slider-fill">Quantity:</label>
			<input type="range" name="slider-fill" id="slider-fill_editPosition" value="1" min="1" max="50" data-highlight="true">';

		/*
		<fieldset data-role="controlgroup" data-type="horizontal" >
		    <legend>Shift:</legend>
		        <input type="radio" name="radio-choice_editPosition" id="radio-choice-day_editPosition" value="0">
		        <label for="radio-choice-day_editPosition">Day</label>
		        <input type="radio" name="radio-choice_editPosition" id="radio-choice-night_editPosition" value="1">
		        <label for="radio-choice-night_editPosition">Night</label>
		</fieldset>';*/
$mySchedule = new Schedule;		
$menu_facility = $mySchedule->drop_down_menu_facility_list();
$edit_popup = $edit_popup .	'<select name="select-choice-newFacility" id="select-choice-editFacility">';
$edit_popup = $edit_popup . $menu_facility;
$edit_popup = $edit_popup .	'</select>';


$edit_popup = $edit_popup . '<select name="select-choice-newStation" id="select-choice-editStation">';
$menu_stations = $mySchedule->drop_down_menu_station_list();
$edit_popup = $edit_popup . $menu_stations;	
$edit_popup = $edit_popup . '</select>';

$edit_popup = $edit_popup . '
		</br><hr>
		<a href="#settings_create_schedule_templates_page" data-role="button" data-icon="check" data-inline="true" onClick="update_settings_edit_template_position_save()">Save</a>
		<a href="#settings_create_schedule_templates_page" data-role="button" data-icon="delete" data-inline="true" onClick="update_settings_edit_template_position_delete()">Remove</a>
		<a href="#settings_create_schedule_templates_page" data-role="button"  data-inline="true">Cancel</a>

		</div>
	</form>
	</div>
';
return $edit_popup;
}





//Provides a tool that can get an index for a facilty.  The index is used in the javascript to update popups with correct information.
private function get_index_for_facility($facility_ID)
{
include 'db_connection.php';

$index = 0;

//First select all of the facility IDs
$sql_index = 'set @row_number:=-1;'; 
$link->query($sql_index);
$sql_index = 'SELECT *, @row_number:=@row_number+1 as row_number FROM `'.$db.'`.`schedule_facility`;';
$result_index = $link->query($sql_index);

//Loop through selected facility ID's and add one to a temp_index.
while ($row = $result_index->fetch_assoc())
{

	if ($row['ID'] == $facility_ID){
	$index = $row['row_number'];
	}

}


include 'db_disconnect.php';
return $index;
}





//Provides a tool that can get an index for a station.  The index is used in the javascript to update popups with correct information.
private function get_index_for_station($station_ID)
{
include 'db_connection.php';

$index = 0;

//First select all of the facility IDs
$sql_index = 'set @row_number:=-1;'; 
$link->query($sql_index);
$sql_index = 'SELECT *, @row_number:=@row_number+1 as row_number FROM `'.$db.'`.`schedule_station`;';
$result_index = $link->query($sql_index);

//Loop through selected facility ID's and add one to a temp_index.
while ($row = $result_index->fetch_assoc())
{

	if ($row['ID'] == $station_ID){
	$index = $row['row_number'];
	}

}


include 'db_disconnect.php';
return $index;
}




//Creates a popup menu for the schedule templates page.  Allows for replicating a template.
public function popup_new_template()
{
include 'db_connection.php';

$list_view =  
'
<div data-role="popup" id="popupNewTemplate" data-theme="a" class="ui-corner-all">
<form>
	<div style="padding:10px 20px;">
		<h3>Create New Template</h3>
		
		<input type="text" id="newTemplateName" value="" placeholder="Name    Example: Cherries (Day/Night) " data-theme="a">
		<label for="select-choice_newTemplateReplication" class="select">Replication:</label>
		<select name="select-choice_newTemplateReplication" id="select-choice_newTemplateReplication">
			<option value="blank">Blank</option>';
		
			$sql_select_templates = "SELECT * FROM `".$db."`.`schedule_template`";
			$result_select_templates = $link->query($sql_select_templates);
			
			while ($object_select_templates = $result_select_templates->fetch_assoc())
			{
			$template_ID = $object_select_templates['ID'];
			$template_name = $object_select_templates['name'];
			$list_view = $list_view. '<option value="'.$template_ID.'">'.$template_name.'</option>';
			}
			
$list_view =  $list_view . '
		</select>
		
		</br><hr>
		<a href="#settings_schedule_templates_page" data-role="button" data-icon="check" data-inline="true" onClick="update_settings_schedule_template_new()">Save</a>
		<a href="#settings_schedule_templates_page" data-role="button"  data-inline="true">Cancel</a>
        </div>
    </form>
</div>
';
include 'db_disconnect.php';
return $list_view;
}


public function popup_edit_template()
{
include 'db_connection.php';

$popup =  
'
<div data-role="popup" id="popupEditTemplate" data-theme="a" class="ui-corner-all">
<form>
	<div style="padding:10px 20px;">
		<h3 id="editTemplateHeader">Edit Template:</h3>
		
		<input type="hidden" id="hiddenTemplateID"  value="">
		</br><hr>
		<a href="#settings_schedule_templates_page" data-role="button" data-icon="check" data-inline="true" onClick="update_settings_schedule_template_delete()">Delete</a>
		<a href="#settings_schedule_templates_page" data-role="button"  data-inline="true">Cancel</a>
        </div>
    </form>
</div>
';
include 'db_disconnect.php';
return $popup;
}


//Creates a list view that displays all the templates in the database.  Also adds in the functionality of deleting them
public function list_view_templates()
{
include 'db_connection.php';

$list_view = '<ul data-role="listview" data-filter="false" data-filter-placeholder="Search fruits..." data-inset="true">';

$sql_select_templates = "SELECT * FROM `".$db."`.`schedule_template`";
$result_select_templates = $link->query($sql_select_templates);

while ($object_select_templates = $result_select_templates->fetch_assoc())
{
$template_ID = $object_select_templates['ID'];
$template_name = $object_select_templates['name'];
$list_view = $list_view . 
'<li data-icon="edit">
<a href="#popupEditTemplate" 
			onClick="update_edit_popup_template('.$template_ID.', \''.$template_name.'\')" 
			class="ui-icon-edit" data-rel="popup" 
			data-position-to="window" 
			data-transition="pop">'.$template_name.
'</a>
</li>';



}
$list_view = $list_view . '</ul>';

include 'db_disconnect.php';
return $list_view;
}


//Delete a schedule template and removes positions associated with that template.
public function schedule_template_delete($template_ID)
{
include 'db_connection.php';

$sql = 'DELETE FROM `'.$db.'`.`schedule_template` WHERE ID = '.$template_ID;
$link->query($sql);

$sql2 = 'DELETE FROM `'.$db.'`.`schedule_template_position_list` WHERE ID_template = '.$template_ID;
$link->query($sql2);

include 'db_disconnect.php';
}




//Functions for the Schedule Setup Page

public function  load_schedule_setup_page()
{
//Include database Connection Script
include 'db_connection.php';


$date_today = date("Y-m-d");
$date_tomorrow = date("Y-m-d", strtotime($date_today . ' + 1 day') );
$page = 
'
<p>This is the Schedule Setup Page</p>
		<p>Fill in the following information to create a schedule:</p>
		
		<label for="text-basic">Date (YYYY-MM-DD):</label>
		<input type="text" name="text-basic" id="text-date" value="'.$date_tomorrow.'" placeholder="YYYY-MM-DD">
		<label for="select-choice-1" class="select">Schedule Template:</label>
		<select name="select-choice-1" id="select-schedule_template">';
		
$sql_select_templates = "SELECT * FROM `".$db."`.`schedule_template`";
$result_select_templates = $link->query($sql_select_templates);

while ($object_select_templates = $result_select_templates->fetch_assoc())
{
$template_ID = $object_select_templates['ID'];
$template_name = $object_select_templates['name'];
$page = $page . '<option value="'.$template_ID.'">'.$template_name.'</option>';
}
$page = $page . '</select>';
$page = $page . '<div data-role="content" id="schedule_setup_page_content2">';
$page = $page . '<a href="#" class="ui-btn ui-corner-all" id="anchor-button" onClick="create_schedule(document.getElementById(\'text-date\').value, document.getElementById(\'select-schedule_template\').value)">Create</a>';
$page = $page . '</div><!-- /content -->';


return $page;
//Include database Termination Script
include 'db_disconnect.php';
}


public function  load_schedule_setup_page2()
{
//Include database Connection Script
include 'db_connection.php';


$date_today = date("Y-m-d");
$date_tomorrow = date("Y-m-d", strtotime($date_today . ' + 1 day') );
$page = 
'
<p>This is the Schedule Setup Page</p>
		<p>Fill in the following information to create a schedule:</p>
		
		<label for="text-basic">Date (YYYY-MM-DD):</label>
		<input type="text" name="text-basic" id="text-date" value="'.$date_tomorrow.'" placeholder="YYYY-MM-DD">';

$page = $page .
'
<ul data-role="listview" data-inset="true" data-divider-theme="a">
	<li data-role="list-divider">Day</li>
		<li>
			<fieldset data-role="controlgroup">';
			
			$sql_templates = 'SELECT * FROM `'.$db.'`.`schedule_template`';
			$result_templates = $link->query($sql_templates);
			$temp_int = 0;
			while ($row = $result_templates->fetch_assoc())
			{
			$template_ID = $row['ID'];
			$template_name = $row['name'];
			$page = $page . '
				<input type="checkbox" name="checkbox-day'.$temp_int.'" id="checkbox-day'.$temp_int.'" value='.$template_ID.'>
				<label for="checkbox-day'.$temp_int.'">'.$template_name.'</label>';
			$temp_int++;
			}//while
				
$page = $page .'
			</fieldset>
		</li>


	<li data-role="list-divider">Night</li>
		<li>
			<fieldset data-role="controlgroup">';

			$sql_templates = 'SELECT * FROM `'.$db.'`.`schedule_template`';
			$result_templates = $link->query($sql_templates);
			$temp_int = 0;
			while ($row = $result_templates->fetch_assoc())
			{
			$template_ID = $row['ID'];
			$template_name = $row['name'];
			$page = $page . '
				<input type="checkbox" name="checkbox-night'.$temp_int.'" id="checkbox-night'.$temp_int.'" value='.$template_ID.'>
				<label for="checkbox-night'.$temp_int.'">'.$template_name.'</label>';
			$temp_int++;
			}//while	

$page = $page .'				
			</fieldset>
		</li>
</ul>
';


$page = $page . '<div data-role="content" id="schedule_setup_page_content2">';
$page = $page . '<a href="#" class="ui-btn ui-corner-all" id="anchor-button" onClick="create_schedule(document.getElementById(\'text-date\').value)">Create</a>';
$page = $page . '</div><!-- /content -->';


return $page;
//Include database Termination Script
include 'db_disconnect.php';
}
//END Functions for the Schedule Setup Page




//Function for displaying a scheudle.
public function display_schedule_winfield_layout($schedule_id)
{
//Include database Connection Script
include 'db_connection.php';

//Retrieve POST Values
$new_schedule_value = $schedule_id;

//Variables
$temp_category;


//Pre Size & A/B Line
echo '
<div class="customPrintPage">
	<div class="customHeaderDay">Tuesday </br> August 25, 2015 7:00AM - 3:30PM</div>
	
		<div class="customStationHeader">PRESIZE</div>
		<div class="customPositionArea">
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
		</div>
		
		
		<div class="customStationHeader">A / B LINE</div>
		<div class="customPositionArea">
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			
		</div>	
		
</div>
';


//Pre Size & A/B Line
echo '
<div class="customPrintPage">
	<div class="customHeaderDay">Tuesday </br> August 25, 2015 7:00AM - 3:30PM</div>
	
		<div class="customStationHeader">PRESIZE</div>
		<div class="customPositionArea">
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
		</div>
		
		
		<div class="customStationHeader">A / B LINE</div>
		<div class="customPositionArea">
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			<div class="customPositions">Alan Zanotto | Dumper Operator</div>
			
		</div>	
		
</div>
';


/*

echo 
'
<div class="customPrintPage">
<div class="customHeaderDay">Tuesday </br> August 25, 2015 7:00AM - 3:30PM</div>
<div class="customStationHeader">PRESIZE</div>
<div class="customStationHeader">A / B LINE</div>


</div>


<div class="customPrintPage">
<div class="customHeaderDay">Tuesday </br> August 25, 2015 7:00AM - 3:30PM</div>
<div class="customStationHeader">PRESIZE</div>
<div class="customStationHeader">A / B LINE</div>

</div>
';
*/









/* COMMENTING OUT THE SCHEDULE PART TO BUILD THE TEMPLATES
//retrieve the schedule.
$sql_schedule = 
"SELECT * 
FROM `".$db."`.`schedule_saved`
WHERE `ID_schedule` = ".$new_schedule_value. "
ORDER BY `schedule_saved`.`ID_schedule_position` ASC, `schedule_saved`.`ID_employee` ASC";
$result_schedule = $link->query($sql_schedule);

//echo $sql_schedule;




//temp table column to expand when 9 people in a row.
$temp_table_column = 0;
//Loop through the people in the schedule
while ($row = $result_schedule->fetch_assoc())
{
//Setup Variables.
$employee_ID = $row['ID_employee'];
$schedule_position_ID = $row['ID_schedule_position'];
$shift = $row['shift'];

//Retrieve extra information  (employee information/ position information)
$sql_employee_information = " 
SELECT senority, first_name, last_name
FROM `".$db."`.`employee`
WHERE ID = ".$employee_ID;
$result_employee_information = $link->query($sql_employee_information);
$object_employee_information = $result_employee_information->fetch_assoc();
$employee_senority = $object_employee_information['senority'];
$employee_first_name = $object_employee_information['first_name'];
$employee_last_name = $object_employee_information['last_name'];

//echo $employee_first_name . " ". $employee_last_name;


$sql_position_information = "
SELECT name
FROM `".$db."`.`schedule_position`
WHERE ID = ".$schedule_position_ID;
//echo $sql_position_information;
$result_position_information = $link->query($sql_position_information);
$object_position_information = $result_position_information->fetch_assoc();
$schedule_position_name = $object_position_information['name'];
//echo $schedule_position_name;



if ( !isset($temp_category) )
{
$temp_category = $schedule_position_name;
echo '<div class="schedule_list_block" id="schedule_list_block">
<h4>'.$temp_category.'</h4>';//start a fresh category

//echo "(". $employee_senority .") ". $employee_first_name . " ". $employee_last_name .'</br>';//OLIVER
echo "&#9744". $employee_first_name . " ". $employee_last_name .'</br>';//WINFIELD

}

elseif ($temp_category != $schedule_position_name)
{
echo '</div>';//close up last category
$temp_category = $schedule_position_name;//set new temp category

echo '<div class="schedule_list_block">
<h4>'.$temp_category.'</h4>';//start a fresh category

//echo "(". $employee_senority .") ". $employee_first_name . " ". $employee_last_name .'</br>';//OLIVER
echo "&#9744". $employee_first_name . " ". $employee_last_name .'</br>';//WINFIELD

}

else
{
	//echo "(". $employee_senority .") ". $employee_first_name . " ". $employee_last_name .'</br>';//OLIVER
	echo "&#9744". $employee_first_name . " ". $employee_last_name .'</br>';//WINFIELD
}


}//while loop

*/


//Include database Termination Script
include 'db_disconnect.php';

}


//Function for making an Excel file and downloading it.
public function generateExcelSchedule($schedule_id)
{
//Include database Connection Script
include 'db_connection.php';
//Retrieve POST Values
$new_schedule_value = $schedule_id;

echo "hello there";

}//END function generateExcelSchedule($schedule_id)



}//class Schedule

?>