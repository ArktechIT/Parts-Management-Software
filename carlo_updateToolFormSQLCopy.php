<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

for($i=1;$i<$_POST['toolCounter'];$i++)
{

		$sql = "UPDATE cadcam_processtoolsdetails SET toolId = '".$_POST['inputTool'.$i]."' WHERE listId = ".$_POST['listId'.$i];
		echo $sql."<br>";

		$updateQuery = $db->query($sql);
	
}	
	
header("Location:anthony_editProductCopy.php?partId=".$_GET['partId']."&src=process&toolId=".$_GET['toolId']."");
?>
