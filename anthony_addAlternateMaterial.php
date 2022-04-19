<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

if(isset($_POST['add']))
{
	$sql = "INSERT INTO engineering_alternatematerial (partId, materialSpecId) VALUES (".$_GET['partId'].", ".$_POST['specId'].") ";
	$insert = $db->query($sql);
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 34, '', '".$_POST['specId']."', '".$userIP."', '".$userID."', 'Add Alternate Material') ";
	$insert = $db->query($sql);
	
	header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
	<link rel = 'stylesheet' type = 'text/css' href = '../Common Data/anthony.css'>
</head>
<body>
<form action = 'anthony_addAlternateMaterial.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>' method = 'POST'>
Metal Type: <select name = 'specId'>
<?php
//~ if($_SESSION['idNumber']!='0346')
//~ {
	//~ $sql = "SELECT materialSpecId, metalType FROM cadcam_materialspecs WHERE metalThickness LIKE '".$_GET['thickness']."' GROUP BY metalType ";
	//~ $getMetalType = $db->query($sql);
	//~ while($getMetalTypeResult = $getMetalType->fetch_array())
	//~ {
		//~ // ------------------------------------- Duplicate Metal Type
		//~ //$sql = "SELECT metalType FROM cadcam_materialspecs WHERE materialSpecId = ".$getMetalTypeResult['metalType']." ";
		//~ $sql = "select distinct metalType from cadcam_materialspecs where (materialSpecId IN (select materialSpecId from engineering_alternatematerial where partId = ".$_GET['partId'].") or materialSpecId IN (select materialSpecId from cadcam_parts where partId = ".$_GET['partId'].")) and metalType like '".$getMetalTypeResult['metalType']."'";
		//~ $getExistingMetalType = $db->query($sql);
		//~ if(!($getExistingMetalType->num_rows > 0))
		//~ {
		//~ echo "<option value = '".$getMetalTypeResult['materialSpecId']."'>".$getMetalTypeResult['metalType']."</option>";
		//~ }	
	//~ }
//~ }
//~ else 
//~ {
	//;cadcam_materialspecs;
	$materialSpecIdArray = array();
	$sqk = "SELECT materialSpecId FROM cadcam_parts WHERE partId = ".$_GET['partId']." LIMIT 1";
	$queryParts = $db->query($sql);
	if($queryParts AND $queryParts->num_rows > 0)
	{
		$resultParts = $queryParts->fetch_assoc();
		$materialSpecIdArray[] = $resultParts['materialSpecId'];
	}
	
	$sql = "SELECT materialSpecId FROM engineering_alternatematerial WHERE partId = ".$_GET['partId']."";
	$queryAlternateMaterial = $db->query($sql);
	if($queryAlternateMaterial AND $queryAlternateMaterial->num_rows > 0)
	{
		while($resultAlternateMaterial = $queryAlternateMaterial->fetch_assoc())
		{
			$materialSpecIdArray[] = $resultAlternateMaterial['materialSpecId'];
		}
	}
	
	$sqlFilter = (count($materialSpecIdArray) > 0) ? "AND materialSpecId NOT IN(".implode(",",$materialSpecIdArray).")" : "";
	
	$sql = "SELECT materialSpecId, materialTypeId FROM cadcam_materialspecs WHERE metalThickness LIKE '".$_GET['thickness']."' ".$sqlFilter." GROUP BY materialTypeId, metalThickness";
	$queryMaterialSpecs = $db->query($sql);
	if($queryMaterialSpecs AND $queryMaterialSpecs->num_rows > 0)
	{
		while($resultMaterialSpecs = $queryMaterialSpecs->fetch_assoc())
		{
			$materialSpecId = $resultMaterialSpecs['materialSpecId'];
			$materialTypeId = $resultMaterialSpecs['materialTypeId'];
			
			$materialType = '';
			$sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$materialTypeId." LIMIT 1";
			$queryMaterialType = $db->query($sql);
			if($queryMaterialType AND $queryMaterialType->num_rows > 0)
			{
				$resultMaterialType = $queryMaterialType->fetch_assoc();
				$materialType = $resultMaterialType['materialType'];
			}
			echo "<option value = '".$materialSpecId."'>".$materialType."</option>";
		}
	}
//~ }
?>
</select><br><br>
<center><input type = 'submit' value = 'Add' class = 'anthony_submit' name = 'add'></center>
</form>
</body>
</html>
