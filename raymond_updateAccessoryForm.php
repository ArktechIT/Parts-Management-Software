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
$patternId = $_GET['patternId'];
$accessoryIdGet = $_GET['accessoryId'];
$subId = $_GET['subId'];

$sql = "SELECT listId FROM engineering_subpartprocesslink WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." AND childId = ".$accessoryIdGet." AND identifier = 2";
$querySubPartProcessLink = $db->query($sql);
if($querySubPartProcessLink AND $querySubPartProcessLink->num_rows > 0)
{
	echo displayText('L1435');//"displayText('L1435')";
	exit(0);
}

$sql = "SELECT `accessoryNumber`, `accessoryName`, `accessoryDescription`, `revisionId` FROM `cadcam_accessories` WHERE status = 0";
$queryAccessory = $db->query($sql);
$count = $queryAccessory->num_rows;
?>

<style type="text/css">
.tbody 
{
	display:block;
	<?php
	if($count > 10)
	{
		echo "height:200px;";
	}
	?>
	overflow:auto;
}
.thead, .tbody tr 
{
	display:table;
	width:100%;
	table-layout:fixed;
}
.thead {
	<?php
	if($count > 10)
	{
		echo "width: calc( 100% - 1.3em)";
	}
	?>
}

</style>
<?php
echo "<center>";
echo "<form action = 'anthony_updateAccessoryFormSQL.php?partId=".$partId."&subId=".$subId."&patternId=".$patternId."' method = 'POST'>";
echo "<table border=1>";
	echo "<thead>";
		echo "<th colspan=2><center><span style='font-size:15px;'>".displayText('L1436')."</span></center></th>";//L1436 Update Accessory Form
	echo "</thead>";
	echo "<tbody>";
		echo "<tr>";
			echo "<td style='width:120px;'><b>".displayText('L96')."</b></td>";//L96 Accessory Number
			echo "<td colspan=3>";
				echo "<select style='width:330px; height:25px;' type='combobox' name = 'accessory' id = 'accessoryName' required>";
					echo "<option></option>";
					$accessoryIdArray = array("''");
					$sql = "SELECT childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND childId != ".$accessoryIdGet." AND identifier = 2 ";
					$queryChildAccessoryId = $db->query($sql);
					if($queryChildAccessoryId->num_rows > 0)
					{
						while($resultChildAccessoryId = $queryChildAccessoryId->fetch_array())
						{
							$accessoryIdArray[] = $resultChildAccessoryId['childId'];
						}
					}
					
					$accessoryId = $accessoryNumber = $accessoryId = $revisionId = '';
					$sql = "SELECT accessoryId, accessoryNumber, accessoryName, accessoryDescription, revisionId FROM cadcam_accessories WHERE accessoryId NOT IN(".implode(",",$accessoryIdArray).") AND status = 0 ORDER by accessoryNumber";
					$queryAccessories = $db->query($sql);
					if($queryAccessories->num_rows > 0)
					{
						while($resultAccessories = $queryAccessories->fetch_array())
						{
							$accessoryId = $resultAccessories['accessoryId'];
							$accessoryNumber = $resultAccessories['accessoryNumber'];
							$accessoryName = $resultAccessories['accessoryName'];
							$accessoryDescription = $resultAccessories['accessoryDescription'];
							$revisionId = $resultAccessories['revisionId'];
							$selectedAccessory = ($accessoryIdGet == $accessoryId) ? 'selected' : '';

							echo "<option ".$selectedAccessory." value = '".$accessoryId."'>".$accessoryNumber." ( ".$accessoryName." ) { ".$accessoryDescription." } [ ".$revisionId." ]</option>";
						}
					}
				echo "</select>";
			echo "</td>";
		echo "</tr>";
	echo "</tbody>";
echo "</table>";

echo "<p>&nbsp;</p>";
echo "<table border = 1>";
	echo "<thead class='thead'>";
		echo "<th style='text-align:center;'>".displayText('L96')."</th>";//L96 Accessory Number
		echo "<th style='text-align:center;'>".displayText('L97')."</th>";//L97 Accessory Name
		echo "<th style='text-align:center;'>".displayText('L303')."</th>";//L374 Description
		echo "<th style='width: 50px; text-align:center;'>".displayText('L1934')."</th>";//L29 Revision
	echo "</thead>";
	echo "<tbody id='ajaxResult' class='tbody'>";
		$sql = "SELECT `accessoryNumber`, `accessoryName`, `accessoryDescription`, `revisionId` FROM `cadcam_accessories` WHERE status = 0 ORDER BY accessoryNumber";
		$queryAccessory = $db->query($sql);
		if($queryAccessory AND $queryAccessory->num_rows > 0)
		{
			while($resultAccessory = $queryAccessory->fetch_assoc())
			{
				$accessoryNumber = $resultAccessory['accessoryNumber'];
				$accessoryName = $resultAccessory['accessoryName'];
				$accessoryDescription = $resultAccessory['accessoryDescription'];
				$revisionId = $resultAccessory['revisionId'];
				echo "<tr>";
					echo "<td>".$accessoryNumber."</td>";
					echo "<td>".$accessoryName."</td>";
					echo "<td style='text-align:left;'>".$accessoryDescription."</td>";
					echo "<td style='text-align:center; width: 10px'>".$revisionId."</td>";
				echo "</tr>";
			}
		}
	echo "</tbody>";
echo "</table>";
echo "<p>&nbsp;</p>";

$sql = "SELECT childId, quantity, remarks FROM cadcam_subparts WHERE subpartId = ".$subId." AND identifier = 2 ";
$getChildId = $db->query($sql);
if($getChildId AND $getChildId->num_rows > 0)
{
	$getChildIdResult = $getChildId->fetch_assoc();
	$quantity = $getChildIdResult['quantity'];
	$remarks = $getChildIdResult['remarks'];
}

echo "<table border = 1>";
	echo "<tbody>";
		echo "<tr>";
			echo "<td style='width:120px;'><b>".displayText('L31')."</b></td>";//L31 Quantity
			echo "<td><input type = 'number' name = 'quantity' min = '1' style='width:330px; height:25px;' value=".$quantity."></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td style='width:120px;'><b>".displayText('L242')."</b></td>";//L242 Remarks
			echo "<td><input type = 'text' name = 'remarks' min = '1' style='width:330px; height:25px;' value='".$remarks."'></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2><center><input type ='submit' name = 'submit' value = '".displayText('L1437')."' class='anthony_submit'></center></td>";//L1437 Update Accessory
		echo "</tr>";
	echo "</tbody>";
echo "</table>";
echo "</form>";
echo "</center>";
?>
