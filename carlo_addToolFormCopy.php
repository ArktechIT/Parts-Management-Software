<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');

$processCode = $_GET['processCode'];
$identifier = $_GET['identifier'];



$sql = "SELECT processSection FROM cadcam_process WHERE processCode = ".$processCode;
$querySection = $db->query($sql);
if($querySection AND $querySection->num_rows > 0)
{
	$resultSection = $querySection->fetch_assoc();
	$sectionId = $resultSection['processSection'];
}

$toolIdArray = $dataArray = Array ();
$sql = "SELECT * FROM cadcam_processtools WHERE processSection = 0 OR processSection = ".$sectionId." ORDER BY toolName";
$queryProcessTools = $db->query($sql);
if($queryProcessTools AND $queryProcessTools->num_rows > 0)
{
	while($resultProcessTools = $queryProcessTools->fetch_assoc())
	{
		$toolId = $resultProcessTools['toolId'];
		$toolName = $resultProcessTools['toolName'];
		$dataOne = $resultProcessTools['dataOne'];
		$dataTwo = $resultProcessTools['dataTwo'];
		$dataThree = $resultProcessTools['dataThree'];
		$dataFour = $resultProcessTools['dataFour'];
		$dataFive = $resultProcessTools['dataFive'];
		$processSection = $resultProcessTools['processSection'];

		$appendData = "";
		if($dataOne != "") 		$appendData .= ", ".$dataOne;
		if($dataTwo != "") 		$appendData .= ", ".$dataTwo;
		if($dataThree != "") 	$appendData .= ", ".$dataThree;
		if($dataFour != "") 	$appendData .= ", ".$dataFour;
		if($dataFive != "") 	$appendData .= ", ".$dataFive;

		$toolIdArray[] = $toolId;
		$dataArray[$toolId] = $toolName.$appendData;
	}
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php
	echo "<form action = 'carlo_addToolFormSQLCopy.php?partId=".$_GET['partId']."&processCode=".$_GET['processCode']."&toolId=".$_GET['patternId']."&identifier=".$_GET['identifier']."' method = 'POST'>";

?>
		
		<!-- <table>
			<tr align='center'>
				<td style="width: 280px;"><?php //echo displayText('L1407');//Tool ?></td>
			</tr>
			<tr align='center'>
				<td colspan="2" style="width: 250px;">
					<div id="TextBoxesGroup2">
						<div id="TextBoxDiv2">
								<select name = "inputNote[]">
								<option value = '0'></option>
								<?php
									// foreach ($toolIdArray as $key) 
									// {
									// 	echo "<option value = '".$key."'>".$dataArray[$key]."</option>";
									// }
								?>
						 		</select>
						</div>
					</div>
				</td>
			</tr>
		</table> -->
		<div class="container-fluid">
			
			<div class="row" style= "height:500px;overflow-y:auto;">
				<?php
								
				$sql="SELECT DISTINCT * FROM cadcam_processtools WHERE identifier = '".$identifier."' AND (processSection = 0 OR processSection = ".$sectionId.") ORDER BY toolName";
				$querypTools = $db->query($sql);
				while($resultpTools = $querypTools->fetch_assoc())
				{
				?>
					<div class="col-auto">
						<input class="form-check-input" type="checkbox" name="inputNote[]" value="<?php echo $resultpTools['toolId'];?>" id="" style="font-size:24px">
						<label><?php echo $resultpTools['toolName']; ?> <?php echo $resultpTools['dataOne']; ?> <?php echo $resultpTools['dataTwo']; ?> <?php echo $resultpTools['dataThree']; ?> <?php echo $resultpTools['dataFour']; ?> <?php echo $resultpTools['dataFive']; ?></label>
					</div>
				<?php
				}
				?>
			</div>
			
		</div>

		<div id="submitButton" class="text-center">
			<!-- <center><input type ="submit" name =" submit" value = "<?php //echo displayText('B4');//Add ?>" class="art-button"><center> -->
			<input type="submit" class="btn btn-primary" value="<?php echo displayText('B4');//Add ?>">
		</div>

</form>										  
</body>
</html>
