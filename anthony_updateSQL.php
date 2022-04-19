<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT customerId FROM cadcam_parts WHERE partId = ".$_GET['partId']." ";
$getCustomerId = $db->query($sql);
$getCustomerIdResult = $getCustomerId->fetch_array();
	
$oldPartName = '';
$oldMaterialSpecId = '0';
$oldMaterialSpecDetail = '';
$oldPVC = '0';
$oldX = '0.00';
$oldY = '0.00';
if($_POST['pvc'] == '')
{
	$_POST['pvc'] = 0;
}
else
{
	$_POST['pvc'] = 1;
}
if($_POST['BendRobot'] == ''){	$_POST['BendRobot'] = 0; }
if($_POST['WeldRobot'] == ''){	$_POST['WeldRobot'] = 0; }
$sql = "SELECT partName, materialSpecId, materialSpecDetail, PVC, x, y, itemWeight, itemArea, itemLength, itemWidth, itemHeight, treatmentId, bendRobot, weldRobot FROM cadcam_parts where partId = ".$_GET['partId']." ";
$getParts = $db->query($sql);
if($getParts->num_rows > 0)
{
	$getPartsResult = $getParts->fetch_array();
	$oldPartName = $getPartsResult['partName'];
	$oldMaterialSpecId = $getPartsResult['materialSpecId'];
	$oldMaterialSpecDetail = $getPartsResult['materialSpecDetail'];
	$oldPVC = $getPartsResult['PVC'];
	$oldX = $getPartsResult['x'];
	$oldY = $getPartsResult['y'];
	$oldWeight = $getPartsResult['itemWeight'];
	$oldArea = $getPartsResult['itemArea'];
	$oldLength = $getPartsResult['itemLength'];
	$oldWidth = $getPartsResult['itemWidth'];
	$oldHeight = $getPartsResult['itemHeight'];
	$oldTreatmentId = $getPartsResult['treatmentId'];
	$oldBendRobot = $getPartsResult['bendRobot'];
	$oldWeldRobot = $getPartsResult['weldRobot'];
}
//$sql = "SELECT materialSpecId, metalType FROM cadcam_materialspecs WHERE metalType = '".$_POST['type']."' AND metalThickness = ".$_POST['thickness']." AND metalLength = ".$_POST['length']." AND metalWidth = ".$_POST['width']." ";
//$sql = "SELECT materialSpecId, metalType FROM cadcam_materialspecs WHERE metalType = '".$_POST['type']."' AND metalThickness = ".$_POST['thickness']." AND metalLength = ".$_POST['matX']." AND metalWidth = ".$_POST['matY']." ";
$sql = "SELECT materialSpecId, metalType FROM cadcam_materialspecs WHERE metalType = '".$_POST['type']."' AND metalThickness = ".$_POST['thickness']." ORDER BY materialSpecId DESC LIMIT 1 ";
$getMaterialSpecs = $db->query($sql);
if($getMaterialSpecs->num_rows > 0)
{
	$getMaterialSpecsResult = $getMaterialSpecs->fetch_array();
	$oldMaterialSpecId = $getMaterialSpecsResult['materialSpecId'];
	$oldMetalType = $getMaterialSpecsResult['metalType'];
	$oldMetalLength = $getMaterialSpecsResult['metalLength'];
	$oldMetalWidth = $getMaterialSpecsResult['metalWidth'];
}

