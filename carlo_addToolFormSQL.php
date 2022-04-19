<?php

include("../Common Data/PHP Modules/mysqliConnection.php");

$inputToolArray = $commonTool = array();
$inputToolArray = $_POST['inputNote'];

$commonTool = isset($_POST['commonTool']) ? $_POST['commonTool'] : 0;
$a=0;
foreach($inputToolArray as $inputTool)
{
		$sql = "INSERT INTO cadcam_processtoolsdetails (partId, processCode, toolId) VALUES (".$_GET['partId'].", ".$_GET['processCode'].", ".$inputTool.")";
		//echo $sql."<br>";
		$insertQuery = $db->query($sql);
		$a++;
}		
header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=process&toolId=".$_GET['toolId']."");
?>
