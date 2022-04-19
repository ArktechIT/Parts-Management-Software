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

$detailId = $_GET['detailId'];
$partId = $_GET['partId'];
$specificationIdGet = $_GET['specificationId'];
$customerAlias = $_GET['customerAlias'];

$sql = "SELECT * FROM engineering_specificationdetail WHERE detailId = ".$detailId;
$querySpecDetails = $db->query($sql);
if($querySpecDetails AND $querySpecDetails->num_rows > 0)
{
	$resultSPecDetails = $querySpecDetails->fetch_assoc();
	$detailNumber = $resultSPecDetails['detailNumber'];
}
echo "<input style='width:140px; height:25px;' type='hidden' name='detailId' id='detailId' value=".$detailId.">";
echo "<table border=!>";
	echo "<thead>";
		echo "<th colspan='2'><center><span style='font-size:15px;'>".displayText('L1122')."</span></center><br></th>";
	echo "</thead>";
	echo "<tbody>";
		echo "<tr>";
			echo "<td style='width:180px;'><b>".displayText('L643')."</b></td>";
			echo "<td>";
				echo "<select style='width:140px; height:25px;' type='combobox' name='specificationId' id='specificationId'>";
					echo "<option></option>";
				$sql = "SELECT specificationNumber, specificationId FROM engineering_specifications WHERE status = 0 AND customerAlias LIKE '".$customerAlias."' ORDER BY specificationNumber ASC";
				$querySpecNumber = $db->query($sql);
				if($querySpecNumber AND $querySpecNumber->num_rows > 0)
				{
					while($resultSpecNumber = $querySpecNumber->fetch_assoc())
					{
						$specificationNumber = $resultSpecNumber['specificationNumber'];
						$specificationId = $resultSpecNumber['specificationId'];
						$selected = ($specificationIdGet == $specificationId) ? 'selected' : '';
						echo "<option ".$selected." value=".$specificationId.">".$specificationNumber."</option>";
					}
				}
				echo "</select>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><b>".displayText('L1088')."</b></td>";
			echo "<td><input style='width:140px; height:25px;' type='text' name='detailNumber' id='detailNumber' value=".$detailNumber."></td>";
		echo "</tr>";
		$sql = "SELECT detailFlag FROM engineering_partstandard WHERE partId = ".$partId." AND detailId = ".$detailId;
		$queryDetailFlag = $db->query($sql);
		if($queryDetailFlag AND $queryDetailFlag->num_rows > 0)
		{
			$resultDetailFlag = $queryDetailFlag->fetch_assoc();
			$detailFlag = $resultDetailFlag['detailFlag'];

			$checked = ($detailFlag == 1) ? 'checked' : '';
		}
		echo "<tr>";
			echo "<td colspan = 2><input ".$checked." type='checkbox' name='point' id='point' value=''> <label for='point'><b>Point</b></label></td>";
		echo "</tr>";
	echo "</tbody>";
echo "</table>";
echo "<br>";
echo "<center><button type='button' class='anthony_submit' id='updateSpecifications'>".displayText('L1052')."</button></center>";
?>
