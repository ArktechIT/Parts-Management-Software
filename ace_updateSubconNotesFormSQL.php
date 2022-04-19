<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

for($i=1;$i<$_POST['noteCounter'];$i++)
{
	$sql = "UPDATE engineering_subconremarks SET dataOne = '".$_POST['inputColor'.$i]."', dataTwo = '".$_POST['inputFace'.$i]."', dataThree = '".$_POST['inputQuality'.$i]."' WHERE remarksId = ".$_POST['remarksId'.$i];
	//echo $sql."<br>";
	$updateQuery = $db->query($sql);
}
header("Location:anthony_editProduct.php?partId=".$_POST['partId']."&src=subcon&patternId=".$_POST['patternId']."");
?>
