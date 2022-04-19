<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

// --------------------------------------------- Retrieve Max Order Number -----------------------------------------------------------
$sql = "SELECT MAX(orderNumber) AS maxOrderNumber FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." ORDER BY orderNumber ASC ";
$orderNumberQuery = $db->query($sql);
$orderNumberQueryResult = $orderNumberQuery->fetch_array();
$maxOrderNumber = $orderNumberQueryResult['maxOrderNumber'];
if($maxOrderNumber=="")
{
	$maxOrderNumber=1;
}
// --------------------------------------------- End of Retrieve Max Order Number -----------------------------------------------------

$partNumber = isset($_POST['partNumber']) ? $_POST['partNumber'] : "";
$revisionId = isset($_POST['revisionId']) ? $_POST['revisionId'] : "";
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : "";
$processCode = isset($_POST['processCode']) ? $_POST['processCode'] : "";
$customerId = isset($_POST['customerId']) ? $_POST['customerId'] : "";
$partId=0;
for($i=0; $i<count($partNumber); $i++)
{
	$sql = "SELECT partId FROM cadcam_parts WHERE trim(partNumber) = '".trim($partNumber[$i])."' and trim(revisionId) = '".trim($revisionId[$i])."' and customerId = ".$customerId;
	$partIdNumberQuery = $db->query($sql);
	$partIdNumberQueryResult = $partIdNumberQuery->fetch_array();
	$partId=$partIdNumberQueryResult['partId'];
	if($partId==0)
	{
	// ----------------------------------------- Insert Generated Parts Into cadcam_parts ----------------------------------------------------
	$sql = "INSERT INTO cadcam_parts (partNumber, revisionId, customerId) VALUES ('".$partNumber[$i]."','".$revisionId[$i]."',".$customerId.") ";
	echo $sql."<br>";	
	$insertQuery = $db->query($sql);
	
	// ----------------------------------------- End of Insert Data Into cadcam_parts ----------------------------------------------------
	
	$latestPartId = $db->insert_id;	
	// ----------------------------------------- Insert Into Log --------------------------------------------------------------------------
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 1, '', 'partNumber = ".$latestPartId.", quantity = ".$quantity[$i]."', '".$userIP."', '".$userID."', 'INSERT partNumber and quantity where identifier = 1') ";
	echo $sql."<br>";
	$insertQuery = $db->query($sql);
	// ----------------------------------------- End of Insert Into Log --------------------------------------------------------------------
	
	// ----------------------------------------- Link Generated Parts to Parent Part -------------------------------------------------------
	$sql = "INSERT INTO cadcam_subparts (parentId, childId, quantity, identifier, orderNumber) VALUES (".$_GET['partId'].", ".$latestPartId.", ".$quantity[$i].", 1, ".$maxOrderNumber.")";
	echo $sql."<br><br>";	
	$insertQuery = $db->query($sql);
	// ----------------------------------------- End of Link Generated Parts to Parent Part
	
	$sql = "INSERT INTO `engineering_subpartprocesslink`
					(	`partId`,				`processCode`,			`patternId`,				`childId`,				`identifier`,	`quantity`)
			VALUES	(	'".$_GET['partId']."',	'".$processCode[$i]."',	'".$_GET['patternId']."',	'".$latestPartId."',	1,				'".$quantity[$i]."')";
	$queryInsert = $db->query($sql);

	//~ if($_SESSION['idNumber']=='0346')
	//~ {
		$inputNote = '';
		$sql = "SELECT partNumber FROM cadcam_parts WHERE partId = ".$latestPartId." LIMIT 1";
		$queryParts = $db->query($sql);
		if($queryParts AND $queryParts->num_rows > 0)
		{
			$resultParts = $queryParts->fetch_assoc();
			
			$inputNote = $quantity[$i]."-".$resultParts['partNumber'];
			
			$sql = "INSERT INTO cadcam_partprocessnote (partId, processCode, patternId, remarks)
			VALUES (".$_GET['partId'].", ".$processCode[$i].", ".$_GET['patternId'].", '".$inputNote."')";
			$insertQuery = $db->query($sql);
		}
	//~ }	
	
	}
	$maxOrderNumber++;
}
?>

<script>
window.opener.location.reload(true);
window.close();
</script>
