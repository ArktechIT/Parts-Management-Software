<?php
SESSION_START();
include('../Common Data/PHP Modules/mysqliConnection.php');
include('../Common Data/PHP Modules/rose_prodfunctions.php');
if($_POST['g'] == 'on')
{
	$_POST['g'] = 1;
}
else
{
	$_POST['g'] = 0;
}
list($howManyProgram,$progIds,$progNames)=checkMultiplePartProgram($_GET['partId']);
$_GET['programId'] = (isset($_GET['programId'])) ? $_GET['programId'] : '0';
if($howManyProgram>1 and $_GET['programId']==0)
{
	echo "ERROR, need to know programType";
}
else
{
	$userID = $_SESSION['userID'];
	$userIP = $_SERVER['REMOTE_ADDR'];
	$sql = "SELECT programId, programName, programCode, programX, programY, programI, programJ, programGZero, quantity FROM engineering_programs WHERE partId = ".$_GET['partId']." ";

	if($_GET['programId']>0)
	{
		$sql = "SELECT programId, programName, programCode, programX, programY, programI, programJ, programGZero, quantity FROM engineering_programs WHERE partId = ".$_GET['partId']." and programId=".$_GET['programId'];
	}

	$getOldPrograms = $db->query($sql);
	$getOldProgramsResult = $getOldPrograms->fetch_array();
	$oldProgramCode = $getOldProgramsResult['programCode'];
	$oldProgramX = $getOldProgramsResult['programX'];
	$oldProgramY = $getOldProgramsResult['programY'];
	$oldProgramI = $getOldProgramsResult['programI'];
	$oldProgramJ = $getOldProgramsResult['programJ'];
	$oldProgramG = $getOldProgramsResult['programGZero'];
	$oldQuantity = $getOldProgramsResult['quantity'];
	
	$rose_progId=$getOldProgramsResult['programId'];
	if($oldProgramCode != $_POST['programCode'])
	{	
		$sql = "INSERT INTO engineering_programslog (programId, programName, programCode, date, IP, user, remarks, remarks2) VALUES (".$getOldProgramsResult['programId'].", '".$getOldProgramsResult['programName']."', '".$getOldProgramsResult['programCode']."', now(), '".$userIP."', '".$userID."', '".$_POST['remarks']."', '".$rose_progId."') ";
		$insertProgramsLog = $db->query($sql);
	}
	$changeLog = "";
	if($oldProgramX != $_POST['x'])
	{
		$changeLog = $changeLog."Old X = ".$oldProgramX.";";
	}
	if($oldProgramY != $_POST['y'])
	{
		$changeLog = $changeLog."Old Y = ".$oldProgramY.";";
	}
	if($oldProgramI != $_POST['i'])
	{
		$changeLog = $changeLog."Old I = ".$oldProgramI.";";
	}
	if($oldProgramJ != $_POST['j'])
	{
		$changeLog = $changeLog."Old J = ".$oldProgramJ.";";
	}
	if($oldProgramG != $_POST['g'])
	{
		$changeLog = $changeLog."Old GZero = ".$oldProgramG.";";
	}
	if($oldQuantity != $_POST['produceQuantity'])
	{
		$changeLog = $changeLog."Old Quantity = ".$oldQuantity.";";
	}
	if($changeLog != "")
	{
		$sql = "INSERT INTO engineering_programslog (programId, programName, programCode, date, IP, user, remarks, remarks2) VALUES (".$getOldProgramsResult['programId'].", '".$getOldProgramsResult['programName']."', '".$changeLog."', now(), '".$userIP."', '".$userID."', '".$_POST['remarks']."', '".$rose_progId."') ";
		$insertPrograms = $db->query($sql);
	}
	

	$sql = "UPDATE engineering_programs SET programCode = '".$_POST['programCode']."', programX = '".$_POST['x']."', programY = '".$_POST['y']."', programI = '".$_POST['i']."', programJ = '".$_POST['j']."', programGZero = ".$_POST['g'].", quantity = ".$_POST['produceQuantity'].", programStatus = 0, authorizer = '' WHERE partId = ".$_GET['partId']." and programId = ".$rose_progId." ";
	$updatePrograms = $db->query($sql);

	//2017-06-19 Update NC Program Process Request By Ma'am Mercy
	$lotNumberArray = array();
	$sql = "SELECT lotNumber FROM ppic_lotlist WHERE partId = ".$_GET['partId']." AND identifier = 5";
	$queryLotList = $db->query($sql);
	if($queryLotList AND $queryLotList->num_rows > 0)
	{
		while($resultLotList = $queryLotList->fetch_assoc())
		{
			$lotNumberArray[] = $resultLotList['lotNumber'];
		}
	}
	// in use
	$sql = "UPDATE ppic_workschedule SET status = 1, actualEnd = NOW(), actualFinish = NOW(), employeeId = '".$_SESSION['idNumber']."' WHERE lotNumber IN('".implode("','",$lotNumberArray)."') AND processCode = 301 AND status = 0";
	$queryUpdate = $db->query($sql);
	//2017-06-19 9:51 am Update NC Program Process Request By Ma'am Mercy

	header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=programs&programId=".$rose_progId."&action=updateProgramCode");
}
?>
