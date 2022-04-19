<?php

include("../Common Data/PHP Modules/mysqliConnection.php");

$inputNoteArray = $commonNote = array();
$inputNoteArray = $_POST['inputNote'];
$commonNote = isset($_POST['commonNote']) ? $_POST['commonNote'] : 0;
$a=0;
foreach($inputNoteArray as $inputNote)
{
	// if($_SESSION['idNumber']=='0521')
	// {
		$displayFlag = $commonNote[$a];
		if($displayFlag=='') $displayFlag = 0;
		$sql = "INSERT INTO cadcam_partprocessnote (partId, processCode, patternId, remarks, displayFlag) VALUES (".$_GET['partId'].", ".$_GET['processCode'].", ".$_GET['patternId'].", '".$inputNote."', ".$displayFlag.")";
		echo $sql."<br>";
		$insertQuery = $db->query($sql);
		$a++;
	// }
	// else
	// {
	// 	$sql = "INSERT INTO cadcam_partprocessnote (partId, processCode, patternId, remarks) VALUES (".$_GET['partId'].", ".$_GET['processCode'].", ".$_GET['patternId'].", '".$inputNote."')";
	// 	//echo $sql."<br>";
	// 	$insertQuery = $db->query($sql);
	// }
}
		// exit(0);
header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
?>
