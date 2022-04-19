<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');

$sql = "SELECT partId, partNumber, revisionId FROM cadcam_parts WHERE customerId = ".$_GET['customerId']." AND partId NOT IN (SELECT childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 1) ORDER BY partNumber ASC ";
$getPartIdNumber = $db->query($sql);

$sql = "SELECT childId, quantity FROM cadcam_subparts WHERE subpartId = ".$_GET['subId']." ";
$getChildId = $db->query($sql);
$getChildIdResult = $getChildId->fetch_array();

$sql = "SELECT partId, partNumber, revisionId FROM cadcam_parts WHERE partId = ".$getChildIdResult['childId']." ";
$getParts = $db->query($sql);
$getPartsResult = $getParts->fetch_array();

$sql = "SELECT listId FROM engineering_subpartprocesslink WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." AND childId = ".$getChildIdResult['childId']." AND identifier = 1";
$querySubPartProcessLink = $db->query($sql);
if($querySubPartProcessLink AND $querySubPartProcessLink->num_rows > 0)
{
	echo displayText('L1435');//"displayText('L1435')";
	exit(0);
}
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
<form action = "anthony_updateSubpartsFormSQL.php?partId=<?php echo $_GET['partId']; ?>&subId=<?php echo $_GET['subId']; ?>&patternId=<?php echo $_GET['patternId']; ?>" method = "POST">
	<div>
		<label for = "partNumber"><?php echo displayText('L28');//Part Number?>:</label><select name = "partNumber">
													  <?php
													  if($getPartsResult['revisionId'] != ''){
														  echo "<option value = '".$getPartsResult['partId']."'>".$getPartsResult['partNumber']." [ ".$getPartsResult['revisionId']." ]"."</option>";
													  }else{
														  echo "<option value = '".$getPartsResult['partId']."'>".$getPartsResult['partNumber']."</option>";
													  }
													  while($getPartIdNumberResult = $getPartIdNumber->fetch_array()){
														if($getPartIdNumberResult['revisionId'] != ''){
															echo "<option value = '".$getPartIdNumberResult['partId']."'>".$getPartIdNumberResult['partNumber']." [ ".$getPartIdNumberResult['revisionId']." ]"."</option>";
														}else{
															echo "<option value = '".$getPartIdNumberResult['partId']."'>".$getPartIdNumberResult['partNumber']."</option>";
														}
													  }
													  ?>
												      </select>
	</div><br>	
		
	<div>
		<label for = "quantity"><?php echo displayText('L31');//Quantity?>:</label><input type = "number" name = "quantity" min = "1" value = "<?php echo $getChildIdResult['quantity']; ?>">
	</div><br>
	<center>
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "submit" value = "<?php echo displayText('L1054');//Update?>" class="art-button">
			</div>
			</span>
			</div>
	</center>		
</form>										  
</body>
</html>
