<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 19, '', '".$_POST['subconProcess']."', '".$userIP."', '".$userID."', 'INSERT processCode') ";
$insert = $db->query($sql);

$sql = "INSERT INTO cadcam_subconlist (partId, subconId, processCode, surfaceArea, subconOrder) VALUES (".$_GET['partId'].", 0, ".$_POST['subconProcess'].", '".$_POST['value']."', ".$_POST['subconOrder'].") ";
$add = $db->query($sql);

if($add)
{
	$a = '';
	$sql = "SELECT a FROM cadcam_subconlist ORDER BY a DESC LIMIT 1";
	$querySubconList = $db->query($sql);
	if($querySubconList AND $querySubconList->num_rows > 0)
	{
		$resultSubconList = $querySubconList->fetch_assoc();
		$a = $resultSubconList['a'];
	}

	$sql = "INSERT INTO `engineering_subconprocessor`(`a`, `subconId`) VALUES ('".$a."','".$_POST['subconId']."')";
	$queryInsert = $db->query($sql);
}

// ---------- this code will be deleted ---------- //
	//~ $sql = "SELECT partId FROM cadcam_subcondimension WHERE partId = ".$_GET['partId']." ";
	//~ $getPartId =$db->query($sql);
	//~ if($getPartId->num_rows > 0)
	//~ {
		//~ if($_POST['subconProcess'] == 270)
		//~ {
			//~ $sql = "UPDATE cadcam_subcondimension SET totalSurfaceClear = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
			//~ $update = $db->query($sql);
		//~ }
		//~ else if($_POST['subconProcess'] == 272)
		//~ {
			//~ $sql = "UPDATE cadcam_subcondimension SET totalSurfacePrime = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
			//~ $update = $db->query($sql);
		//~ }
		//~ else if($_POST['subconProcess'] == 251)
		//~ {
			//~ $sql = "UPDATE cadcam_subcondimension SET totalSurfacePassivation = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
			//~ $update = $db->query($sql);
		//~ }
		//~ else if($_POST['subconProcess'] == 284)
		//~ {
			//~ $sql = "UPDATE cadcam_subcondimension SET totalSurfacePassivation = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
			//~ $update = $db->query($sql);
		//~ }	
		//~ else if($_POST['subconProcess'] == 273)
		//~ {
			//~ $sql = "UPDATE cadcam_subcondimension SET totalSurfaceBrush = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
			//~ $update = $db->query($sql);
		//~ }
		//~ else if($_POST['subconProcess'] == 271)
		//~ {
			//~ $sql = "UPDATE cadcam_subcondimension SET totalSurfaceBlackHard = '".$_POST['value']."' WHERE partId = ".$_GET['partId']." ";
			//~ $update = $db->query($sql);
		//~ }	
	//~ }
	//~ else
	//~ {
		//~ if($_POST['subconProcess'] == 270)
		//~ {
			//~ $sql = "INSERT INTO cadcam_subcondimension (partId, totalSurfaceClear) VALUES (".$_GET['partId'].", '".$_POST['value']."') ";
			//~ $insert = $db->query($sql);
		//~ }
		//~ else if($_POST['subconProcess'] == 272)
		//~ {
			//~ $sql = "INSERT INTO cadcam_subcondimension (partId, totalSurfacePrime) VALUES (".$_GET['partId'].", '".$_POST['value']."') ";
			//~ $insert = $db->query($sql);
		//~ }
		//~ else if($_POST['subconProcess'] == 251)
		//~ {
			//~ $sql = "INSERT INTO cadcam_subcondimension (partId, totalSurfacePassivation) VALUES (".$_GET['partId'].", '".$_POST['value']."') ";
			//~ $insert = $db->query($sql);
		//~ }
		//~ else if($_POST['subconProcess'] == 284)
		//~ {
			//~ $sql = "INSERT INTO cadcam_subcondimension (partId, totalSurfacePassivation) VALUES (".$_GET['partId'].", '".$_POST['value']."') ";
			//~ $insert = $db->query($sql);
		//~ }
		//~ else if($_POST['subconProcess'] == 273)
		//~ {
			//~ $sql = "INSERT INTO cadcam_subcondimension (partId, totalSurfaceBrush) VALUES (".$_GET['partId'].", '".$_POST['value']."') ";
			//~ $insert = $db->query($sql);
		//~ }
		//~ else if($_POST['subconProcess'] == 271)
		//~ {
			//~ $sql = "INSERT INTO cadcam_subcondimension (partId, totalSurfaceBlackHard) VALUES (".$_GET['partId'].", '".$_POST['value']."') ";
			//~ $insert = $db->query($sql);
		//~ }
	//~ }
// -------- END this code will be deleted -------- //	
header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=subcon&patternId=".$_GET['patternId']."");
?>
