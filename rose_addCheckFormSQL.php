<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];
$queryFlag = 0;
$remarks = "";

	$sql = "SELECT partId, remarks FROM engineering_partsCheck WHERE partId = ".$_GET['partId']." LIMIT 1"; $query = $db->query($sql);
	if($query->num_rows > 0)
	{
		$result = $query->fetch_assoc();
		$remarks = $result['result'];
		$queryFlag = 1; 
		$sqlRose = "UPDATE engineering_partsCheck SET remarks = '".$_POST['remarks']."' WHERE partId = ".$_GET['partId']; $query = $db->query($sqlRose); 
	}
	else
	{
		$queryFlag = 2;
		$sqlRose = "INSERT INTO engineering_partsCheck(partId, remarks) VALUES (".$_GET['partId'].", '".$_POST['remarks']."')"; 
		$query = $db->query($sqlRose); 
	}
	
	//gusteng//
	if($queryFlag == 1)
	{
		$queryEvent = 2;
	}
	elseif($queryFlag == 2)
	{
		$queryEvent = 3;
	}
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), ".$queryEvent.", 36, '".$remarks."', '".$_POST['remarks']."', '".$userIP."', '".$userID."', 'engineering_partsChecks') ";
	$insert = $db->query($sql);
header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=programs");
?>
