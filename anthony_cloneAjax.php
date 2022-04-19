<?php
include('../Common Data/Templates/mysqliConnection.php');

$sql = "SELECT DISTINCT patternId FROM cadcam_partprocess WHERE partId = ".$_POST['partId']." ORDER BY patternId ASC ";
$getPatternId = $db->query($sql);
while($getPatternIdResult = $getPatternId->fetch_array())
{
	echo "<option value = '".$getPatternIdResult['patternId']."'>".$getPatternIdResult['patternId']."</option>";
}
?>
