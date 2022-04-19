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
$processCode = $_GET['processCode'];
$count = $_GET['count'];

$sql = "SELECT processCode, processName FROM cadcam_process WHERE processCode NOT IN (SELECT processCode FROM cadcam_partprocess WHERE partId = ".$partId." AND patternId = ".$patternId.") AND status = 0 ORDER BY processName ASC ";
$getProcessName = $db->query($sql);

$sql = "SELECT processName FROM cadcam_process WHERE processCode = ".$processCode." ";
$getProcessName2 = $db->query($sql);
$getProcessName2Result = $getProcessName2->fetch_array();

$sql = "SELECT processSection, toolId, dataOne, dataTwo, dataThree, dataFour, dataFive FROM cadcam_partprocess WHERE count = ".$count." ";
$getDetail = $db->query($sql);
$getDetailResult = $getDetail->fetch_array();

$sql = "SELECT sectionId, sectionName FROM ppic_section WHERE sectionId = ".$getDetailResult['processSection']." ";
$getSectionName = $db->query($sql);
$getSectionNameResult = $getSectionName->fetch_array();

$sql = "SELECT sectionId, sectionName FROM ppic_section WHERE sectionStatus = 0 AND sectionId NOT IN (SELECT processSection FROM cadcam_partprocess WHERE count = ".$count.") ORDER BY sectionName ASC ";
$getSection = $db->query($sql);

$sql = "SELECT * FROM cadcam_processtools ORDER BY toolName ASC ";
$getToolName = $db->query($sql);
echo "<center>";
echo "<form id='formUpdate' action = 'anthony_updateProcessFormSQLv2.php?partId=".$partId."&count=".$count."&patternId=".$patternId."&processCode=".$processCode."' method = 'POST'></form>";
echo "<table border=1>";
	echo "<thead>";
		echo "<th colspan='2'><center><span style='font-size:15px;'>Update Process Detail</span></center><br></th>";
	echo "</thead>";
	echo "<tbody>";
		echo "<tr>";
			echo "<td style='width:180px;'><b>".displayText('L59')."</b></td>";
			echo "<td style='width:180px;'>";
				echo "<select name = 'processName' id = 'processName' form='formUpdate'>";
					echo "<option value = '".$_GET['processCode']."'>".$getProcessName2Result['processName']."</option>";
						while($getProcessNameResult = $getProcessName->fetch_array())
						{
							echo "<option value = '".$getProcessNameResult['processCode']."'>".$getProcessNameResult['processName']."</option>";
						}
				echo "</select>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td style='width:180px;'><b>".displayText('L1407')."</b></td>";
			echo "<td style='width:180px;'>";
				echo "<select name = 'toolName' id = 'toolName' form='formUpdate'>";
					echo "<option value = '0'></option>";
						while($getToolNameResult = $getToolName->fetch_array())
						{
							echo "<option value = '".$getToolNameResult['toolId']."' "; if($getToolNameResult['toolId'] == $getDetailResult['toolId']){ echo 'selected'; } echo ">".$getToolNameResult['toolName']."</option>";
						}
				echo "</select>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td style='width:180px;'><b>".displayText('L61')."</b></td>";
			echo "<td style='width:180px;'>";
				echo "<select name = 'sectionName' id = 'sectionName' form='formUpdate'>";
					echo "<option value = '".$getSectionNameResult['sectionId']."'>".$getSectionNameResult['sectionName']."</option>";
						while($getSectionResult = $getSection->fetch_array())
						{
							echo "<option value = '".$getSectionResult['sectionId']."'>".$getSectionResult['sectionName']."</option>";
						}
				echo "</select>";
			echo "</td>";
		echo "</tr>";
	echo "</tbody>";
echo "</table>";
echo "<table border=1 id='showTapp' style='display:none;'>";
	echo "<thead>";
		echo "<td style='width:182px;' align='center'><b>".displayText('L1420')."</b></td>";
		echo "<td style='width:182px;' align='center'><b>".displayText('L1421')."</b></td>";
		echo "<th align='center' style='width:10px;'><span id='addRow'><img align='center' src='../Common Data/Templates/buttons/addIcon.png' width=20 height=20></span></th>";
	echo "</thead>";
	echo "<tbody class='tbody'>";
		$i=0;
		$sql = "SELECT noteId, noteDetails, noteIdentifier,noteMultiplier FROM cadcam_partprocessnote WHERE partId = ".$partId." AND noteIdentifier = 10 AND processCode = ".$processCode;
		$queryNotes = $db->query($sql);
		if($queryNotes AND $queryNotes->num_rows > 0)
		{
			while ($resultNotes = $queryNotes->fetch_assoc()) 
			{
				$noteIdTap = $resultNotes['noteId'];
				$noteDetails = $resultNotes['noteDetails'];
				$noteIdentifier = $resultNotes['noteIdentifier'];
				$noteMultiplier = $resultNotes['noteMultiplier'];
				echo "<tr class='removeRow' id=".$noteIdTap.">";
					echo "<td align='center'>
							<input type='hidden' name='noteId[]' value=".$noteIdTap." form='formUpdate'>
							<input type = 'text' name = 'tapSize[]' value = ".$noteDetails." form='formUpdate'></td>";
					echo "<td align='center'><input type = 'number' step = 'any' name = 'tapCount[]' min = '0.1' value = ".$noteMultiplier." form='formUpdate'></td>";
					echo "<td align='center'><img title='displayText('L678')' class='deleteRow".$i."' src='../Common Data/Templates/buttons/deleteIcon.png' width='20' height='20'></td>";
				echo "</tr>";
				$i++;
			}
		}
		else
		{
			echo "<tr>";
				echo "<td align='center'><input type = 'text' name = 'tapSize[]' value = '' form='formUpdate'></td>";
				echo "<td align='center'><input type = 'number' step = 'any' name = 'tapCount[]' min = '0.1' value = '' form='formUpdate'></td>";
				echo "<td align='center'></td>";
			echo "</tr>";
		}
	echo "</tbody>";
