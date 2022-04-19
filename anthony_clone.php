<?php
SESSION_START();
include('../Common Data/Templates/mysqliConnection.php');
include('../Common Data/PHP Modules/anthony_retrieveText.php');
ini_set('display_errors','on');

if(isset($_POST['clone']))
{
	$sql = "DELETE FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId'];
	$delete = $db->query($sql);
	
	$sql = "DELETE FROM cadcam_partprocessnote WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId'];
	$delete = $db->query($sql);
	
	//~ $sql = "SELECT MAX(processOrder) AS maxProcessOrder FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." ";
	//~ $getMaxProcessOrder = $db->query($sql);
	//~ $getMaxProcessOrderResult = $getMaxProcessOrder->fetch_array();
	//~ if($getMaxProcessOrderResult['maxProcessOrder'] != 0)
	//~ {
		//~ $incremented = $getMaxProcessOrderResult['maxProcessOrder'];
		//~ $sql = "SELECT processCode, processDetail, setupTime, cycleTime FROM cadcam_partprocess WHERE partId = ".$_POST['partId']." ORDER BY processOrder ASC ";
		//~ $getPartProcess = $db->query($sql);
		//~ while($getPartProcessResult = $getPartProcess->fetch_array())
		//~ {
			//~ $sql = "INSERT INTO cadcam_partprocess (partId, processOrder, processCode, processDetail, setupTime, cycleTime) VALUES (".$_GET['partId'].", ".++$incremented.", ".$getPartProcessResult['processCode'].", '".$getPartProcessResult['processDetail']."', '".$getPartProcessResult['setupTime']."', '".$getPartProcessResult['cycleTime']."') ";
			//~ $insert = $db->query($sql);
		//~ }
	//~ }
	//~ else
	//~ {
	$sql = "SELECT processCode, processDetail, processSection, setupTime, cycleTime FROM cadcam_partprocess WHERE partId = ".$_POST['partId']." AND patternId = ".$_POST['pattern']." ORDER BY processOrder ASC ";
	$getPartProcess = $db->query($sql);
	if($getPartProcess->num_rows > 0)
	{
		while($getPartProcessResult = $getPartProcess->fetch_array())
		{
			$sql = "INSERT INTO cadcam_partprocess (partId, processOrder, processCode, processDetail, processSection, setupTime, cycleTime, patternId) VALUES (".$_GET['partId'].", ".++$i.", ".$getPartProcessResult['processCode'].", '".$getPartProcessResult['processDetail']."', ".$getPartProcessResult['processSection']." ,'".$getPartProcessResult['setupTime']."', '".$getPartProcessResult['cycleTime']."', ".$_GET['patternId'].") ";
			$insert = $db->query($sql);
		}
	}
	
	$sql = "SELECT processCode, patternId, remarks FROM cadcam_partprocessnote WHERE partId = ".$_POST['partId']." AND patternId = ".$_POST['pattern'];
	$getProcessNote = $db->query($sql);
	if($getProcessNote->num_rows > 0)
	{
		while($getProcessNoteResult = $getProcessNote->fetch_array())
		{
			$sql = "INSERT INTO cadcam_partprocessnote (partId, processCode, patternId, remarks) VALUES (".$_GET['partId'].", ".$getProcessNoteResult['processCode'].", ".$_GET['patternId'].", '".$getProcessNoteResult['remarks']."') ";
			$insert = $db->query($sql);
		}
	}
	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 4, 16, '".$_GET['partId']."', '".$_POST['partId']."', '".$_SERVER['REMOTE_ADDR']."', '".$_SESSION['userID']."', 'Clone partId=".$_POST['partId']." to ".$_GET['partId']."') ";
	$insert = $db->query($sql);
	
	//~ }
	header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
	<link rel = 'stylesheet' type = 'text/css' href = '../Common Data/anthony.css'>
</head>
<body>
<form action = 'anthony_clone.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>' method = 'POST'>
<?php echo displayText('L28');//Part Number ?>: <select name = 'partId' id = 'partId' style = 'width: 200px;'>
	<option></option>
<?php
$sql = "SELECT partId, partNumber, partName, revisionId, partNote FROM cadcam_parts WHERE customerId = ".$_GET['customerId']." ORDER BY partNumber ASC ";
if($_GET['customerId']==37)
{
	$sql = "SELECT partId, partNumber, partName, revisionId, partNote FROM cadcam_parts WHERE customerId = ".$_GET['customerId']." OR customerId = 28 ORDER BY partNumber ASC ";
}
if($_GET['customerId']==11)
{
	$sql = "SELECT partId, partNumber, partName, revisionId, partNote FROM cadcam_parts WHERE customerId = ".$_GET['customerId']." OR customerId IN(10,85) ORDER BY partNumber ASC ";
}
if($_GET['customerId']==10)
{
	$sql = "SELECT partId, partNumber, partName, revisionId, partNote FROM cadcam_parts WHERE customerId = ".$_GET['customerId']." OR customerId IN(11,85) ORDER BY partNumber ASC ";
}
if($_GET['customerId']==85)
{
	$sql = "SELECT partId, partNumber, partName, revisionId, partNote FROM cadcam_parts WHERE customerId = ".$_GET['customerId']." OR customerId IN(10,11) ORDER BY partNumber ASC ";
}
$getParts = $db->query($sql);
while($getPartsResult = $getParts->fetch_array())
{
	if($getPartsResult['revisionId'] != '')
	{
		echo "<option value = '".$getPartsResult['partId']."'>".$getPartsResult['partNumber']." [ ".$getPartsResult['revisionId']." ] "." [ ".$getPartsResult['partName']." ] "." [ ".$getPartsResult['partNote']." ] "."</option>";
	}
	else
	{
		echo "<option value = '".$getPartsResult['partId']."'>".$getPartsResult['partNumber']." [ ".$getPartsResult['partName']." ] "." [ ".$getPartsResult['partNote']." ] "."</option>";
	}
}
?>
</select><br><br>
<?php echo displayText('L1405');//PatternId?>: <select name = 'pattern' id = 'pattern'>

</select><br><br>
<center><input type = 'submit' name = 'clone' value = '<?php echo displayText('L1406');//Clone?>' class = 'anthony_submit'></center>
</form>
</body>
</html>
