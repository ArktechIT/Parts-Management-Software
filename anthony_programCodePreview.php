<?php
include('../Common Data/PHP Modules/mysqliConnection.php');
ini_set("display_errors", "on");

$sql = "SELECT partNumber, revisionId, customerId, materialSpecId, x, y FROM cadcam_parts WHERE partId = ".$_GET['partId']." ";
$getParts = $db->query($sql);
$getPartsResult = $getParts->fetch_array();
$partNumber = $getPartsResult['partNumber'];
$revisionId = $getPartsResult['revisionId'];
$customerId = $getPartsResult['customerId'];
$materialSpecId = $getPartsResult['materialSpecId'];
$itemX = $getPartsResult['x'];
$itemY = $getPartsResult['y'];	

//;cadcam_materialspecs;
$sql = "SELECT materialTypeId, metalType, metalThickness FROM cadcam_materialspecs WHERE materialSpecId = ".$materialSpecId." ";
$getMaterialSpecs = $db->query($sql);
$getMaterialSpecsResult = $getMaterialSpecs->fetch_array();
$materialTypeId = $getMaterialSpecsResult['materialTypeId'];	 
$metalType = $getMaterialSpecsResult['metalType'];	 
$metalThickness = $getMaterialSpecsResult['metalThickness'];

$sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$materialTypeId." LIMIT 1";
$queryMaterialType = $db->query($sql);
if($queryMaterialType AND $queryMaterialType->num_rows > 0)
{
	$resultMaterialType = $queryMaterialType->fetch_assoc();
	$metalType = $resultMaterialType['materialType'];
}
//;cadcam_materialspecs;

$sql = "SELECT programCode, programX, programY, programI, programJ, programGZero FROM engineering_programs WHERE partId = ".$_GET['partId']." ";
$getPrograms = $db->query($sql);
$getProgramsResult = $getPrograms->fetch_array();
$programCode = $getProgramsResult['programCode'];
if($getProgramsResult['programX'] != '')
{
	$programX = $getProgramsResult['programX'];
}
else
{
	$programX = 15;
}

if($getProgramsResult['programY'] != '')
{
	$programY = $getProgramsResult['programY'];
}
else
{
	$programY = 65;
}

if($getProgramsResult['programI'] != '')
{
	$programI = $getProgramsResult['programI'];
}
else
{
	$programI = 17;
}

if($getProgramsResult['programJ'] != '')
{
	$programJ = $getProgramsResult['programJ'];
}
else
{
	$programJ = 17;
}

$m61='M61';
$f1='F1';
$codeSummary = "(".$partNumber.$revisionId.")";
/* remove special code by sir Mar 2019-07-19
if(strpos($metalType, "5052") !== false)
{
	if($metalThickness >= 2)
	{
		$m61 = 'M62';
	}
	if($metalThickness >= 2.6)
	{
		$f1 = 'F3';
	}
}
else if(strpos($metalType, "SECC") !== false)
{
	if($metalThickness >= 2)
	{
		$m61='M62';
	}
	if($metalThickness >= 3)
	{
		$f1 = 'F3';
	}
}
else if(strpos($metalType, "SPCC") !== false)
{
	if($metalThickness >= 2)
	{
		$m61 = 'M62';
	}
	if($metalThickness >= 3)
	{
		$f1 = 'F3';
	}
}
else if(strpos($metalType, "304") !== false)
{
	if($metalThickness >= 1.5)
	{
		$m61 = 'M62';
	}
	if($metalThickness >= 2)
	{
		$f1 = 'F3';
	}
}
else if(strpos($metalType, "430") !== false)
{
	if($metalThickness >= 1.5)
	{
		$m61 = 'M62';
	}
	if($metalThickness >= 2)
	{
		$f1 = 'F3';
	}
}*/
$codeSummary=$codeSummary."\r\nG90G92X-2000.Y1000.\r\nM36\r\nM38\r\nM50\r\nM58\r\n".$m61."\r\nM66\r\nM68\r\n".$f1;

//~ $itemX2 = explode('.', $itemX);
//~ if($itemX2[1] > 0)
//~ {
	//~ $itemX = $itemX2[0];
//~ }
//~ $itemY2 = explode('.', $itemY);
//~ if($itemY2[1] > 0)
//~ {
	//~ $itemY = $itemY2[0];
//~ }

$codeSummary = $codeSummary."\r\nG98X-".$programX;

if(preg_match("/\./", $programX))
{
	$codeSummary=$codeSummary."Y";
}
else
{
	$codeSummary=$codeSummary.".Y";
}

$codeSummary=$codeSummary.$programY;

if(preg_match("/\./", $programY))
{
	$codeSummary=$codeSummary."I";
}
else
{
	$codeSummary=$codeSummary.".I";
}

$codeSummary=$codeSummary.($itemX+$programI);

if(preg_match("/\./", ($itemX+$programI)))
{ 
	$codeSummary = $codeSummary."J"; 
}
else
{ 
	$codeSummary=$codeSummary.".J";
}

$codeSummary=$codeSummary."".($itemY+$programJ);

if(preg_match("/\./", $itemY+$programJ))
{
	$codeSummary = $codeSummary."P0K0";
}
else
{
	$codeSummary = $codeSummary.".P0K0";
}

if($getProgramsResult['programGZero'] != 1)
{	
	$codeSummary = $codeSummary."\r\nG00";
}

$codeSummary = $codeSummary."\r\nU50";
$codeSummary = $codeSummary."\r\n".$programCode;
$codeSummary = $codeSummary."\r\nV50\r\nG75W50Q3\r\nG50\r\n%"; 
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
	<link rel = "stylesheet" type = "text/css" href = "../Common Data/anthony.css">
</head>
<body>
<form>
	<textarea rows = "31" cols = "40" readonly><?php echo $codeSummary; ?></textarea>
</form>
</body>
</html>
