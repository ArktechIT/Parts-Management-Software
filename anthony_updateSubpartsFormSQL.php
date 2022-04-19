<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");
ini_set('display_errors','on');

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT childId, quantity FROM cadcam_subparts WHERE subpartId = ".$_GET['subId']." ";
$oldSubParts = $db->query($sql);
if($oldSubParts->num_rows > 0)
{
	$oldSubPartsResult = $oldSubParts->fetch_array();
	$oldChildId = $oldSubPartsResult['childId'];
	$oldQuantity = $oldSubPartsResult['quantity'];
}

if($oldChildId != $_POST['partNumber'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 11, '".$oldChildId."', '".$_POST['partNumber']."', '".$userIP."', '".$userID."', 'UPDATE childId where identifier = 1') ";
	$insert = $db->query($sql);
}

if($oldQuantity != $_POST['quantity'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 13, '".$oldQuantity."', '".$_POST['quantity']."', '".$userIP."', '".$userID."', 'UPDATE quantity where identifier = 1') ";
	$insert = $db->query($sql);
}

$sql = "UPDATE cadcam_subparts SET childId = ".$_POST['partNumber'].", quantity = ".$_POST['quantity']." WHERE subpartId = ".$_GET['subId']." ";
$update = $db->query($sql);

header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=subparts&patternId=".$_GET['patternId']."");
?>
