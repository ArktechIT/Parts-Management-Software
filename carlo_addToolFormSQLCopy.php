<?php

include("../Common Data/PHP Modules/mysqliConnection.php");

$inputToolArray = $commonTool = array();
$inputToolArray = $_POST['inputNote'];
echo $identifier = $_GET['identifier'];

$commonTool = isset($_POST['commonTool']) ? $_POST['commonTool'] : 0;
$a=0;
foreach($inputToolArray as $inputTool)
{
		echo $sql = "INSERT INTO cadcam_processtoolsdetails (partId, processCode, toolId, identifier) VALUES (".$_GET['partId'].", ".$_GET['processCode'].", ".$inputTool.", ".$identifier.")";
		//echo $sql."<br>";
		$insertQuery = $db->query($sql);
		$a++;
}		
//header("Location:anthony_editProductCopy.php?partId=".$_GET['partId']."&src=process&toolId=".$_GET['toolId']."");
?>
