<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];
$queryFlag = 0;
$remarks = "";
$oldlotSize= isset($_POST['oldlotSize']) ? $_POST['oldlotSize']: "0";
$lotSize= isset($_POST['lotSize']) ? $_POST['lotSize']: "0";
$partId= isset($_POST['partId2']) ? $_POST['partId2']: "0";
	
	if($oldlotSize!=$lotSize)
	{
		$queryEvent = 2; 
		$sqlRose = "UPDATE cadcam_parts SET lotSize = '".$lotSize."' WHERE partId = ".$partId; 
		//echo $sqlRose."<br>";
		$query = $db->query($sqlRose); 
		
		$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), ".$queryEvent.", 41, '".$oldlotSize."', '".$lotSize."', '".$userIP."', '".$userID."', 'lot_Size') ";
		//echo $sql."<br>";
		$insert = $db->query($sql);
	}	
header("Location:anthony_editProduct.php?partId=".$partId."");
?>
