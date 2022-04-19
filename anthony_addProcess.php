<?php
SESSION_START();
include('../Common Data/PHP Modules/mysqliConnection.php');
ini_set("display_errors", "on");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

$counter = 0;
$sql = "SELECT MAX(processOrder) AS maxOrder FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." ";
$getMaxOrder = $db->query($sql);
if($getMaxOrder->num_rows > 0){
	$getMaxOrderResult = $getMaxOrder->fetch_array();
	$counter = $getMaxOrderResult['maxOrder'];
}

if($_GET['add'] == "1"){
	$array = array('91', '173', '187', '139', '94', '144');
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 16, '', '".implode('-',$array)."', '".$userIP."', '".$userID."', 'Add QC-Del') ";
	$insert = $db->query($sql);
	
	for ($i = 0; $i < count($array); $i++){
		$sql = "SELECT processSection FROM cadcam_process WHERE processCode = ".$array[$i]." ";
		$getProcessSection = $db->query($sql);
		$getProcessSectionResult = $getProcessSection->fetch_array();
		
		$sql = "INSERT INTO cadcam_partprocess(partId, processOrder, processCode, processSection) VALUES (".$_GET['partId'].", ".++$counter.", ".$array[$i].", ".$getProcessSectionResult['processSection'].") ";
		$add = $db->query($sql);
	}
}else if($_GET['add'] == "2"){
	$array = array('92', '221', '223', '140', '96', '145', '137', '93', '222', '224', '139', '94', '144');
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 16, '', '".implode('-',$array)."', '".$userIP."', '".$userID."', 'Add QC-Del with Subcon') ";
	$insert = $db->query($sql);
	
	for ($i = 0; $i < count($array); $i++)
	{
		$sql = "SELECT processSection FROM cadcam_process WHERE processCode = ".$array[$i]." ";
		$getProcessSection = $db->query($sql);
		$getProcessSectionResult = $getProcessSection->fetch_array();
		
		$sql = "INSERT INTO cadcam_partprocess(partId, processOrder, processCode, processSection) VALUES (".$_GET['partId'].", ".++$counter.", ".$array[$i].", ".$getProcessSectionResult['processSection'].") ";
		$add = $db->query($sql);
	}
		
	$subcon = $_POST['subcon'];
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 19, '', '".implode('-',$subcon)."', '".$userIP."', '".$userID."', 'INSERT processCode') ";
	$insert = $db->query($sql);
	for($x=0; $x<count($subcon); $x++)
	{
		$sql = "INSERT INTO cadcam_subconlist (partId, subconId, processCode) VALUES (".$_GET['partId'].", 0, ".$subcon[$x].") ";
		$add = $db->query($sql);
	}
}else if($_GET['add'] == "3"){
	$array = array('91', '198', '200', '149', '218', '204', '205', '264', '187', '139', '94', '144');
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 16, '', '".implode('-',$array)."', '".$userIP."', '".$userID."', 'Add Painting Process') ";
	$insert = $db->query($sql);
	
	for ($i = 0; $i < count($array); $i++){
		$sql = "SELECT processSection FROM cadcam_process WHERE processCode = ".$array[$i]." ";
		$getProcessSection = $db->query($sql);
		$getProcessSectionResult = $getProcessSection->fetch_array();
		
		$sql = "INSERT INTO cadcam_partprocess(partId, processOrder, processCode, processSection) VALUES (".$_GET['partId'].", ".++$counter.", ".$array[$i].", ".$getProcessSectionResult['processSection'].") ";
		$add = $db->query($sql);
	}
}else if($_GET['add'] == "4"){
	$array = array('91', '219', '160', '204', '220', '265', '187', '139', '94', '144');
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 16, '', '".implode('-',$array)."', '".$userIP."', '".$userID."', 'Add Printing Process') ";
	$insert = $db->query($sql);
	
	for ($i = 0; $i < count($array); $i++){
		$sql = "SELECT processSection FROM cadcam_process WHERE processCode = ".$array[$i]." ";
		$getProcessSection = $db->query($sql);
		$getProcessSectionResult = $getProcessSection->fetch_array();
		
		$sql = "INSERT INTO cadcam_partprocess(partId, processOrder, processCode, processSection) VALUES (".$_GET['partId'].", ".++$counter.", ".$array[$i].", ".$getProcessSectionResult['processSection'].") ";
		$add = $db->query($sql);
	}
}else if($_GET['add'] == "5"){
	$array = array('91', '198', '200', '149', '218', '204', '205', '219', '160', '216', '220', '265', '187', '139', '94', '144');
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 16, '', '".implode('-',$array)."', '".$userIP."', '".$userID."', 'Add Paint Printing Process') ";
	$insert = $db->query($sql);
	
	for ($i = 0; $i < count($array); $i++){
		$sql = "SELECT processSection FROM cadcam_process WHERE processCode = ".$array[$i]." ";
		$getProcessSection = $db->query($sql);
		$getProcessSectionResult = $getProcessSection->fetch_array();
		
		$sql = "INSERT INTO cadcam_partprocess(partId, processOrder, processCode, processSection) VALUES (".$_GET['partId'].", ".++$counter.", ".$array[$i].", ".$getProcessSectionResult['processSection'].") ";
		$add = $db->query($sql);
	}
}else if($_GET['add'] == "6"){
	$array = array('92', '221', '223', '140', '96', '145', '137', '93', '162');
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 16, '', '".implode('-',$array)."', '".$userIP."', '".$userID."', 'Add QC-Assy with Subcon') ";
	$insert = $db->query($sql);
	
	for ($i = 0; $i < count($array); $i++){
		$sql = "SELECT processSection FROM cadcam_process WHERE processCode = ".$array[$i]." ";
		$getProcessSection = $db->query($sql);
		$getProcessSectionResult = $getProcessSection->fetch_array();
		
		$sql = "INSERT INTO cadcam_partprocess(partId, processOrder, processCode, processSection) VALUES (".$_GET['partId'].", ".++$counter.", ".$array[$i].", ".$getProcessSectionResult['processSection'].") ";
		$add = $db->query($sql);
	}
	
	$subcon = $_POST['subcon'];
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 19, '', '".implode('-',$subcon)."', '".$userIP."', '".$userID."', 'INSERT processCode') ";
	$insert = $db->query($sql);
	for($x=0; $x<count($subcon); $x++)
	{
		$sql = "INSERT INTO cadcam_subconlist (partId, subconId, processCode) VALUES (".$_GET['partId'].", 0, ".$subcon[$x].") ";
		$add = $db->query($sql);
	}
}else if($_GET['add'] == "7"){
	$array = array('86', '104', '91', '181', '173', '187', '139', '94', '144');
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 16, '', '".implode('-',$array)."', '".$userIP."', '".$userID."', 'Add JAMCO') ";
	$insert = $db->query($sql);
	
	for ($i = 0; $i < count($array); $i++){
		$sql = "SELECT processSection FROM cadcam_process WHERE processCode = ".$array[$i]." ";
		$getProcessSection = $db->query($sql);
		$getProcessSectionResult = $getProcessSection->fetch_array();
		
		$sql = "INSERT INTO cadcam_partprocess(partId, processOrder, processCode, processSection) VALUES (".$_GET['partId'].", ".++$counter.", ".$array[$i].", ".$getProcessSectionResult['processSection'].") ";
		$add = $db->query($sql);
	}
}else if($_GET['add'] == "8"){
	$array = array('342', '344', '329', '149', '195', '326', '303', '346', '181', '345', '187', '139', '94', '144');
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 16, '', '".implode('-',$array)."', '".$userIP."', '".$userID."', 'Add Priming Process') ";
	$insert = $db->query($sql);
	
	for ($i = 0; $i < count($array); $i++){
		$sql = "SELECT processSection FROM cadcam_process WHERE processCode = ".$array[$i]." ";
		$getProcessSection = $db->query($sql);
		$getProcessSectionResult = $getProcessSection->fetch_array();
		
		$sql = "INSERT INTO cadcam_partprocess(partId, processOrder, processCode, processSection) VALUES (".$_GET['partId'].", ".++$counter.", ".$array[$i].", ".$getProcessSectionResult['processSection'].") ";
		$add = $db->query($sql);
	}
}
header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=process");
?>
