<?php
//Include database Connection Script
include 'db_connection.php';

//Retieve all the schedule templates.
$sql_templates = '
SELECT *
FROM `'.$db.'`.`schedule_template`
ORDER BY ID ASC
';
$result_templates = $link->query($sql_templates);


echo '<select name="select-choice-template" id="select-choice-template" onChange="load_settings_create_schedule_template_position_list(this.value)">';
echo '<option value="">Select</option>';
while ($row = $result_templates->fetch_assoc())
{
$template_ID = $row['ID'];
$template_name = $row['name'];

	echo '<option value="'.$template_ID.'">'.$template_name.'</option>';
			
}
echo '</select>';


//Include database Termination Script
include 'db_disconnect.php';
?>