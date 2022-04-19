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
$noteId = $_GET['noteId'];

$sql = "SELECT * FROM cadcam_partprocessnote WHERE noteId = ".$noteId;
$queryNote = $db->query($sql);
if($queryNote AND $queryNote->num_rows > 0)
{
	$resultNote = $queryNote->fetch_assoc();
	$noteNumber = $resultNote['patternId'];
	$noteDetail = $resultNote['remarks'];
	$remarksFlag = $resultNote['remarksFlag'];

	$checked = ($remarksFlag == 1) ? 'checked' : '';
}
echo "<table border=1>";
	echo "<thead>";
		echo "<th colspan='2'><center><span style='font-size:15px;'>Note Update Form</span></center><br></th>";
	echo "</thead>";
	echo "<tbody>";
		echo "<tr>";
			echo "<td style='width:180px;'><b>".displayText('L1465')."</b></td>";
			echo "<td>";
				echo "<input style='width:140px; height:25px;' type='number' name='noteNumber' id='noteNumber' value=".$noteNumber.">";
				echo "<input style='width:140px; height:25px;' type='hidden' name='noteId' id='noteId' value=".$noteId.">";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><b>".displayText('L1467')."</b></td>";
			echo "<td><input style='width:140px; height:25px;' type='text' name='noteDetail' id='noteDetail' value='".$noteDetail."'></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan = 2><input type='checkbox' ".$checked." name='point' id='point' value=''> <label for='point'><b>Point</b></label></td>";
		echo "</tr>";
	echo "</tbody>";
echo "</table>";
echo "<br>";
echo "<center><button class='anthony_submit' id='updateNote'>".displayText('L1052')."</button></center>";
?>