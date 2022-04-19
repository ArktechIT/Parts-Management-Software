<?php
include('../Common Data/PHP Modules/mysqliConnection.php');

$processCodeArray = array();
$sql = "SELECT processCode FROM cadcam_processpatterndetails WHERE patternId = ".$_POST['patternProcess']." ORDER BY processOrder ASC";
$getProcessCode = $db->query($sql);
if($getProcessCode AND $getProcessCode->num_rows > 0)
{
	while($getProcessCodeResult = $getProcessCode->fetch_assoc())
	{
		$processCodeArray[] = $getProcessCodeResult['processCode'];
	}
}

$sql  = "SELECT count FROM cadcam_partprocess WHERE partId = ".$_POST['partId']." AND processCode IN (".implode(",",$processCodeArray).") AND patternId = ".$_POST['patternId'];
$getCount = $db->query($sql);
if($getCount AND $getCount->num_rows > 0)
{
	echo "1";
}
else
{
	echo "0";
}
?>