if(isset($_POST['matSpecsId']) AND $_POST['matSpecsId'] > 0)
{
	//;cadcam_materialspecs;
	$sql = "SELECT materialTypeId, metalThickness FROM cadcam_materialspecs WHERE materialSpecId = ".$oldMaterialSpecId." LIMIT 1";
	$queryMaterialSpecs = $db->query($sql);
	if($queryMaterialSpecs AND $queryMaterialSpecs->num_rows > 0)
	{
		$resultMaterialSpecs = $queryMaterialSpecs->fetch_assoc();
		$oldMaterialTypeId = $resultMaterialSpecs['materialTypeId'];
		$oldMetalThickness = $resultMaterialSpecs['metalThickness'];
		
		$sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$oldMaterialTypeId." LIMIT 1";
		$queryMaterialType = $db->query($sql);
		if($queryMaterialType AND $queryMaterialType->num_rows > 0)
		{
			$resultMaterialType = $queryMaterialType->fetch_assoc();
			$oldMaterialType = $resultMaterialType['materialType'];
		}
	}
	
	$sql = "SELECT materialTypeId, metalThickness FROM cadcam_materialspecs WHERE materialSpecId = ".$_POST['matSpecsId']." LIMIT 1";
	$queryMaterialSpecs = $db->query($sql);
	if($queryMaterialSpecs AND $queryMaterialSpecs->num_rows > 0)
	{
		$resultMaterialSpecs = $queryMaterialSpecs->fetch_assoc();
		$materialTypeId = $resultMaterialSpecs['materialTypeId'];
		$metalThickness = $resultMaterialSpecs['metalThickness'];
		
		$materialType = "";
		$sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$materialTypeId." LIMIT 1";
		$queryMaterialType = $db->query($sql);
		if($queryMaterialType AND $queryMaterialType->num_rows > 0)
		{
			$resultMaterialType = $queryMaterialType->fetch_assoc();
			$materialType = $resultMaterialType['materialType'];
		}
	}
	
	if($oldMaterialSpecId != $_POST['matSpecsId'])
	{
		$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 6, '".$oldMaterialSpecId."=".$oldMaterialType." t".$oldMetalThickness."', '".$_POST['matSpecsId']."=".$materialType." t".$metalThickness."', '".$userIP."', '".$userID."', 'UPDATE materialSpecId') ";
		$insert = $db->query($sql);
	}
	//;cadcam_materialspecs;
}

// ---------- this code will be deleted ---------- //
//~ $sql = "SELECT totalSurfaceClear, totalSurfacePrime, totalSurfacePassivation FROM cadcam_subcondimension WHERE partId = ".$_GET['partId']." ";
//~ $getSubconDimension = $db->query($sql);
//~ if($getSubconDimension->num_rows > 0)
//~ {
	//~ $getSubconDimensionResult = $getSubconDimension->fetch_array();
	//~ $oldClear = $getSubconDimensionResult['totalSurfaceClear'];
	//~ $oldPrime = $getSubconDimensionResult['totalSurfacePrime'];
	//~ $oldPassivation = $getSubconDimensionResult['totalSurfacePassivation'];
//~ }
// -------- END this code will be deleted -------- //

if($oldPartName != $_POST['partName'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 2, '".$oldPartName."', '".$_POST['partName']."', '".$userIP."', '".$userID."', 'UPDATE partName') ";
	$insert = $db->query($sql);
}
//~ if($oldMetalType != $_POST['matSpecs'])
//~ {
	//~ $sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 6, '".$oldMetalType."', '".$_POST['matSpecs']."', '".$userIP."', '".$userID."', 'UPDATE materialSpecId') ";
	//~ $insert = $db->query($sql);
//~ }
if($oldMaterialSpecDetail != $_POST['matDetail'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 7, '".$oldMaterialSpecDetail."', '".$_POST['matDetail']."', '".$userIP."', '".$userID."', 'UPDATE materialSpecDetail') ";
	$insert = $db->query($sql);
}
if($oldPVC != $_POST['pvc'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 7, '".$oldPVC."', '".$_POST['pvc']."', '".$userIP."', '".$userID."', 'UPDATE PVC') ";
	$insert = $db->query($sql);
}

//~ if($oldMetalLength != $_POST['matX'])
//~ {
	//~ $sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 26, '"."metalLength = ".$oldMetalLength."', '"."metalLength = ".$_POST['matX']."', '".$userIP."', '".$userID."', 'UPDATE metalLength') ";
	//~ $insert = $db->query($sql);
//~ }
//~ if($oldMetalWidth != $_POST['matY'])
//~ {
	//~ $sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 26, '"."metalWidth = ".$oldMetalWidth."', '"."metalWidth = ".$_POST['matY']."', '".$userIP."', '".$userID."', 'UPDATE metalWidth') ";
	//~ $insert = $db->query($sql);
//~ }

