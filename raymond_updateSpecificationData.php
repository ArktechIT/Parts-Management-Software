<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
// $path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
$javascriptLib = "/".v."/Common Data/Libraries/Javascript/";
$templates = "/".v."/Common Data/Libraries/Javascript/";
set_include_path($path);    
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_wholeNumber.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
ini_set("display_errors", "on");

if($_GET['type'] == 'update')
{
	$detailNumber = $_POST['detailNumber'];
	$specificationId = $_POST['specificationId'];
	$detailId = $_POST['detailId'];
	$point = $_POST['point'];
	$partId = $_POST['partId'];

	// $sql = "SELECT detailId FROM engineering_specificationdetail WHERE detailNumber LIKE '".$detailNumber."'";
	// $queryDetail = $db->query($sql);
	// if($queryDetail AND $queryDetail->num_rows > 0)
	// {
	// 	echo "exist";
	// }
	// else
	// {
		$sql = "UPDATE `engineering_specificationdetail` SET `specificationId`= ".$specificationId.",`detailNumber`='".$detailNumber."' WHERE detailId=".$detailId." LIMIT 1";
		$queryUpdate = $db->query($sql);

		$sql = "UPDATE `engineering_partstandard` SET `detailFlag` = ".$point." WHERE partId = ".$partId." AND detailId = ".$detailId;
		$queryUpdateDetail = $db->query($sql);
		echo "success";
	// }
}
else if($_GET['type'] == 'delete')
{
	$listId = $_GET['listId'];
	$patternId = $_GET['patternId'];
	$partId = $_GET['partId'];

	$sql = "DELETE FROM engineering_partstandard WHERE listId = ".$listId." LIMIT 1";
	$queryDelete = $db->query($sql);
	
	header("location: anthony_editProduct.php?partId=".$partId."&src=specifications&patternId=".$patternId);
}
else if($_GET['type'] == 'addNote')
{
	$partId = $_POST['partId'];
	$noteDetail = $_POST['noteDetail'];
	$noteNumber = $_POST['noteNumber'];
	$point = $_POST['point'];

	$sql = "INSERT INTO `cadcam_partprocessnote`(	`partId`, 	`processCode`, 	`patternId`, 		`remarks`, 			`remarksFlag`) 
											VALUES (".$partId.", 	0,			".$noteNumber.", '".$noteDetail."', 	".$point.")";
	$queryInsertNote = $db->query($sql);
}
else if($_GET['type'] == 'updateNote')
{
	$partId = $_POST['partId'];
	$noteDetail = $_POST['noteDetail'];
	$noteNumber = $_POST['noteNumber'];
	$noteId = $_POST['noteId'];
	$point = $_POST['point'];

	$sql = "UPDATE `cadcam_partprocessnote` SET `patternId`		= ".$noteNumber.",
												`remarks`		= '".$noteDetail."',
												`remarksFlag` 	= ".$point." 
												 WHERE noteId = ".$noteId;
	$queryUpdateNote = $db->query($sql);
}
else if($_GET['type'] == 'deleteNote')
{
	$noteId = $_GET['noteId'];
	$partId = $_GET['partId'];
	$patternId = $_GET['patternId'];

	$sql = "DELETE FROM `cadcam_partprocessnote` WHERE noteId = ".$noteId." LIMIT 1";
	$queryDeleteNote = $db->query($sql);
	
	header("location: anthony_editProduct.php?partId=".$partId."&src=specifications&patternId=".$patternId);
}
?>