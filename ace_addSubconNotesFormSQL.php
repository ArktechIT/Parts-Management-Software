<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$sql = "INSERT INTO engineering_subconremarks (subconListId, dataOne, dataTwo, dataThree) VALUES (".$_POST['subconListId'].", '".$_POST['inputColor']."', '".$_POST['inputFace']."', '".$_POST['inputQuality']."')";
//echo $sql."<br>";
$insertQuery = $db->query($sql);

header("Location:anthony_editProduct.php?partId=".$_POST['partId']."&src=subcon&patternId=".$_POST['patternId']."");
?>
