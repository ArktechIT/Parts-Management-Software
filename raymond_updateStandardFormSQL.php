<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

for($i=1;$i<$_POST['specCounter'];$i++)
{
	$sql = "UPDATE engineering_partprocessstandard SET specificationId = '".$_POST['updateStandard'.$i]."'  WHERE listId = ".$_POST['listId'.$i];
	//~ echo $sql."<br>";
	$updateQuery = $db->query($sql);
}
header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
?>
