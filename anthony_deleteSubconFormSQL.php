<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT processCode FROM cadcam_subconlist WHERE a = ".$_GET['subconId']." ";
$getOldSubconList = $db->query($sql);
if($getOldSubconList->num_rows > 0)
{
	$getOldSubconListResult = $getOldSubconList->fetch_array();
	$oldProcessCode = $getOldSubconListResult['processCode'];
}
$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 3, 19, '".$oldProcessCode."', '', '".$userIP."', '".$userID."', 'DELETE processCode') ";
$insert = $db->query($sql);

$sql = "DELETE FROM cadcam_subconlist WHERE a = ".$_GET['subconId']." ";
$delete = $db->query($sql);
header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=subcon&patternId=".$_GET['patternId']."");
?>
