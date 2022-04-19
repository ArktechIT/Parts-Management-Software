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
	echo "<form form='formNotes' action = 'ace_addNotesFormSQL.php?partId=".$_GET['partId']."&processCode=".$_GET['processCode']."&patternId=".$_GET['patternId']."' method = 'POST'>";
?>
	<?php

	// if($_SESSION['idNumber']=='0521')
	// {	?>
		<img id='addButton2' src = '../Common Data/Templates/images/add1.png' width = '20' height = '20' align="right"><br>
		<table>
			<tr align='center'>
				<td style="width: 280px;"><?php echo displayText('L1409');//Notes ?></td>
				<td><?php echo displayText('L1429');//Common Note ?></td>
			</tr>
			<tr align='center'>
				<td colspan="2" style="width: 250px;">
					<div id="TextBoxesGroup2">
						<div id="TextBoxDiv2">
							<p align='left'><input type="text" name="inputNote[]" value="" style="height:30px; width:150px;">&emsp;&emsp;
							<input id="" type='checkbox' name="commonNote[]" value="1"></p>
						</div>
					</div>
				</td>
			</tr>
		</table>
		<?php
	// }
	// else
	// {
		?>
		<!-- <img id='addButton' src = '../Common Data/Templates/images/add1.png' width = '20' height = '20' align="right">
		
		<br>
		<center>
			<label>Notes</label><br>
		</center>
		<div id="TextBoxesGroup">
			<div id="TextBoxDiv">
		<center>
			<input type="text" name="inputNote[]" value="" required style="height:30px; width:230px">
		</center>
			</div>
		</div>
		<br> -->
		<?php
	// }
	?>
		<div id="submitButton">
			<center>
			<button type ="submit" id='addNotesBTN'name=" submit"><?php echo displayText('B4');//Add ?></button>	
			<!-- <input type ="submit" id='addNotesBTN' name =" submit" value = "<?php echo displayText('B4');//Add ?>" class="art-button"><center> -->
		</div>

</form>										  
</body>
</html>
