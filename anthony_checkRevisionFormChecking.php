<?php
include('../Common Data/PHP Modules/mysqliConnection.php');
ini_set("display_errors", "on");

$sql = "SELECT * FROM cadcam_parts WHERE partId = ".$_GET['partId']." ";
$getRevision = $db->query($sql);
$getRevisionResult = $getRevision->fetch_array();

$sql = "SELECT partNumber, revisionId FROM cadcam_parts WHERE partNumber like '".$getRevisionResult['partNumber']."' AND revisionId like '".strtoupper($_POST['revision'])."' ";
$getParts = $db->query($sql);
// --------------------------- Execute If Parts Already Exist ----------------------------------------------
if($getParts->num_rows > 0)
{
	header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=process&duplicateFlag=1&patternId=".$_GET['patternId']."");
}
// -------------------------- End Of Execute If Parts Already Exist -----------------------------------------
else
{
	$sql = "INSERT INTO cadcam_parts (partNumber, partName, revisionId, customerId, quantityPerSheet, materialSpecId, materialSpecDetail, partDrawing, status, x, y, treatmentId) VALUES ('".$getRevisionResult['partNumber']."', '".$getRevisionResult['partName']."', '".strtoupper($_POST['revision'])."', ".$getRevisionResult['customerId'].", ".$getRevisionResult['quantityPerSheet'].", ".$getRevisionResult['materialSpecId'].", '".$getRevisionResult['materialSpecDetail']."', '".$getRevisionResult['partDrawing']."', ".$getRevisionResult['status'].", ".$getRevisionResult['x'].", ".$getRevisionResult['y'].", ".$getRevisionResult['treatmentId'].") ";
	$add = $db->query($sql);
 
	$sql = "SELECT max(partId) AS maxId FROM cadcam_parts WHERE partNumber = '".$getRevisionResult['partNumber']."' AND revisionId = '".strtoupper($_POST['revision'])."' ";
	$getmaxId = $db->query($sql);
	$getmaxIdResult = $getmaxId->fetch_array();

	//--------------------------- Copy Process From Old Revision ---------------------------
	$sql = "SELECT * FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." ";
	$getpartId = $db->query($sql);
	while($getpartIdResult = $getpartId->fetch_array())
	{
		$sql = "INSERT INTO cadcam_partprocess (partId, processOrder, processCode, processDetail, processSection, setupTime, cycleTime, patternId) VALUES (".$getmaxIdResult['maxId'].", ".$getpartIdResult['processOrder'].", ".$getpartIdResult['processCode'].", '".$getpartIdResult['processDetail']."', '".$getpartIdResult['processSection']."', ".$getpartIdResult['setupTime'].", ".$getpartIdResult['cycleTime'].", ".$getpartIdResult['patternId'].") ";
		$addPartProcess = $db->query($sql);
	}
	// -------------------------- End Of Copy Process From Old Revision -------------------
	
	//--------------------------- Copy Subparts From Old Revision ---------------------------
	$sql = "SELECT * FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." ";
	$getSubParts = $db->query($sql);
	while($getSubPartsResult = $getSubParts->fetch_array())
	{
		$sql = "INSERT INTO cadcam_subparts (parentId, childId, quantity, identifier) VALUES (".$getmaxIdResult['maxId'].", ".$getSubPartsResult['childId'].", ".$getSubPartsResult['quantity'].", ".$getSubPartsResult['identifier'].") ";
		$addSubparts = $db->query($sql);
	}
	// ------------------------- End Of Copy Subparts From Old Revision -------------------
	
	// ------------------------- Copy Subcon From Old Revision ----------------------------
	$sql = "SELECT * FROM cadcam_subconlist WHERE partId = ".$_GET['partId']." ";
	$getSubconList = $db->query($sql);
	while($getSubconListResult = $getSubconList->fetch_array())
	{
		$sql = "INSERT INTO cadcam_subconlist (partId, subconId, processCode) VALUES (".$getmaxIdResult['maxId'].", ".$getSubconListResult['subconId'].", ".$getSubconListResult['processCode'].") ";
		$addSubconlist = $db->query($sql);
	}
	
	// kulang ng subcon processor KAPCO .. engineering_subconprocessor

	// -------------------------- End Of Copy Subcon From Old Revision --------------------
	
	// -------------------------- Copy Process Note From Old Revision ---------------------
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
	// -------------------------- End Of Copy Process Note From Old Revision ----------------
	
	// -------------------------- Link To Main Part Of Old Revision (Ace Dec 16, 2017) -------------------------
	$sql = "SELECT * FROM cadcam_subparts WHERE childId = ".$_GET['partId']." AND identifier = 1";
	$getMainParts = $db->query($sql);
	while($getMainPartsResult = $getMainParts->fetch_array())
	{
		$sql = "INSERT INTO cadcam_subparts (parentId, childId, quantity, identifier) VALUES (".$getMainPartsResult['parentId'].", ".$getmaxIdResult['maxId'].", ".$getMainPartsResult['quantity'].", ".$getMainPartsResult['identifier'].") ";
		$addMainParts = $db->query($sql);
	}	
	// -------------------------- End Of Link To Main Part Of Old Revision (Ace Dec 16, 2017) ------------------
	
	// --------------------------- Delete Link Of Old Revision From Main Parts ---------------------------------
	$sql = "DELETE FROM cadcam_subparts WHERE childId = ".$_GET['partId']." AND identifier = 1";
	$deleteQuery = $db->query($sql);
	// --------------------------- End Of Delete Link Of Old Revision From Main Parts --------------------------
	
	// -------------------------- Redirect To Parts Details ---------------------------------
	header("Location: anthony_editProduct.php?partId=".$getmaxIdResult['maxId']."&src=process&patternId=".$_GET['patternId']."");
	// -------------------------- End Of Redirect To Parts Details --------------------------
}
?>
