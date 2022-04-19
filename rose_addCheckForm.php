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
<form action = "rose_addCheckFormSQL.php?partId=<?php echo $_GET['partId']; ?>" method = "POST">
	
	
	<div>
<!--
		<label for = "remarks">Check:</label><input type = "text" name = "remarks" value = "<?php echo $remarks; ?>">
-->
		<label for = "remarks">Cutting condition:</label>
<!--
		<input type = "text" name = "remarks" value = "<?php //echo $remarks; ?>">
-->
<?php
	$checkStatus = ($remarks == 'A') ? 'selected' : 'C';
	?>
		<select style = 'width:100px' name = 'remarks' <?php echo $checkStatus;?>>
			<option></option>
			<option value = 'A'<?php if ($partListQueryResult['remarks'] === 'A') echo ' selected="selected"'?> >A</option>
			<option value = 'C' <?php if ($partListQueryResult['remarks'] === 'C') echo ' selected="selected"'?> >C</option>
			<?php
			
			
			?>
			
		</select>
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
