<?php
SESSION_START();
include('../Common Data/PHP Modules/mysqliConnection.php');
ini_set("display_errors","on");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

if($_GET['action']=="down"){
	$sql = "SELECT partId, processOrder, processCode, count FROM cadcam_partprocess WHERE count = ".$_GET['id']." AND patternId = ".$_GET['patternId']." ";
	$getIncProcessOrder = $db->query($sql);
	$getIncProcessOrderResult = $getIncProcessOrder->fetch_array();
	
	$incProcessOrder = $getIncProcessOrderResult['processOrder'] + 1;
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details, userRemarks) VALUES (".$_GET['partId'].", now(), 2, 15, '".$getIncProcessOrderResult['processOrder']."', '".$incProcessOrder."', '".$userIP."', '".$userID."', '".$getIncProcessOrderResult['processCode']." Down', 'patternId=".$_GET['patternId']."') ";
	$insert = $db->query($sql);
	
	$sql = "SELECT processOrder, count FROM cadcam_partprocess WHERE partId = ".$getIncProcessOrderResult['partId']." AND processOrder = ".$incProcessOrder." AND patternId = ".$_GET['patternId']." ";
	$getDecProcessOrder = $db->query($sql);
	$getDecProcessOrderResult = $getDecProcessOrder->fetch_array();
	
	$decProcessOrder = $getDecProcessOrderResult['processOrder'] - 1;
	
	$sql = "UPDATE cadcam_partprocess SET processOrder = ".$incProcessOrder." WHERE count = ".$getIncProcessOrderResult['count']." AND patternId = ".$_GET['patternId']." ";
	$updateInc = $db->query($sql);
	
	$sql = "UPDATE cadcam_partprocess SET processOrder = ".$decProcessOrder." WHERE count = ".$getDecProcessOrderResult['count']." AND patternId = ".$_GET['patternId']." ";
	$updateDec = $db->query($sql);
	header("Location:anthony_editProduct.php?partId=".$getIncProcessOrderResult['partId']."&src=process&actions=change&patternId=".$_GET['patternId']."");
}else if($_GET['action']=="up"){
	$sql = "SELECT partId, processOrder, processCode, count FROM cadcam_partprocess WHERE count = ".$_GET['id']." AND patternId = ".$_GET['patternId']." ";
	$getDecProcessOrder = $db->query($sql);
	$getDecProcessOrderResult = $getDecProcessOrder->fetch_array();
	
	$decProcessOrder = $getDecProcessOrderResult['processOrder'] - 1;
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 15, '".$getDecProcessOrderResult['processOrder']."', '".$decProcessOrder."', '".$userIP."', '".$userID."', '".$getDecProcessOrderResult['processCode']." Up', 'patternId=".$_GET['patternId']."') ";
	$insert = $db->query($sql);
	
	$sql = "SELECT processOrder, count FROM cadcam_partprocess WHERE partId = ".$getDecProcessOrderResult['partId']." AND processOrder = ".$decProcessOrder." AND patternId = ".$_GET['patternId']." ";
	$getIncProcessOrder = $db->query($sql);
	$getIncProcessOrderResult = $getIncProcessOrder->fetch_array();
	
	$incProcessOrder = $getIncProcessOrderResult['processOrder'] + 1;
	
	$sql = "UPDATE cadcam_partprocess SET processOrder = ".$decProcessOrder." WHERE count = ".$getDecProcessOrderResult['count']." AND patternId = ".$_GET['patternId']." ";
	$updateDec = $db->query($sql);
	
	$sql = "UPDATE cadcam_partprocess SET processOrder = ".$incProcessOrder." WHERE count = ".$getIncProcessOrderResult['count']." AND patternId = ".$_GET['patternId']." ";
	$updateInc = $db->query($sql);
	header("Location:anthony_editProduct.php?partId=".$getDecProcessOrderResult['partId']."&src=process&actions=change&patternId=".$_GET['patternId']."");
}
?>
