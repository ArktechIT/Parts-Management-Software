<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');

if(isset($_GET['count']))
{
	$primaryIdGet = "count=".$_GET['count'];
	
	$sql = "SELECT processCode, processName FROM cadcam_process WHERE processCode NOT IN (SELECT processCode FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId'].") AND status = 0 ORDER BY processName ASC ";
	$getProcessName = $db->query($sql);

	$sql = "SELECT processName FROM cadcam_process WHERE processCode = ".$_GET['processCode']." ";
	$getProcessName2 = $db->query($sql);
	$getProcessName2Result = $getProcessName2->fetch_array();
	
	$sql = "SELECT processSection, toolId, dataOne, dataTwo, dataThree, dataFour, dataFive FROM cadcam_partprocess WHERE count = ".$_GET['count']." ";
	$getDetail = $db->query($sql);
	$getDetailResult = $getDetail->fetch_array();

	$sql = "SELECT sectionId, sectionName FROM ppic_section WHERE sectionId = ".$getDetailResult['processSection']." ";
	$getSectionName = $db->query($sql);
	$getSectionNameResult = $getSectionName->fetch_array();

	$sql = "SELECT sectionId, sectionName FROM ppic_section WHERE sectionStatus = 0 AND sectionId NOT IN (SELECT processSection FROM cadcam_partprocess WHERE count = ".$_GET['count'].") ORDER BY sectionName ASC ";
	$getSection = $db->query($sql);

	$sql = "SELECT * FROM cadcam_processtools ORDER BY toolName ASC ";
	$getToolName = $db->query($sql);
}
else if(isset($_GET['subProcessListId']))
{
	$primaryIdGet = "subProcessListId=".$_GET['subProcessListId'];
	
	$sql = "SELECT processCode, processName FROM cadcam_process WHERE processCode NOT IN (SELECT processCode FROM engineering_partsubprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId'].") AND status = 0 ORDER BY processName ASC ";
	$getProcessName = $db->query($sql);

	$sql = "SELECT processName FROM cadcam_process WHERE processCode = ".$_GET['processCode']." ";
	$getProcessName2 = $db->query($sql);
	$getProcessName2Result = $getProcessName2->fetch_array();
	
	$sql = "SELECT toolId, dataOne, dataTwo, dataThree, dataFour, dataFive FROM engineering_partsubprocess WHERE listId = ".$_GET['subProcessListId']." ";
	$getDetail = $db->query($sql);
	$getDetailResult = $getDetail->fetch_array();
	
	$sql = "SELECT * FROM cadcam_processtools ORDER BY toolName ASC ";
	$getToolName = $db->query($sql);
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
</head>
<body>
<form action = "anthony_updateProcessFormSQL.php?partId=<?php echo $_GET['partId']; ?>&<?php echo $primaryIdGet; ?>&patternId=<?php echo $_GET['patternId']; ?>&processCode=<?php echo $_GET['processCode']; ?>" method = "POST">
<center>	
		<label><?php echo displayText('L59');//Process Name ?></label><br>
			<select name = "processName" id = "processName">
			<?php
			echo "<option value = '".$_GET['processCode']."'>".$getProcessName2Result['processName']."</option>";
			while($getProcessNameResult = $getProcessName->fetch_array()){
				echo "<option value = '".$getProcessNameResult['processCode']."'>".$getProcessNameResult['processName']."</option>";
			}
			?>
			</select><br>
			
		<label for = "toolName"><?php echo displayText('L1407');//Process Tool ?>:</label><br>
		<select name = "toolName">
			<option value = '0'></option>
			<?php
				while($getToolNameResult = $getToolName->fetch_array()){
					echo "<option value = '".$getToolNameResult['toolId']."' "; if($getToolNameResult['toolId'] == $getDetailResult['toolId']){ echo 'selected'; } echo ">".$getToolNameResult['toolName']."</option>";
				}
			?>
		 </select><br>
		<?php
		if(isset($_GET['count']))
		{
			?>
		<label><?php echo displayText('L61');//Process Group ?></label><br>
			<select name = "sectionName" id = "sectionName">
			<?php
				echo "<option value = '".$getSectionNameResult['sectionId']."'>".$getSectionNameResult['sectionName']."</option>";
				while($getSectionResult = $getSection->fetch_array()){
					echo "<option value = '".$getSectionResult['sectionId']."'>".$getSectionResult['sectionName']."</option>";
				}
			?>
			</select><br>
		<?php
		}
		?>
			<div id='showTapp' style = 'display:none;'>
				<label for = "tapSize"><?php echo displayText('L1420');//Tap Size ?></label><br>
				<input type = 'number' step = 'any' name = 'tapSize' min = '0.1' value = ''><br>

				<label for = "tapCount"><?php echo displayText('L1421');//Tap Count ?></label><br>
				<input type = 'number' step = 'any' name = 'tapCount' min = '0.1' value = ''><br>
			</div>
			<div id = 'showMe' style = 'display:none;'>
				<label for = "sectionName"><?php echo displayText('L1290');//V-Size ?></label><br>
				<input type = 'number' step = 'any' name = 'vSize' min = '0.1' value = '<?php echo $getDetailResult['dataOne']; ?>'><br>
				
				<label for = "sectionName"><?php echo displayText('L1414');//Punch-R ?></label><br>
				<input type = 'number' step = 'any' name = 'punchR' min = '0.1' value = '<?php echo $getDetailResult['dataTwo']; ?>'><br>
				
				<label for = "sectionName"><?php echo displayText('L1415');//Bending Deduction ?></label><br>
				<input type = 'number' step = 'any' name = 'bendDeduct' min = '0.01' value = '<?php echo $getDetailResult['dataThree']; ?>'><br>

				<label for = "superyagenHeight"><?php echo displayText('L1416');//Super Yagen Height ?></label><br>
				<input type = 'number' step = 'any' name = 'superyagenHeight' min = '0.1' value = '<?php echo $getDetailResult['dataFour']; ?>'><br>

				<label for = "superyagenDistance"><?php echo displayText('L1417');//Super Yagen Distance ?></label><br>
				<input type = 'number' step = 'any' name = 'superyagenDistance' min = '0.1' value = '<?php echo $getDetailResult['dataFive']; ?>'><br>
			</div>	
			
			<label><?php echo displayText('L1411');//User Remark ?></label><br>
			<textarea rows = "5" cols = "30" name = "userRemarks" placeholder = "Enter User Remarks" required></textarea><br>
				
		<span class="art-button-wrapper">
		<span class="art-button-l"> </span>
		<span class="art-button-r"> </span>
		<div id="submitButton">
			<input type ="submit" name = "submit" value = "<?php echo displayText('L1054');//Update ?>" class="art-button">
		</div>
		</span>
</center>
</form>
<br>									  
<br>									  
<br>									  
<br>									  
<br>									  
<br>									  
<br>									  
<br>									  
<br>									  
<br>									  
</body>
</html>
