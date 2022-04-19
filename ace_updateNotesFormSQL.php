<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

// $commonNote = isset($_POST['commonNote']) ? $_POST['commonNote'] : 0;

for($i=1;$i<$_POST['noteCounter'];$i++)
{
	// if($_SESSION['idNumber']=='0521')
	// {
		if($_POST['commonNote'.$i]=='')
		{
			$_POST['commonNote'.$i] = 0;
		}
		$sql = "UPDATE cadcam_partprocessnote SET remarks = '".$_POST['inputNote'.$i]."', displayFlag = ".$_POST['commonNote'.$i]."  WHERE noteId = ".$_POST['noteId'.$i];
		echo $sql."<br>";

		$updateQuery = $db->query($sql);
	// }
	// else
	// {
	// 	$sql = "UPDATE cadcam_partprocessnote SET remarks = '".$_POST['inputNote'.$i]."'  WHERE noteId = ".$_POST['noteId'.$i];
	// 	//echo $sql."<br>";
	// 	$updateQuery = $db->query($sql);
	// }
}
	// exit(0);
header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
?>
