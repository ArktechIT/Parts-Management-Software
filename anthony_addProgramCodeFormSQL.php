<?php
//~ SESSION_START();
include('../Common Data/PHP Modules/mysqliConnection.php');


// ---- Code Modified by Gerald 2015-06-05 ---- //

// ---------- Gerald Code ---------- //
if($_POST['type']=='checkAjax')
{
	$programCode = $_POST['programCode'];
	$count = $error = 0;
	$programCodeArray = explode("\n",$programCode);
	foreach($programCodeArray as $val)
	{
		$count++;
		if(strstr($val,'(') != FALSE)	continue;
		$string = count_chars($val,3);
		$stringCount = strlen(strtoupper($string));
		for ($i = 0; $i < $stringCount; $i++)
		{
			if(ctype_alpha($string[$i]))
			{
				$alphaCount = substr_count(strtoupper($val),$string[$i]);
				if($alphaCount > 1)
				{
					//~ echo " ".$string[$i]." = ".substr_count($val,$string[$i]);
					echo "Error!, There was ".$alphaCount." ".$string[$i]." in line number ".$count."";
					break;
				}
			}
		}
	}
	exit(0);
}
// -------- End Gerald Code -------- //

$programCode = $_POST['programCode'];

$sql = "SELECT partNumber FROM cadcam_parts WHERE partId = ".$_GET['partId']." ";
$getPartNumber = $db->query($sql);
$getPartNumberResult = $getPartNumber->fetch_array();

if($_POST['gZero'] == 'on')
{
	$gZero = 1;
}
else
{
	$gZero = 0;
}

$sql = "INSERT INTO engineering_programs (programName, programCode, partId, programX, programY, programI, programJ, programGZero, programStatus, programDate, programmer, quantity) VALUES ('".$getPartNumberResult['partNumber']."', '".$programCode."', ".$_GET['partId'].", '".$_POST['x']."', '".$_POST['y']."', '".$_POST['i']."', '".$_POST['j']."', ".$gZero.", 0, now(), '".$_SESSION['userID']."', '".$_POST['produceQuantity']."') ";
$insertPrograms = $db->query($sql);
header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=programs");
?>
