<?php
//~ SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];

$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details, userRemarks) VALUES (".$_GET['partId'].", now(), 1, 16, '', '".$_POST['processName']."', '".$userIP."', '".$userID."', 'INSERT processCode', '".$_POST['userRemarks']." ;patternId=".$_GET['patternId']."') ";
$insert = $db->query($sql);


if($_GET['insert'] == '1')
{
	$processOrder = 0;
	$countArray = array();
	$sql = "SELECT processOrder, count FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder ASC ";
	$incrementedProcessOrder = 1;
	$getPartProcess = $db->query($sql);
	while($getPartProcessResult = $getPartProcess->fetch_array())
	{
		$incrementedProcessOrder = $incrementedProcessOrder + 1;
		$sql = "UPDATE cadcam_partprocess SET processOrder = ".$incrementedProcessOrder." WHERE count = ".$getPartProcessResult['count']." ";
		//~ $update = $db->query($sql);
		
		$countArray[] = $getPartProcessResult['count'];
	}
	//ken edit start
	$inputNoteArray = array();
	$inputNoteArray = $_POST['inputNote'];

	foreach($inputNoteArray as $inputNote)
	{
		$sql = "INSERT INTO cadcam_partprocessnote (partId, processCode, patternId, remarks)
		VALUES (".$_GET['partId'].", ".$_POST['processName'].", ".$_GET['patternId'].", '".$inputNote."')";
		$insertQuery = $db->query($sql);
		//ken edit end
	}
	
	if ($_POST['processName'] == '94') 
	{
		$sql = "INSERT INTO	cadcam_partprocess
						(	partId,					processOrder,			processCode,				processSection,				patternId,				toolId,					dataOne,				dataTwo,				dataThree,					dataFour,							dataFive)
				VALUES	(	".$_GET['partId'].",	".(++$processOrder).",	539,						".$_POST['sectionName'].",	".$_GET['patternId'].", ".$_POST['toolName'].", '".$_POST['vSize']."',	'".$_POST['punchR']."',	'".$_POST['bendDeduct']."', '".$_POST['superyagenHeight']."',	'".$_POST['superyagenDistance']."') ";//Column mismatched 2017-03-09 added `dataSix` field in table cadcam_partprocess
		$insert = $db->query($sql);
	}

	if ($_POST['processName'] == '96') 
	{
		$sql = "INSERT INTO	cadcam_partprocess
						(	partId,					processOrder,			processCode,				processSection,				patternId,				toolId,					dataOne,				dataTwo,				dataThree,					dataFour,							dataFive)
				VALUES	(	".$_GET['partId'].",	".(++$processOrder).",	540,						".$_POST['sectionName'].",	".$_GET['patternId'].", ".$_POST['toolName'].", '".$_POST['vSize']."',	'".$_POST['punchR']."',	'".$_POST['bendDeduct']."', '".$_POST['superyagenHeight']."',	'".$_POST['superyagenDistance']."') ";//Column mismatched 2017-03-09 added `dataSix` field in table cadcam_partprocess
		$insert = $db->query($sql);
	}

	$sql = "INSERT INTO	cadcam_partprocess
					(	partId,					processOrder,				processCode,				processSection,				patternId,				toolId,					dataOne,				dataTwo,				dataThree,					dataFour,							dataFive)
			VALUES	(	".$_GET['partId'].",	".(++$processOrder).",		".$_POST['processName'].",	".$_POST['sectionName'].",	".$_GET['patternId'].", ".$_POST['toolName'].", '".$_POST['vSize']."',	'".$_POST['punchR']."',	'".$_POST['bendDeduct']."', '".$_POST['superyagenHeight']."',	'".$_POST['superyagenDistance']."') ";//Column mismatched 2017-03-09 added `dataSix` field in table cadcam_partprocess
	$insert = $db->query($sql);

	if(($_GET['country']==1 AND $_POST['processName'] =='381') OR ($_GET['country']==2 AND in_array($_POST['processName'],array(314,378))))
	{
		$sql = "INSERT INTO	cadcam_partprocess
						(	partId,					processOrder,			processCode,				processSection,				patternId,				toolId,					dataOne,				dataTwo,				dataThree,					dataFour,							dataFive)
				VALUES	(	".$_GET['partId'].",	".(++$processOrder).",	184,						0,							".$_GET['patternId'].", ".$_POST['toolName'].", '".$_POST['vSize']."',	'".$_POST['punchR']."',	'".$_POST['bendDeduct']."', '".$_POST['superyagenHeight']."',	'".$_POST['superyagenDistance']."') ";//Column mismatched 2017-03-09 added `dataSix` field in table cadcam_partprocess
		$insert = $db->query($sql);
	}

	// ------------------------------------ If Country Is Japan : Auto Insert Added Subcon Process To Subcon Tab ------------------------------------------------
	if($_GET['country']=="2" AND $_POST['sectionName'] == 10)
	{
		$processName = "";
		$sql = "SELECT processName FROM cadcam_process where processCode = ".$_POST['processName'];
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
							
				$receivingProcessArray = array(137,138,229);
				
				$receivingProcess = '';
				foreach($receivingProcessArray as $rProcess)
				{
					$sql = "SELECT processCode FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." AND processCode = ".$rProcess." ORDER BY processOrder ASC LIMIT 1";
					$queryPartProcess2 = $db->query($sql);
					if($queryPartProcess2 AND $queryPartProcess2->num_rows == 0)
					{
						$receivingProcess = $rProcess;
						break;
					}
				}
				
				$nextProcessCode = $nextProcessSection = '';
				$sql = "SELECT processCode, processSection FROM cadcam_partprocess WHERE count IN(".implode(",",$countArray).") ORDER BY FIELD(count,'".implode("','",$countArray)."') LIMIT 1";
				$queryPartProcess3 = $db->query($sql);
				if($queryPartProcess3 AND $queryPartProcess3->num_rows > 0)
				{
					$resultPartProcess3 = $queryPartProcess3->fetch_assoc();
					$nextProcessCode = $resultPartProcess3['processCode'];
					$nextProcessSection = $resultPartProcess3['processSection'];
				}
				
				if(!in_array($nextProcessCode,$receivingProcessArray) AND $nextProcessSection!=10)
				{
					$sql = "INSERT INTO	cadcam_partprocess
									(	partId,					processOrder,			processCode,				processSection,				patternId,				toolId,					dataOne,				dataTwo,				dataThree,					dataFour,							dataFive)
							VALUES	(	".$_GET['partId'].",	".(++$processOrder).",	".$receivingProcess.",		4,							".$_GET['patternId'].", ".$_POST['toolName'].", '".$_POST['vSize']."',	'".$_POST['punchR']."',	'".$_POST['bendDeduct']."', '".$_POST['superyagenHeight']."',	'".$_POST['superyagenDistance']."') ";//Column mismatched 2017-03-09 added `dataSix` field in table cadcam_partprocess
					$insert = $db->query($sql);
				}
				
				$subconOrder = 1;
				if($receivingProcess==137)		$subconOrder = 1;
				else if($receivingProcess==138)	$subconOrder = 2;
				else if($receivingProcess==229)	$subconOrder = 3;				
				
				$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 19, '', '".$treatmentQueryResult['treatmentId']."', '".$userIP."', '".$userID."', 'INSERT processCode') ";
				//echo $sql."<br>";
				$insertQuery = $db->query($sql);

				$sql = "INSERT INTO cadcam_subconlist (partId, subconId, processCode, surfaceArea, subconOrder) VALUES (".$_GET['partId'].", 0, ".$treatmentQueryResult['treatmentId'].", '', '".$subconOrder."') ";
				//echo $sql."<br>";
				$insertQuery = $db->query($sql);	
			}
		}
	}
	// ------------------------------------ End Of If Country Is Japan : Auto Insert Added Subcon Process To Subcon Tab ------------------------------------------------	
	
	// ************************************************** Update Process Code Gerald 2019-07-30 ************************************************** //
	$sql = "SET @newProcessOrder = ".$processOrder;
	$query = $db->query($sql);

	$sql = "UPDATE `cadcam_partprocess` SET processOrder = @newProcessOrder := ( @newProcessOrder +1 ) WHERE count IN(".implode(",",$countArray).") ORDER BY FIELD(count,'".implode("','",$countArray)."')";
	$queryUpdate = $db->query($sql);
	// ************************************************** Update Process Code Gerald 2019-07-30 ************************************************** //
}
else if($_GET['insert'] == '2')
{
	$sql = "SELECT processOrder FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." AND count = ".$_GET['count']." ";
	$getProcessOrder = $db->query($sql);
	$getProcessOrderResult = $getProcessOrder->fetch_array();
	$increment = $getProcessOrderResult['processOrder'] + 1;

	$countArray = array();
	$sql = "SELECT processOrder, count FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND processOrder > ".$getProcessOrderResult['processOrder']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder ASC ";
	$getPartProcess = $db->query($sql);
	while($getPartProcessResult = $getPartProcess->fetch_array())
	{
		$incrementedProcessOrder = $getPartProcessResult['processOrder'] + 1;
		$sql = "UPDATE cadcam_partprocess SET processOrder = ".$incrementedProcessOrder." WHERE count = ".$getPartProcessResult['count']." ";
		//~ $update = $db->query($sql);
		
		$countArray[] = $getPartProcessResult['count'];
	}
		//ken edit start
	$inputNoteArray = array();
	$inputNoteArray = $_POST['inputNote'];
	
	foreach($inputNoteArray as $inputNote)
	{
		if(trim($inputNote)!='')
		{
			$sql = "INSERT INTO cadcam_partprocessnote (partId, processCode, patternId, remarks)
			VALUES (".$_GET['partId'].", ".$_POST['processName'].", ".$_GET['patternId'].", '".$inputNote."')";
			$insertQuery = $db->query($sql);
			//ken edit end
		}
	}
	
	if ($_POST['processName'] == '94') {
		$sql = "INSERT INTO cadcam_partprocess(partId, processOrder, processCode, processSection, patternId, toolId, dataOne, dataTwo, dataThree, dataFour, dataFive) VALUES (".$_GET['partId'].", ".$increment.",539, ".$_POST['sectionName'].", ".$_GET['patternId'].", ".$_POST['toolName'].", '".$_POST['vSize']."', '".$_POST['punchR']."', '".$_POST['bendDeduct']."', '".$_POST['superyagenHeight']."', '".$_POST['superyagenDistance']."') ";
		$insert = $db->query($sql);
		$increment++;
	}

	if ($_POST['processName'] == '96') {
		$sql = "INSERT INTO cadcam_partprocess(partId, processOrder, processCode, processSection, patternId, toolId, dataOne, dataTwo, dataThree, dataFour, dataFive) VALUES (".$_GET['partId'].", ".$increment.",540, ".$_POST['sectionName'].", ".$_GET['patternId'].", ".$_POST['toolName'].", '".$_POST['vSize']."', '".$_POST['punchR']."', '".$_POST['bendDeduct']."', '".$_POST['superyagenHeight']."', '".$_POST['superyagenDistance']."') ";
		$insert = $db->query($sql);
		$increment++;
	}
	
	$sql = "INSERT INTO cadcam_partprocess(partId, processOrder, processCode, processSection, patternId, toolId, dataOne, dataTwo, dataThree, dataFour, dataFive) VALUES (".$_GET['partId'].", ".$increment.", ".$_POST['processName'].", ".$_POST['sectionName'].", ".$_GET['patternId'].", ".$_POST['toolName'].", '".$_POST['vSize']."', '".$_POST['punchR']."', '".$_POST['bendDeduct']."', '".$_POST['superyagenHeight']."', '".$_POST['superyagenDistance']."') ";
	$insert = $db->query($sql);
	
	if(($_GET['country']==1 AND $_POST['processName'] =='381') OR ($_GET['country']==2 AND in_array($_POST['processName'],array(314,378)))) {
		$increment++;
		$sql = "INSERT INTO cadcam_partprocess(partId, processOrder, processCode, processSection, patternId, toolId, dataOne, dataTwo, dataThree, dataFour, dataFive) VALUES (".$_GET['partId'].", ".$increment.",184, 0, ".$_GET['patternId'].", ".$_POST['toolName'].", '".$_POST['vSize']."', '".$_POST['punchR']."', '".$_POST['bendDeduct']."', '".$_POST['superyagenHeight']."', '".$_POST['superyagenDistance']."') ";
		$insert = $db->query($sql);
	}

	// ------------------------------------ If Country Is Japan : Auto Insert Added Subcon Process To Subcon Tab ------------------------------------------------
	if($_GET['country']=="2" AND $_POST['sectionName'] == 10)
	{
		$processName = "";
		$sql = "SELECT processName FROM cadcam_process where processCode = ".$_POST['processName'];
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
							
				$receivingProcessArray = array(137,138,229);
				
				$receivingProcess = '';
				foreach($receivingProcessArray as $rProcess)
				{
					$sql = "SELECT processCode FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." AND processCode = ".$rProcess." ORDER BY processOrder ASC LIMIT 1";
					$queryPartProcess2 = $db->query($sql);
					if($queryPartProcess2 AND $queryPartProcess2->num_rows == 0)
					{
						$receivingProcess = $rProcess;
						break;
					}
				}
				
				$nextProcessCode = $nextProcessSection = '';
				$sql = "SELECT processCode, processSection FROM cadcam_partprocess WHERE count IN(".implode(",",$countArray).") ORDER BY FIELD(count,'".implode("','",$countArray)."') LIMIT 1";
				$queryPartProcess3 = $db->query($sql);
				if($queryPartProcess3 AND $queryPartProcess3->num_rows > 0)
				{
					$resultPartProcess3 = $queryPartProcess3->fetch_assoc();
					$nextProcessCode = $resultPartProcess3['processCode'];
					$nextProcessSection = $resultPartProcess3['processSection'];
				}
				
				if(!in_array($nextProcessCode,$receivingProcessArray) AND $nextProcessSection!=10)
				{
					$increment++;
					$sql = "INSERT INTO cadcam_partprocess(partId, processOrder, processCode, processSection, patternId, toolId, dataOne, dataTwo, dataThree, dataFour, dataFive) VALUES (".$_GET['partId'].", ".$increment.",".$receivingProcess.", 4, ".$_GET['patternId'].", ".$_POST['toolName'].", '".$_POST['vSize']."', '".$_POST['punchR']."', '".$_POST['bendDeduct']."', '".$_POST['superyagenHeight']."', '".$_POST['superyagenDistance']."') ";
					$insert = $db->query($sql);
				}		
				
				$subconOrder = 1;
				if($receivingProcess==137)		$subconOrder = 1;
				else if($receivingProcess==138)	$subconOrder = 2;
				else if($receivingProcess==229)	$subconOrder = 3;				
				
				$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 1, 19, '', '".$treatmentQueryResult['treatmentId']."', '".$userIP."', '".$userID."', 'INSERT processCode') ";
				//echo $sql."<br>";
				$insertQuery = $db->query($sql);

				$sql = "INSERT INTO cadcam_subconlist (partId, subconId, processCode, surfaceArea, subconOrder) VALUES (".$_GET['partId'].", 0, ".$treatmentQueryResult['treatmentId'].", '', '".$subconOrder."') ";
				//echo $sql."<br>";
				$insertQuery = $db->query($sql);
					
			}	
		}		
	}
	// ------------------------------------ End Of If Country Is Japan : Auto Insert Added Subcon Process To Subcon Tab ------------------------------------------------
	
	// ************************************************** Update Process Code Gerald 2019-07-30 ************************************************** //
	$sql = "SET @newProcessOrder = ".$increment;
	$query = $db->query($sql);
	
	$sql = "UPDATE `cadcam_partprocess` SET processOrder = @newProcessOrder := ( @newProcessOrder +1 ) WHERE count IN(".implode(",",$countArray).") ORDER BY FIELD(count,'".implode("','",$countArray)."')";
	$queryUpdate = $db->query($sql);
	// ************************************************** Update Process Code Gerald 2019-07-30 ************************************************** //
}
//~ if($_SESSION['idNumber']=='0346') exit(0);
header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
?>
