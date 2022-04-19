<?php
include('../Common Data/PHP Modules/mysqliConnection.php');
include('../Common Data/PHP Modules/anthony_retrieveText.php');
ini_set("display_errors", "on");
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
	<link rel="stylesheet" href="../Common Data/anthony.css" type="text/css" media="screen" />
	<style>
	label{
		display: inline-block;
		width: 20%;
		text-align: right;
		margin-left: 13%;
	}
	
	div.height_separate{
		width: 50%;
		display: inline-block;
		height: 25px;
	}
	</style>
</head>
<body>
<form action = 'anthony_addProcessPatternSQL.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>' method = 'POST'>
	<label><?php echo displayText('L211');//Position ?>:</label>
	<div class = 'height_separate'>
		<select name = 'position' style = 'width:100%;'>
		<?php
			echo "<option value = '0'>Beginning</option>";
		$sql = "SELECT processCode FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." AND processCode != 137 ORDER BY processOrder ASC ";
		$getProcessCode = $db->query($sql);
		while($getProcessCodeResult = $getProcessCode->fetch_array())
		{
			$sql = "SELECT processName FROM cadcam_process WHERE processCode = ".$getProcessCodeResult['processCode']." ";
			$getProcessName = $db->query($sql);
			$getProcessNameResult = $getProcessName->fetch_array();
			
			echo "<option value = '".$getProcessCodeResult['processCode']."'>After ".$getProcessNameResult['processName']."</option>";
		}
		?>
		</select>
	</div><br>
	
	<label><?php echo displayText('L763');//Pattern ?>:</label>
	<div class = 'height_separate'>
		<select name = 'pattern' style = 'width:100%;' id = 'patternprocess' required>
			<option value = ''></option>
		<?php
		$sql = "SELECT * FROM cadcam_processpattern ORDER BY patternName ASC ";
		$getPattern = $db->query($sql);
		while($getPatternResult = $getPattern->fetch_array())
		{
			echo "<option value = '".$getPatternResult['patternId']."'>".$getPatternResult['patternName']."</option>";
		}
		?>
		</select>
	</div><br>
	<center><input type = 'submit' class = 'anthony_submit' id = 'disabled_button' value='<?php echo displayText('B1');//Submit ?>'></center>
</form>
</body>
</html>
