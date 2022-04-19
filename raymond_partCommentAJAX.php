<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
// $path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
set_include_path($path);
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/gerald_functions.php');
ini_set("display_errors", "on");

$ctrl = new PMSDatabase;

$partId = isset($_POST['partId']) ? $_POST['partId'] : "";
$comments = isset($_POST['comments']) ? $_POST['comments'] : "";

$where = "partId = ".$partId;
$ctrl->setTableName('cadcam_parts')
        ->setFieldsValues([
            "partsComment" => $comments
        ])
        ->update($where);
exit();
?>