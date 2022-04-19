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

$partId = isset($_POST['partId']) ? $_POST['partId'] : "";
$partStatus = isset($_POST['partStatus']) ? $_POST['partStatus'] : "";

echo $sql = "UPDATE cadcam_parts SET status = ".$partStatus." WHERE partId = ".$partId." LIMIT 1";
$queryUpdate = $db->query($sql);
?>