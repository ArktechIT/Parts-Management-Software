<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
// $path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
$javascriptLib = "/".v."/Common Data/Libraries/Javascript/";
$templates = "/".v."/Common Data/Templates/";
set_include_path($path);	
include('PHP Modules/mysqliConnection.php');
ini_set("display_errors", "on");

$accessoryId = $_POST['accessoryId'];
$accessoryIdQueryAdd = "";
if($accessoryId != '')
{
	$accessoryIdQueryAdd = " accessoryId = ".$accessoryId." AND ";
}
$sql = "SELECT `accessoryNumber`, `accessoryName`, `accessoryDescription`, `revisionId` FROM `cadcam_accessories` WHERE  ".$accessoryIdQueryAdd." status = 0 ORDER BY accessoryNumber";
$queryAccessory = $db->query($sql);
if($queryAccessory AND $queryAccessory->num_rows > 0)
{
	while($resultAccessory = $queryAccessory->fetch_assoc())
	{
		$accessoryNumber = $resultAccessory['accessoryNumber'];
		$accessoryName = $resultAccessory['accessoryName'];
		$accessoryDescription = $resultAccessory['accessoryDescription'];
		$revisionId = $resultAccessory['revisionId'];

		echo "<tr>";
			echo "<td>".$accessoryNumber."</td>";
			echo "<td>".$accessoryName."</td>";
			echo "<td style='text-align:left;'>".$accessoryDescription."</td>";
			echo "<td style='text-align:center; width: 10px'>".$revisionId."</td>";
		echo "</tr>";
	}
}
?>