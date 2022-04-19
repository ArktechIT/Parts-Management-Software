<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
</head>
<body>
<?php
	echo "<form action = 'ace_updateNotesFormSQL.php?partId=".$_GET['partId']."&processCode=".$_GET['processCode']."&patternId=".$_GET['patternId']."' method = 'POST'>";
?>
	<center>
		<label>Update Notes Form</label><br>
	</center>
		<?php
		$counter = 1;
		$dataValCounter = 0;
		$sql = "SELECT noteId, remarks, displayFlag FROM cadcam_partprocessnote WHERE partId = ".$_GET['partId']." AND processCode =".$_GET['processCode']." AND patternId =".$_GET['patternId']." ORDER BY noteId";
		$partProcessNoteQuery = $db->query($sql);
		while($partProcessNoteQueryResult = $partProcessNoteQuery->fetch_array())
		{
			if($partProcessNoteQueryResult['displayFlag']==1)
			{
				$checked = "checked";
			}
			else
			{
				$checked = "";
			}
			echo "<div class='deleteVal' id ='deleteVal".$dataValCounter."' dataVal=".$dataValCounter.">";
				echo "<input type='hidden' name='noteId".$counter."' id='noteId".$counter."' value='".$partProcessNoteQueryResult['noteId']."'>";
				echo "<input type='text' name='inputNote".$counter."' size='30' value='".$partProcessNoteQueryResult['remarks']."' required>";
				// if($_SESSION['idNumber']=='0521')
				// {	
					echo "<input id='' type='checkbox' name='commonNote".$counter."' value='1' ".$checked.">";
				// }
				// echo "<a href='ace_deleteNotesFormSQL.php?noteId=".$partProcessNoteQueryResult['noteId']."&partId=".$_GET['partId']."&processCode=".$_GET['processCode']."&patternId=".$_GET['patternId']."'></a>";
				echo "<img src='../Common Data/Templates/images/close1.png' width = '20' height = '20' class='deleteValue' delete-counter=".$dataValCounter." noteId='".$partProcessNoteQueryResult['noteId']."'><br>";
			echo "</div>";
			$counter++;
			$dataValCounter++;
		}
		echo "<input type='hidden' name='noteCounter' value='".$counter."'>";
		?>
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
</form>										  
</body>
</html>