if($oldX != $_POST['itemX'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 26, '"."Item X = ".$oldX."', '"."Item X = ".$_POST['itemX']."', '".$userIP."', '".$userID."', 'UPDATE Item X') ";
	$insert = $db->query($sql);
}
if($oldY != $_POST['itemY'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 26, '"."Item Y = ".$oldY."', '"."Item Y = ".$_POST['itemY']."', '".$userIP."', '".$userID."', 'UPDATE Item  Y') ";
	$insert = $db->query($sql);
}
if($oldWeight != $_POST['weight'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 26, '"."Weight = ".$oldWeight."', '"."Weight = ".$_POST['weight']."', '".$userIP."', '".$userID."', 'UPDATE itemWeight') ";
	$insert = $db->query($sql);
}
if($oldArea != $_POST['area'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 26, '"."Area = ".$oldArea."', '"."Area = ".$_POST['area']."', '".$userIP."', '".$userID."', 'UPDATE itemArea') ";
	$insert = $db->query($sql);
}
if($oldLength != $_POST['length'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 26, '"."Length = ".$oldLength."', '"."Length = ".$_POST['length']."', '".$userIP."', '".$userID."', 'UPDATE itemLength') ";
	$insert = $db->query($sql);
}
if($oldWidth != $_POST['width'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 26, '"."Width = ".$oldWidth."', '"."Width = ".$_POST['width']."', '".$userIP."', '".$userID."', 'UPDATE itemWidth') ";
	$insert = $db->query($sql);
}
if($oldHeight != $_POST['height'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 26, '"."Height = ".$oldHeight."', '"."Width = ".$_POST['height']."', '".$userIP."', '".$userID."', 'UPDATE itemHeight') ";
	$insert = $db->query($sql);
}
if($oldTreatmentId != $_POST['treatmentName'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 27, '".$oldTreatmentId."', '".$_POST['treatmentName']."', '".$userIP."', '".$userID."', 'UPDATE treatmentId') ";
	$insert = $db->query($sql);
}
if($oldBendRobot != $_POST['BendRobot'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 42, '"."bendRobot = ".$oldBendRobot."', '"."bendRobot = ".$_POST['BendRobot']."', '".$userIP."', '".$userID."', 'UPDATE bendRobot') ";
	$insert = $db->query($sql);
}
if($oldWeldRobot != $_POST['WeldRobot'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 43, '"."weldRobot = ".$oldWeldRobot."', '"."weldRobot = ".$_POST['WeldRobot']."', '".$userIP."', '".$userID."', 'UPDATE weldRobot') ";
	$insert = $db->query($sql);
}
// ---------- this code will be deleted ---------- //
//~ if($oldClear != $_POST['anodize'])
//~ {
	//~ $sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 28, '".$oldClear."', '".$_POST['anodize']."', '".$userIP."', '".$userID."', 'UPDATE totalSurfaceClear') ";
	//~ $insert = $db->query($sql);
//~ }
//~ if($oldPrime != $_POST['prime'])
//~ {
	//~ $sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 29, '".$oldPrime."', '".$_POST['prime']."', '".$userIP."', '".$userID."', 'UPDATE totalSurfacePrime') ";
	//~ $insert = $db->query($sql);
//~ }
//~ if($oldPassivation != $_POST['passivation'])
//~ {
	//~ $sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 30, '".$oldPassivation."', '".$_POST['passivation']."', '".$userIP."', '".$userID."', 'UPDATE totalSurfacePassivation') ";
	//~ $insert = $db->query($sql);
//~ }
// -------- END this code will be deleted -------- //

$sql = "SELECT processCode, surfaceArea FROM cadcam_subconlist WHERE partId = ".$_GET['partId']." ";
$querySubconList = $db->query($sql);
if($querySubconList AND $querySubconList->num_rows > 0)
{
	$resultSubconList = $querySubconList->fetch_assoc();
	if($resultSubconList['processCode']==270 AND $resultSubconList['surfaceArea']!=$_POST['anodize'])
	{
		$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 28, '".$resultSubconList['surfaceArea']."', '".$_POST['anodize']."', '".$userIP."', '".$userID."', 'UPDATE totalSurfaceClear') ";
		$insert = $db->query($sql);
	}
	else if($resultSubconList['processCode']==272 AND $resultSubconList['surfaceArea']!=$_POST['prime'])
	{
		$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 29, '".$resultSubconList['surfaceArea']."', '".$_POST['prime']."', '".$userIP."', '".$userID."', 'UPDATE totalSurfacePrime') ";
		$insert = $db->query($sql);
	}
	else if(in_array($resultSubconList['processCode'],array(251,284)) AND $resultSubconList['surfaceArea']!=$_POST['passivation'])
	{
		$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 30, '".$resultSubconList['surfaceArea']."', '".$_POST['passivation']."', '".$userIP."', '".$userID."', 'UPDATE totalSurfacePassivation') ";
		$insert = $db->query($sql);
	}
}

