<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");
ini_set('display_errors','on');

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

if(isset($_GET['count']))
{
	$oldProcessCode = '0';
	$oldProcessDetail = '';

	$sql = "SELECT processCode, processSection FROM cadcam_partprocess WHERE count = ".$_GET['count']." ";
	$getOldParProcess = $db->query($sql);
	if($getOldParProcess->num_rows > 0)
	{
		$getOldParProcessResult = $getOldParProcess->fetch_array();
		
		$sql = "SELECT sectionName FROM ppic_section WHERE sectionId = ".$getOldParProcessResult['processSection']." ";
		$getOldSectionName = $db->query($sql);
		$getOldSectionNameResult = $getOldSectionName->fetch_array();
		
		$oldProcessCode = $getOldParProcessResult['processCode'];	
		$oldProcessSection = $getOldParProcessResult['processSection'];
	}

	if($oldProcessCode != $_POST['processName'])
	{
		$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details, userRemarks) VALUES (".$_GET['partId'].", now(), 2, 16, '".$oldProcessCode."', '".$_POST['processName']."', '".$userIP."', '".$userID."', 'UPDATE processCode', '".$_POST['userRemarks']." ;patternId=".$_GET['patternId']."') ";
		$insert = $db->query($sql);
	}
	if($oldToolId != $_POST['toolName'])
	{
		$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details, userRemarks) VALUES (".$_GET['partId'].", now(), 2, 33, '".$oldToolId."', '".$_POST['toolName']."', '".$userIP."', '".$userID."', 'UPDATE toolId', '".$_POST['userRemarks']."') ";
		$insert = $db->query($sql);
	}
	if($oldProcessSection != $_POST['sectionName'])
	{
		$sql = "SELECT sectionName FROM ppic_section WHERE sectionId = ".$_POST['sectionName']." ";
		$getSectionName = $db->query($sql);
		$getSectionNameResult = $getSectionName->fetch_array();
		
		$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details, userRemarks) VALUES (".$_GET['partId'].", now(), 1, 32, '".$getOldSectionNameResult['sectionName']."', '".$getSectionNameResult['sectionName']."', '".$userIP."', '".$userID."', 'UPDATE processSection', '".$_POST['userRemarks']." ;patternId=".$_GET['patternId']."') ";
		$insert = $db->query($sql);
	}

	$sql = "UPDATE cadcam_partprocess SET dataOne = '".$_POST['vSize']."', dataTwo = '".$_POST['punchR']."', dataThree = '".$_POST['bendDeduct']."', dataFour = '".$_POST['superyagenHeight']."', dataFive = '".$_POST['superyagenDistance']."' WHERE partId = ".$_GET['partId']." AND processCode = ".$_GET['processCode'];
	$update = $db->query($sql);

	$sql = "UPDATE cadcam_partprocess SET processCode = ".$_POST['processName'].", processSection = ".$_POST['sectionName'].", toolId = ".$_POST['toolName']." WHERE count = ".$_GET['count'];
	$update = $db->query($sql);
}
else if(isset($_GET['subProcessListId']))
{
	$sql = "UPDATE engineering_partsubprocess SET processCode = ".$_POST['processName'].", toolId = '".$_POST['toolName']."', dataOne = '".$_POST['vSize']."', dataTwo = '".$_POST['punchR']."', dataThree = '".$_POST['bendDeduct']."', dataFour = '".$_POST['superyagenHeight']."', dataFive = '".$_POST['superyagenDistance']."' WHERE listId = ".$_GET['subProcessListId'];
	$update = $db->query($sql);	
}

header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']." ");
?>
