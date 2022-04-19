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
	echo "<form action = 'raymond_addStandardFormSQL.php?partId=".$_GET['partId']."&processCode=".$_GET['processCode']."&patternId=".$_GET['patternId']."' method = 'POST'>";
	
	$sql = "SELECT specificationId FROM engineering_partprocessstandard WHERE processCode = ".$_GET['processCode']." AND partId=".$_GET['partId'];
	$querySpecId = $db->query($sql);
	if ($querySpecId AND $querySpecId->num_rows > 0)
	{
		$resultSpecId = $querySpecId->fetch_assoc();
		$specificationId = $resultSpecId['specificationId'];
	}
?>
	<center>
		<label><?php echo displayText('L1427');//Add Standard Form ?></label><br>
	</center>
	<center>
		<label><?php echo displayText('L1368');//Standard ?> </label>
		<select type="combobox" name="standard" id="standard">
			<option></option>
			<?php
				$customerFilter = (in_array($_GET['customerAlias'],array('JAMCO PHILS','Jamco Corporation'))) ? "AND customerAlias IN('JAMCO PHILS','Jamco Corporation')" : "AND customerAlias = '".$_GET['customerAlias']."'";
				$sql = "SELECT specificationId, specificationNumber, specificationName FROM engineering_specifications WHERE status = 0 ".$customerFilter." ORDER BY specificationNumber";
				$querySpecification = $db->query($sql);
				if($querySpecification AND $querySpecification->num_rows > 0)
				{
					while ($resultSpecification = $querySpecification->fetch_assoc())
					{
						$specificationId = $resultSpecification['specificationId'];
						$sql = "SELECT specificationId FROM engineering_partprocessstandard WHERE processCode = ".$_GET['processCode']." AND partId=".$_GET['partId']." AND specificationId=".$specificationId;
						$querySpecNumber = $db->query($sql);
						if($querySpecNumber AND $querySpecNumber->num_rows == 0)
						{
							echo "<option data-value='".$resultSpecification['specificationNumber']."' ".$selectedSpec." value='".$resultSpecification['specificationId']."'>".$resultSpecification['specificationNumber']."</option>";
						}
					}
				}
			?>
		</select>
		<img class='addStandard' src = '../Common Data/Templates/images/add1.png' width = '20' height = '20' align="right"><br>
	</center>
	<br>
	<div id="TextBoxesGroup3">
		<div id="TextBoxDiv3">
		</div>
	</div>

	<center>
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "submit" value = "<?php echo displayText('B4');//Add ?>" class="art-button">
			</div>
			</span>
			</div>
	</center>		
</form>										  
</body>
</html>
