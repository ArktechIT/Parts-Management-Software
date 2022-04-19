<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
</head>
<body>
<?php
	echo "<form action = 'ace_addSubconNotesFormSQL.php' method = 'POST'>";
?>
	<input type="hidden" name="partId" value="<?php echo $_GET['partId']; ?>">
	<input type="hidden" name="patternId" value="<?php echo $_GET['patternId']; ?>">
	<input type="hidden" name="subconListId" value="<?php echo $_GET['subconListId']; ?>">
	<center>
		<label><?php echo displayText('L1447');//Add Subcon Notes Form?></label><br>
	</center>
	<center>
		<table border="1">
			<tr>
				<td><center><?php echo displayText('L1449');//Color?></center></td>
				<td><center><?php echo displayText('L1450');//Face?></center></td>
				<td><center><?php echo displayText('L1451');//Quality?></center></td>
			</tr>
			<tr>
				<td><input type='text' name='inputColor' value='' required></td>
				<td><input type='text' name='inputFace' value='' required></td>
				<td><input type='text' name='inputQuality' value=''></td>
			</tr>
		</table>
	</center>
	<center>
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "submit" value = "<?php echo displayText('B4');//Add?>" class="art-button">
			</div>
			</span>
			</div>
	</center>		
</form>										  
</body>
</html>
