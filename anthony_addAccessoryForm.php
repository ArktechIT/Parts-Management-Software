<?php
include("../Common Data/PHP Modules/mysqliConnection.php");

$sql = "SELECT childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 2 ";
$selectChildId = $db->query($sql);
?>
<style>
div {
	line-height: 15px;
}

label
{
	float: left;
	width: 9em;
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
<form action = "anthony_addAccessoryFormSQL.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>" method = "POST">
	<div>
		<label for = "accesory">Accessory Number:</label>
		<select name = "accessory" id = 'accessoryName'>
		<?php
		$accessoryIdArray = array("''");
		$sql = "SELECT childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 2 ";
		$queryChildAccessoryId = $db->query($sql);
		if($queryChildAccessoryId->num_rows > 0)
		{
			while($resultChildAccessoryId = $queryChildAccessoryId->fetch_array())
			{
				$accessoryIdArray[] = $resultChildAccessoryId['childId'];
			}
		}
		
		$accessoryId = $accessoryNumber = $accessoryId = $revisionId = '';
		$sql = "SELECT accessoryId, accessoryNumber, accessoryName, revisionId FROM cadcam_accessories WHERE accessoryId NOT IN(".implode(",",$accessoryIdArray).") AND status = 0 ORDER by accessoryNumber";
		$queryAccessories = $db->query($sql);
		if($queryAccessories->num_rows > 0)
		{
			while($resultAccessories = $queryAccessories->fetch_array())
			{
				$accessoryId = $resultAccessories['accessoryId'];
				$accessoryNumber = $resultAccessories['accessoryNumber'];
				$accessoryName = $resultAccessories['accessoryName'];
				$revisionId = $resultAccessories['revisionId'];
				echo "<option value = '".$accessoryId."'>".$accessoryNumber." ( ".$accessoryName." ) "." [ ".$revisionId." ]</option>";
			}
		}												
		
		//~ if($selectChildId->num_rows > 0){
			//~ while($selectChildIdResult = $selectChildId->fetch_array()){
				//~ $sql = "SELECT accessoryId, accessoryNumber FROM cadcam_accessories WHERE accessoryId NOT IN (SELECT childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 2) AND status = 0 ORDER BY accessoryNumber ASC ";
				//~ $selectAccessories = $db->query($sql);
				//~ while($selectAccessoriesResult = $selectAccessories->fetch_array()){
					//~ echo "<option value = '".$selectAccessoriesResult['accessoryId']."'>".$selectAccessoriesResult['accessoryNumber']."</option>";
				//~ }
			//~ }
		//~ }else{
			//~ $sql = "SELECT accessoryId, accessoryNumber FROM cadcam_accessories ORDER BY accessoryNumber ASC ";
			//~ $selectAccessories = $db->query($sql);
			//~ while($selectAccessoriesResult = $selectAccessories->fetch_array()){
				//~ echo "<option value = '".$selectAccessoriesResult['accessoryId']."'>".$selectAccessoriesResult['accessoryNumber']."</option>";
			//~ }
		//~ }
		?>
		 </select>
	</div>
	
	<br>		

		
	<div>
		<label for = "quantity">Quantity:</label><input type = "number" name = "quantity" min = "1">
	</div><br>
	
	<div>
		<label for = "remarks">Remarks:</label><input type = "text" name = "remarks">
	</div><br>
	
	<center>
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "submit" value = "Add" class="art-button">
			</div>
			</span>
			</div>
	</center>		
</form>										  
</body>

</html>
