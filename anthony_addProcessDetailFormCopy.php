<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');

$sql = "SELECT processCode, processName FROM cadcam_process WHERE processCode NOT IN (SELECT processCode FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId'].") AND status = 0 ORDER BY processName ASC ";
$getProcessName = $db->query($sql);

$sql = "SELECT sectionId, sectionName FROM ppic_section WHERE sectionStatus = 0 ORDER BY sectionName ASC ";
$getSectionName = $db->query($sql);

$sql = "SELECT * FROM cadcam_processtools ORDER BY toolName ASC ";
$getToolName = $db->query($sql);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
</head>
<body>

<?php
if($_GET['insert'] == '1')
{
									//~ partId=".$_GET['partId']."&processCode=".$_GET['processCode']."&patternId=".$_GET['patternId']."
	echo "<form action = 'anthony_addProcessDetailFormSQL.php?partId=".$_GET['partId']."&processCode=".$_GET['processCode']."&count=".$_GET['count']."&insert=1&patternId=".$_GET['patternId']."' method = 'POST'>";
}
else if($_GET['insert'] == '2')
{
	echo "<form action = 'anthony_addProcessDetailFormSQL.php?partId=".$_GET['partId']."&processCode=".$_GET['processCode']."&count=".$_GET['count']."&insert=2&patternId=".$_GET['patternId']."' method = 'POST'>";
}
?>
	<center>
			<input type="hidden" id="partId" value="<?php echo $_GET['partId']; ?>"/>
			<label for = "processName"><?php echo displayText('L59');//Process Name ?>:</label><br><select name = "processName" id = "processName" required>
									<option value = ''></option>
						<?php
							while($getProcessNameResult = $getProcessName->fetch_array()){
								echo "<option value = '".$getProcessNameResult['processCode']."'>".$getProcessNameResult['processName']."</option>";
							}
						?>
						 </select><br><br>
						 

			<label for = "toolName"><?php echo displayText('L1407');//Process Tool ?>:</label><br><select name = "toolName">
								<option value = '0'></option>
						<?php
							while($getToolNameResult = $getToolName->fetch_array()){
								echo "<option value = '".$getToolNameResult['toolId']."'>".$getToolNameResult['toolName']."</option>";
							}
						?>
						 </select><br><br>

						 
			<label for = "sectionName"><?php echo displayText('L61');//Process Group ?>:</label><br><select name = "sectionName" id = "sectionName">
						<?php
							while($getSectionNameResult = $getSectionName->fetch_array()){
								echo "<option value = '".$getSectionNameResult['sectionId']."'>".$getSectionNameResult['sectionName']."</option>";
							}
						?>
						 </select><br><br> 
						 
			<div id = 'showMe' style = 'display:none;'>
				<label for = "sectionName"><?php echo displayText('L1290');//V-Size ?></label><br>
				<input type = 'number' step = 'any' name = 'vSize' min = '0.1'><br><br>
				
				<label for = "sectionName"><?php echo displayText('L1414');//Punch-R ?>:</label><br>
				<input type = 'number' step = 'any' name = 'punchR' min = '0.1'><br><br>
				
				<label for = "sectionName"><?php echo displayText('L1415');//Bending Deduction ?></label><br>
				<input type = 'number' step = 'any' name = 'bendDeduct' min = '0.1'><br><br>

				<label for = "superyagenHeight"><?php echo displayText('L1416');//Super Yagen Height ?></label><br>
				<input type = 'number' step = 'any' name = 'superyagenHeight' min = '0.1'><br><br>

				<label for = "superyagenDistance"><?php echo displayText('L1417');//Super Yagen Distance ?></label><br>
				<input type = 'number' step = 'any' name = 'superyagenDistance' min = '0.1'><br><br>
			</div>
						<!--------Ken edit start------->
				<img id='addButton' src = '../Common Data/Templates/images/add1.png' width = '20' height = '20' align="right">
					<br>
					<center>
						<label><?php echo displayText('L1409');//Notes ?>:</label><br>
					</center>
					<div id="TextBoxesGroup">
						<div id="TextBoxDiv">
							<center>
								<input type="text" name="inputNote[]" value="" style="height:30px; width:230px;"></label><br>
							</center>
						</div>
					</div>
						
				<!---- Ken edit end ------>
			
			<?php
			if($_GET['country'] == 1)
			{ ?>
				<label><?php echo displayText('L1411');//User Remark ?></label><br>
				<textarea rows = "5" cols = "30" name = "userRemarks" placeholder = "Enter User Remarks" required></textarea> <?php
			} ?>
			
	</center><br>
	<center>
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "submit" value = "<?php echo displayText('B4');//Add ?>" class="art-button">
			</div>
			</span>
			</div>
	</center>		
</form>										  
</body>
</html>
