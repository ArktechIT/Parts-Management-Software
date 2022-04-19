<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include("../Common Data/PHP Modules/anthony_retrieveText.php");
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
</head>
<body>
<?php
	echo "<form action = 'ace_updateSubconNotesFormSQL.php' method = 'POST'>";
?>
	<input type="hidden" name="partId" value="<?php echo $_GET['partId']; ?>">
	<input type="hidden" name="patternId" value="<?php echo $_GET['patternId']; ?>">
	<input type="hidden" name="subconListId" value="<?php echo $_GET['subconListId']; ?>">
	<center>
		<label>Update Subcon Notes Form</label><br>
	</center>
	
	<table border="1">
		<tr>
			<td><center><?php echo displayText('L1449');?></center></td>
			<td><center><?php echo displayText('L1450');?></center></td>
			<td><center><?php echo displayText('L1451');?></center></td>
			<td><center><?php echo displayText('L1120');?></center></td>
		</tr>
		
		<?php
		$counter = 1;
		$sql = "SELECT remarksId, dataOne, dataTwo, dataThree FROM engineering_subconremarks WHERE subconListId = ".$_GET['subconListId'];
		$subconRemarksQuery=$db->query($sql);
		while($subconRemarksQueryResult = $subconRemarksQuery->fetch_assoc())
		{
			echo "<input type='hidden' name='remarksId".$counter."' value='".$subconRemarksQueryResult['remarksId']."'>";
			echo "<tr>";
				echo "<td><input type='text' name='inputColor".$counter."' value='".$subconRemarksQueryResult['dataOne']."' required></td>";
				echo "<td><input type='text' name='inputFace".$counter."' value='".$subconRemarksQueryResult['dataTwo']."' required></td>";
				echo "<td><input type='text' name='inputQuality".$counter."' value='".$subconRemarksQueryResult['dataThree']."'></td>";
				echo "<td><center><a href='ace_deleteSubconNotesFormSQL.php?remarksId=".$subconRemarksQueryResult['remarksId']."&partId=".$_GET['partId']."&patternId=".$_GET['patternId']."'><img src='../Common Data/Templates/images/close1.png' width = '20' height = '20'></a></center></td>";
			echo "</tr>";
			$counter++;
		}
		echo "<input type='hidden' name='noteCounter' value='".$counter."'>";
		?>		
	
		<tr>
			<td colspan="4">
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
			</td>
		</tr>
</form>										  
</body>
</html>
