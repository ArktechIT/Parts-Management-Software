<?php
include("../Common Data/PHP Modules/mysqliConnection.php");

if($_POST['pvc'] == 'on')
{
	$pvc = 1;
}
else
{
	$pvc = 0;
}

$errorFlag = 0;
$sql = "SELECT partId FROM cadcam_parts WHERE partNumber LIKE '".$_POST['partNumber']."' AND revisionId LIKE '".strtoupper($_POST['revision'])."' AND trim(partNote) LIKE '".trim($_POST['partNote'])."' AND customerId = ".$_POST['customer']." ";
$getParts = $db->query($sql);
if($getParts->num_rows < 1)
{
	if(isset($_POST['matSpecsId']))//;cadcam_materialspecs;
	{
		$matSpec = $_POST['matSpecsId'];
	}
	//~ else
	//~ {
		//~ $sql = "SELECT materialSpecId FROM cadcam_materialspecs WHERE metalType = '".$_POST['matSpecs']."' AND metalThickness = '".$_POST['thickness']."' LIMIT 1 ";
		//~ $getMaterialSpecId = $db->query($sql);
		//~ if($getMaterialSpecId->num_rows > 0)
		//~ {
			//~ $getMaterialSpecIdResult = $getMaterialSpecId->fetch_array();
			
			//~ $matSpec = $getMaterialSpecIdResult['materialSpecId'];
		//~ }
		//~ else
		//~ {
			//~ $sql = "INSERT INTO cadcam_materialspecs (metalType, metalThickness) VALUES ('".$_POST['matSpecs']."', '".$_POST['thickness']."') ";
			//~ $insert = $db->query($sql);
			
			//~ $sql = "SELECT MAX(materialSpecId) AS maxSpecId FROM cadcam_materialspecs WHERE metalType LIKE '".$_POST['matSpecs']."' AND metalThickness = '".$_POST['thickness']."' ";
			//~ $getMaxSpecId = $db->query($sql);
			//~ $getMaxSpecIdResult = $getMaxSpecId->fetch_array();
			
			//~ $matSpec = $getMaxSpecIdResult['maxSpecId'];
		//~ }
	//~ }
	
	$sql = "INSERT INTO cadcam_parts(partNumber, partName, revisionId, partNote, customerId, materialSpecId, materialSpecDetail, partDrawing, status, PVC, x, y, itemWeight, itemArea, itemLength, itemWidth, itemHeight, treatmentId)
			VALUES
			('".$_POST['partNumber']."', '".$_POST['partName']."', '".strtoupper($_POST['revision'])."', '".trim($_POST['partNote'])."', ".$_POST['customer'].", ".$matSpec.", '".$_POST['matRemarks']."', 0, 0, ".$pvc.", '".$_POST['itemX']."', '".$_POST['itemY']."', '".$_POST['weight']."', '".$_POST['area']."', '".$_POST['length']."', '".$_POST['width']."', '".$_POST['height']."', ".$_POST['treatmentName'].") ";
	$insert = $db->query($sql);
	
	$sql = "SELECT MAX(partId) AS maxPartId FROM cadcam_parts ";
	$getMaxPartId = $db->query($sql);
	$getMaxPartIdResult = $getMaxPartId->fetch_array();
	
	header("Location: ../Others/Raymond/raymond_customerDetails.php?partId=".$getMaxPartIdResult['maxPartId']."&src=process");
	//~ header("Location: anthony_editProduct.php?partId=".$getMaxPartIdResult['maxPartId']."&src=process");
}
else
{
	$errorFlag = 1;
	$errorBank = array($_POST['customer'], $_POST['partNumber'], $_POST['partName'], $_POST['revision'], $_POST['partNote'], $_POST['matSpecs'], $_POST['matRemarks'], $_POST['treatmentName'], $pvc, $_POST['itemX'], $_POST['itemY'], $_POST['weight'], $_POST['area'], $_POST['length'], $_POST['width'], $_POST['height']);
	header("Location: anthony_addParts.php?error=".$errorFlag."&data=".implode('`', $errorBank)."");
}
?>
