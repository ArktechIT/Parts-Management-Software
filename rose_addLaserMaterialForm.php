<?php
include("../Common Data/PHP Modules/mysqliConnection.php");

$sql = "SELECT remarks FROM engineering_partsCheck where partId=".$_GET['partId'];
$partListQuery = $db->query($sql);
if($partListQuery->num_rows > 0)
{
	$partListQueryResult = $partListQuery->fetch_assoc();
	$remarks = $partListQueryResult['remarks'];	
}
?>
<style>
div {
	line-height: 15px;
}

label
{
	float: left;
	width: 9em;
	margin-right: 1em;
	text-align: right;
}
</style>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
</head>
<body>
<form action = "rose_addLaserMaterialFormSQL.php?partId=<?php echo $_GET['partId']; ?>" method = "POST">
<div>

		<label for = "remarks">Material:</label>

<?php
	$checkStatus = ($remarks == 'A') ? 'selected' : 'C';
	echo "<select name = 'remarks'>";	
	echo "<option></option>";
	$sqlLaser = "SELECT listId, materialId FROM system_laserparameter where active=0 ORDER BY materialId";
	$partLaserQuery = $db->query($sqlLaser);
	if($partLaserQuery->num_rows > 0)
	{
		while($partLaserQueryResult = $partLaserQuery->fetch_array())
		{
		$laserParameterName = $partLaserQueryResult['materialId'];
		echo "<option value = '".$partLaserQueryResult['listId']."'>".$laserParameterName."</option>";
		}
	}
	echo "</select>";
	?>
	
	</div><br>
	
	<center>
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "submit" value = "Add" class="art-button">
			</div>
			</span>
			</div>
	</center>		
</form>										  
</body>
</html>
