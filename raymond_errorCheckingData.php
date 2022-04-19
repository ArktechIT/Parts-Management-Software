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

$detailNumber = $_POST['detailNumber'];
$specificationId = $_POST['specificationId'];
$partId = $_POST['partId'];
$point = $_POST['point'];
$checkSpecId = "";
$sql = "SELECT specificationId FROM engineering_specifications WHERE specificationId = ".$specificationId;
$querySpecId = $db->query($sql);
if($querySpecId AND $querySpecId->num_rows > 0)
{
	$checkSpecId = "true";
}

$checkDetail = "";
$sql = "SELECT detailId FROM engineering_specificationdetail WHERE detailNumber LIKE '".$detailNumber."' AND specificationId = ".$specificationId;
$queryDetail = $db->query($sql);
if($queryDetail AND $queryDetail->num_rows > 0)
{
	$resultDetail = $queryDetail->fetch_assoc();
	$detailId = $resultDetail['detailId'];
	
	$sql = "SELECT detailId FROM engineering_partstandard WHERE partId = ".$partId." AND detailId = ".$detailId;
	$queryStandard = $db->query($sql);
	if($queryStandard AND $queryStandard->num_rows > 0)
	{
		$checkDetail = "exist";	
	}
	else
	{
		$checkDetail = "true";
	}
}

if($checkDetail == 'true' AND $checkSpecId =='true')
{
	$sql = "INSERT INTO `engineering_partstandard`(`partId`, `detailId`, `detailFlag`) VALUES (".$partId.", ".$detailId.", ".$point.")";
	$queryInsert = $db->query($sql);
	echo "success";
}
else if($checkDetail == 'exist')
{
	echo 'exist';
}
?>