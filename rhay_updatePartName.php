<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');

$partId = $_GET['partId'];

$sql = "SELECT * FROM cadcam_parts WHERE partId = ".$partId;
$process = $db->query($sql);
if($process->num_rows > 0)
{
	$result = $process->fetch_assoc();
}

?>
<style>
div {
	line-height: 30px;
}

label
{
    display: inline-block;
    width: 125px;
}
input[type='text']
{
	background: #FFFF99;
	border-radius: 3px;
}
select
{
	background: #FFFF99;
	width: 57%;
	border-radius: 3px;
}
</style>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
</head>
<body>
	<form method="POST" id="editPartNameForm"></form>
	<input type="hidden" name="partId" id="partId" style="height:30px;" form="editPartNameForm" value="<?php echo $result['partId']?>">
	<input type="hidden" name="oldValue" id="oldValue" style="height:30px;" form="editPartNameForm" value="<?php echo $result['partName']?>">

	<label><?php echo displayText('L30');?></label>
	<table>
		<tr>
			<td>
				<input type="text" name="partName" id="partName" style="height:30px;" form="editPartNameForm" value="<?php echo $result['partName']?>">
			</td>
			<td>
				<input type="submit" name="updatePartName" id="updatePartName"  style="height:30px;" form="editPartNameForm" value="Update">
			</td>
		</tr>
	</table>
