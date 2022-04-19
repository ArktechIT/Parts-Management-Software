<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$sql = "DELETE FROM engineering_subconremarks WHERE remarksId = ".$_GET['remarksId'];
//echo $sql."<br>";
$deleteQuery = $db->query($sql);

header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=subcon&patternId=".$_GET['patternId']."");
?>
