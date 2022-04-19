<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');

echo $processCode = $_POST['processCode'];
echo $identifier = $_POST['identifier'];



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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
        .machine:hover{
            background:#3F51B5;
            color:white;
            padding-left:10px;
        }
    </style>
</head>
<body>
<?php
	echo "<form action = 'carlo_addToolFormSQLCopy.php?partId=".$_GET['partId']."&processCode=".$_GET['processCode']."&toolId=".$_GET['patternId']."&identifier=".$_GET['identifier']."' method = 'POST'>";

?>
<div class="container-fluid">
			
        <div class="row" style= "height:500px;overflow-y:auto;">
				<?php
				
				$sql="SELECT DISTINCT * FROM cadcam_processtools WHERE identifier = '".$identifier."'";
				$querypTools = $db->query($sql);
				while($resultpTools = $querypTools->fetch_assoc())
				{
				?>
					<div class="col-auto machine">
						<input class="form-check-input" type="checkbox" name="inputNote[]" value="<?php echo $resultpTools['toolId'];?>" id="" style="font-size:24px;background:#f9ca24;">
						<label><?php echo $resultpTools['toolName']; ?> <?php echo $resultpTools['dataOne']; ?> <?php echo $resultpTools['dataTwo']; ?> <?php echo $resultpTools['dataThree']; ?> <?php echo $resultpTools['dataFour']; ?> <?php echo $resultpTools['dataFive']; ?></label>
					</div>
				<?php
				}
				?>
			</div>
			
		</div>

		<div id="submitButton" class="text-center" style="margin-top:20px;">
			<!-- <center><input type ="submit" name =" submit" value = "<?php //echo displayText('B4');//Add ?>" class="art-button"><center> -->
			<input type="submit" class="w3-btn w3-round w3-indigo" value="Submit">
		</div>
</form>	
</body>
</html>