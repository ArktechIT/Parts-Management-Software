<?php
include("../Common Data/Templates/mysqliConnection.php");
include("../Common Data/PHP Modules/anthony_retrieveText.php");
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
	<link rel = "stylesheet" type = "text/css" href = "../Common Data/anthony.css">
</head>
<body>
<?php
if($_GET['source'] == 'qcsubcon')
{
	echo "<form action = 'anthony_addProcess.php?partId=".$_GET['partId']."&add=2' method = 'POST'>";
}
else if($_GET['source'] == 'qcassysubcon')
{
	echo "<form action = 'anthony_addProcess.php?partId=".$_GET['partId']."&add=6' method = 'POST'>";
}
?>
<table border = '1'>
<tr>
	<td colspan = '2' align = 'center' style = 'font-weight: bold;'><?php echo displayText('L407');?></td>
</tr>
	<?php
	for($i=0; $i<$_GET['hm']; $i++)
	{
		echo "<tr>";
			echo "<td colspan = '2' align = 'center'>";
				echo "<select name = 'subcon[]'>";
					$sql = "SELECT processCode, processName FROM cadcam_process WHERE processCode NOT IN (SELECT processCode FROM cadcam_subconlist WHERE partId = ".$_GET['partId'].") AND processSection = 10 ORDER BY processName ASC ";
					$getProcess = $db->query($sql);
					while($getProcessResult = $getProcess->fetch_array()){
						echo "<option value = '".$getProcessResult['processCode']."'>".$getProcessResult['processName']."</option>";
					}
				echo "</select>";
			echo "</td>";
		echo "</tr>";
	}
	?>
<tr>
	<td colspan = '2' align = 'center'>
		<input type = "submit" value = "Add" name = "Add" class = "anthony_submit">
	</td>
</tr>
</table>
</form>
</body>
</html>
