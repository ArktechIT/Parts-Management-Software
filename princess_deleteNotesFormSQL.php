<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$noteId = $_POST['noteId'];
$sql = "DELETE FROM cadcam_partprocessnote WHERE noteId = ".$noteId;
//echo $sql."<br>";
$deleteQuery = $db->query($sql);

// header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
?>
