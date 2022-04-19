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

$type = isset($_GET['type']) ? $_GET['type'] : '';

if($type == 'tap')
{
	$noteId = $_POST['noteId'];
	$sql = "DELETE FROM cadcam_partprocessnote WHERE noteId = ".$noteId." LIMIT 1";
	$queryDelete = $db->query($sql);
}
if($type == 'bend')
{
	$noteId = $_POST['noteId'];
	$sql = "DELETE FROM cadcam_partprocessnote WHERE noteId = ".$noteId." LIMIT 1";
	$queryDelete = $db->query($sql);
}
?>