<?php
//Include database Connection Script
include 'db_connection.php';


echo 
'
<div data-role="popup" id="popupNewPostedPosition" data-theme="a" class="ui-corner-all">
<form>
	<div style="padding:10px 20px;">
		<h3>Create New Posted Position</h3>
		
		<input type="text" id="newPostedPosition" value="" placeholder="Name" data-theme="a">
		</br><hr>
		<a href="#settings_posted_positions_page" data-role="button" data-icon="check" data-inline="true" onClick="update_settings_posted_position_new()">Save</a>
		<a href="#settings_posted_positions_page" data-role="button"  data-inline="true">Cancel</a>
    </div>
</form>
</div>
';



echo '<ul data-role="listview" data-filter="true" data-filter-placeholder="Search..." data-inset="true">';

$sql_select_posted_positions = "SELECT * FROM `".$db."`.`posted_positions`";
$result_select_posted_positions = $link->query($sql_select_posted_positions);

while ($object_select_posted_positions = $result_select_posted_positions->fetch_assoc())
{
$pp_ID = $object_select_posted_positions['ID'];
$pp_posted_position = $object_select_posted_positions['posted_position'];
$pp_name = $object_select_posted_positions['name'];
echo '<li data-icon="edit"><a href="#" >'.$pp_name.'</a></li>';
}
echo '</ul>';

//EDIT POPUP
//RENAME TEMPLATE 
//DELETE (WARNING> THIS WILL DELETE...
//...ALL SAVED TEMPLATES THAT USED THIS TEMPLATE


//Include database Termination Script
include 'db_disconnect.php';
?>