$sql = "UPDATE cadcam_parts SET partName = '".$db->real_escape_string($_POST['partName'])."',
							materialSpecId = ".$_POST['matSpecsId'].",
							materialSpecDetail = '".$_POST['matDetail']."',
							PVC = ".$_POST['pvc'].",
							x = '".$_POST['itemX']."',
							y = '".$_POST['itemY']."',
							itemWeight = '".$_POST['weight']."',
							itemArea = '".$_POST['area']."',
							itemLength = '".$_POST['length']."',
							itemWidth = '".$_POST['width']."',
							itemHeight = '".$_POST['height']."',
							treatmentId = ".$_POST['treatmentName'].",
							bendRobot = ".$_POST['BendRobot'].",
							weldRobot = ".$_POST['WeldRobot']."
					  WHERE partId = ".$_GET['partId']." ";
$updateParts = $db->query($sql);
// echo $sql;
$sqlRobot = "UPDATE system_processreview SET bendRobot =".$_POST['BendRobot'].", weldRobot =".$_POST['WeldRobot']." WHERE partId = ".$_GET['partId'];
$queryUpdateRobot = $db->query($sqlRobot);
// $sqlRobotA = "UPDATE view_workschedule SET robotProgram =".$_POST['BendRobot']." WHERE partId = ".$_GET['partId']." and processSection=1";
// $queryUpdateRobotA = $db->query($sqlRobotA);

/*
//$sql = "SELECT materialSpecId FROM cadcam_materialspecs WHERE metalType LIKE '".$_POST['matSpecs']."' AND metalThickness = '".trim($_POST['metalThickness'])."' AND metalLength = '".trim($_POST['matX'])."' AND metalWidth = '".trim($_POST['matY'])."' ORDER BY materialSpecId DESC LIMIT 1 ";
$sql = "SELECT materialSpecId FROM cadcam_materialspecs WHERE trim(metalType) LIKE '".trim($_POST['matSpecs'])."' AND metalThickness = '".trim($_POST['metalThickness'])."' ORDER BY materialSpecId DESC LIMIT 1";
$getMetalType = $db->query($sql);
if($getMetalType->num_rows > 0)
{
	$getMetalTypeResult = $getMetalType->fetch_array();

	$sql = "UPDATE cadcam_parts SET partName = '".$db->real_escape_string($_POST['partName'])."',
								materialSpecId = ".$getMetalTypeResult['materialSpecId'].",
								materialSpecDetail = '".$_POST['matDetail']."',
								PVC = ".$_POST['pvc'].",
								x = '".$_POST['itemX']."',
								y = '".$_POST['itemY']."',
								itemWeight = '".$_POST['weight']."',
								itemArea = '".$_POST['area']."',
								itemLength = '".$_POST['length']."',
								itemWidth = '".$_POST['width']."',
								itemHeight = '".$_POST['height']."',
								treatmentId = ".$_POST['treatmentName']."
						  WHERE partId = ".$_GET['partId']." ";
	$updateParts = $db->query($sql);
}
else
{	
	//$sql = "INSERT INTO cadcam_materialspecs (metalType, metalThickness, metalLength, metalWidth) VALUES ('".$_POST['matSpecs']."', '".$_POST['metalThickness']."', '".$_POST['matX']."', '".$_POST['matY']."') ";
	$sql = "INSERT INTO cadcam_materialspecs (metalType, metalThickness) VALUES ('".$_POST['matSpecs']."', '".$_POST['metalThickness']."') ";
	$insert = $db->query($sql);
	
	//$sql = "SELECT materialSpecId FROM cadcam_materialspecs WHERE metalType LIKE '".$_POST['matSpecs']."' AND metalThickness = '".$_POST['metalThickness']."' AND metalLength = '".$_POST['matX']."' AND metalWidth = '".$_POST['matY']."' ";
	$sql = "SELECT materialSpecId FROM cadcam_materialspecs WHERE metalType LIKE '".$_POST['matSpecs']."' AND metalThickness = '".$_POST['metalThickness']."' ORDER BY materialSpecId DESC LIMIT 1 ";
	$getMaterialSpecsId = $db->query($sql);
	$getMaterialSpecsIdResult = $getMaterialSpecsId->fetch_array();
	
	$sql = "UPDATE cadcam_parts SET partName = '".$db->real_escape_string($_POST['partName'])."',
								materialSpecId = ".$getMaterialSpecsIdResult['materialSpecId'].",
								materialSpecDetail = '".$_POST['matDetail']."',
								PVC = ".$_POST['pvc'].",
								x = '".$_POST['itemX']."',
								y = '".$_POST['itemY']."',
								itemWeight = '".$_POST['weight']."',
								itemArea = '".$_POST['area']."',
								itemLength = '".$_POST['length']."',
								itemWidth = '".$_POST['width']."',
								itemHeight = '".$_POST['height']."',
								treatmentId = ".$_POST['treatmentName']."
						  WHERE partId = ".$_GET['partId']." ";
	$updateParts = $db->query($sql);
}
*/

