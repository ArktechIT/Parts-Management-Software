<?php
SESSION_START();
include('../Common Data/Templates/mysqliConnection.php');

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

$count = explode(',', $_GET['count']);

foreach($count AS $val)
{	
	$sql = "SELECT processCode FROM cadcam_partprocess WHERE count = ".$val." ";
	$getOldProcessCode = $db->query($sql);
	if($getOldProcessCode->num_rows > 0)
	{
		$getOldProcessCodeResult = $getOldProcessCode->fetch_array();
		$oldProcessCode = $getOldProcessCodeResult['processCode'];
	}
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details, userRemarks) VALUES (".$_GET['partId'].", now(), 3, 16, '".$oldProcessCode."', '', '".$userIP."', '".$userID."', 'DELETE processCode', '".$_POST['userRemarks']."') ";
	$insert = $db->query($sql);
	
	$sql = "SELECT processOrder, count FROM cadcam_partprocess WHERE count = ".$val." ";
	$getPartProcess = $db->query($sql);
	$getPartProcessResult = $getPartProcess->fetch_array();
	
	$sql = "DELETE FROM cadcam_partprocess WHERE count = ".$val." ";
	$delete = $db->query($sql);
	
	$sql = "SELECT processOrder, count FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND processOrder > ".$getPartProcessResult['processOrder']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder";
	$getNextProcessOrder = $db->query($sql);
	while($getNextProcessOrderResult = $getNextProcessOrder->fetch_array())
	{
		$decremented = $getNextProcessOrderResult['processOrder'] - 1;
		$sql = "UPDATE cadcam_partprocess SET processOrder = ".$decremented." WHERE count = ".$getNextProcessOrderResult['count']." ";
		$update = $db->query($sql);
	}
}
$newProcessOrder = 0;
$sql = "SELECT count FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder";
$getNextProcessOrder = $db->query($sql);
while($getNextProcessOrderResult = $getNextProcessOrder->fetch_array())
{
	$newProcessOrder++;
	$sql = "UPDATE cadcam_partprocess SET processOrder = ".$newProcessOrder." WHERE count = ".$getNextProcessOrderResult['count']." ";
	$update = $db->query($sql);
}

if(isset($_GET['source']) AND $_GET['source'] == 'pending')
{
	$source = '&source=pending';
}
header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=process".$source."&patternId=".$_GET['patternId']."");
?>
