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

$sheetNumber = $_POST['sheetNumber'];
$partSheet = $_POST['partSheet'];
$partId = $_POST['partId'];


if(isset($_POST['submit']))
{
 	$sql = "UPDATE `cadcam_parts` SET `sheetNumber` = '".$sheetNumber."', `partSheet`= '".$partSheet."' WHERE partId = ".$partId." LIMIT 1";
	$queryUpdate = $db->query($sql);
	header('location:gerald_product.php');
}

?>
