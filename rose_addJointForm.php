<?php
include("../Common Data/PHP Modules/mysqliConnection.php");

$sql = "SELECT jointNote FROM engineering_partsCheck where partId=".$_GET['partId'];
$partListQuery = $db->query($sql);
if($partListQuery->num_rows > 0)
{
	$partListQueryResult = $partListQuery->fetch_assoc();
	$remarks = $partListQueryResult['jointNote'];	
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
<form action = "rose_addJointFormSQL.php?partId=<?php echo $_GET['partId']; ?>" method = "POST">
	
		<input type="hidden" name="remarks" value="X" checked = 'unchecked'/>
		
		
		<?php
		$checkStatus = ($remarks == 'O') ? 'checked' : '';
		?>
		<table border = 1>
			<tr>
				<td><input type='checkbox' name='remarks' value='O' <?php echo $checkStatus;?>></td>
				<td style = 'align:left'><label for = "remarks">Common Cut:</label></td>	
			<tr>
		
		
		</table>
<!--
		<input type='checkbox' name='remarks' value='O' <?php //echo $checkStatus;?>>
		<label for = "remarks">Common Cut:</label>
-->
		
	</div><br>
	<div>
		
		</div>
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
