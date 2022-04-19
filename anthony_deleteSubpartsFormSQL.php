<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT childId FROM cadcam_subparts WHERE subpartId = ".$_GET['subId']." ";
$getOldSubParts = $db->query($sql);
if($getOldSubParts->num_rows > 0)
{
	$getOldSubPartsResult = $getOldSubParts->fetch_array();
	$oldChildId = $getOldSubPartsResult['childId'];
	
	$sql = "DELETE FROM engineering_subpartprocesslink WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." AND childId = ".$oldChildId." AND identifier = 1 ";
	$queryDelete = $db->query($sql);
}
$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 3, 11, '".$oldChildId."', '', '".$userIP."', '".$userID."', 'DELETE childId WHERE identifier = 1') ";
$insert = $db->query($sql);

$sql = "DELETE FROM cadcam_subparts WHERE subpartId = ".$_GET['subId']." ";
$delete = $db->query($sql);

header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=subparts&patternId=".$_GET['patternId']."");
?>
