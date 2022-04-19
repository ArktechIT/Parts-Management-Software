<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$sql = "DELETE FROM engineering_partprocessstandard WHERE listId = ".$_GET['listId'];
//echo $sql."<br>";
$deleteQuery = $db->query($sql);

header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
?>
