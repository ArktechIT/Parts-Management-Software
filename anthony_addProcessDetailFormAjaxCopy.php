<?php
include('../Common Data/Templates/mysqliConnection.php');



$sql = "SELECT processSection FROM cadcam_process WHERE processCode = ".$_POST['processCode']." ";
$getProcessSection = $db->query($sql);
$getProcessSectionResult = $getProcessSection->fetch_array();

$sql = "SELECT sectionId, sectionName FROM ppic_section WHERE sectionId = ".$getProcessSectionResult['processSection']." ";
$getSection = $db->query($sql);
$getSectionResult = $getSection->fetch_array();
$section = $getSectionResult['sectionId'];
//----------------------------------------------------JAmes Start -----------------------------------------------------

$sqlJAmes = "SELECT customerId FROM cadcam_parts WHERE partId = '".$_POST['partId']."' LIMIT 1";
$queryJAmes = $db->query($sqlJAmes);
$resultJAmes= $queryJAmes->fetch_assoc();
$customerId = $resultJAmes['customerId'];

//~ if($getProcessSectionResult['processSection'] == 36)
//~ {
	//~ if($customerId == 28 OR $customerId == 37 OR $customerId == 15)
	//~ {
		//~ $section = 4;
	//~ }
	//~ else
	//~ {
		//~ $section = 22;
	//~ }
//~ }

if($_POST['processCode'] == 181)
{
	if($customerId == 28 OR $customerId == 37 OR $customerId == 15)
	{
		$section = 4;
	}
}

if($getProcessSectionResult['processSection'] == 12)
{
	if($customerId == 45)
	{
		$section = 48;
	}
}
//-----------------------------------------------------JAmes End ------------------------------------------------------

$sql = "SELECT sectionId, sectionName FROM ppic_section WHERE sectionStatus = 0 ORDER BY sectionName ASC ";
$getSectionName = $db->query($sql);
while($getSectionNameResult = $getSectionName->fetch_array())
{
	echo "<option value = '".$getSectionNameResult['sectionId']."'"; if($getSectionNameResult['sectionId'] == $section){ echo "selected"; } echo ">".$getSectionNameResult['sectionName']."</option>";
}
?>
