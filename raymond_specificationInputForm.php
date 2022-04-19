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

$customerAlias = $_GET['customerAlias'];

echo "<table border=1>";
	echo "<thead>";
		echo "<th colspan='2'><center><span style='font-size:15px;'>".displayText('L1458')."</span></center><br></th>";//L1458 Specification Input Form
	echo "</thead>";
	echo "<tbody>";
		echo "<tr>";
			echo "<td style='width:180px;'><b>".displayText('L643')."</b></td>";//L643 Specification Number
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
						echo "<option value=".$specificationId.">".$specificationNumber."</option>";
					}
				}
				echo "</select>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><b>".displayText('L1088')."</b></td>";//L1088 Detail Number
			echo "<td><input style='width:140px; height:25px;' type='text' name='detailNumber' id='detailNumber'></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan = 2><input type='checkbox' name='point' id='point' value=''> <label for='point'><b>".displayText('L1462')."</b></label></td>";//L1462 Point
		echo "</tr>";
	echo "</tbody>";
echo "</table>";
echo "<br>";
echo "<center><button class='anthony_submit' id='addSpecifications'>".displayText('L1052')."</button></center>";
?>
