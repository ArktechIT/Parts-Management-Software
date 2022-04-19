<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');

//maybe wrong must select at engineering treatment!!!! // rose 2017-08-03
//~ $sql = "SELECT processCode, processName FROM cadcam_process WHERE processCode NOT IN (SELECT processCode FROM cadcam_subconlist WHERE partId = ".$_GET['partId'].") AND processSection = 10 ORDER BY processName ASC ";
//~ $getProcess = $db->query($sql);
$sql = "SELECT treatmentId as processCode, treatmentName as processName FROM engineering_treatment WHERE treatmentId NOT IN (SELECT processCode FROM cadcam_subconlist WHERE partId = ".$_GET['partId'].") ORDER BY treatmentName ASC ";
$getProcess = $db->query($sql);

$sql = "SELECT processCode, surfaceArea, subconOrder FROM cadcam_subconlist WHERE a = ".$_GET['subconId']." ";
$getProcessCode = $db->query($sql);
$getProcessCodeResult = $getProcessCode->fetch_array();

//~ $sql = "SELECT processCode, processName FROM cadcam_process WHERE processCode = ".$getProcessCodeResult['processCode']." ";
//~ $getProcessName = $db->query($sql);
//~ $getProcessNameResult = $getProcessName->fetch_array();

$sql = "SELECT treatmentId as processCode, treatmentName as processName FROM engineering_treatment WHERE treatmentId = ".$getProcessCodeResult['processCode']." ";
$getProcessName = $db->query($sql);
$getProcessNameResult = $getProcessName->fetch_array();

$value = $getProcessCodeResult['surfaceArea'];

// ---------- this code will be deleted ---------- //
//~ $sql = "SELECT * FROM cadcam_subcondimension WHERE partId = ".$_GET['partId']." ";
//~ $getSubconDimension = $db->query($sql);
//~ $getSubconDimensionResult = $getSubconDimension->fetch_array();

//~ $value = '';
//~ if($getProcessNameResult['processCode'] == 270)
//~ {
	//~ $value = $getSubconDimensionResult['totalSurfaceClear'];
//~ }
//~ else if($getProcessNameResult['processCode'] == 272)
//~ {
	//~ $value = $getSubconDimensionResult['totalSurfacePrime'];
//~ }
//~ else if($getProcessNameResult['processCode'] == 251)
//~ {
	//~ $value = $getSubconDimensionResult['totalSurfacePassivation'];
//~ }
//~ else if($getProcessNameResult['processCode'] == 284)
//~ {
	//~ $value = $getSubconDimensionResult['totalSurfacePassivation'];
//~ }
//~ else if($getProcessNameResult['processCode'] == 273)
//~ {
	//~ $value = $getSubconDimensionResult['totalSurfaceBrush'];
//~ }
//~ else if($getProcessNameResult['processCode'] == 271)
//~ {
	//~ $value = $getSubconDimensionResult['totalSurfaceBlackHard'];
//~ }
// -------- END this code will be deleted -------- //	
?>
<style>
div.separator {
	height: 25px;
}

label
{
	float: left;
	width: 10em;
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
<form action = "anthony_updateSubconFormSQL.php?partId=<?php echo $_GET['partId']; ?>&subconId=<?php echo $_GET['subconId']; ?>&patternId=<?php echo $_GET['patternId']; ?>" method = "POST">
	<div class = 'separator'>
		<label><?php echo displayText('L1369');//Subcon Order?>:</label>
		<input type = 'number' name = 'subconOrder' min='1' value='<?php echo $getProcessCodeResult['subconOrder'];?>' required>
	</div>

	<div class = 'separator'>
		<label for = "processName"><?php echo displayText('L407');//Subcon Process?>:</label><select name = "processName" style = 'width: 50%;'>
														<?php
															echo "<option value = '".$getProcessNameResult['processCode']."'>".$getProcessNameResult['processName']."</option>";
														while($getProcessResult = $getProcess->fetch_array()){
															echo "<option value = '".$getProcessResult['processCode']."'>".$getProcessResult['processName']."</option>";
														}
														?>
														 </select>
	</div>
	
	<?php
	if($getProcessCodeResult['processCode'] == 270 OR $getProcessCodeResult['processCode'] == 272 OR $getProcessCodeResult['processCode'] == 251 OR $getProcessCodeResult['processCode'] == 284 OR $getProcessCodeResult['processCode'] == 273 OR $getProcessCodeResult['processCode'] == 271)
	{ ?>
		<div class = 'separator'>
			<label><?php echo displayText('L101');//Value?>:</label>
			<input type = 'text' name = 'value' value = '<?php echo $value; ?>'>
		</div> <?php 
	} ?>
	
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
