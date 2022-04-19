<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
</head>
<body>
<?php
	echo "<form action = 'raymond_updateStandardFormSQL.php?partId=".$_GET['partId']."&processCode=".$_GET['processCode']."&patternId=".$_GET['patternId']."' method = 'POST'>";
?>
	<center>
		<label>Update Standard Form</label><br>
	</center>
	<center>
		<?php
		$counter = 1;
		$sql = "SELECT specificationId, listId FROM engineering_partprocessstandard WHERE processCode = ".$_GET['processCode']." AND partId = ".$_GET['partId']."";
		$querySpecId = $db->query($sql);
		if ($querySpecId AND $querySpecId->num_rows > 0)
		{
			while($resultSpecId = $querySpecId->fetch_assoc())
			{
				$specificationId = $resultSpecId['specificationId'];
				$listId = $resultSpecId['listId'];
				?>
				<input type ="hidden" name="listId<?php echo $counter; ?>" value='<?php echo $listId; ?>'>
				<select type="combobox" name="updateStandard<?php echo $counter?>" >
					<option></option>
				<?php
					$customerFilter = (in_array($_GET['customerAlias'],array('JAMCO PHILS','Jamco Corporation'))) ? "AND customerAlias IN('JAMCO PHILS','Jamco Corporation')" : "AND customerAlias = '".$_GET['customerAlias']."'";
					$sql = "SELECT specificationId, specificationNumber, specificationName FROM engineering_specifications WHERE status = 0 ".$customerFilter." ORDER BY specificationNumber";
					$querySpecification = $db->query($sql);
					if($querySpecification AND $querySpecification->num_rows > 0)
					{
						while ($resultSpecification = $querySpecification->fetch_assoc())
						{
							$selectedSpec = ($specificationId == $resultSpecification['specificationId']) ? 'selected' : '';
							echo "<option ".$selectedSpec." value='".$resultSpecification['specificationId']."'>".$resultSpecification['specificationNumber']."</option>";
						}
					}
				?>
				
				</select>
				<br>
				<?php 
				echo "<a href='raymond_deleteStandardFormSQL.php?listId=".$listId."&partId=".$_GET['partId']."&processCode=".$_GET['processCode']."&patternId=".$_GET['patternId']."'><img src='../Common Data/Templates/images/close1.png' width = '20' height = '20'></a><br>";
				$counter++;
			}
			echo "<input type='hidden' name='specCounter' value='".$counter."'>";
		}
		?>
	</center>
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
