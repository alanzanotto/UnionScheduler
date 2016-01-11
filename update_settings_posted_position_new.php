<?php
//Include database Connection Script
include 'db_connection.php';


$new_name = $_POST['new_name'];

//$new_name = "test/hi there385974329857843!@#$%^&*() dslajfasfj;lsaflsa ;kjsafj;sjalkf f;jsafsakj";
//$new_name = 'sorter[4]';
//if there is a new name then insert it into the database.
if ($new_name != "")
{
$new_name_stripped = preg_replace("/[^a-zA-Z0-9]+/", "", $new_name);
//echo $new_name_stripped;

$sql_insert_new_name = 
"
INSERT INTO `".$db."`.`posted_positions` 
(ID, posted_position, name) VALUES (NULL, '".$new_name_stripped."', '".$new_name."')
";
//echo $sql_insert_new_name;
$link->query($sql_insert_new_name);
}


//Include database Termination Script
include 'db_disconnect.php';
?>