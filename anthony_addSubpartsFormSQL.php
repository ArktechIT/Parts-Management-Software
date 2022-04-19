<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 1, '', '"."partNumber = ".$_POST['partNumber'].", quantity = ".$_POST['quantity']."', '".$userIP."', '".$userID."', 'INSERT partNumber and quantity where identifier = 1') ";
$insert = $db->query($sql);

$sql = "SELECT MAX(orderNumber) AS maxOrderNumber FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." ORDER BY orderNumber ASC ";
$getMaxOrder = $db->query($sql);
$getMaxOrderResult = $getMaxOrder->fetch_array();

$sql = "INSERT INTO cadcam_subparts (parentId, childId, quantity, identifier, orderNumber) VALUES (".$_GET['partId'].", ".$_POST['partNumber'].", ".$_POST['quantity'].", 1, ".($getMaxOrderResult['maxOrderNumber'] + 1).") ";
$add = $db->query($sql);

	$sql = "INSERT INTO `engineering_subpartprocesslink`
					(	`partId`,				`processCode`,					`patternId`,				`childId`,					`identifier`,	`quantity`)
			VALUES	(	'".$_GET['partId']."',	'".$_POST['processCode']."',	'".$_GET['patternId']."',	'".$_POST['partNumber']."',	1,				'".$_POST['quantity']."')";
	$queryInsert = $db->query($sql);

	//~ if($_SESSION['idNumber']=='0346')
	//~ {
		$inputNote = '';
		$sql = "SELECT partNumber FROM cadcam_parts WHERE partId = ".$_POST['partNumber']." LIMIT 1";
		$queryParts = $db->query($sql);
		if($queryParts AND $queryParts->num_rows > 0)
		{
			$resultParts = $queryParts->fetch_assoc();
			
			$inputNote = $_POST['quantity']."-".$resultParts['partNumber'];
			
			$sql = "INSERT INTO cadcam_partprocessnote (partId, processCode, patternId, remarks)
			VALUES (".$_GET['partId'].", ".$_POST['processCode'].", ".$_GET['patternId'].", '".$inputNote."')";
			$insertQuery = $db->query($sql);
		}
	//~ }

header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=subparts&patternId=".$_GET['patternId']."");
?>
