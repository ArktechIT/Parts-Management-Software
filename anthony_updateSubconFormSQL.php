<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT processCode, subconOrder FROM cadcam_subconlist WHERE a = ".$_GET['subconId']." ";
$getOldSubconList = $db->query($sql);
if($getOldSubconList->num_rows > 0)
{
	$getOldSubconListResult = $getOldSubconList->fetch_array();
	$oldprocessCode = $getOldSubconListResult['processCode'];
	$subconOrder = $getOldSubconListResult['subconOrder'];
}

if($oldprocessCode != $_POST['processName'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 19, '".$oldprocessCode."', '".$_POST['processName']."', '".$userIP."', '".$userID."', 'UPDATE processCode') ";
	$insert = $db->query($sql);
}

if($subconOrder != $_POST['subconOrder'])
{
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 2, 19, '".$subconOrder."', '".$_POST['subconOrder']."', '".$userIP."', '".$userID."', 'UPDATE subconOrder') ";
	$insert = $db->query($sql);
}
$sql = "UPDATE cadcam_subconlist SET processCode = ".$_POST['processName'].", surfaceArea = '".$_POST['value']."', subconOrder = ".$_POST['subconOrder']." WHERE a = ".$_GET['subconId']." ";
$update = $db->query($sql);

// -------------New Add --------------
// ---------- this code will be deleted ---------- //
//~ $sql = "SELECT partId FROM cadcam_subcondimension WHERE partId = ".$_GET['partId']." ";
//~ $getPartId =$db->query($sql);
//~ if($getPartId->num_rows > 0)
//~ {
	//~ if($_POST['processName'] == 270)
	//~ {
		//~ $sql = "UPDATE cadcam_subcondimension SET totalSurfaceClear = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
		//~ $update = $db->query($sql);
	//~ }
	//~ else if($_POST['processName'] == 272)
	//~ {
		//~ $sql = "UPDATE cadcam_subcondimension SET totalSurfacePrime = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
		//~ $update = $db->query($sql);
	//~ }
	//~ else if($_POST['processName'] == 251)
	//~ {
		//~ $sql = "UPDATE cadcam_subcondimension SET totalSurfacePassivation = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
		//~ $update = $db->query($sql);
	//~ }
	//~ else if($_POST['processName'] == 284)
	//~ {
		//~ $sql = "UPDATE cadcam_subcondimension SET totalSurfacePassivation = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
		//~ $update = $db->query($sql);
	//~ }
	//~ else if($_POST['processName'] == 273)
	//~ {
		//~ $sql = "UPDATE cadcam_subcondimension SET totalSurfaceBrush = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
		//~ $update = $db->query($sql);
	//~ }
	//~ else if($_POST['processName'] == 271)
	//~ {
		//~ $sql = "UPDATE cadcam_subcondimension SET totalSurfaceBlackHard = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
		//~ $update = $db->query($sql);
	//~ }
//~ }
//~ else
//~ {
	//~ if($_POST['processName'] == 270)
	//~ {
		//~ $sql = "INSERT INTO cadcam_subcondimension (partId, totalSurfaceClear) VALUES (".$_GET['partId'].", '".$_POST['value']."') ";
		//~ $insert = $db->query($sql);
	//~ }
	//~ else if($_POST['processName'] == 272)
	//~ {
		//~ $sql = "INSERT INTO cadcam_subcondimension (partId, totalSurfacePrime) VALUES (".$_GET['partId'].", '".$_POST['value']."') ";
		//~ $insert = $db->query($sql);
	//~ }
	//~ else if($_POST['processName'] == 251)
	//~ {
		//~ $sql = "INSERT INTO cadcam_subcondimension (partId, totalSurfacePassivation) VALUES (".$_GET['partId'].", '".$_POST['value']."') ";
		//~ $insert = $db->query($sql);
	//~ }
	//~ else if($_POST['processName'] == 284)
	//~ {
		//~ $sql = "INSERT INTO cadcam_subcondimension (partId, totalSurfacePassivation) VALUES (".$_GET['partId'].", '".$_POST['value']."') ";
		//~ $insert = $db->query($sql);
	//~ }
	//~ else if($_POST['processName'] == 273)
	//~ {
		//~ $sql = "INSERT INTO cadcam_subcondimension (partId, totalSurfaceBrush) VALUES (".$_GET['partId'].", '".$_POST['value']."') ";
		//~ $insert = $db->query($sql);
	//~ }
	//~ else if($_POST['processName'] == 271)
	//~ {
		//~ $sql = "INSERT INTO cadcam_subcondimension (partId, totalSurfaceBlackHard) VALUES (".$_GET['partId'].", '".$_POST['value']."') ";
		//~ $insert = $db->query($sql);
	//~ }
//~ }
// -------- END this code will be deleted -------- //	

/*
if($_POST['processName'] == 270)
{
	$sql = "UPDATE cadcam_subcondimension SET totalSurfaceClear = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
	$update = $db->query($sql);
}
else if($_POST['processName'] == 272)
{
	$sql = "UPDATE cadcam_subcondimension SET totalSurfacePrime = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
	$update = $db->query($sql);
}
else if($_POST['processName'] == 251)
{
	$sql = "UPDATE cadcam_subcondimension SET totalSurfacePassivation = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
	$update = $db->query($sql);
}
*/
header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=subcon&patternId=".$_GET['patternId']."");
?>
