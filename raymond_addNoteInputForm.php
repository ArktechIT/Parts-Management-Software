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

$partId = $_GET['partId'];

echo "<table border=1>";
	echo "<thead>";
		echo "<th colspan='2'><center><span style='font-size:15px;'>".displayText('L1464')."</span></center><br></th>";//L1464 Note Input Form
	echo "</thead>";
	echo "<tbody>";
		echo "<tr>";
			echo "<td style='width:180px;'><b>".displayText('L1465')."</b></td>";//L1465 Note Number
			echo "<td>";
				echo "<input style='width:140px; height:25px;' type='number' name='noteNumber' id='noteNumber'>";
				echo "<input style='width:140px; height:25px;' type='hidden' name='partId' id='partId' value = ".$partId.">";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><b>".displayText('L1467')."</b></td>";//L1467 Note Detail
			echo "<td><input style='width:140px; height:25px;' type='text' name='noteDetail' id='noteDetail'></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan = 2><input type='checkbox' name='point' id='point' value=''> <label for='point'><b>".displayText('L1462')."</b></label></td>";//L1462 Point
		echo "</tr>";
	echo "</tbody>";
echo "</table>";
echo "<br>";
echo "<center><button class='anthony_submit' id='addNote'>".displayText('L1052')."</button></center>";//L1052 Save
?>
