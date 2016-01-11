<?php

//Include database Connection Script
include 'db_connection.php';

$new_first_name = $_POST['new_first_name'];
$new_last_name = $_POST['new_last_name'];

//insert into employees.  
//retrieve new auto_increment
//update row senority with auto_increment
$sql_new_employee = 
"
INSERT INTO `".$db."`.`employee` 
(`ID`, `senority`, `first_name`, `last_name`) 
VALUES (NULL, '0', '".$new_first_name."', '".$new_last_name."')
";
$result_new_employee = $link->query($sql_new_employee);
$ID = $link->insert_id;

$sql_update_senority = 
"
UPDATE `".$db."`.`employee` SET `senority` = ".$ID." 
WHERE `employee`.`ID` = ".$ID.";
";
$link->query($sql_update_senority);



//Create new employee_shift_preference table.
$sql_new_employee_preference = 
"
INSERT INTO `".$db."`.`employee_shift_preference` 
(`ID_employee`, `available`, `shift`, `non_rotational`, `posted_position`) 
VALUES (".$ID.", '1', 'day', '0', 'Sorter');
";
$link->query($sql_new_employee_preference);

//Include database Termination Script
include 'db_disconnect.php';

?>
