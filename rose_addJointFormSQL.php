<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");












$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];


	$sql = "SELECT partId FROM engineering_partsCheck WHERE partId = ".$_GET['partId']." LIMIT 1"; 
	$query = $db->query($sql);
	if($query->num_rows > 0)
	{ 
		$sqlRose = "UPDATE engineering_partsCheck SET jointNote = '".$_POST['remarks']."' WHERE partId = ".$_GET['partId'];
	    $query = $db->query($sqlRose); 
	}
	else
	{ 
		$sqlRose = "INSERT INTO engineering_partsCheck(partId, jointNote) VALUES (".$_GET['partId'].", '".$_POST['remarks']."')"; 
		$query = $db->query($sqlRose); 
	}



header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=programs");
?>
