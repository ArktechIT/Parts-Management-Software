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

$sheetNumber = $_GET['sheetNumber'];
$partSheet = $_GET['partSheet'];
$partId = $_GET['partId'];

?>
<html>
	<head>
	</head>
	<body>
		<center>
			<form method='POST' action='raymond_editSheetNumber&partSheetSQL.php'>
				<input type='hidden' name='partId' value='<?php echo $partId; ?>'>
				<br>
				<table border =1px>
					<tr>
						<td><strong>KCO</strong></td>
						<td><input  style='background-color:yellow; height:25px;'  type='text' id='sheetNumber' name='sheetNumber' style='height:30px;' min='0' value='<?php echo $sheetNumber; ?>' placeholder= 'displayText('L396')'></td>
					</tr>
					<tr>
						<td><strong>Sheet</strong></td>
						<td><input style='background-color:yellow; height:25px;' type='text' id='partSheet' name='partSheet' style='height:30px;' value='<?php echo $partSheet; ?>' placeholder= 'Sheet'></td>
					</tr>
					<tr>
						<td align='center' colspan=2>
							<input style='height:35px; width:80px;' type='submit' name='submit' value='Update'>
						</td>
					</tr>
				</table>
			</form>
		</center>
	</body>
</html>
