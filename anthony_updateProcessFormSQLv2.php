<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");
ini_set('display_errors','on');

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

$oldProcessCode = '0';
$oldProcessDetail = '';

$processCode = $_GET['processCode'];
$processCodeTappingArray = array (122,123,124);
$processCodeBendingArray = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,31,32,33,34,46,47,48,261,262,283);
if(in_array($processCode, $processCodeTappingArray))
{
	$tapSize = isset($_POST['tapSize']) ? $_POST['tapSize'] : '';
	$tapCount = isset($_POST['tapCount']) ? $_POST['tapCount'] : '';
	$noteId = isset($_POST['noteId']) ? $_POST['noteId'] : '';

	for ($i=0; $i < count($tapSize) ; $i++) 
	{ 
		$sql = "SELECT noteId FROM cadcam_partprocessnote WHERE noteId = ".$noteId[$i];
		$queryNote = $db->query($sql);
		if($queryNote AND $queryNote->num_rows > 0)
		{
			echo "<br>".$sql = "UPDATE cadcam_partprocessnote SET noteDetails = '".$tapSize[$i]."', noteMultiplier = ".$tapCount[$i]." WHERE noteId = ".$noteId[$i];
			$queryUpdate = $db->query($sql);
		}
		else
		{
			if($tapSize[$i] != '' OR $tapCount[$i] != '')
			{
				echo "<br>".$sql = "INSERT INTO `cadcam_partprocessnote`(`partId`, `processCode`, `patternId`, `remarks`, `noteDetails`, `noteMultiplier`, `noteIdentifier`) 
											VALUES (".$_GET['partId'].", ".$_GET['processCode'].", ".$_GET['patternId'].", '".$_POST['userRemarks']."', '".$tapSize[$i]."', ".$tapCount[$i].",10)";
				$queryInsert = $db->query($sql);
			}
		}
	}
}

if(in_array($processCode, $processCodeBendingArray))
{
	$vSize = isset($_POST['vSize']) ? $_POST['vSize'] : '';
	$punchR = isset($_POST['punchR']) ? $_POST['punchR'] : '';
	$punchRNoteId = isset($_POST['punchRNoteId']) ? $_POST['punchRNoteId'] : '';
	$bendDeduct = isset($_POST['bendDeduct']) ? $_POST['bendDeduct'] : '';
	$superyagenHeight = isset($_POST['superyagenHeight']) ? $_POST['superyagenHeight'] : '';
	$superyagenDistance = isset($_POST['superyagenDistance']) ? $_POST['superyagenDistance'] : '';
	$noteId = isset($_POST['noteIdBending']) ? $_POST['noteIdBending'] : '';
	$partId = isset($_POST['partId']) ? $_POST['partId'] : '';
	echo count($vSize);

	for ($i=0; $i < count($vSize); $i++) 
	{
		$sql = "SELECT noteId, noteIdentifier  FROM cadcam_partprocessnote_1 WHERE noteId = ".$noteId[$i]."";
		$queryNote = $db->query($sql);
		if($queryNote AND $queryNote->num_rows > 0)
		{
			echo "<br>".$sql = "UPDATE cadcam_partprocessnote_1 SET noteDetails = ".$vSize[$i]." WHERE noteId = ".$noteId[$i]." AND noteIdentifier =  0";

			// echo "<br>".$sql = "UPDATE cadcam_partprocessnote_1 SET noteDetails = ".$punchR[$i]." WHERE childNoteId = ".$noteId[$i]." AND noteIdentifier =  1 AND patternId = ".$_GET['patternId'];
		}
		else
		{
			$sql = "";
		}

	}
}

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

// if($_SESSION['idNumber'] == "0412") exit(0);
header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']." ");
?>
