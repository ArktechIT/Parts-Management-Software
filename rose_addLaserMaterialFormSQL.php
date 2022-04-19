<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];
$queryFlag = 0;
$remarks = "";

	$sql = "SELECT laserParameterId FROM cadcam_parts WHERE partId = ".$_GET['partId']." LIMIT 1"; 
	$query = $db->query($sql);
	if($query->num_rows > 0)
	{
		$result = $query->fetch_assoc();
		$remarks = $result['laserParameterId'];
		$queryEvent = 2; 
		$sqlRose = "UPDATE cadcam_parts SET laserParameterId = '".$_POST['remarks']."' WHERE partId = ".$_GET['partId']; 
		//echo $sqlRose."<br>";
		$query = $db->query($sqlRose); 
	}
	
	$materialIdOld = $remarks;
	$sql2 = "SELECT materialId FROM system_laserparameter WHERE listId = ".$remarks." LIMIT 1"; 
	$query2 = $db->query($sql2);
	if($query2->num_rows > 0)
	{
		$result2 = $query2->fetch_assoc();
		$materialIdOld = $result2['materialId'];
	}
	$materialIdNew = $_POST['remarks'];
	$sql3 = "SELECT materialId FROM system_laserparameter WHERE listId = ".$_POST['remarks']." LIMIT 1"; 
	$query3 = $db->query($sql3);
	if($query3->num_rows > 0)
	{
		$result3 = $query3->fetch_assoc();
		$materialIdNew = $result3['materialId'];
	}
	
	//$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), ".$queryEvent.", 40, '".$remarks."', '".$_POST['remarks']."', '".$userIP."', '".$userID."', 'laser_Material_Name') ";
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), ".$queryEvent.", 40, '".$materialIdOld."', '".$materialIdNew."', '".$userIP."', '".$userID."', 'laser_Material_Name') ";
	//echo $sql."<br>";
	$insert = $db->query($sql);
header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=programs");
?>