if($_GET['country'] == 2)
{
	// ------------------------------------------------------------------------- Ace Sandoval --------------------------------------------------------------------------------
	// ----------------------------------------- Copy Material Specification and Item Size to Process Details of TPP, Laser, etc ---------------------------------------------
	$thickness = "";
	if($_POST['inputThickness']!=0)
	{
		$thickness = " t".$_POST['inputThickness'];
	}
	
	// -------------------------------------------- Added By Ace Copied From Updat Form ---------------------------------
	$materialType = "";
	$sql = "SELECT `materialTypeId`, `materialType` FROM `engineering_materialtype` WHERE materialTypeId = ".$_POST['matSpecs'];
	$queryMaterialType = $db->query($sql);
	if($queryMaterialType AND $queryMaterialType->num_rows > 0)
	{
		$resultMaterialType = $queryMaterialType->fetch_assoc();	
		$materialType = $resultMaterialType['materialType'];	
	}	
	
	$processDetail = $_POST['itemX']." X ".$_POST['itemY']." ".$materialType."".$thickness." ".$_POST['matDetail'];
	
	$sql = "SELECT processCode, patternId from cadcam_partprocess where partId = ".$_GET['partId']." AND processSection=3";
	$partProcessQuery=$db->query($sql);
	while($partProcessQueryResult=$partProcessQuery->fetch_array())
	{
		$sql = "SELECT noteId, remarks from cadcam_partprocessnote WHERE processCode = ".$partProcessQueryResult['processCode']." AND partId = ".$_GET['partId']." AND remarks like '%X%'";
		$partProcessNoteQuery=$db->query($sql);
		if($partProcessNoteQuery->num_rows>0)
		{
			while($partProcessNoteQueryResult = $partProcessNoteQuery->fetch_assoc())
			{
				$sql = "UPDATE cadcam_partprocessnote SET remarks = '".$processDetail."' WHERE noteId = ".$partProcessNoteQueryResult['noteId'];
				$updateQuery=$db->query($sql);
			}
		}
		else
		{
			$sql = "INSERT INTO cadcam_partprocessnote (partId, processCode, patternId, remarks) VALUES (".$_GET['partId'].",".$partProcessQueryResult['processCode'].",".$partProcessQueryResult['patternId'].", '".$processDetail."')";
			$insertQuery=$db->query($sql);
		}		
		
		$sql = "INSERT INTO cadcam_partprocessnote (partId, processCode, patternId, remarks) VALUES (".$_GET['partId'].",".$partProcessQueryResult['processCode'].",".$partProcessQueryResult['patternId'].", '".$processDetail."')";
		$insertQuery=$db->query($sql);		
	}
	// ----------------------------------------- End of Copy Material Specification and Item Size to Process Details of TPP, Laser, etc --------------------------------------
}

if(isset($_GET['source']) AND $_GET['source'] == 'pending')
{ 
	$source = '&source=pending';
}

/*$sql = "INSERT INTO `engineering_partsdetailsalert`(`partId`, `alertDate`, `status`, `employeeFixed`) VALUES ('".$_GET['partId']."', now(), 0, '".$_SESSION['idNumber']."')";
$queryInsert = $db->query($sql);*/

// --------------------------------------------------- Redirect To Other Program That Would Automatically Insert Packaging Material For Aero Parts Commented By Ace Sandoval (August 25,2018) Change Of Packaging Sizes) -----------
//header("Location: ../Others/Raymond/raymond_customerDetails.php?partId=".$_GET['partId']."&src=process".$source."&patternId=".$_GET['patternId']."");

header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=process".$source."&patternId=".$_GET['patternId']."");
?>
