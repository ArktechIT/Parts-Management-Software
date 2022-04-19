<?php

	SESSION_START();
	include("../Common Data/PHP Modules/mysqliConnection.php");
	ini_set("display_errors", "on");

	$userID = $_SESSION['userID'];
	$userIP = $_SERVER['REMOTE_ADDR'];

	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 23, '', '"."accessoryNumber = ".$_POST['accessory'].", quantity = ".$_POST['quantity'].", remarks = ".$_POST['remarks']."', '".$userIP."', '".$userID."', 'INSERT accessoryNumber and quantity where identifier = 2') ";
	$insert = $db->query($sql);

	$sql = "SELECT MAX(orderNumber) AS maxOrderNumber FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." ORDER BY orderNumber ASC ";
	$getMaxOrder = $db->query($sql);
	$getMaxOrderResult = $getMaxOrder->fetch_array();

	$sql = "INSERT INTO cadcam_subparts (parentId, childId, quantity, identifier, remarks, orderNumber) VALUES (".$_GET['partId'].", ".$_POST['accessory'].", ".$_POST['quantity'].", 2, '".$_POST['remarks']."', ".($getMaxOrderResult['maxOrderNumber'] + 1).") ";
	$update = $db->query($sql);

	$sql = "INSERT INTO `engineering_subpartprocesslink`
					(	`partId`,				`processCode`,					`patternId`,				`childId`,					`identifier`,	`quantity`)
			VALUES	(	'".$_GET['partId']."',	'".$_POST['processCode']."',	'".$_GET['patternId']."',	'".$_POST['accessory']."',	2,				'".$_POST['quantity']."')";
	$queryInsert = $db->query($sql);
	
	//~ if($_SESSION['idNumber']=='0346')
	//~ {
		$inputNote = '';
		$sql = "SELECT accessoryNumber FROM cadcam_accessories WHERE accessoryId = ".$_POST['accessory']." LIMIT 1";
		$queryAccessories = $db->query($sql);
		if($queryAccessories AND $queryAccessories->num_rows > 0)
		{
			$resultAccessories = $queryAccessories->fetch_assoc();
			
			$inputNote = $_POST['quantity']."-".$resultAccessories['accessoryNumber'];
			
			$sql = "INSERT INTO cadcam_partprocessnote (partId, processCode, patternId, remarks)
			VALUES (".$_GET['partId'].", ".$_POST['processCode'].", ".$_GET['patternId'].", '".$inputNote."')";
			$insertQuery = $db->query($sql);
		}
	//~ }
	
	header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=accessories&patternId=".$_GET['patternId']."");
?>
