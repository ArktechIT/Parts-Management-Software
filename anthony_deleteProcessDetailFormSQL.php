<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

if(isset($_GET['count']))
{
	$sql = "SELECT processCode FROM cadcam_partprocess WHERE count = ".$_GET['count']." ";
	$getOldProcessCode = $db->query($sql);
	if($getOldProcessCode->num_rows > 0)
	{
		$getOldProcessCodeResult = $getOldProcessCode->fetch_array();
		$oldProcessCode = $getOldProcessCodeResult['processCode'];
	}
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details, userRemarks) VALUES (".$_GET['partId'].", now(), 3, 16, '".$oldProcessCode."', '', '".$userIP."', '".$userID."', 'DELETE processCode', '".$_POST['userRemarks']." ;patternId=".$_GET['patternId']."') ";
	$insert = $db->query($sql);

	$sql = "DELETE FROM cadcam_partprocess WHERE count = ".$_GET['count']." ";
	$delete = $db->query($sql);

	$sql = "SELECT processOrder, processCode, count FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND processOrder > ".$_GET['processOrder']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder ASC ";
	$getProcessOrder = $db->query($sql);
	while($getProcessOrderResult = $getProcessOrder->fetch_array()){
		$decProcessOrder = $getProcessOrderResult['processOrder']-1;
		
		$sql = "UPDATE cadcam_partprocess SET processOrder = ".$decProcessOrder." WHERE count = ".$getProcessOrderResult['count']." ";
		$update = $db->query($sql);	
	}
	
	$firstProcessOrder = 0;
	$sql = "SELECT processOrder FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder ASC LIMIT 1";
	$queryPartProcess = $db->query($sql);
	if($queryPartProcess AND $queryPartProcess->num_rows > 0)
	{
		$resultPartProcess = $queryPartProcess->fetch_assoc();
		$firstProcessOrder = $resultPartProcess['processOrder'];
		
		if($firstProcessOrder > 1)
		{
			$newProcessOrder = 0;
			$sql = "SELECT processOrder, processCode, count FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND processOrder > ".$_GET['processOrder']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder ASC ";
			$getProcessOrder = $db->query($sql);
			while($getProcessOrderResult = $getProcessOrder->fetch_array()){
				$newProcessOrder++;
				
				$sql = "UPDATE cadcam_partprocess SET processOrder = ".$newProcessOrder." WHERE count = ".$getProcessOrderResult['count']." ";
				$update = $db->query($sql);	
			}
		}
	}
}
else if(isset($_GET['subProcessListId']))
{
	$sql = "SELECT processCode FROM engineering_partsubprocess WHERE listId = ".$_GET['subProcessListId']." ";
	$getOldProcessCode = $db->query($sql);
	if($getOldProcessCode->num_rows > 0)
	{
		$getOldProcessCodeResult = $getOldProcessCode->fetch_array();
		$oldProcessCode = $getOldProcessCodeResult['processCode'];
	}
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details, userRemarks) VALUES (".$_GET['partId'].", now(), 3, 16, '".$oldProcessCode."', '', '".$userIP."', '".$userID."', 'DELETE processCode', '".$_POST['userRemarks']." ;patternId=".$_GET['patternId']."') ";
	$insert = $db->query($sql);

	$sql = "DELETE FROM engineering_partsubprocess WHERE listId = ".$_GET['subProcessListId']." ";
	$delete = $db->query($sql);

	$sql = "SELECT processOrder, processCode, listId FROM engineering_partsubprocess WHERE partId = ".$_GET['partId']." AND processOrder > ".$_GET['processOrder']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder ASC ";
	$getProcessOrder = $db->query($sql);
	if($getProcessOrder AND $getProcessOrder->num_rows > 0)
	{
		while($getProcessOrderResult = $getProcessOrder->fetch_array()){
			$decProcessOrder = $getProcessOrderResult['processOrder']-1;
			
			$sql = "UPDATE engineering_partsubprocess SET processOrder = ".$decProcessOrder." WHERE listId = ".$getProcessOrderResult['listId']." ";
			$update = $db->query($sql);	
		}
	}
}
else if(isset($_POST['subProcessListIdArray']))
{
	$subProcessListIdArray = $_POST['subProcessListIdArray'];
	if(count($subProcessListIdArray) > 0)
	{
		foreach($subProcessListIdArray as $subProcessListId)
		{
			$sql = "SELECT processCode FROM engineering_partsubprocess WHERE listId = ".$subProcessListId." ";
			$getOldProcessCode = $db->query($sql);
			if($getOldProcessCode->num_rows > 0)
			{
				$getOldProcessCodeResult = $getOldProcessCode->fetch_array();
				$oldProcessCode = $getOldProcessCodeResult['processCode'];
			}
			$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details, userRemarks) VALUES (".$_GET['partId'].", now(), 3, 16, '".$oldProcessCode."', '', '".$userIP."', '".$userID."', 'DELETE processCode', '".$_POST['userRemarks']." ;patternId=".$_GET['patternId']."') ";
			$insert = $db->query($sql);

			$sql = "DELETE FROM engineering_partsubprocess WHERE listId = ".$subProcessListId." ";
			$delete = $db->query($sql);

			$sql = "SELECT processOrder, processCode, listId FROM engineering_partsubprocess WHERE partId = ".$_GET['partId']." AND processOrder > ".$_GET['processOrder']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder ASC ";
			$getProcessOrder = $db->query($sql);
			if($getProcessOrder AND $getProcessOrder->num_rows > 0)
			{
				while($getProcessOrderResult = $getProcessOrder->fetch_array()){
					$decProcessOrder = $getProcessOrderResult['processOrder']-1;
					
					$sql = "UPDATE engineering_partsubprocess SET processOrder = ".$decProcessOrder." WHERE listId = ".$getProcessOrderResult['listId']." ";
					$update = $db->query($sql);	
				}
			}
		}
	}
}
header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId'].""); 
?>
