<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel='' type='text/css' href='../Common Data/anthony.css'>
<?php
SESSION_START();
include('../Common Data/PHP Modules/mysqliConnection.php');
include('../Common Data/PHP Modules/anthony_retrieveText.php');

if(isset($_POST['clone']))
{	
	// ********************************** DELETE AND INSERT INTO cadcam_parts **********************************
	//$sql = "DELETE FROM cadcam_parts WHERE partId = ".$_GET['partId']." ";
	//$delete = $db->query($sql);
	
	//sql = "INSERT INTO cadcam_parts (partId, partNumber, partName, revisionId, customerId, quantityPerSheet, materialSpecId, materialSpecDetail, partDrawing, status, PVC, x, y, itemWeight, itemArea, itemLength, itemWidth, itemHeight, treatmentId) SELECT ".$_GET['partId'].", partNumber, partName, revisionId, customerId, quantityPerSheet, materialSpecId, materialSpecDetail, partDrawing, status, PVC, x, y, itemWeight, itemArea, itemLength, itemWidth, itemHeight, treatmentId FROM cadcam_parts WHERE partId = ".$_POST['partId']." ";
	//$insert = $db->query($sql);
	
	$sql = "SELECT quantityPerSheet, materialSpecId, materialSpecDetail, partDrawing, status, PVC, x, y, itemWeight, itemArea, itemLength, itemWidth, itemHeight, treatmentId FROM cadcam_parts WHERE partId = ".$_POST['partId']." ";
	$getGetParts = $db->query($sql);
	if($getGetParts->num_rows > 0)
	{
		$getGetPartsResult = $getGetParts->fetch_array();
		
		$sql = "UPDATE cadcam_parts SET quantityPerSheet = ".$getGetPartsResult['quantityPerSheet'].", 
										materialSpecId = ".$getGetPartsResult['materialSpecId'].",
										materialSpecDetail = '".$getGetPartsResult['materialSpecDetail']."',
										partDrawing = '".$getGetPartsResult['partDrawing']."',
										status = ".$getGetPartsResult['status'].",
										PVC = ".$getGetPartsResult['PVC'].",
										x = '".$getGetPartsResult['x']."',
										y = '".$getGetPartsResult['y']."',
										y = '".$getGetPartsResult['y']."',
										itemWeight = '".$getGetPartsResult['itemWeight']."',
										itemArea = '".$getGetPartsResult['itemArea']."',
										itemLength = '".$getGetPartsResult['itemLength']."',
										itemWidth = '".$getGetPartsResult['itemWidth']."',
										itemHeight = '".$getGetPartsResult['itemHeight']."',
										treatmentId = ".$getGetPartsResult['treatmentId']."
			WHERE partId = ".$_GET['partId']." ";
		$update = $db->query($sql);
	}
	
	// ********************************** DELETE AND INSERT INTO engineering_alternatematerial **********************************
	$sql = "DELETE FROM engineering_alternatematerial WHERE partId = ".$_GET['partId']." ";
	$delete = $db->query($sql);
	
	$sql = "SELECT materialSpecId FROM engineering_alternatematerial WHERE partId = ".$_POST['partId']." ";
	$getMaterialSpecId = $db->query($sql);
	if($getMaterialSpecId->num_rows > 0)
	{
		while($getMaterialSpecIdResult = $getMaterialSpecId->fetch_array())
		{
			$sql = "INSERT INTO engineering_alternatematerial (partId, materialSpecId) VALUES (".$_GET['partId'].", ".$getMaterialSpecIdResult['materialSpecId'].") ";
			$insert = $db->query($sql);
		}
	}
	
	// ********************************** DELETE AND INSERT INTO cadcam_partprocess **********************************
	$sql = "DELETE FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." ";
	$delete = $db->query($sql);
	
	$sql = "SELECT * FROM cadcam_partprocess WHERE partId = ".$_POST['partId']." ";
	$getPartProcess = $db->query($sql);
	if($getPartProcess->num_rows > 0)
	{
		while($getPartProcessResult = $getPartProcess->fetch_array())
		{
			$sql = "INSERT INTO cadcam_partprocess (partId, processOrder, processCode, processDetail, processSection, setupTime, cycleTime, patternId, toolId) VALUES (".$_GET['partId'].", ".$getPartProcessResult['processOrder'].", ".$getPartProcessResult['processCode'].", '".$getPartProcessResult['processDetail']."', ".$getPartProcessResult['processSection'].", '".$getPartProcessResult['setupTime']."', '".$getPartProcessResult['cycleTime']."', ".$getPartProcessResult['patternId'].", ".$getPartProcessResult['toolId'].") ";
			$insert = $db->query($sql);
		}
	}
	
	// ********************************** DELETE AND INSERT INTO cadcam_processtoolsdetails **********************************
	$sql = "DELETE FROM cadcam_processtoolsdetails WHERE partId = ".$_GET['partId']." ";
	$delete = $db->query($sql);
	
	$sql = "SELECT * FROM cadcam_processtoolsdetails WHERE partId = ".$_POST['partId']." ";
	$getProcessToolsDetails = $db->query($sql);
	if($getProcessToolsDetails->num_rows > 0)
	{
		while($getProcessToolsDetailsResult = $getProcessToolsDetails->fetch_array())
		{
			$sql = "INSERT INTO cadcam_processtoolsdetails (toolId, partId, processCode) VALUES (".$getProcessToolsDetailsResult['toolId'].", ".$_GET['partId'].", ".$getProcessToolsDetailsResult['processCode'].") ";
			$insert = $db->query($sql);
		}
	}
	
	// ********************************** DELETE AND INSERT INTO cadcam_subparts (Subparts and Accessories) **********************************
	$sql = "DELETE FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." ";
	$delete = $db->query($sql);
	
	$sql = "SELECT * FROM cadcam_subparts WHERE parentId = ".$_POST['partId']." ";
	$getSubParts = $db->query($sql);
	if($getSubParts->num_rows > 0)
	{
		while($getSubPartsResult = $getSubParts->fetch_array())
		{
			$sql = "INSERT INTO cadcam_subparts (parentId, childId, quantity, identifier, remarks, orderNumber) VALUES (".$_GET['partId'].", ".$getSubPartsResult['childId'].", ".$getSubPartsResult['quantity'].", ".$getSubPartsResult['identifier'].", '".$getSubPartsResult['remarks']."', ".$getSubPartsResult['orderNumber'].") ";
			$insert = $db->query($sql);
		}
	}
	
	// ********************************** DELETE AND INSERT INTO cadcam_subparts (Main Part) **********************************
	$sql = "DELETE FROM cadcam_subparts WHERE childId = ".$_GET['partId']." and identifier=1";
	$delete = $db->query($sql);
	
	$sql = "SELECT * FROM cadcam_subparts WHERE childId = ".$_POST['partId']." ";
	$getMainParts = $db->query($sql);
	if($getMainParts->num_rows > 0)
	{
		while($getMainPartsResult = $getMainParts->fetch_array())
		{
			$sql = "INSERT INTO cadcam_subparts (parentId, childId, quantity, identifier, remarks, orderNumber) VALUES (".$getMainPartsResult['parentId'].", ".$_GET['partId'].", ".$getMainPartsResult['quantity'].", ".$getMainPartsResult['identifier'].", '".$getMainPartsResult['remarks']."', ".$getMainPartsResult['orderNumber'].") ";
			$insert = $db->query($sql);
		}
	}
	
	// ********************************** DELETE AND INSERT INTO cadcam_subconlist **********************************
	$sql = "DELETE FROM cadcam_subconlist WHERE partId = ".$_GET['partId']." ";
	$delete = $db->query($sql);
	
	$sql = "SELECT * FROM cadcam_subconlist WHERE partId = ".$_POST['partId']." ";
	$getSubconList = $db->query($sql);
	if($getSubconList->num_rows > 0)
	{
		while($getSubconListResult = $getSubconList->fetch_array())
		{
			$sql = "INSERT INTO cadcam_subconlist (partId, subconId, processCode, surfaceArea, remarks, subconOrder) VALUES (".$_GET['partId'].", ".$getSubconListResult['subconId'].", ".$getSubconListResult['processCode'].", ".$getSubconListResult['surfaceArea'].", '".$getSubconListResult['remarks']."','".$getSubconListResult['subconOrder']."') ";
			$insert = $db->query($sql);
		}
	}
	
	// ********************************** DELETE AND INSERT INTO cadcam_partprocessnote **********************************
	$sql = "DELETE FROM cadcam_partprocessnote WHERE partId = ".$_GET['partId'];
	$delete = $db->query($sql);
	
	$sql = "SELECT processCode, patternId, remarks FROM cadcam_partprocessnote WHERE partId = ".$_POST['partId'];
	$getProcessNote = $db->query($sql);
	if($getProcessNote->num_rows > 0)
	{
		while($getProcessNoteResult = $getProcessNote->fetch_array())
		{
			$sql = "INSERT INTO cadcam_partprocessnote (partId, processCode, patternId, remarks) VALUES (".$_GET['partId'].", ".$getProcessNoteResult['processCode'].", ".$getProcessNoteResult['patternId'].", '".$getProcessNoteResult['remarks']."') ";
			$insert = $db->query($sql);
		}
	}
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 4, 16, '".$_GET['partId']."', '".$_POST['partId']."', '".$_SERVER['REMOTE_ADDR']."', '".$_SESSION['userID']."', 'Clone partId=".$_POST['partId']." to ".$_GET['partId']."') ";
	$insert = $db->query($sql);
	
	// ********************************** DELETE AND INSERT INTO engineering_programs **********************************
	//~ $sql = "DELETE FROM engineering_programs WHERE partId = ".$_GET['partId']." ";
	//~ echo $sql."<br>";
	//~ 
	//~ $sql = "INSERT INTO engineering_programs (programName, programCode, partId, programX, programY, programI, programJ, programGZero, programStatus, programDate, programmer, authorizer) SELECT programName, programCode, ".$_GET['partId'].", programX, programY, programI, programJ, programGZero, programStatus, programDate, programmer, authorizer FROM engineering_programs WHERE partId = ".$_POST['partId']." ";
	//~ echo $sql."<br>";
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", 'now()', 2, 1, 'Old partId=".$_GET['partId']."', 'New partId=".$_POST['partId']."', '".$_SERVER['REMOTE_ADDR']."', '".$_SESSION['userID']."', 'Clone ".$_GET['partId']." to ".$_POST['partId']."') ";
	$insert = $db->query($sql);
	
	header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
}

echo "<form action='anthony_cloneToThisPart.php?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."' method='POST'>";
	echo "<center><select name='partId' style='width:90%;'>";
	$sql = "SELECT partId, partNumber, revisionId, customerId FROM cadcam_parts WHERE status = 0 ORDER BY partNumber, partId";
	$getParts = $db->query($sql);
	while($getPartsResult = $getParts->fetch_array())
	{
		$customerAlias="";
		if($getPartsResult['customerId'] == 37)
		{
		$customerAlias=" [UK]";
		}
		if($getPartsResult['revisionId'] != '')
		{
			echo "<option value = '".$getPartsResult['partId']."'>".$getPartsResult['partNumber']." [ ".$getPartsResult['revisionId']." ]".$customerAlias." </option>";
		}
		else
		{
			echo "<option value = '".$getPartsResult['partId']."'>".$getPartsResult['partNumber'].$customerAlias."</option>";
		}
	}
	echo "</select></center><br>";
	
	echo "<center><input type='submit' value='".displayText('L1406')."' name='clone' class='anthony_submit'></center>";
echo "</form>";
?>
