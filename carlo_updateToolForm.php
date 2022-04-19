<?php
include("../Common Data/PHP Modules/mysqliConnection.php");

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
</head>
<body>
<?php
	echo "<form action = 'carlo_updateToolFormSQL.php?partId=".$_GET['partId']."&processCode=".$_GET['processCode']."&toolId=".$_POST['toolId']."&listId=".$_POST['listId']."' method = 'POST'>";
?>
	<center>
		<label>Update Tool Form</label><br>
	</center>
		<?php
		$counter = 1;
		$dataValCounter = 0;

		$sql = "SELECT * FROM cadcam_processtoolsdetails WHERE partId = ".$_GET['partId']." AND processCode =".$_GET['processCode'];
		$partProcessToolQuery = $db->query($sql);
		while($partProcessToolQueryResult = $partProcessToolQuery->fetch_array())
		{
				echo "<div class='deleteVal' id ='deleteVal".$dataValCounter."' dataVal=".$dataValCounter.">";
				echo "<input type='hidden' name='listId".$counter."' id='listId".$counter."' value='".$partProcessToolQueryResult['listId']."'>";
				
				$toolId = $partProcessToolQueryResult['toolId'];

				$dataDetails = Array ();
				$sql = "SELECT * FROM cadcam_processtools WHERE toolId = ".$toolId;
				$queryDetails = $db->query($sql);
				if($queryDetails AND $queryDetails->num_rows > 0)
				{
					$resultDetails = $queryDetails->fetch_assoc();
					$toolName = $resultDetails['toolName'];
					$dataDetails[] = $resultDetails['dataOne'];
					$dataDetails[] = $resultDetails['dataTwo'];
					$dataDetails[] = $resultDetails['dataThree'];
					$dataDetails[] = $resultDetails['dataFour'];
				}
												
				echo "<input type='text' name='inputTool".$counter."' size='30' value='".$toolName." ".implode(" ",$dataDetails)."' required>";								
				//echo "<select name = 'inputNote[]'>";			
				//echo "<option>".$toolName."</option>";
				//echo "</select>";
				
				
				echo "<img src='../Common Data/Templates/images/close1.png' width = '20' height = '20' class='deleteValue' delete-counter=".$dataValCounter." listId='".$partProcessToolQueryResult['listId']."'><br>";
			
			echo "</div>";
			$counter++;
			$dataValCounter++;
		}
		echo "<input type='hidden' name='toolCounter' value='".$counter."'>";
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