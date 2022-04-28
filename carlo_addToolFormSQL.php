<?php

include("../Common Data/PHP Modules/mysqliConnection.php");

$inputToolArray = $commonTool = array();
$inputToolArray = $_POST['inputNote'];
$identifier = $_GET['identifier'];//TAMANG

$commonTool = isset($_POST['commonTool']) ? $_POST['commonTool'] : 0;
$a=0;

if($identifier == '') //TAMANG EDITED 
{
	foreach($inputToolArray as $inputTool)
	{
			$sql = "INSERT INTO cadcam_processtoolsdetails (partId, processCode, toolId,identifier) VALUES (".$_GET['partId'].", ".$_GET['processCode'].", ".$inputTool.",NULL)";
			//echo $sql."<br>";
			$insertQuery = $db->query($sql);
			$a++;
	}		
}
else 
{
	foreach($inputToolArray as $inputTool)//TAMANG EDITED
	{
			echo $sql = "INSERT INTO cadcam_processtoolsdetails (partId, processCode, toolId, identifier) VALUES (".$_GET['partId'].", ".$_GET['processCode'].", ".$inputTool.", ".$identifier.")";
			//echo $sql."<br>";
			$insertQuery = $db->query($sql);
			$a++;
	}
}
header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=process&toolId=".$_GET['toolId']."");
?>
