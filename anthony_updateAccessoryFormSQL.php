<?php
	SESSION_START();
	include("../Common Data/PHP Modules/mysqliConnection.php");
	ini_set("display_errors", "on");

	$userID = $_SESSION['userID'];
	$userIP = $_SERVER['REMOTE_ADDR'];

	$sql = "SELECT childId, quantity, remarks FROM cadcam_subparts WHERE subpartId = ".$_GET['subId']." ";
	$oldSubParts = $db->query($sql);
	if($oldSubParts->num_rows > 0)
	{
		$oldSubPartsResult = $oldSubParts->fetch_array();
		$oldChildId = $oldSubPartsResult['childId'];
		$oldQuantity = $oldSubPartsResult['quantity'];
		$oldRemarks = $oldSubPartsResult['remarks'];
	}

	if($oldChildId != $_POST['accessory'])
	{
		$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 12, '".$oldChildId."', '".$_POST['accessory']."', '".$userIP."', '".$userID."', 'UPDATE childId where identifier = 2') ";
		$insert = $db->query($sql);
	}

	if($oldQuantity != $_POST['quantity'])
	{
		$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 13, '".$oldQuantity."', '".$_POST['quantity']."', '".$userIP."', '".$userID."', 'UPDATE quantity where identifier = 2') ";
		$insert = $db->query($sql);
	}

	if($oldRemarks != $_POST['remarks'])
	{
		$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 13, '".$oldRemarks."', '".$_POST['remarks']."', '".$userIP."', '".$userID."', 'UPDATE remarks where identifier = 2') ";
		$insert = $db->query($sql);
	}

	$sql = "UPDATE cadcam_subparts SET childId = ".$_POST['accessory'].", quantity = ".$_POST['quantity'].", remarks = '".$_POST['remarks']."' WHERE subpartId = ".$_GET['subId']." ";
	$update = $db->query($sql);
	header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=accessories&patternId=".$_GET['patternId']."");
?>