echo "</table>";
echo "<table border=1 id='showBend' style='display:none;'>";
	echo "<thead>";
		echo "<td style='width:182px;' align='center'><b>V-Size</b></td>";
		echo "<td style='width:182px;' align='center'><b>".displayText('L1414')."</b></td>";
		echo "<td style='width:182px;' align='center'><b>".displayText('L1415')."</b></td>";
		echo "<td style='width:182px;' align='center'><b>".displayText('L1416')."</b></td>";
		echo "<td style='width:182px;' align='center'><b>".displayText('L1417')."</b></td>";
		echo "<th colspan='' style='width:10px;'><center><span id='addRowBending'><img align='center' src='../Common Data/Templates/buttons/addIcon.png' width=20 height=20></span></center></th>";
	echo "</thead>";
	echo "<tbody class='tbodyBending'>";
		$x = 0;
		echo "<input type='hidden' name='partId' value='".$partId."' form='formUpdate'>";
		$sql = "SELECT noteId, noteDetails FROM cadcam_partprocessnote_1 WHERE patternId = ".$patternId." AND noteIdentifier = 0 AND processCode = ".$processCode." AND partId = ".$partId;
		$queryNoteId = $db->query($sql);
		if($queryNoteId AND $queryNoteId->num_rows > 0)
		{
			while($resultNoteId = $queryNoteId->fetch_assoc())
			{
				$parentNoteId = $resultNoteId['noteId'];
				$vSize = $resultNoteId['noteDetails'];

				echo "<tr class='removeRowBending' id=".$parentNoteId.">";
					echo "<td style='width:182px;'>
							<input type='hidden' name='noteIdBending[]' value='".$parentNoteId."' form='formUpdate'>
							<input type = 'number' step = 'any' name = 'vSize[]' min = '0.1' value = '".$vSize."' form='formUpdate'></td>";

					$sql = "SELECT noteId, noteDetails, noteIdentifier, childNoteId FROM cadcam_partprocessnote_1 WHERE childNoteId = ".$parentNoteId." AND noteIdentifier = 1 AND patternId = ".$patternId." AND processCode = ".$processCode." AND partId = ".$partId;
					$queryPunchR = $db->query($sql);
					if($queryPunchR AND $queryPunchR->num_rows > 0)
					{
						$resultPunchR = $queryPunchR->fetch_assoc(); 
						$punchRNoteId = $resultPunchR['noteId'];
						$punchR = $resultPunchR['noteDetails'];

						echo "<input type='hidden' name='punchRNoteId[]' value='".$punchRNoteId."' form='formUpdate'>";
						echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'punchR[]' min = '0.1' value = '".$punchR."' form='formUpdate'></td>";
					}
					else
					{
						echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'punchR[]' min = '0.1' value = '' form='formUpdate'></td>";
					}

					$sql = "SELECT noteId, noteDetails, noteIdentifier, childNoteId FROM cadcam_partprocessnote_1 WHERE childNoteId = ".$parentNoteId." AND noteIdentifier = 2 AND patternId = ".$patternId." AND processCode = ".$processCode." AND partId = ".$partId;
					$queryBendDeduct = $db->query($sql);
					if($queryBendDeduct AND $queryBendDeduct->num_rows > 0)
					{
						$resultBendDeduct = $queryBendDeduct->fetch_assoc(); 
						$bendDeduct = $resultBendDeduct['noteDetails'];
						echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'bendDeduct[]' min = '0.1' value = '".$bendDeduct."' form='formUpdate'></td>";
					}
					else
					{
						echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'bendDeduct[]' min = '0.1' value = '' form='formUpdate'></td>";
					}

					$sql = "SELECT noteId, noteDetails, noteIdentifier, childNoteId FROM cadcam_partprocessnote_1 WHERE childNoteId = ".$parentNoteId." AND noteIdentifier = 3 AND patternId = ".$patternId." AND processCode = ".$processCode." AND partId = ".$partId;
					$querySyagenHeight = $db->query($sql);
					if($querySyagenHeight AND $querySyagenHeight->num_rows > 0)
					{
						$resultSyagenHeight = $querySyagenHeight->fetch_assoc(); 
						$superyagenHeight = $resultSyagenHeight['noteDetails'];
						echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'superyagenHeight[]' min = '0.1' value = '".$superyagenHeight."' form='formUpdate'></td>";
					}
					else
					{
						echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'superyagenHeight[]' min = '0.1' value = '' form='formUpdate'></td>";
					}

					$sql = "SELECT noteId, noteDetails, noteIdentifier, childNoteId FROM cadcam_partprocessnote_1 WHERE childNoteId = ".$parentNoteId." AND noteIdentifier = 3 AND patternId = ".$patternId." AND processCode = ".$processCode." AND partId = ".$partId;
					$querySyagenDistance = $db->query($sql);
					if($querySyagenDistance AND $querySyagenDistance->num_rows > 0)
					{
						$resultSyagenDistance = $querySyagenDistance->fetch_assoc(); 
						$superyagenDistance = $resultSyagenDistance['noteDetails'];
						echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'superyagenDistance[]' min = '0.1' value = '".$superyagenDistance."' form='formUpdate'></td>";
					}
					else
					{
						echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'superyagenDistance[]' min = '0.1' value = '' form='formUpdate'></td>";
					}
					echo "<td align='center'><img title='displayText('L678')' class='deleteRowBending".$x."' src='../Common Data/Templates/buttons/deleteIcon.png' width='20' height='20'></td>";
				echo "</tr>";
				$x++;
			}
		}
		/*
		$sql = "SELECT noteId, noteDetails, noteIdentifier, childNoteId FROM cadcam_partprocessnote_1 WHERE noteIdentifier IN (0,1,2,3,4) AND processCode = ".$processCode." AND partId = ".$partId;
		$queryBending = $db->query($sql);
		if($queryBending AND $queryBending->num_rows > 0)
		{
			while ($resultBending = $queryBending->fetch_assoc()) 
			{
				$noteIdBending = $resultBending['noteId'];
				$noteDetails = $resultBending['noteDetails'];
				$noteIdentifier = $resultBending['noteIdentifier'];
				$childNoteId = $resultBending['childNoteId'];

				if($noteIdentifier == 0) $vSize = 3;
				if($noteIdentifier == 1) $punchR = $noteDetails;
				if($noteIdentifier == 2) $bendDeduct = $noteDetails;
				if($noteIdentifier == 3) $superyagenHeight = $noteDetails;
				if($noteIdentifier == 4) $superyagenDistance = $noteDetails;

				echo "<tr class='removeRowBending' id=".$noteIdBending.">";
					echo "<td style='width:182px;'>
							<input type='hidden' name='noteIdBending[]' value='".$noteIdBending."' form='formUpdate'>
							<input type = 'number' step = 'any' name = 'vSize[]' min = '0.1' value = '".$vSize."' form='formUpdate'></td>";
					echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'punchR[]' min = '0.1' value = '".$punchR."' form='formUpdate'></td>";
					echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'bendDeduct[]' min = '0.01' value = '".$bendDeduct."' form='formUpdate'></td>";
					echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'superyagenHeight[]' min = '0.1' value = '".$superyagenHeight."' form='formUpdate'></td>";
					echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'superyagenDistance[]' min = '0.1' value = '".$superyagenDistance."' form='formUpdate'></td>";
					echo "<td align='center'><img title='displayText('L678')' class='deleteRowBending".$x."' src='/Common Data/Templates/buttons/deleteIcon.png' width='20' height='20'></td>";
				echo "</tr>";
				$x++;
			}
		}
		else
		{
			echo "<tr>";
				echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'vSize[]' min = '0.1' value = '' form='formUpdate'></td>";
				echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'punchR[]' min = '0.1' value = '' form='formUpdate'></td>";
				echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'bendDeduct[]' min = '0.01' value = '' form='formUpdate'></td>";
				echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'superyagenHeight[]' min = '0.1' value = '' form='formUpdate'></td>";
				echo "<td style='width:182px;'><input type = 'number' step = 'any' name = 'superyagenDistance[]' min = '0.1' value = '' form='formUpdate'></td>";
				echo "<td align='center'></td>";
			echo "</tr>";
		}*/
	echo "</tbody>";
echo "</table>";
echo "<table border=1>";
	echo "<tr>";
		echo "<td style='width:197px;'><b>User Remarks</b></td>";
		echo "<td style='width:197px;'><textarea rows = '5' cols = '25' name = 'userRemarks' placeholder = 'Enter User Remarks' required form='formUpdate'></textarea></td>";
	echo "</tr>";
echo "</table>";
echo "<br>";
echo "<center><button style='width:120;' type='submit' class='anthony_submit' id='addSpecifications' form='formUpdate'>UPDATE</button></center>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "</center>";
?>