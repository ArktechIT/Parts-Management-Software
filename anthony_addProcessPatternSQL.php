<?php
include('../Common Data/PHP Modules/mysqliConnection.php');
ini_set("display_errors", "on");

$sql = "SELECT processOrder FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND processCode = ".$_POST['position']." AND patternId = ".$_GET['patternId']." ";
$getProcessOrder = $db->query($sql);
if($getProcessOrder->num_rows > 0)
{
	$getProcessOrderResult = $getProcessOrder->fetch_array();
	$processOrder = $getProcessOrderResult['processOrder'];
}
else
{
	$processOrder = 0;
}

$sql = "SELECT COUNT(count) AS lessCount FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND processOrder <= ".$processOrder." AND patternId = ".$_GET['patternId']." ";
$getLessCount = $db->query($sql);
if($getLessCount->num_rows > 0)
{
	$getLessCountResult = $getLessCount->fetch_array();
	$lessCount = $getLessCountResult['lessCount'];
}
else
{
	$lessCount = 0;
}

$sql = "SELECT COUNT(listId) AS count FROM cadcam_processpatterndetails WHERE patternId = ".$_POST['pattern']." ";
$getCountPatternId = $db->query($sql);
$getCountPatternIdResult = $getCountPatternId->fetch_array();

$incrementProcessOrder = $getCountPatternIdResult['count'] + $lessCount;

$countArray = array();
$sql = "SELECT count FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND processOrder > ".$processOrder." AND patternId = ".$_GET['patternId']." ORDER BY processOrder ASC ";
$getCount = $db->query($sql);
while($getCountResult = $getCount->fetch_array())
{
	$sql = "UPDATE cadcam_partprocess SET processOrder = ".++$incrementProcessOrder." WHERE count = ".$getCountResult['count']." ";
	//~ $update = $db->query($sql);
	
	$countArray[] = $getCountResult['count'];
}

$sql = "SELECT processCode, processSection, processTool FROM cadcam_processpatterndetails WHERE patternId = ".$_POST['pattern']." ORDER BY processOrder ASC ";
$getProcessPatternDetails = $db->query($sql);
while($getProcessPatternDetailsResult = $getProcessPatternDetails->fetch_assoc())

{
	if($getProcessPatternDetailsResult['processCode'] =='94')	{
		$sql = "INSERT INTO cadcam_partprocess (partId, processOrder, processCode, processSection, patternId, toolId) VALUES (".$_GET['partId'].", ".++$processOrder.",539, ".$getProcessPatternDetailsResult['processSection'].", ".$_GET['patternId'].", ".$getProcessPatternDetailsResult['processTool'].") ";
		$insert = $db->query($sql);
	}
	if($getProcessPatternDetailsResult['processCode'] =='96')	{
		$sql = "INSERT INTO cadcam_partprocess (partId, processOrder, processCode, processSection, patternId, toolId) VALUES (".$_GET['partId'].", ".++$processOrder.",540, ".$getProcessPatternDetailsResult['processSection'].", ".$_GET['patternId'].", ".$getProcessPatternDetailsResult['processTool'].") ";
		$insert = $db->query($sql);
	}

	$sql = "INSERT INTO cadcam_partprocess (partId, processOrder, processCode, processSection, patternId, toolId) VALUES (".$_GET['partId'].", ".++$processOrder.", ".$getProcessPatternDetailsResult['processCode'].", ".$getProcessPatternDetailsResult['processSection'].", ".$_GET['patternId'].", ".$getProcessPatternDetailsResult['processTool'].") ";
	$insert = $db->query($sql);
	
	if(($_GET['country']==1 AND $getProcessPatternDetailsResult['processCode'] =='381') OR ($_GET['country']==2 AND in_array($getProcessPatternDetailsResult['processCode'],array(314,378))))	{
		$sql = "INSERT INTO cadcam_partprocess (partId, processOrder, processCode, processSection, patternId, toolId) VALUES (".$_GET['partId'].", ".++$processOrder.",184, ".$getProcessPatternDetailsResult['processSection'].", ".$_GET['patternId'].", ".$getProcessPatternDetailsResult['processTool'].") ";
		$insert = $db->query($sql);//insert item removal process 2019-07-30 gerald
	}

	if($_GET['country']=="2" AND $getProcessPatternDetailsResult['processSection'] == 10)
	{
		$processName = "";
		$sql = "SELECT processName FROM cadcam_process where processCode = ".$getProcessPatternDetailsResult['processCode'];
		//echo $sql."<br>";
		$processNameQuery = $db->query($sql);
		if($processNameQuery->num_rows > 0)
		{
			$processNameQueryResult = $processNameQuery->fetch_array();
			$processName = $processNameQueryResult['processName'];
			
			$sql = "SELECT treatmentId FROM engineering_treatment WHERE treatmentName LIKE '".$processName."'";
			//echo $sql."<br>";
			$treatmentQuery = $db->query($sql);
			if($treatmentQuery->num_rows > 0)
			{
				$treatmentQueryResult = $treatmentQuery->fetch_array();
							
				$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 19, '', '".$treatmentQueryResult['treatmentId']."', '".$userIP."', '".$userID."', 'INSERT processCode') ";
				//echo $sql."<br>";
				$insertQuery = $db->query($sql);

				$sql = "INSERT INTO cadcam_subconlist (partId, subconId, processCode, surfaceArea, subconOrder) VALUES (".$_GET['partId'].", 0, ".$treatmentQueryResult['treatmentId'].", '', 1) ";
				//echo $sql."<br>";
				$insertQuery = $db->query($sql);			
			}	
		}		
	}
	// ------------------------------------ End Of If Country Is Japan : Auto Insert Added Subcon Process To Subcon Tab ------------------------------------------------
	

}

// ************************************************** Update Process Code Gerald 2019-07-30 ************************************************** //
$sql = "SET @newProcessOrder = ".$processOrder;
$query = $db->query($sql);

$sql = "UPDATE `cadcam_partprocess` SET processOrder = @newProcessOrder := ( @newProcessOrder +1 ) WHERE count IN(".implode(",",$countArray).") ORDER BY FIELD(count,'".implode("','",$countArray)."')";
$queryUpdate = $db->query($sql);
// ************************************************** Update Process Code Gerald 2019-07-30 ************************************************** //

// ----------------------------- Log Changes ------------------------------------------
$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details, userRemarks) VALUES (".$_GET['partId'].", now(), 1, 16, '', 'processPattern = ".$_POST['pattern']."', '".$userIP."', '".$userID."', 'INSERT processPattern', 'processPattern') ";
$insertQuery = $db->query($sql);

header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
?>
