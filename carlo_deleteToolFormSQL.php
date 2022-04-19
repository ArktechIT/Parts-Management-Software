<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$listId = $_POST['listId'];
$sql = "DELETE FROM cadcam_processtoolsdetails WHERE listId = ".$listId;

$deleteQuery = $db->query($sql);


?>
