<?php
include('../Common Data/PHP Modules/mysqliConnection.php');
ini_set("display_errors", "on");

$sql = "SELECT * FROM cadcam_parts WHERE partId = ".$_GET['partId']." ";
$getParts = $db->query($sql);
$getPartsResult = $getParts->fetch_array();

$sql = "SELECT partNumber, revisionId FROM cadcam_parts WHERE partNumber LIKE '".$_POST['partNumber']."' AND revisionId LIKE '".strtoupper($_POST['revision'])."'";
$getPartNumberRevision = $db->query($sql);
if($getPartNumberRevision->num_rows > 0)
{
	header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=process&duplicateFlag=2&patternId=".$_GET['patternId']."");
}
else
{
	$sql = "INSERT INTO cadcam_parts (partNumber, partName, revisionId, customerId, quantityPerSheet, materialSpecId, materialSpecDetail, partDrawing, status, x, y) VALUES ('".$_POST['partNumber']."', '".$_POST['partName']."', '".strtoupper($_POST['revision'])."', ".$getPartsResult['customerId'].", ".$getPartsResult['quantityPerSheet'].", ".$getPartsResult['materialSpecId'].", '".$getPartsResult['materialSpecDetail']."', '".$getPartsResult['partDrawing']."', ".$getPartsResult['status'].", ".$getPartsResult['x'].", ".$getPartsResult['y'].") ";
	$add = $db->query($sql);
 
	$sql = "SELECT max(partId) AS maxId FROM cadcam_parts WHERE partNumber = '".$_POST['partNumber']."' AND revisionId = '".strtoupper($_POST['revision'])."' ";
	$getmaxId = $db->query($sql);
	$getmaxIdResult = $getmaxId->fetch_array();

	//--------------------------- INSERT INTO cadcam_partprocess ---------------------------
	$sql = "SELECT * FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." ";
	$getpartId = $db->query($sql);
	while($getpartIdResult = $getpartId->fetch_array()){
		$sql = "INSERT INTO cadcam_partprocess (partId, processOrder, processCode, processDetail, processSection ,setupTime, cycleTime, patternId) VALUES (".$getmaxIdResult['maxId'].", ".$getpartIdResult['processOrder'].", ".$getpartIdResult['processCode'].", '".$getpartIdResult['processDetail']."', ".$getpartIdResult['processSection'].", ".$getpartIdResult['setupTime'].", ".$getpartIdResult['cycleTime'].", ".$getpartIdResult['patternId'].") ";
		$addPartProcess = $db->query($sql);
	}
	
	//--------------------------- INSERT INTO cadcam_subparts ---------------------------
	$sql = "SELECT * FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." ";
	$getSubParts = $db->query($sql);
	while($getSubPartsResult = $getSubParts->fetch_array()){
		$sql = "INSERT INTO cadcam_subparts (parentId, childId, quantity, identifier) VALUES (".$getmaxIdResult['maxId'].", ".$getSubPartsResult['childId'].", ".$getSubPartsResult['quantity'].", ".$getSubPartsResult['identifier'].") ";
		$addSubparts = $db->query($sql);
	}
	
	//--------------------------- INSERT INTO cadcam_subconlist ---------------------------
	$sql = "SELECT * FROM cadcam_subconlist WHERE partId = ".$_GET['partId']." ";
	$getSubconList = $db->query($sql);
	while($getSubconListResult = $getSubconList->fetch_array()){
		$sql = "INSERT INTO cadcam_subconlist (partId, subconId, processCode, surfaceArea, remarks, subconOrder) VALUES (".$getmaxIdResult['maxId'].", ".$getSubconListResult['subconId'].", ".$getSubconListResult['processCode'].", ".$getSubconListResult['surfaceArea'].", '".$getSubconListResult['remarks']."', ".$getSubconListResult['subconOrder'].") ";
		$addSubconlist = $db->query($sql);
		
		//CHICHA 12-18-17
		$sql = "SELECT * FROM engineering_subconprocessor WHERE a = ".$getSubconListResult['a']."";
		$queryA = $db->query($sql);
		if($queryA->num_rows > 0)
		{
			$resultA = $queryA->fetch_assoc();
			$subConIdA = $resultA['subconId'];
			$processCodeA = $getSubconListResult['processCode'];

			$sql = "SELECT * FROM cadcam_subconlist WHERE processCode = ".$processCodeA." AND partId = ".$getmaxIdResult['maxId']."";
			$queryB = $db->query($sql);
			if($queryB->num_rows > 0)
			{
				$resultB = $queryB->fetch_assoc();
				$value = $resultB['a'];
			}

			$sql = "INSERT INTO engineering_subconprocessor (a, subconId) VALUES (".$value.", ".$subConIdA.")";
			$addA = $db->query($sql);
		}

	}

	$sql = "SELECT * FROM cadcam_standardtime WHERE partId = ".$_GET['partId']."";
	$queryStandardTime = $db->query($sql);
	if($queryStandardTime->num_rows > 0)
	{
		while($resultStandardTime = $queryStandardTime->fetch_assoc())
		{
			$sql = "INSERT INTO cadcam_standardtime (partID, processUnitId, unitCount, processCode) VALUES (".$getmaxIdResult['maxId'].", ".$resultStandardTime['processUnitId'].", ".$resultStandardTime['unitCount'].", ".$resultStandardTime['processCode'].")";
			$addStandardTime = $db->query($sql);
		}		
	}
	
	//--------------------------- INSERT INTO cadcam_partprocessnote ---------------------------	
	$sql = "SELECT processCode, patternId, remarks FROM cadcam_partprocessnote WHERE partId = ".$_GET['partId'];
	$getProcessNote = $db->query($sql);
	if($getProcessNote->num_rows > 0)
	{
		while($getProcessNoteResult = $getProcessNote->fetch_array())
		{
			$sql = "INSERT INTO cadcam_partprocessnote (partId, processCode, patternId, remarks) VALUES (".$getmaxIdResult['maxId'].", ".$getProcessNoteResult['processCode'].", ".$getProcessNoteResult['patternId'].", '".$getProcessNoteResult['remarks']."') ";
			$insert = $db->query($sql);
		}
	}
	
	header("Location: anthony_editProduct.php?partId=".$getmaxIdResult['maxId']."&src=process&patternId=".$_GET['patternId']."");
}
?>
