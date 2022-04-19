<?php
include("../Common Data/PHP Modules/mysqliConnection.php");

$sql = "SELECT accessoryId, accessoryNumber, accessoryName, revisionId FROM cadcam_accessories WHERE accessoryId NOT IN (SELECT childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 2) ORDER BY accessoryNumber ASC ";
$getAccessoryNumber = $db->query($sql);

$sql = "SELECT childId, quantity, remarks FROM cadcam_subparts WHERE subpartId = ".$_GET['subId']." AND identifier = 2 ";
$getChildId = $db->query($sql);
$getChildIdResult = $getChildId->fetch_array();

$sql = "SELECT accessoryId, accessoryNumber, accessoryName, revisionId FROM cadcam_accessories WHERE accessoryId = ".$getChildIdResult['childId']." ";
$getAccessoryId = $db->query($sql);
$getAccessoryIdResult = $getAccessoryId->fetch_array();
?>
<style>
div {
	line-height: 15px;
}

label
{
	float: left;
	width: 8em;
	margin-right: 1em;
	text-align: right;
}
</style>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
</head>
<body>
<form action = "anthony_updateAccessoryFormSQL.php?partId=<?php echo $_GET['partId']; ?>&subId=<?php echo $_GET['subId']; ?>&patternId=<?php echo $_GET['patternId']; ?>" method = "POST">
	<div>
		<label for = "accesory">Accessory Number:</label><select name = "accessory">
														<?php
															echo "<option value = '".$getAccessoryIdResult['accessoryId']."'>".$getAccessoryIdResult['accessoryNumber']." ( ".$getAccessoryIdResult['accessoryName']." ) [ ".$getAccessoryIdResult['revisionId']." ]</option>";
														while($getAccessoryNumberResult = $getAccessoryNumber->fetch_array()){
															echo "<option value = '".$getAccessoryNumberResult['accessoryId']."'>".$getAccessoryNumberResult['accessoryNumber']." ( ".$getAccessoryNumberResult['accessoryName']." ) [ ".$getAccessoryNumberResult['revisionId']." ]</option>";
														}
														?>
														 </select>
	</div><br>	
		
	<div>
		<label for = "quantity">Quantity:</label><input type = "number" name = "quantity" min = "1" value = "<?php echo $getChildIdResult['quantity']; ?>">
	</div><br>
	
	<div>
		<label for = "remarks">Remarks:</label><input type = "text" name = "remarks" value = "<?php echo $getChildIdResult['remarks']; ?>">
	</div><br>
	
	<center>
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "submit" value = "Update" class="art-button">
			</div>
			</span>
			</div>
	</center>		
</form>										  
</body>
</html>
