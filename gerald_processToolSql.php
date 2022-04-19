<?php
	include('../Common Data/PHP Modules/mysqliConnection.php');
	ini_set("display_errors", "on");
	
	if($_GET['type']=='addTool')
	{
		$toolId = $_POST['toolId'];
		$partId = $_POST['partId'];
		$processCode = $_POST['processCode'];
		
		$sql = "INSERT INTO	`cadcam_processtoolsdetails`
						(	`toolId`,	`partId`,	`processCode`)
				VALUES	(	".$toolId.",".$partId.",".$processCode.")";
		$queryInsert = $db->query($sql);
		
		header('location:anthony_editProduct.php?partId='.$partId.'&src=process');
		exit(0);
	}
	/*
	else if($_GET['type']=='editTool')
	{
		$listId = $_POST['listId'];
		$partId = $_POST['partId'];
		$toolId = $_POST['toolId'];
		
		$sql = "UPDATE	`cadcam_processtoolsdetails`
				SET		`toolId` = ".$toolId."
				WHERE 	`listId` = ".$listId." LIMIT 1";
		$queryUpdate = $db->query($sql);
		
		header('location:anthony_editProduct.php?partId='.$partId.'&src=process');
		exit(0);
	}
	*/
	else if($_GET['type']=='deleteTool')
	{
		$listId = $_GET['listId'];
		$partId = $_GET['partId'];
		
		$sql = "DELETE FROM `cadcam_processtoolsdetails` WHERE `listId` = ".$listId." LIMIT 1";
		$queryDelete = $db->query($sql);
		
		header('location:anthony_editProduct.php?partId='.$partId.'&src=process');
		exit(0);
	}
?>
