<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
$javascriptLib = "/".v."/Common Data/Libraries/Javascript/";
$templates = "/".v."/Common Data/Libraries/Javascript/";
set_include_path($path);    
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_wholeNumber.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
include('PHP Modules/rose_prodfunctions.php');
ini_set("display_errors", "on");

//---------------------------------------TAMANG 03-14-2022 WALANG TITIBAG----------------------------------------
function getBucket($lotNumber,$type=0)
{
    //include('V4/Common Data/PHP Modules/mysqliConnection.php');
	include('PHP Modules/mysqliConnection.php');
	$partIdArray = Array();
	$sql = "SELECT partId,workingQuantity from ppic_lotlist WHERE lotNumber ='$lotNumber'";
	$queryLotList = $db->query($sql);
    $resultLotList = $queryLotList->fetch_assoc();

    $partId = $resultLotList['partId'];
    $workingQuantity = $resultLotList['workingQuantity'];

    $sql="SELECT materialSpecId,x,y,itemWeight,itemLength,itemWidth,itemHeight FROM cadcam_parts WHERE partId = '$partId'";
    $queryParts = $db->query($sql);
    $resultParts = $queryParts->fetch_assoc();
 
    $materialSpecId = $resultParts['materialSpecId'];
    $x              = $resultParts['x'];
    $y              = $resultParts['y'];
    $weight         = $resultParts['itemWeight'];
    $itemLength     = $resultParts['itemLength'];
    $itemWidth      = $resultParts['itemWidth'];
    $itemHeight     = $resultParts['itemHeight'];

    $sql = "SELECT metalThickness FROM cadcam_materialspecs WHERE materialSpecId = '$materialSpecId'";
    $queryMaterialSpecs = $db->query($sql);
    $resultMaterial = $queryMaterialSpecs->fetch_assoc();
    
    $thickness      = $resultMaterial['metalThickness'];

    
    
    $smallBucketVolume_flat =   1280300;
    $mediumBucketVolume_flat=   1618200;
    $largeBucketVolume_flat =   3591760;

    //SMALL BUCKET START HERE------------------------------------------------------------------------------
    $smallBucketLength     =   155;  
    $smallBucketWidth      =   118;  
    $smallBucketHeight     =   70;   
    $smallBucketWeight     =   8.12;
    $smallBucketVolume     =   $smallBucketLength*$smallBucketWidth*$smallBucketHeight;
    //SMALL BUCKET END HERE------------------------------------------------------------------------------

    //MEDIUM BUCKET START HERE------------------------------------------------------------------------------
    $mediumBucketLength     =   186; 
    $mediumBucketWidth      =   116;  
    $mediumBucketHeight     =   75;
    $mediumBucketWeight     =   8.56;
    $mediumBucketVolume     =   $mediumBucketLength*$mediumBucketWidth*$mediumBucketHeight;
    //MEDIUM BUCKET END HERE------------------------------------------------------------------------------

    //LARGE BUCKET START HERE------------------------------------------------------------------------------
    $largeBucketLength     =   278; 
    $largeBucketWidth      =   152;  
    $largeBucketHeight     =   85;
    $largeBucketVolume     =   $largeBucketLength*$largeBucketWidth*$largeBucketHeight;
    //LARGE BUCKET END HERE------------------------------------------------------------------------------

    $itemVolume             =   $thickness * $x * $y;
    $totalItemVolume        =   $itemVolume * $workingQuantity;

    $itemVolume_3D          =   $itemLength * $itemWidth * $itemHeight;
    $totalItemVolume_3D     =   $itemVolume_3D * $workingQuantity;

    // $lwhBucketVolume_flat    =   ($thickness * $x * $y);
    $convertedWeight  =   ($weight/1000);

    //FLAT BUCKET
    if($type==0)
    {
    //SMALL BUCKET FLAT------------------------------------------------------------------------------------
        if($convertedWeight <= $smallBucketWeight AND $totalItemVolume <= $smallBucketVolume_flat AND (($thickness <= $smallBucketHeight AND $x <= $smallBucketLength AND $y <= $smallBucketWidth) OR ($thickness <= $smallBucketHeight AND $y <= $smallBucketLength AND $x <= $smallBucketWidth) OR ($x <= $smallBucketHeight AND $thickness <= $smallBucketLength AND $y <=  $smallBucketWidth) OR ($x <= $smallBucketHeight AND $y <=  $smallBucketLength AND $thickness <= $smallBucketWidth) OR ($y <=  $smallBucketHeight AND $thickness <= $smallBucketLength AND $x <= $smallBucketWidth) OR ($y <=  $smallBucketHeight AND $x <= $smallBucketLength AND $thickness <= $smallBucketWidth)))
        { 
            
            return "SMALL BUCKET";             
                    
        }

        //MEDIUM BUCKET FLAT-----------------------------------------------------------------------
        elseif($convertedWeight <= $mediumBucketWeight AND $totalItemVolume <= $mediumBucketVolume_flat AND (($thickness <= $mediumBucketHeight AND $x <= $mediumBucketLength AND $y <= $mediumBucketWidth) OR ($thickness <= $mediumBucketHeight AND $y <= $mediumBucketLength AND $x <= $mediumBucketWidth) OR ($x <= $mediumBucketHeight AND $thickness <= $mediumBucketLength AND $y <=  $mediumBucketWidth) OR ($x <= $mediumBucketHeight AND $y <=  $mediumBucketLength AND $thickness <= $mediumBucketWidth) OR ($y <=  $mediumBucketHeight AND $thickness <= $mediumBucketLength AND $x <= $mediumBucketWidth) OR ($y <=  $mediumBucketHeight AND $x <= $mediumBucketLength AND $thickness <= $mediumBucketWidth)))
        { 
            return "MEDIUM BUCKET"; 
                        
                    
        }
        
        //LARGE BUCKET FLAT-----------------------------------------------------------------------
        elseif($convertedWeight <= $mediumBucketWeight AND $totalItemVolume <= $largeBucketVolume_flat AND (($thickness <= $largeBucketHeight AND $x <= $largeBucketLength AND $y <= $largeBucketWidth) OR ($thickness <= $largeBucketHeight AND $y <= $largeBucketLength AND $x <= $largeBucketWidth) OR ($x <= $largeBucketHeight AND $thickness <= $largeBucketLength AND $y <=  $largeBucketWidth) OR ($x <= $largeBucketHeight AND $y <=  $largeBucketLength AND $thickness <= $largeBucketWidth) OR ($y <=  $largeBucketHeight AND $thickness <= $largeBucketLength AND $x <= $largeBucketWidth) OR ($y <=  $largeBucketHeight AND $x <= $largeBucketLength AND $thickness <= $largeBucketWidth)))
        { 
            
            return "LARGE BUCKET";             
                    
        }
    }   
    //3D BUCKET
    elseif($type==1)
    {
        //SMALL BUCKET 3D------------------------------------------------------------------------------------
        if($convertedWeight <= $smallBucketWeight AND $totalItemVolume_3D <= $smallBucketVolume_flat AND (($itemHeight <= $smallBucketHeight AND $itemLength <= $smallBucketLength AND $itemWidth <= $smallBucketWidth) OR ($itemHeight <= $smallBucketHeight AND $itemWidth <= $smallBucketLength AND $itemLength <= $smallBucketWidth) OR ($itemLength <= $smallBucketHeight AND $itemHeight <= $smallBucketLength AND $itemWidth <=  $smallBucketWidth) OR ($itemLength <= $smallBucketHeight AND $itemWidth <=  $smallBucketLength AND $itemHeight <= $smallBucketWidth) OR ($itemWidth <=  $smallBucketHeight AND $itemHeight <= $smallBucketLength AND $itemLength <= $smallBucketWidth) OR ($itemWidth <=  $smallBucketHeight AND $itemLength <= $smallBucketLength AND $itemHeight <= $smallBucketWidth)))
        { 
            
            return "SMALL BUCKET 3D";             
                    
        }

        //MEDIUM BUCKET 3D-----------------------------------------------------------------------
        elseif($convertedWeight <= $mediumBucketWeight AND $totalItemVolume_3D <= $mediumBucketVolume_flat AND (($itemHeight <= $mediumBucketHeight AND $itemLength <= $mediumBucketLength AND $itemWidth <= $mediumBucketWidth) OR ($itemHeight <= $mediumBucketHeight AND $itemWidth <= $mediumBucketLength AND $itemLength <= $mediumBucketWidth) OR ($itemLength <= $mediumBucketHeight AND $itemHeight <= $mediumBucketLength AND $itemWidth <=  $mediumBucketWidth) OR ($itemLength <= $mediumBucketHeight AND $itemWidth <=  $mediumBucketLength AND $itemHeight <= $mediumBucketWidth) OR ($itemWidth <=  $mediumBucketHeight AND $itemHeight <= $mediumBucketLength AND $itemLength <= $mediumBucketWidth) OR ($itemWidth <=  $mediumBucketHeight AND $itemLength <= $mediumBucketLength AND $itemHeight <= $mediumBucketWidth)))
        { 
            return "MEDIUM BUCKET 3D"; 
                        
                    
        }
        
        //LARGE BUCKET 3D-----------------------------------------------------------------------
        elseif($convertedWeight <= $largeBucketWeight AND $totalItemVolume_3d <= $largeBucketVolume_flat AND (($itemHeight <= $largeBucketHeight AND $itemLength <= $largeBucketLength AND $itemWidth <= $largeBucketWidth) OR ($itemHeight <= $largeBucketHeight AND $itemWidth <= $largeBucketLength AND $itemLength <= $largeBucketWidth) OR ($itemLength <= $largeBucketHeight AND $itemHeight <= $largeBucketLength AND $itemWidth <=  $largeBucketWidth) OR ($itemLength <= $largeBucketHeight AND $itemWidth <=  $largeBucketLength AND $itemHeight <= $largeBucketWidth) OR ($itemWidth <=  $largeBucketHeight AND $itemHeight <= $largeBucketLength AND $itemLength <= $largeBucketWidth) OR ($itemWidth <=  $largeBucketHeight AND $itemLength <= $largeBucketLength AND $thickness <= $largeBucketWidth)))
        { 
            
            return "LARGE BUCKET 3D";             
                    
        }
    
    }
   

}

//-----------------------------------------Carlo 04/08/21--------------------------------------

$sql = "SELECT languageFlag FROM hr_employee WHERE idNumber = '".$_SESSION['idNumber']."'";
$query = $db->query($sql);
$result = $query->fetch_assoc();

$languageFlag = $result['languageFlag'];
//----------------------------------------------------------------------------------------------

//TAMANG
if(isset($_POST['btnUpdatePartsComment']))
{
    $sql="UPDATE cadcam_parts SET partsComment ='".$_POST['partsComment']."' WHERE partId ='".$_GET['partId']."'";
    $queryPartsComment = $db->query($sql);
}
//TAMANG

function dataParameter($processCode, $parameterNumber)
{
    include('PHP Modules/mysqliConnection.php');

    $displayId = "";
    $sql = "SELECT * FROM system_processparameter WHERE processCode = ".$processCode." AND parameterNumber = '".$parameterNumber."'";
    $queryParameter = $db->query($sql);
    if($queryParameter AND $queryParameter->num_rows > 0)
    {
        $resultParameter = $queryParameter->fetch_assoc();
        $displayId = displayText($resultParameter['displayId']);
    }
    
    return $displayId;
}

$test= isset($_POST['test']) ? $_POST['test']: "";
$remarks= isset($_POST['remarks']) ? $_POST['remarks']: "";
if (isset($_GET['partId']))
{
	$getPartId = $_GET['partId'];
}

if(isset($_POST['updatePartName']))
{
	$partId = $_POST['partId'];
	$partName = $_POST['partName'];
	$oldValue = $_POST['oldValue'];
	$update  ="UPDATE cadcam_parts SET partName = '".$partName."' WHERE partId = ".$partId;
	$processUpdate = $db->query($update);
	if($processUpdate)
	{
		$userID = $_SESSION['userID'];
		$userIP = $_SERVER['REMOTE_ADDR'];
		$sql = "INSERT INTO system_partlog
							(	partId,					date,	query,	field,	oldValue,	newValue,					ip,				user,			details)
					VALUES	(	".$partId.",now(),2,2,'".$oldValue."','".$partName."',	'".$userIP."',	'".$userID."','') ";
		$insert = $db->query($sql);
	}
	$update  ="UPDATE system_lotlist SET partName = '".$partName."' WHERE partId = ".$partId;
	$processUpdate = $db->query($update);

}



	$sql = "SELECT a.partId, a.partNumber, a.revisionId, a.partNote,
			b.station, b.angle, b.punchtype, b.punchsize, b.clearance,
			c.programCode 
			FROM cadcam_parts AS a
			INNER JOIN engineering_toollist AS b ON a.partId = b.partId
			INNER JOIN engineering_programs AS c ON b.partId = c.partId
			WHERE a.customerId =45 and a.partId = ".$getPartId."";

		

	$queryPartNumber = $db->query($sql);

	if($queryPartNumber->num_rows > 0)
	{
		while($resultPartNumber = $queryPartNumber->fetch_assoc())
		{

		$partId = $resultPartNumber['partId'];
		$partNumber = $resultPartNumber['partNumber'];
		$revisionId = $resultPartNumber['revisionId'];
		$partNote = $resultPartNumber['partNote'];
		$station = $resultPartNumber['station'];
		$angle = $resultPartNumber['angle'];
		$punchtype = $resultPartNumber['punchtype'];
		$punchsize = $resultPartNumber['punchsize'];
		$clearance = $resultPartNumber['clearance'];
		$programCode = $resultPartNumber['programCode'];

		$errorFlag="GOOD";
		$errorSuggest="";

		
			$punchExplode=array();
			$punchExplode=explode("`",$punchsize);
			$punchTypeExplode=explode("`",$punchtype);
			if(count($punchExplode)>0)
			{
				for($a=0;$a<count($punchExplode);$a++)
				{
					$punchExplode[$a]=str_replace(trim('*'),'X',$punchExplode[$a]);
					if(stristr($punchTypeExplode[$a], "rectangle"))
					{
						$errorSuggest='ERROR';

					}

					if(stristr($punchTypeExplode[$a], "round") and $errorSuggest=="")
					{
						$errorSuggest='SUGGEST';
						$check=1;
					
					}
					if(trim($punchExplode[$a])!='' and trim($punchExplode[$a])!=NULL)
					{
					$sqlA = "SELECT * FROM system_atctools WHERE tppNumber = 1 and punchSize LIKE '".trim($punchExplode[$a])."'";
					if(stristr($punchTypeExplode[$a], "round"))
					{   
						$punchExplode[$a]=((trim($punchExplode[$a])+1)-1);
						$punchExplode2=explode(".",$punchExplode[$a]);
						if(trim($punchExplode[$a])){}
						$sqlA = "SELECT * FROM system_atctools WHERE tppNumber = 1 and punchSize LIKE '".trim($punchExplode[$a])."'";
					}
					$queryLot = $db->query($sqlA);
					if($queryLot->num_rows > 0)
					{
						
						$resultLotNumber = $queryLot->fetch_assoc();
						$punchSizeMatch = $resultLotNumber['punchSize'];
					}
					else
					{
					
                    if (stristr($punchTypeExplode[$a], "round") and $errorSuggest == 'SUGGEST')
                    {
					
					    $sqlSuggestHigh = "SELECT (MIN( CAST( punchSize AS DECIMAL(10,3)) )) AS sizeHigh FROM system_atctools WHERE CAST(punchSize AS DECIMAL(10,3)) > ".trim($punchExplode[$a])." AND punchType =  'round' ";
					    $querySuggestHigh = $db->query($sqlSuggestHigh);
					    $resultSuggestHigh = $querySuggestHigh->fetch_assoc();
					    $suggestHigh = $resultSuggestHigh['sizeHigh'];
                        $sqlSuggestLow = "SELECT (MAX( CAST( punchSize AS DECIMAL(10,3)))) AS sizeLow FROM system_atctools WHERE CAST(punchSize AS DECIMAL(10,3)) < ".trim($punchExplode[$a])." AND punchType =  'round' ";

					    $querySuggestLow = $db->query($sqlSuggestLow);
                        $resultSuggestLow = $querySuggestLow->fetch_assoc();
                        $suggestLow = $resultSuggestLow['sizeLow'];
						$errorFlag="";
                    
					}
					else
					{

						$errorFlag="";
					}	
				
					}
					}
				}
			}
		if($errorFlag=="GOOD")
		{

			$errorSuggest='';
		}
		}
	}


//--------------------------------EEEEEENNNNNNNNNNNNNNNNNNNNNNNNNDDDDDD------------------------


if(!isset($_SESSION['idNumber']))
{
	header("Location: ../index.php");
}

if(isset($_POST['ajax']))
{	
	if($_POST['ajax']=='updateProcessOrder')
	{
		$workscheduleId = $_POST['workscheduleId'];
		$processRemarks = $_POST['processRemarks'];
		
		//if($_SESSION['idNumber']=='0276' or $_SESSION['idNumber']=='0434') //IT OR QC
		//{
			$sql = "UPDATE cadcam_partprocess SET processOrder = '".$processRemarks."' WHERE count = ".$workscheduleId." LIMIT 1";
			$queryUpdate = $db->query($sql);
		//}
	}
    elseif($_POST['ajax']=='updateDefaultPattern')
    {
        $partId = $_POST['partId'];
		$patternId = $_POST['patternId'];
        $sql = "UPDATE cadcam_parts SET patternId = ".$patternId." WHERE partId = ".$partId." LIMIT 1";
        $queryUpdate = $db->query($sql);
    }
	exit(0);
}

if(isset($_POST['tinyBoxType']))
{
	if($_POST['tinyBoxType'] == 'addSubcon')
	{
		$select = "<select name='subconId' form='formIdAddSubcon' required>";
		$select .= "<option value=''>Select Subcon</option>";
		$sql = "SELECT subconId, subconAlias FROM purchasing_subcon WHERE status = 0 ORDER BY subconAlias";
		$querySubcon = $db->query($sql);
		if($querySubcon AND $querySubcon->num_rows > 0)
		{
			while($resultSubcon = $querySubcon->fetch_assoc())
			{
				$subconId = $resultSubcon['subconId'];
				$subconAlias = $resultSubcon['subconAlias'];
				
				$select .= "<option value='".$subconId."'>".$subconAlias."</option>";
			}
		}
		$select .= "</select>";
			
		echo "
			<form action='".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."' method='post' id='formIdAddSubcon'></form>
			<input type='hidden' name='tinyBoxType' value='insertSubcon' form='formIdAddSubcon'>
			<input type='hidden' name='a' value='".$_POST['a']."' form='formIdAddSubcon'>
			<table>
				<tr>
					<td><center>".displayText('L1455')."</center></td>
				</tr>
				<tr>
					<td>".$select."</td>
				</tr>
				<tr>
					<td><center><input type='submit' name='' value='".displayText('B1')."' form='formIdAddSubcon'></center></td>
				</tr>
			</table>
		";//L1455 ADD SUBCON  L489 SUBMIT
	}
	else if($_POST['tinyBoxType'] == 'insertSubcon')
	{
		$sql = "INSERT INTO `engineering_subconprocessor`(`a`, `subconId`) VALUES ('".$_POST['a']."','".$_POST['subconId']."')";
		$queryInsert = $db->query($sql);
	
		$userID = $_SESSION['userID'];
		$userIP = $_SERVER['REMOTE_ADDR'];
		
		$sql = "INSERT INTO system_partlog
						(	partId,					date,	query,	field,	oldValue,	newValue,					ip,				user,			details)
				VALUES	(	".$_GET['partId'].",	now(),	1,		37,		'',			'".$_POST['subconId']."',	'".$userIP."',	'".$userID."',	'INSERT subcon') ";
		$insert = $db->query($sql);
		
		header("location:".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&src=subcon&patternId=".$_GET['patternId']);
	}
	else if($_POST['tinyBoxType'] == 'addSubpartProcess')
	{
		$subpartprocessQuantityTotal = 0;
		$sqlFilter = "";
		$subPartProcessCodeArray = array();
		$sql = "SELECT processCode, quantity FROM engineering_subpartprocesslink WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." AND childId = ".$_POST['childId']." AND identifier = ".$_POST['identifier']." ";
		$querySubPartProcessLink = $db->query($sql);
		if($querySubPartProcessLink AND $querySubPartProcessLink->num_rows > 0)
		{
			while($resultSubPartProcessLink = $querySubPartProcessLink->fetch_assoc())
			{
				$subPartProcessCodeArray[] = $resultSubPartProcessLink['processCode'];
				$subpartprocessQuantityTotal += $resultSubPartProcessLink['quantity'];
			}
			$sqlFilter = " AND processCode NOT IN(".implode(",",$subPartProcessCodeArray).")";
		}
		
		
		$select = "<select name='processCode' form='formIdAddSubpartProcess' required>";
		$select .= "<option value=''>Select Process</option>";		
		$sql = "SELECT processCode FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." ".$sqlFilter." ORDER BY processOrder";
		$queryPartProcess = $db->query($sql);
		if($queryPartProcess AND $queryPartProcess->num_rows > 0)
		{
			while($resultPartProcess = $queryPartProcess->fetch_assoc())
			{
				$processCode = $resultPartProcess['processCode'];
				
				
				
				$processName = '';
				$sql = "SELECT processName, alternateProcessName FROM cadcam_process WHERE processCode = ".$processCode." LIMIT 1";
				$queryProcess = $db->query($sql);
				if($queryProcess AND $queryProcess->num_rows > 0)
				{
					$resultProcess = $queryProcess->fetch_assoc();
					if ($languageFlag == 1) 
					{
						$processName = $resultProcess['processName'];
					}
					else
					{
						$processName = $resultProcess['alternateProcessName'];
					}
				}
				
				$select .= "<option value='".$processCode."'>".$processName."</option>";
			}
		}
		
		$quantity = 0;
		$sql = "SELECT quantity FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND childId = ".$_POST['childId']." AND identifier = ".$_POST['identifier']." LIMIT 1";
		$querySubParts = $db->query($sql);
		if($querySubParts AND $querySubParts->num_rows > 0)
		{
			$resultSubParts = $querySubParts->fetch_assoc();
			$quantity = $resultSubParts['quantity'];
		}
		
		$maxQuantity = $quantity - $subpartprocessQuantityTotal;
		
		echo "
			<form action='".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."&src=".$_GET['src']."' method='post' id='formIdAddSubpartProcess'></form>
			<input type='hidden' name='tinyBoxType' value='insertSubpartProcess' form='formIdAddSubpartProcess'>
			<input type='hidden' name='childId' value='".$_POST['childId']."' form='formIdAddSubpartProcess'>		
			<input type='hidden' name='identifier' value='".$_POST['identifier']."' form='formIdAddSubpartProcess'>		
			<table>
				<tr>
					<td><center>".displayText('L1418')."</center></td>
				</tr>
				<tr>
					<td>".$select."</td>
				</tr>
				<tr>
					<td>Quantity : <input type='number' name='quantity' min='1' max='".$maxQuantity."' value='".$maxQuantity."' required form='formIdAddSubpartProcess'></td>
				</tr>
				<tr>
					<td><center><input type='submit' name='' value='SUBMIT' form='formIdAddSubpartProcess'></center></td>
				</tr>
			</table>
		";
	}
	else if($_POST['tinyBoxType'] == 'insertSubpartProcess')
	{
		$sql = "INSERT INTO `engineering_subpartprocesslink`
						(	`partId`,				`processCode`,					`patternId`,				`childId`,					`identifier`,				`quantity`)
				VALUES	(	'".$_GET['partId']."',	'".$_POST['processCode']."',	'".$_GET['patternId']."',	'".$_POST['childId']."',	'".$_POST['identifier']."',	'".$_POST['quantity']."')";
		$queryInsert = $db->query($sql);
		
		$inputNote = '';
		if($_POST['identifier']==1)
		{
			$sql = "SELECT partNumber FROM cadcam_parts WHERE partId = ".$_POST['childId']." LIMIT 1";
			$queryParts = $db->query($sql);
			if($queryParts AND $queryParts->num_rows > 0)
			{
				$resultParts = $queryParts->fetch_assoc();
				
				$inputNote = $_POST['quantity']."-".$resultParts['partNumber'];
			}
		}
		else if($_POST['identifier']==2)
		{
			$sql = "SELECT accessoryNumber FROM cadcam_accessories WHERE accessoryId = ".$_POST['childId']." LIMIT 1";
			$queryParts = $db->query($sql);
			if($queryParts AND $queryParts->num_rows > 0)
			{
				$resultParts = $queryParts->fetch_assoc();
				
				$inputNote = $_POST['quantity']."-".$resultParts['accessoryNumber'];
			}
		}
	
		if($inputNote!='')
		{
			$sql = "INSERT INTO cadcam_partprocessnote (partId, processCode, patternId, remarks)
			VALUES (".$_GET['partId'].", ".$_POST['processCode'].", ".$_GET['patternId'].", '".$inputNote."')";
			$insertQuery = $db->query($sql);
		}
	
		$userID = $_SESSION['userID'];
		$userIP = $_SERVER['REMOTE_ADDR'];
		
		//~ $sql = "INSERT INTO system_partlog
						//~ (	partId,					date,	query,	field,	oldValue,	newValue,					ip,				user,			details)
				//~ VALUES	(	".$_GET['partId'].",	now(),	1,		37,		'',			'".$_POST['subconId']."',	'".$userIP."',	'".$userID."',	'INSERT subcon') ";
		//~ $insert = $db->query($sql);
		
		header("location:".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&src=".$_GET['src']."&patternId=".$_GET['patternId']);
	}
	else if($_POST['tinyBoxType'] == 'editSubpartProcess')
	{
		$maxQuantity = 0;
		$sql = "SELECT quantity FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND childId = ".$_POST['childId']." AND identifier = ".$_POST['identifier']." LIMIT 1";
		$querySubParts = $db->query($sql);
		if($querySubParts AND $querySubParts->num_rows > 0)
		{
			$resultSubParts = $querySubParts->fetch_assoc();
			$maxQuantity = $resultSubParts['quantity'];
		}
		
		$subPartProcessCodeArray = array();
		$sql = "SELECT DISTINCT processCode FROM engineering_subpartprocesslink WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." AND childId = ".$_POST['childId']." AND identifier = ".$_POST['identifier']." ";
		$querySubPartProcessLink = $db->query($sql);
		if($querySubPartProcessLink AND $querySubPartProcessLink->num_rows > 0)
		{
			while($resultSubPartProcessLink = $querySubPartProcessLink->fetch_assoc())
			{
				$subPartProcessCodeArray[] = $resultSubPartProcessLink['processCode'];
			}
		}
		
		echo "
			<span style='font-style:italic;color:red;'>".displayText('L1432')."</span>
			<form action='".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."&src=".$_GET['src']."' method='post' id='formIdEditSubpartProcess'></form>
			<input type='hidden' name='tinyBoxType' value='updateSubpartProcess' form='formIdEditSubpartProcess'>
			<input type='hidden' name='childId' value='".$_POST['childId']."' form='formIdEditSubpartProcess'>		
			<input type='hidden' name='identifier' value='".$_POST['identifier']."' form='formIdEditSubpartProcess'>
			<table border='1'>
				<tr>
					<th>".displayText('L59')."</th>
					<th>".displayText('L31')."</th>
				</tr>
		";
		$sql = "SELECT listId, processCode, quantity FROM engineering_subpartprocesslink WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." AND childId = ".$_POST['childId']." AND identifier = ".$_POST['identifier']." ";
		$querySubPartProcessLink = $db->query($sql);
		if($querySubPartProcessLink AND $querySubPartProcessLink->num_rows > 0)
		{
			while($resultSubPartProcessLink = $querySubPartProcessLink->fetch_assoc())
			{
				$listId = $resultSubPartProcessLink['listId'];
				$processCode = $resultSubPartProcessLink['processCode'];
				$quantity = $resultSubPartProcessLink['quantity'];
				
				$processName = '';
				$sql = "SELECT processName, alternateProcessName FROM cadcam_process WHERE processCode = ".$processCode." LIMIT 1";
				$queryProcess = $db->query($sql);
				if($queryProcess AND $queryProcess->num_rows > 0)
				{
					$resultProcess = $queryProcess->fetch_assoc();
					if ($languageFlag == 1) 
					{
						$processName = $resultProcess['processName'];
					}
					else
					{
						$processName = $resultProcess['alternateProcessName'];
					}
				}
				
				$subPartProcessCodeArr = $subPartProcessCodeArray;
				
				$sqlFilter = "";
				if(count($subPartProcessCodeArr) > 0)
				{
					$arrayKey = array_search($processCode,$subPartProcessCodeArr);
					unset($subPartProcessCodeArr[$arrayKey]);
					if(count($subPartProcessCodeArr) > 0)	$sqlFilter = " AND processCode NOT IN(".implode(",",$subPartProcessCodeArr).")";
				}
				
				$select = "<select name='processCode[".$listId."]' form='formIdEditSubpartProcess' required>";
				$sql = "SELECT processCode FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." ".$sqlFilter." ORDER BY processOrder";
				$queryPartProcess = $db->query($sql);
				if($queryPartProcess AND $queryPartProcess->num_rows > 0)
				{
					while($resultPartProcess = $queryPartProcess->fetch_assoc())
					{
						$processName = '';
						$sql = "SELECT processName, alternateProcessName FROM cadcam_process WHERE processCode = ".$resultPartProcess['processCode']." LIMIT 1";
						$queryProcess = $db->query($sql);
						if($queryProcess AND $queryProcess->num_rows > 0)
						{
							$resultProcess = $queryProcess->fetch_assoc();
							if ($languageFlag == 1) 
							{
								$processName = $resultProcess['processName'];
							}
							else
							{
								$processName = $resultProcess['alternateProcessName'];
							}
						}
						
						$selected = ($processCode==$resultPartProcess['processCode']) ? 'selected' : '';
						
						$select .= "<option value='".$resultPartProcess['processCode']."' ".$selected.">".$processName."</option>";
					}
				}
				
				echo "
					<input type='hidden' name='listId[]' value='".$listId."' form='formIdEditSubpartProcess'>
					<tr>
						<td>".$select."</td>
						<td><input type='number' name='quantity[".$listId."]' max='".$maxQuantity."' value='".$quantity."' required form='formIdEditSubpartProcess'></td>
					</tr>
				";
			}
		}	
		echo "
			<tr>
				<th colspan='2'><input type='submit' name='' value='".displayText('L1387')."' form='formIdEditSubpartProcess'></th>
			</tr>
		";	
		echo "</table><br>";
	}
	else if($_POST['tinyBoxType'] == 'updateSubpartProcess')
	{
		$listIdArray = $_POST['listId'];
		$processCodeArray = $_POST['processCode'];
		$quantityArray = $_POST['quantity'];
		
		foreach($listIdArray as $listId)
		{
			$processCode = $processCodeArray[$listId];
			$quantity = $quantityArray[$listId];
			
			if($quantity > 0)
			{
				$sql = "UPDATE engineering_subpartprocesslink SET processCode = ".$processCode.", quantity = ".$quantity." WHERE listId = ".$listId." LIMIT 1";
				$queryUpdate = $db->query($sql);
			}
			else
			{
				$sql = "DELETE FROM engineering_subpartprocesslink WHERE listId = ".$listId." LIMIT 1";
				$queryUpdate = $db->query($sql);
			}
		}
		
		header("location:".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&src=".$_GET['src']."&patternId=".$_GET['patternId']);
	}
	else if($_POST['tinyBoxType'] == 'addSubProcess')	
	{
		$sqlFilter = "";
		$subProcessCodeArray = array();
		$sql = "SELECT processCode FROM engineering_partsubprocess WHERE partId = ".$_GET['partId']." AND mainProcessCode = ".$_POST['mainProcessCode']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder";
		$queryPartSubProcess = $db->query($sql);
		if($queryPartSubProcess AND $queryPartSubProcess->num_rows > 0)
		{
			while($resultPartSubProcess = $queryPartSubProcess->fetch_assoc())
			{
				$subProcessCodeArray[] = $resultPartSubProcess['processCode'];
			}
			$sqlFilter = " AND processCode NOT IN(".implode(",",$subProcessCodeArray).")";
		}
		//rhay
		$select = "<select  form='formIdAddSubProcess' required id='subprocessSelect' name='processCode[]' multiple class='w3-pale-yellow'>";
		if($_POST['mainProcessCode'] == 1)
		{
			$sql = "SELECT processCode, processName, alternateProcessName FROM cadcam_process WHERE status = 0 ".$sqlFilter." AND processName LIKE '%Bending%' ORDER BY processName";
			$queryPartProcess = $db->query($sql);
			if($queryPartProcess AND $queryPartProcess->num_rows > 0)
			{
				while($resultPartProcess = $queryPartProcess->fetch_assoc())
				{
					$processCode = $resultPartProcess['processCode'];
					if ($languageFlag == 1) 
					{
						$processName = $resultPartProcess['processName'];
					}
					else
					{
						$processName = $resultPartProcess['alternateProcessName'];
					}
					$select .= "<option value='".$processCode."'>".$processName."</option>";
				}
			}
		}
		else
		{
			$sql = "SELECT processSection FROM cadcam_process WHERE processCode = ".$_POST['mainProcessCode'];
			$querySection = $db->query($sql);
			if($querySection AND $querySection->num_rows > 0)
			{
				$resultSection = $querySection->fetch_assoc();
				$sectionId = $resultSection['processSection'];

				$sqlFilter .= " AND processSection = ".$sectionId;
			}			
			
			$sql = "SELECT processCode, processName, alternateProcessName FROM cadcam_process WHERE status = 0 ".$sqlFilter." ORDER BY processName";
			$queryPartProcess = $db->query($sql);
			if($queryPartProcess AND $queryPartProcess->num_rows > 0)
			{
				while($resultPartProcess = $queryPartProcess->fetch_assoc())
				{
					$processCode = $resultPartProcess['processCode'];
					if ($languageFlag == 1) 
					{
						$processName = $resultPartProcess['processName'];
					}
					else
					{
						$processName = $resultPartProcess['alternateProcessName'];
					}
					
					$select .= "<option value='".$processCode."'>".$processName."</option>";
				}
			}
		}
			$select .= "</select>";
		echo "
			<form action='".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."&src=".$_GET['src']."' method='post' id='formIdAddSubProcess'></form>
			<input type='hidden' name='tinyBoxType' value='insertSubProcess' form='formIdAddSubProcess'>
			<input type='hidden' name='mainProcessCode' value='".$_POST['mainProcessCode']."' form='formIdAddSubProcess'>
			<input type='hidden' name='subProcessOrder' value='".$_POST['subProcessOrder']."' form='formIdAddSubProcess'>
			<input type='hidden' name='sectionId' value='".$sectionId."' form='formIdAddSubProcess'>
			<table>
				<tr>
					<td><center>".displayText('L1418')."</center></td>
				</tr>
				<tr>
					<td>".$select."</td>
				</tr>
				<tr>
					<td><center><input type='submit' name='' value='".displayText('B1')."' form='formIdAddSubProcess'></center></td>
				</tr>
			</table>
		";		
	}
	else if($_POST['tinyBoxType'] == 'insertSubProcess')
	{
		foreach ($_POST['processCode'] as $key)
		{
			$sql = "INSERT INTO `engineering_partsubprocess`
						(	`partId`,				`mainProcessCode`,					`patternId`,				`processOrder`,							`processCode`)
					VALUES	(	'".$_GET['partId']."',	'".$_POST['mainProcessCode']."',	'".$_GET['patternId']."',	'".($_POST['subProcessOrder']+1)."',	'".$key."')";
			$queryInsert = $db->query($sql);
		}
		
		header("location:".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&src=".$_GET['src']."&patternId=".$_GET['patternId']);
	}
	else if($_POST['tinyBoxType'] == 'editSubProcess')
	{
		echo "
			<form action='".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."&src=".$_GET['src']."' method='post' id='formIdEditSubProcess'></form>
			<input type='hidden' name='tinyBoxType' value='updateSubProcess' form='formIdEditSubProcess'>
			<input type='hidden' name='mainProcessCode' value='".$_POST['childId']."' form='formIdEditSubProcess'>		
			<input type='hidden' name='subProcessOrder' value='".$_POST['identifier']."' form='formIdEditSubProcess'>
			<table border='1'>
				<tr>
					<th><input type='checkbox' id='subProcessCheckAll'><img src = '../Common Data/Templates/images/trash1.png' width = '20' height = '20' style='cursor:pointer;' id='deleteSelectedSubProcess'></th>
					<th>".displayText('L1433')."</th>
					<th>".displayText('L1434')."</th>
					<th></th>
				</tr>
		";		
		$sql = "SELECT listId, processCode, processOrder FROM engineering_partsubprocess WHERE partId = ".$_GET['partId']." AND mainProcessCode = ".$_POST['mainProcessCode']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder";
		$queryPartSubProcess = $db->query($sql);
		if($queryPartSubProcess AND $queryPartSubProcess->num_rows > 0)
		{
			while($resultPartSubProcess = $queryPartSubProcess->fetch_assoc())
			{
				$listId = $resultPartSubProcess['listId'];
				$processCode = $resultPartSubProcess['processCode'];
				$processOrder = $resultPartSubProcess['processOrder'];
				
				$processName = '';
				$sql = "SELECT processName, alternateProcessName FROM cadcam_process WHERE processCode = ".$processCode." LIMIT 1";
				$queryProcess = $db->query($sql);
				if($queryProcess AND $queryProcess->num_rows > 0)
				{
					$resultProcess = $queryProcess->fetch_assoc();
					if ($languageFlag == 1) 
					{
						$processName = $resultProcess['processName'];
					}
					else
					{
						$processName = $resultProcess['alternateProcessName'];
					}
				}
				
				//~ $select = "<select name='processCode[".$listId."]' form='formIdEditSubProcess' required>";
				//~ $sql = "SELECT processCode, processName FROM cadcam_process WHERE status = 0 ORDER BY processName";
				//~ $queryPartProcess = $db->query($sql);
				//~ if($queryPartProcess AND $queryPartProcess->num_rows > 0)
				//~ {
					//~ while($resultPartProcess = $queryPartProcess->fetch_assoc())
					//~ {
						//~ $selected = ($processCode==$resultPartProcess['processCode']) ? 'selected' : '';
						
						//~ $select .= "<option value='".$resultPartProcess['processCode']."' ".$selected.">".$resultPartProcess['processName']."</option>";
					//~ }
				//~ }
				
				//~ echo "
					//~ <input type='hidden' name='listId[]' value='".$listId."' form='formIdEditSubProcess'>
					//~ <tr>
						//~ <td>".$select."</td>
					//~ </tr>
				//~ ";
				
				$processNoteList = "<ul style='list-style-type:square'>";										
					$sql = "SELECT patternId, remarks as processDetail FROM cadcam_partprocessnote WHERE processCode = ".$processCode." and partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." ORDER BY noteId";
					$processDetailQuery=$db->query($sql);
					while($processDetailQueryResult = $processDetailQuery->fetch_assoc())
					{
						$processNoteList .= "<li>".$processDetailQueryResult['processDetail']."</li>";
					}
				$processNoteList .= "</ul>";
				
				echo "
					<tr>
						<td><input type='checkbox' class='subProcessCheck' name='subProcessListIdArray[]' value='".$listId."'></td>
						<td>".$processName."</td>
						<td>".$processNoteList."</td>
						<td>
							<a onclick=\"openTinyBox('300','520','anthony_updateProcessForm.php?partId=".$_GET['partId']."&processCode=".$processCode."&subProcessListId=".$listId."&patternId=".$_GET['patternId']."')\"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20'></a>
							<a onclick=\"TINY.box.show({url:'anthony_deleteProcessDetailForm.php?partId=".$_GET['partId']."&processOrder=".$processOrder."&subProcessListId=".$listId."&patternId=".$_GET['patternId']."',width:'300',height:'200',opacity:10,topsplit:6,animate:false,close:true})\"><img src = '../Common Data/Templates/images/trash1.png' width = '20' height = '20'></a>
						</td>
					</tr>
				";
			}
		}
		//~ echo "
			//~ <tr>
				//~ <th colspan='2'><input type='submit' name='' value='EDIT' form='formIdEditSubProcess'></th>
			//~ </tr>
		//~ ";	
		echo "</table><br>";
	}
	else if($_POST['tinyBoxType'] == 'updateSubProcess')
	{
		$listIdArray = $_POST['listId'];
		$processCodeArray = $_POST['processCode'];
		
		foreach($listIdArray as $listId)
		{
			$processCode = $processCodeArray[$listId];
			
			$sql = "UPDATE `engineering_partsubprocess` SET `processCode`='".$processCode."' WHERE listId = ".$listId." LIMIT 1";
			$queryUpdate = $db->query($sql);
		}
		
		header("location:".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&src=".$_GET['src']."&patternId=".$_GET['patternId']);		
	}
	exit(0);
}

if(isset($_POST['ajaxType']) AND $_POST['ajaxType'] == 'updateItemHandlingFlag')
{
	$sql = "UPDATE cadcam_partprocess SET itemHandlingFlag = ".$_POST['itemHandlingFlag']." WHERE count = ".$_POST['count']." LIMIT 1";
	$queryUpdate = $db->query($sql);
	
	exit(0);
}

if(isset($_GET['subconProcessorListId']))
{
	$userID = $_SESSION['userID'];
	$userIP = $_SERVER['REMOTE_ADDR'];
	
	$subconId = '';
	$sql = "SELECT subconId FROM engineering_subconprocessor WHERE listId = ".$_GET['subconProcessorListId']." LIMIT 1";
	$querySubconProcessor = $db->query($sql);
	if($querySubconProcessor AND $querySubconProcessor->num_rows > 0)
	{
		$resultSubconProcessor = $querySubconProcessor->fetch_assoc();
		$subconId = $resultSubconProcessor['subconId'];
	}
	
	$sql = "INSERT INTO system_partlog
					(	partId,					date,	query,	field,	oldValue,								newValue,	ip,				user,			details)
			VALUES	(	".$_GET['partId'].",	now(),	3,		37,		'".$_GET['subconProcessorListId']."',	'',			'".$userIP."',	'".$userID."',	'DELETE subcon = ".$subconId."') ";
	$insert = $db->query($sql);	
	
	$sql = "DELETE FROM engineering_subconprocessor WHERE listId = ".$_GET['subconProcessorListId']." LIMIT 1";
	$queryDelete = $db->query($sql);
	
	header("location:".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&src=subcon&patternId=".$_GET['patternId']);
	exit(0);
}

//rosemie
$_GET['patternId'] = isset($_GET['patternId']) ? $_GET['patternId'] : 0;
if($_GET['patternId']=="")
{
	$_GET['patternId'] = 0;
}
//rosemie

if($_GET['type']=='programName')
{
	$programName = $_POST['programName'];
	$sql = "UPDATE engineering_laserprogram SET programName = '".$programName."', idNumber = '".$_SESSION['idNumber']."' WHERE partId = ".$_GET['partId']." LIMIT 1";
	$queryUpdate = $db->query($sql);

	header("location:".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&src=programs&patternId=".$_GET['patternId']."");
	exit(0);
}

if($_GET['type']=='laserStatus')
{
	$laserStatus = ($_GET['laserStatus'] == '1') ? '0' : '1';
	$sql = "SELECT status FROM engineering_laserprogram WHERE partId = ".$_GET['partId']." LIMIT 1";
	$queryLaserProgram = $db->query($sql);
	if($queryLaserProgram->num_rows > 0)
	{
		$resultLaserProgram = $queryLaserProgram->fetch_array();
		$status = $resultLaserProgram['status'];
		
		$sql = "UPDATE engineering_laserprogram SET status = ".$laserStatus.", idNumber = '".$_SESSION['idNumber']."' WHERE partId = ".$_GET['partId']." LIMIT 1";
		$queryUpdate = $db->query($sql);
	}
	else
	{
		$sql = "INSERT INTO engineering_laserprogram (partId, status, idNumber) VALUES(".$_GET['partId'].",1,'".$_SESSION['idNumber']."')";
		$queryInsert = $db->query($sql);
		
		//2017-06-19 Update NC Program Process Request By Ma'am Mercy
		$lotNumberArray = array();
		$sql = "SELECT lotNumber FROM ppic_lotlist WHERE partId = ".$_GET['partId']." AND identifier = 5";
		$queryLotList = $db->query($sql);
		if($queryLotList AND $queryLotList->num_rows > 0)
		{
			while($resultLotList = $queryLotList->fetch_assoc())
			{
				$lotNumberArray[] = $resultLotList['lotNumber'];
			}
		}
		// in use
		$sql = "UPDATE ppic_workschedule SET status = 1, actualEnd = NOW(), actualFinish = NOW(), employeeId = '".$_SESSION['idNumber']."' WHERE lotNumber IN('".implode("','",$lotNumberArray)."') AND processCode = 301 AND status = 0";
		$queryUpdate = $db->query($sql);
		//2017-06-19 9:58 am Update NC Program Process Request By Ma'am Mercy
	}
	header("location:".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&src=programs&patternId=".$_GET['patternId']."");
	exit(0);
}
if($_GET['type']=='ncData')
{
	$ncData = ($_GET['ncData']==3) ? 0 : 3;
	
	$sql = "SELECT partId, ncData FROM engineering_partsCheck WHERE partId = ".$_GET['partId']." LIMIT 1";
	$queryPartsCheck = $db->query($sql);
	if($queryPartsCheck AND $queryPartsCheck->num_rows > 0)
	{
		$sql = "UPDATE engineering_partsCheck SET ncData = ".$ncData." WHERE partId = ".$_GET['partId']." LIMIT 1";
		$queryUpdate = $db->query($sql);
	}
	else
	{
		$sql = "INSERT INTO engineering_partsCheck (partId, ncData) VALUES('".$_GET['partId']."','".$ncData."')";
		$queryInsert = $db->query($sql);
	}
	
	header("location:".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&src=programs&patternId=".$_GET['patternId']."");
	exit(0);
}
//TAMANG
$sql = "SELECT partId, partNote, partNumber, partName, revisionId, customerId, materialSpecId, materialSpecDetail, PVC, x, y, itemWeight, itemArea, itemLength, itemWidth, itemHeight, treatmentId, laserParameterId, patternId, bendRobot, weldRobot,partsComment,bucketType FROM cadcam_parts WHERE partId = ".$_GET['partId']." ";
$partsQuery = $db->query($sql);
if($partsQuery->num_rows > 0)
{
	$partsQueryResult = $partsQuery->fetch_array();
    //TAMANG
    $materialSpecId = $partsQueryResult['materialSpecId'];
    $parteId        = $partsQueryResult['partId'];
    $x              = $partsQueryResult['x'];
    $y              = $partsQueryResult['y'];
    $weight         = $partsQueryResult['itemWeight'];
    $itemLength     = $partsQueryResult['itemLength'];
    $itemWidth      = $partsQueryResult['itemWidth'];
    $itemHeight     = $partsQueryResult['itemHeight'];
    $bucketType     = $partsQueryResult['bucketType'];

    $sql = "SELECT metalThickness FROM cadcam_materialspecs WHERE materialSpecId = '".$materialSpecId."'";
    $queryMaterialSpecs = $db->query($sql);
    $resultMaterial = $queryMaterialSpecs->fetch_assoc();
    
    $thickness      = $resultMaterial['metalThickness'];

    $smallBucketVolume_flat =   1280300;
    $mediumBucketVolume_flat=   1618200;
    $largeBucketVolume_flat =   3591760;
    $extraLargeVolume_flat  =    8208000;

     //SMALL BUCKET START HERE------------------------------------------------------------------------------
     $smallBucketLength     =   155;  
     $smallBucketWidth      =   118;  
     $smallBucketHeight     =   70;   
     $smallBucketWeight     =   8.12;
     $smallBucketVolume     =   $smallBucketLength*$smallBucketWidth*$smallBucketHeight;
     //SMALL BUCKET END HERE------------------------------------------------------------------------------
 
     //MEDIUM BUCKET START HERE------------------------------------------------------------------------------
     $mediumBucketLength     =   186; 
     $mediumBucketWidth      =   116;  
     $mediumBucketHeight     =   75;
     $mediumBucketWeight     =   8.56;
     $mediumBucketVolume     =   $mediumBucketLength*$mediumBucketWidth*$mediumBucketHeight;
     //MEDIUM BUCKET END HERE------------------------------------------------------------------------------
 
     //LARGE BUCKET START HERE------------------------------------------------------------------------------
     $largeBucketLength     =   278; 
     $largeBucketWidth      =   152;  
     $largeBucketHeight     =   85;
     $largeBucketVolume     =   $largeBucketLength*$largeBucketWidth*$largeBucketHeight;
     //LARGE BUCKET END HERE------------------------------------------------------------------------------

     //EXTRA LARGE START HERE-------------------------------------------------------------------------------
     $extraLargeBucketLength     =   300; 
     $extraLargeBucketWidth      =   228;  
     $extraLargeBucketHeight     =   120;
     $extraLargeBucketVolume     =   $extraLargeBucketLength*$extraLargeBucketWidth*$extraLargeBucketHeight;
     //EXTRA LARGE END HERE----------------------------------------------------------------------------------
     
 
     $itemVolume             =   $thickness * $x * $y;
     $totalItemVolume        =   $itemVolume * $workingQuantity;
 
     $itemVolume_3D          =   $itemLength * $itemWidth * $itemHeight;
     $totalItemVolume_3D     =   $itemVolume_3D * $workingQuantity;
 
     // $lwhBucketVolume_flat    =   ($thickness * $x * $y);
     $convertedWeight  =   ($weight/1000);

    if($thickness == 0 OR $x == 0 OR $y == 0)
    {
        //SMALL BUCKET 3D------------------------------------------------------------------------------------
        if($convertedWeight <= $smallBucketWeight AND $totalItemVolume_3D <= $smallBucketVolume_flat AND (($itemHeight <= $smallBucketHeight AND $itemLength <= $smallBucketLength AND $itemWidth <= $smallBucketWidth) OR ($itemHeight <= $smallBucketHeight AND $itemWidth <= $smallBucketLength AND $itemLength <= $smallBucketWidth) OR ($itemLength <= $smallBucketHeight AND $itemHeight <= $smallBucketLength AND $itemWidth <=  $smallBucketWidth) OR ($itemLength <= $smallBucketHeight AND $itemWidth <=  $smallBucketLength AND $itemHeight <= $smallBucketWidth) OR ($itemWidth <=  $smallBucketHeight AND $itemHeight <= $smallBucketLength AND $itemLength <= $smallBucketWidth) OR ($itemWidth <=  $smallBucketHeight AND $itemLength <= $smallBucketLength AND $itemHeight <= $smallBucketWidth)))
        { 
            
            $sql = "UPDATE cadcam_parts SET bucketType ='1' WHERE partId = '".$parteId."' LIMIT 1";
            $queryBucketSmall3D = $db->query($sql);          
                    
        }

        //MEDIUM BUCKET 3D-----------------------------------------------------------------------
        elseif($convertedWeight <= $mediumBucketWeight AND $totalItemVolume_3D <= $mediumBucketVolume_flat AND (($itemHeight <= $mediumBucketHeight AND $itemLength <= $mediumBucketLength AND $itemWidth <= $mediumBucketWidth) OR ($itemHeight <= $mediumBucketHeight AND $itemWidth <= $mediumBucketLength AND $itemLength <= $mediumBucketWidth) OR ($itemLength <= $mediumBucketHeight AND $itemHeight <= $mediumBucketLength AND $itemWidth <=  $mediumBucketWidth) OR ($itemLength <= $mediumBucketHeight AND $itemWidth <=  $mediumBucketLength AND $itemHeight <= $mediumBucketWidth) OR ($itemWidth <=  $mediumBucketHeight AND $itemHeight <= $mediumBucketLength AND $itemLength <= $mediumBucketWidth) OR ($itemWidth <=  $mediumBucketHeight AND $itemLength <= $mediumBucketLength AND $itemHeight <= $mediumBucketWidth)))
        { 
            $sql = "UPDATE cadcam_parts SET bucketType ='2' WHERE partId = '".$parteId."' LIMIT 1";
            $queryBucketMedium3D = $db->query($sql); 
                        
                    
        }
        
        //LARGE BUCKET 3D-----------------------------------------------------------------------
        elseif($convertedWeight <= $largeBucketWeight AND $totalItemVolume_3d <= $largeBucketVolume_flat AND (($itemHeight <= $largeBucketHeight AND $itemLength <= $largeBucketLength AND $itemWidth <= $largeBucketWidth) OR ($itemHeight <= $largeBucketHeight AND $itemWidth <= $largeBucketLength AND $itemLength <= $largeBucketWidth) OR ($itemLength <= $largeBucketHeight AND $itemHeight <= $largeBucketLength AND $itemWidth <=  $largeBucketWidth) OR ($itemLength <= $largeBucketHeight AND $itemWidth <=  $largeBucketLength AND $itemHeight <= $largeBucketWidth) OR ($itemWidth <=  $largeBucketHeight AND $itemHeight <= $largeBucketLength AND $itemLength <= $largeBucketWidth) OR ($itemWidth <=  $largeBucketHeight AND $itemLength <= $largeBucketLength AND $thickness <= $largeBucketWidth)))
        { 
            
            $sql = "UPDATE cadcam_parts SET bucketType ='3' WHERE partId = '".$parteId."' LIMIT 1";
            $queryBucketLarge3D = $db->query($sql);             
                    
        } 
        //EXTRA LARGE BUCKET 3D-----------------------------------------------------------------
        elseif($convertedWeight <= $mediumBucketWeight AND $totalItemVolume <= $extraLargeBucketVolume_flat AND (($itemHeight <= $extraLargeBucketHeight AND $itemLength <= $extraLargeBucketLength AND $itemWidth <= $extraLargeBucketWidth) OR ($itemHeight <= $extraLargeBucketHeight AND $itemWidth <= $extraLargeBucketLength AND $itemLength <= $extraLargeBucketWidth) OR ($itemLength <= $extraLargeBucketHeight AND $itemHeight <= $extraLargeBucketLength AND $itemWidth <=  $extraLargeBucketWidth) OR ($itemLength <= $extraLargeBucketHeight AND $itemWidth <=  $extraLargeBucketLength AND $itemHeight <= $extraLargeBucketWidth) OR ($itemWidth <=  $extraLargeBucketHeight AND $itemHeight <= $extraLargeBucketLength AND $itemLength <= $extraLargeBucketWidth) OR ($itemWidth <=  $extraLargeBucketHeight AND $itemLength <= $extraLargeBucketLength AND $itemHeight <= $extraLargeBucketWidth)))
        {
            $sql = "UPDATE cadcam_parts SET bucketType ='4' WHERE partId = '".$parteId."' LIMIT 1";
            $queryBucketExtraLarge3D = $db->query($sql); 
        }
        else
        {
            $sql = "UPDATE cadcam_parts SET bucketType ='5' WHERE partId = '".$parteId."' LIMIT 1";
            $queryBucketPallete3D = $db->query($sql); 
        }
    }
    else
    {

        //SMALL BUCKET FLAT----------------------------------------------------------------------
        if($convertedWeight <= $smallBucketWeight AND $totalItemVolume <= $smallBucketVolume_flat AND (($thickness <= $smallBucketHeight AND $x <= $smallBucketLength AND $y <= $smallBucketWidth) OR ($thickness <= $smallBucketHeight AND $y <= $smallBucketLength AND $x <= $smallBucketWidth) OR ($x <= $smallBucketHeight AND $thickness <= $smallBucketLength AND $y <=  $smallBucketWidth) OR ($x <= $smallBucketHeight AND $y <=  $smallBucketLength AND $thickness <= $smallBucketWidth) OR ($y <=  $smallBucketHeight AND $thickness <= $smallBucketLength AND $x <= $smallBucketWidth) OR ($y <=  $smallBucketHeight AND $x <= $smallBucketLength AND $thickness <= $smallBucketWidth)))
        { 
            
            $sql = "UPDATE cadcam_parts SET bucketType ='1' WHERE partId = '".$parteId."' LIMIT 1";
            $queryBucketSmall = $db->query($sql);
                    
                    
        }

        //MEDIUM BUCKET FLAT-----------------------------------------------------------------------
        elseif($convertedWeight <= $mediumBucketWeight AND $totalItemVolume <= $mediumBucketVolume_flat AND (($thickness <= $mediumBucketHeight AND $x <= $mediumBucketLength AND $y <= $mediumBucketWidth) OR ($thickness <= $mediumBucketHeight AND $y <= $mediumBucketLength AND $x <= $mediumBucketWidth) OR ($x <= $mediumBucketHeight AND $thickness <= $mediumBucketLength AND $y <=  $mediumBucketWidth) OR ($x <= $mediumBucketHeight AND $y <=  $mediumBucketLength AND $thickness <= $mediumBucketWidth) OR ($y <=  $mediumBucketHeight AND $thickness <= $mediumBucketLength AND $x <= $mediumBucketWidth) OR ($y <=  $mediumBucketHeight AND $x <= $mediumBucketLength AND $thickness <= $mediumBucketWidth)))
        { 
            $sql = "UPDATE cadcam_parts SET bucketType ='2' WHERE partId = '".$parteId."' LIMIT 1";
            $queryBucketMedium = $db->query($sql);  
                        
                    
        }

        //LARGE BUCKET FLAT-----------------------------------------------------------------------
        elseif($convertedWeight <= $mediumBucketWeight AND $totalItemVolume <= $largeBucketVolume_flat AND (($thickness <= $largeBucketHeight AND $x <= $largeBucketLength AND $y <= $largeBucketWidth) OR ($thickness <= $largeBucketHeight AND $y <= $largeBucketLength AND $x <= $largeBucketWidth) OR ($x <= $largeBucketHeight AND $thickness <= $largeBucketLength AND $y <=  $largeBucketWidth) OR ($x <= $largeBucketHeight AND $y <=  $largeBucketLength AND $thickness <= $largeBucketWidth) OR ($y <=  $largeBucketHeight AND $thickness <= $largeBucketLength AND $x <= $largeBucketWidth) OR ($y <=  $largeBucketHeight AND $x <= $largeBucketLength AND $thickness <= $largeBucketWidth)))
        { 
            
            $sql = "UPDATE cadcam_parts SET bucketType ='3' WHERE partId = '".$parteId."' LIMIT 1";
            $queryBucketLarge = $db->query($sql);               
                    
        }
        //EXTRA LARGE BUCKET FLAT-----------------------------------------------------------------
        elseif($convertedWeight <= $mediumBucketWeight AND $totalItemVolume <= $extraLargeBucketVolume_flat AND (($thickness <= $extraLargeBucketHeight AND $x <= $extraLargeBucketLength AND $y <= $extraLargeBucketWidth) OR ($thickness <= $extraLargeBucketHeight AND $y <= $extraLargeBucketLength AND $x <= $extraLargeBucketWidth) OR ($x <= $extraLargeBucketHeight AND $thickness <= $extraLargeBucketLength AND $y <=  $extraLargeBucketWidth) OR ($x <= $extraLargeBucketHeight AND $y <=  $extraLargeBucketLength AND $thickness <= $extraLargeBucketWidth) OR ($y <=  $extraLargeBucketHeight AND $thickness <= $extraLargeBucketLength AND $x <= $extraLargeBucketWidth) OR ($y <=  $extraLargeBucketHeight AND $x <= $extraLargeBucketLength AND $thickness <= $extraLargeBucketWidth)))
        {
            $sql = "UPDATE cadcam_parts SET bucketType ='4' WHERE partId = '".$parteId."' LIMIT 1";
            $queryBucketExtraLarge = $db->query($sql); 
        }
        else
        {
            $sql = "UPDATE cadcam_parts SET bucketType ='5' WHERE partId = '".$parteId."' LIMIT 1";
            $queryBucketPallete = $db->query($sql); 
        }

        
    }
        



 


    if($partsQueryResult['bucketType'] == 0)
    {
        $bucketType = 'Not Decide';
    }
    elseif($partsQueryResult['bucketType'] == 1)
    {
        $bucketType = 'Small Bucket';
    }
    elseif($partsQueryResult['bucketType'] == 2)
    {
        $bucketType = 'Medium Bucket';
    }
    elseif($partsQueryResult['bucketType'] == 3)
    {
        $bucketType = 'Large Bucket';
    }
    elseif($partsQueryResult['bucketType'] == 4)
    {
        $bucketType = 'X-Large Bucket';
    }
    else
    {
        $bucketType = 'Pallete';
    }


    
    //TAMANG
}

$height = "455";

$sql = "SELECT customerName, customerAlias FROM sales_customer WHERE customerId = ".$partsQueryResult['customerId']." ";
$getCustomer = $db->query($sql);
if($getCustomer->num_rows > 0)
{
	$getCustomerResult = $getCustomer->fetch_array();
}

//;cadcam_materialspecs;
$sql = "SELECT materialTypeId, metalType, metalThickness, metalLength, metalWidth, materialName FROM cadcam_materialspecs WHERE materialSpecId = ".$partsQueryResult['materialSpecId']." ";
$getMaterialSpecs = $db->query($sql);
if($getMaterialSpecs->num_rows > 0)
{
	$getMaterialSpecsResult = $getMaterialSpecs->fetch_array();
	$materialType = $getMaterialSpecsResult['metalType'];
	$sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$getMaterialSpecsResult['materialTypeId']." LIMIT 1";
	$queryMaterialType = $db->query($sql);
	if($queryMaterialType AND $queryMaterialType->num_rows > 0)
	{
		$resultMaterialType = $queryMaterialType->fetch_assoc();
		$materialType = $resultMaterialType['materialType'];
	}
}

$sql = "SELECT treatmentName FROM cadcam_treatmentprocess WHERE treatmentId = ".$partsQueryResult['treatmentId']." ";
$getTreatmentProcess = $db->query($sql);
if($getTreatmentProcess->num_rows > 0)
{
	$getTreatmentProcessResult = $getTreatmentProcess->fetch_array();
}

$sql = "SELECT processOrder, processCode, processSection, toolId, count, dataOne, dataTwo, dataThree, dataFour, dataFive, itemHandlingFlag FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder ASC ";
$getPartProcess = $db->query($sql);

if($partsQueryResult['PVC'] == 1)
{
	$pvc = 'PVC';
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo (displayText("2-C", "utf8", 0, 1)); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/<?php echo v; ?>/Common Data/Templates/Bootstrap/w3css/w3.css">
    <link rel="stylesheet" type="text/css" href="/<?php echo v; ?>/Common Data/Libraries/Javascript/Super Quick Table/datatables.min.css">
	<link rel="stylesheet" href="/<?php echo v; ?>/Common Data/Templates/Bootstrap/Bootstrap 3.3.7/css/bootstrap.css">
	<link rel="stylesheet" href="/<?php echo v; ?>/Common Data/Templates/Bootstrap/Font Awesome/css/font-awesome.css">
	<link rel="stylesheet" href="/<?php echo v; ?>/Common Data/Templates/Bootstrap/Bootstrap 3.3.7/Roboto Font/roboto.css">
	<script type="text/javascript" src="/<?php echo v; ?>/Common Data/Libraries/Javascript/Tiny Box/tinybox.js"></script>
	<link rel="stylesheet" href="/<?php echo v; ?>/Common Data/Libraries/Javascript/Tiny Box/stylebox.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
	<style>
        .dataTables_wrapper .dataTables_filter {
			position: absolute;
			text-align: right;
			visibility: hidden;
		}
        
        body
		{
			font-size: 11px;
			font-family: Roboto;
			margin:0px;
			padding:0px;
			background-color:whitesmoke;
		}

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #FFF;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 2px;
        }

        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            /* color: #818181; */
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            color: #f1f1f1;
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        #main {
            transition: margin-left .5s;
            /* padding: 16px; */
        }
        <?php
        if($_GET['actions'] != "change"){ $src = "../Common Data/Templates/images/edit1.png";?>
            .switch {
                display: none;
            }
        <?php }else{ $src = "../Common Data/Templates/images/close1.png"; }?>
	</style>
</head>
<body id="loading" class=''>
    <?php 
    $displayId = "L63";
    $version = "v2.0";
    $previousLink = "/".v."/2-C Parts Management Software/jazmin_product.php";
    createHeader($displayId, $version, $previousLink);
    // displayText($displayId, $conversionType='utf8', $viewType=0, $placeholder=0, $characterCase=0)
    ?>
    <div id="mySidebar" class="sidebar w3-border" style='margin-top:55px;'>
        <div class="container-fluid"> 
            <div class="row">
                <div class="col-md-9">
                    <ul class='list-inline'>
                        <li>
                            <select name='partStatus' id='partStatus' class='w3-input w3-border w3-pale-yellow'>
                                <option></option>
                                <?php 
                                $sql = "SELECT status FROM cadcam_parts WHERE partId = ".$_GET['partId'];
                                $queryStatus = $db->query($sql);
                                if($queryStatus AND $queryStatus->num_rows > 0)
                                {
                                    $resultStatus = $queryStatus->fetch_assoc();
                                    $partStatus = $resultStatus['status'];
                                }
                                $activeSelect = ($partStatus == 0) ? "selected" : "";
                                $inactiveSelect = ($partStatus == 1) ? "selected" : "";
                                $pendingSelect = ($partStatus == 2) ? "selected" : "";
                                $checkRevSelect = ($partStatus == 3) ? "selected" : "";

                                echo "<option ".$activeSelect." value=0>Active</option>";
                                echo "<option ".$inactiveSelect." value=1>Inactive</option>";
                                echo "<option ".$pendingSelect." value=2>Pending</option>";
                                echo "<option ".$checkRevSelect." value=3>For Check Rev.</option>";
                                ?>
                            </select>
                        </li>
                        <li>
                            <button class='w3-tiny w3-btn w3-round w3-blue' type ='button' id='buttonStatus'><?php echo displayText('L1054'); ?></button> <!-- Update -->
                        </li>
                        <li>
                            <a onclick="TINY.box.show({url:'anthony_updateForm.php?partId=<?php echo $_GET['partId']; if(isset($_GET['source']) AND $_GET['source'] == 'pending'){ echo '&source=pending'; }?>&patternId=<?php echo $_GET['patternId']; ?>',width:350,height:<?php echo ($height+($height/3)); ?>,opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJS()}})"><img src = '../Common Data/Templates/images/edit1.png' width = '25' height = '25'></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <div class="w3-right">
                        <ul class='list-inline'>
                            <li>
                                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class='fa fa-remove'></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class = 'table table-condensed table-bordered table-striped'>
                        <thead class='w3-indigo' style='text-transform:uppercase;'>
                            <th class = 'theadname' colspan = '4'><center><?php echo displayText('L64'); ?></center></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class = 'tname' colspan = '2' style="width:50%;"><?php echo displayText('L24'); ?></td>
                                <td class = 'tresult' colspan = '2' style="width:50%;"><?php echo $getCustomerResult['customerName']; ?></td>
                            </tr>
                            
                            <tr>
                                <td class = 'tname' colspan = '2'><?php echo displayText('L28'); ?></td>
                                <td class = 'tresult' colspan = '2'><?php echo $partsQueryResult['partNumber']; ?></td>
                            </tr>
                            
                            <tr>
                                <td class = 'tname' colspan = '2'><?php echo displayText('L30'); ?></td>
                                <td class = 'tresult' colspan = '2'><?php echo $partsQueryResult['partName']; ?>
<!--
                                    <a onclick="TINY.box.show({url:'rhay_updatePartName.php?partId=<?php echo $_GET['partId']; ?>',width:'250',height:'100',opacity:10,topsplit:6,animate:false,close:true})" href="#" rowId="<?php echo $partsQueryResult['partId']?>"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20'></a>
-->
                                    <img onclick="TINY.box.show({url:'rhay_updatePartName.php?partId=<?php echo $_GET['partId']; ?>',width:'250',height:'100',opacity:10,topsplit:6,animate:false,close:true})" src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20'>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class = 'tname' colspan = '2'><?php echo displayText('L1934'); ?></td>
                                <td class = 'tresult' colspan = '2'><?php echo $partsQueryResult['revisionId']; ?></td>
                            </tr>
                            <tr>
                                <td class = 'tname' colspan = '2'><?php echo displayText('L3446'); ?></td>
                                <td class = 'tresult' colspan = '2'><?php echo $partsQueryResult['partNote']; ?></td>
                            </tr>
                            
                            <tr>
                                <td class = 'tname' colspan = '2'><?php echo displayText('L57'); ?></td>
                                <td class = 'tresult' colspan = '2'><?php echo $materialType ." t" .$getMaterialSpecsResult['metalThickness'] ." ".$pvc; ?></td>
                            </tr>
                            
                            <tr>
                                <td class = 'tname' colspan = '2'><?php echo displayText('L65'); ?></td>
                                <td class = 'tresult' colspan = '2'>
                                <?php
                                //~ $sql = "SELECT materialSpecId FROM engineering_alternatematerial WHERE partId = ".$_GET['partId']." ";
                                //~ $getMaterialSpecId = $db->query($sql);
                                //~ while($getMaterialSpecIdResult = $getMaterialSpecId->fetch_array())
                                //~ {
                                    //~ $sql = "SELECT metalType FROM cadcam_materialspecs WHERE materialSpecId = ".$getMaterialSpecIdResult['materialSpecId']." ";
                                    //~ $getType = $db->query($sql);
                                    //~ $getTypeResult = $getType->fetch_array();
                                    
                                    //~ $metalType = $getTypeResult['metalType'].",";
                                    //~ echo $metalType;
                                //~ }
                                
                                //;cadcam_materialspecs;
                                $materialSpecIdArray = array();
                                $sql = "SELECT materialSpecId FROM engineering_alternatematerial WHERE partId = ".$_GET['partId']."";
                                $queryAlternateMaterial = $db->query($sql);
                                if($queryAlternateMaterial AND $queryAlternateMaterial->num_rows > 0)
                                {
                                    while($resultAlternateMaterial = $queryAlternateMaterial->fetch_assoc())
                                    {
                                        $materialSpecIdArray[] = $resultAlternateMaterial['materialSpecId'];
                                    }
                                }
                                
                                if(count($materialSpecIdArray) > 0)
                                {
                                    $sql = "SELECT materialSpecId, materialTypeId FROM cadcam_materialspecs WHERE materialSpecId IN(".$materialSpecIdArray[0].")";
                                    if(count($materialSpecIdArray) > 1)
                                    {
                                    $sql = "SELECT materialSpecId, materialTypeId FROM cadcam_materialspecs WHERE materialSpecId IN(".implode(",",$materialSpecIdArray).")";
                                    }
                                    $queryMaterialSpecs = $db->query($sql);
                                    if($queryMaterialSpecs AND $queryMaterialSpecs->num_rows > 0)
                                    {
                                        while($resultMaterialSpecs = $queryMaterialSpecs->fetch_assoc())
                                        {
                                            $materialSpecId = $resultMaterialSpecs['materialSpecId'];
                                            $materialTypeId = $resultMaterialSpecs['materialTypeId'];
                                            
                                            $materialType = '';
                                            $sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$materialTypeId." LIMIT 1";
                                            $queryMaterialType = $db->query($sql);
                                            if($queryMaterialType AND $queryMaterialType->num_rows > 0)
                                            {
                                                $resultMaterialType = $queryMaterialType->fetch_assoc();
                                                $materialType = $resultMaterialType['materialType'];
                                            }
                                            $materialTypeArray[] = $materialType;
                                        }
                                    }
                                    
                                    if(count($materialTypeArray)>1)
                                    {
                                        echo implode(",",$materialTypeArray);
                                    }
                                    else
                                    {
                                        echo $materialTypeArray[0];
                                    }
                                    //echo implode(",",$materialTypeArray);
                                    //echo implode(",",$materialSpecIdArray);
                                }
                                //;cadcam_materialspecs;
                                //~ if($_SESSION['idNumber'] == '0063' or $_SESSION['idNumber'] == '0358' or $_SESSION['idNumber'] == '0467' or $_SESSION['idNumber'] == '0672')
                                if($_SESSION['idNumber'] == '0063' or $_SESSION['idNumber'] == 'J074')//2019-10-16 by mam rose co sir mar // addkimura 2022-03-25
                                {
                                ?>	
                                    <a onclick="TINY.box.show({url:'anthony_addAlternateMaterial.php?partId=<?php echo $_GET['partId']; ?>&thickness=<?php echo $getMaterialSpecsResult['metalThickness']; ?>&insert=1&patternId=<?php echo $_GET['patternId']; ?>',width:'',height:'',opacity:10,topsplit:6,animate:false,close:true})"><img src = '../Common Data/Templates/images/add1.png' width = '20' height = '20' align = 'right'></a>
                                    <a onclick="TINY.box.show({url:'rose_deleteAlternateMaterial.php?partId=<?php echo $_GET['partId']; ?>&thickness=<?php echo $getMaterialSpecsResult['metalThickness']; ?>&insert=1&patternId=<?php echo $_GET['patternId']; ?>',width:'',height:'',opacity:10,topsplit:6,animate:false,close:true})"><img src = '../Common Data/Templates/images/trash1.png' width = '20' height = '20' align = 'right'></a>
                                <?php
                                }
                                ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class = 'tname' colspan = '2'><?php echo displayText('L66'); ?></td>
                                <td class = 'tresult' colspan = '2'><?php echo $getMaterialSpecsResult['materialName']; ?></td>
                            </tr>
                            
                            <tr>
                                <td class = 'tname' colspan = '2'><?php echo displayText('L56'); ?></td>
                                <td class = 'tresult' colspan = '2'><?php echo $partsQueryResult['materialSpecDetail']; ?></td>
                            </tr>
                            
                            <tr>
                                <td class = 'tname' colspan = '2'><?php echo displayText('L67'); ?></td>
                                <td class = 'tresult' colspan = '2'><?php echo $getTreatmentProcessResult['treatmentName']; ?></td>
                            </tr>
                            
                            <!--
                            <tr>
                                <td class = 'tname'><?php echo displayText('L68'); ?></td>
                                <td class = 'tresult'><?php echo $getMaterialSpecsResult['metalLength']; ?></td>
                            </tr>
                            
                            <tr>
                                <td class = 'tname'><?php echo displayText('L69'); ?></td>
                                <td class = 'tresult'><?php echo $getMaterialSpecsResult['metalWidth']; ?></td>
                            </tr>
                            -->
                            
                            <!--TAMANG -->
                            <tr>
                                <td class = 'tname' style="width:25%;"><?php echo displayText('L70'); ?>&emsp;&emsp;<font color=green style="float:right;">mm</font></td>
                                <td class = 'tresult' style="width:25%;"><?php echo $partsQueryResult['x']; ?></td>
                                <td class = 'tname' style="width:25%;"><?php echo displayText('L71'); ?>&emsp;&emsp;<font color=green style="float:right;">mm</font></td>
                                <td class = 'tresult' style="width:25%;"><?php echo $partsQueryResult['y']; ?></td>
                            </tr>
                        
                        
                            
                            <tr>
                                <td class = 'tname' style="width:25%;"><?php echo displayText('L72'); ?>&emsp;&emsp;&emsp;<font color=green style="float:right;">g</font></td>
                                <td class = 'tresult' style="width:25%;"><?php echo $partsQueryResult['itemWeight']; ?></td>
                                <td class = 'tname' style="width:25%;"><?php echo displayText('L73'); ?>&emsp;&emsp;&emsp;<font color=green style="float:right;">dm2</font></td>
                                <td class = 'tresult' style="width:25%;"><?php echo $partsQueryResult['itemArea']; ?></td>
                            </tr>
                            

                            
                            <tr>
                                <td class = 'tname' style="width:25%;"><?php echo displayText('L74'); ?>&emsp;&emsp;&emsp;<font color=green style="float:right;">mm</font></td>
                                <td class = 'tresult' style="width:25%;"><?php echo $partsQueryResult['itemLength']; ?></td>
                                <td class = 'tname' style="width:25%;"><?php echo displayText('L75'); ?>&emsp;&emsp;&emsp;<font color=green style="float:right;">mm</font></td>
                                <td class = 'tresult' style="width:25%;"><?php echo $partsQueryResult['itemWidth']; ?></td>
                            </tr>
                            

                            
                            <tr>
                                <td class = 'tname' style="width:25%;"><?php echo displayText('L76'); ?>&emsp;&emsp;&emsp;<font color=green style="float:right;">mm</font></td>
                                <td class = 'tresult' style="width:25%;"><?php echo $partsQueryResult['itemHeight']; ?></td>
                                <td class = 'tname' style="width:25%;">Bucket Size</td>
                                <td class = 'tresult' style="width:25%;"><?php echo $bucketType; ?></td>
                            </tr>
                            <tr>
								<?php
								$bendRobot="NO";
								$weldRobot="NO";
								if($partsQueryResult['bendRobot']==1){$bendRobot="YES";}
								if($partsQueryResult['weldRobot']==1){$weldRobot="YES";}
								?>
                                <td colspan=2 class = 'tname'><?php echo displayText('L4558'); ?>
								&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<font color=green><?php echo displayText('L4556').":"; ?></font>
								<!--<input type="checkbox" id="BendRobot" name="BendRobot" value="1" disabled checked>-->
								<?php echo $bendRobot; ?>
								&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<font color=green><?php echo displayText('L4557').":"; ?></font>
								<!--<input type="checkbox" id="WeldRobot" name="WeldRobot" value="1" disabled readonly>-->
								<?php echo $weldRobot; ?>
								</td>               
                            </tr>



                        </tbody>
                    </table>
                    <!-- TAMANG -->
                        <form action="" method="post" id="formUpdatePartsComment"></form>
                        <tr>
                            <label>Parts Comment</label><br>
                            <td><textarea name="partsComment" id="" cols="70" rows="2" style="resize:none;" form="formUpdatePartsComment" readonly><?php echo $partsQueryResult['partsComment'] ;?></textarea><button type="button" name="btnUpdatePartsComment" class="w3-btn btn w3-round w3-indigo" style="margin-top:-30px;margin-left:10px;width:70px;" onclick="partsComment()">Edit</button></td>
                        </tr>
                    <!-- TAMANG -->
                </div>
            </div>
            <table>
                <tr>
                    <td class='w3-center'>
                        <?php
                        $locationMasterFolder = "../../Document Management System/Master Folder/MAIN_".$_GET['partId'].".pdf";
                        $locationArktechFolder = "../../Document Management System/Arktech Folder/ARK_".$_GET['partId'].".pdf";
                        echo "<img src = '../Common Data/Templates/images/drawingIcon.png'  width = '50' height = '50' id = 'openDrawing'>";
                        ?>				
                        <label><?php echo displayText('L77'); ?></label>
                    </td>
                    <td class='w3-center'>
                        <a href="#" onclick= "window.open('rose_tree.php?product=<?php echo $_GET['partId']; ?>','tree','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><img src = "../Common Data/Templates/buttons/inventoryIcon.png"  width = "50" height = "50"></a>
                        <label><?php echo displayText('L78'); ?></label>
                    </td>
                    <td class='w3-center'>
                        <?php
                        if($_GET['country']=='2')
                        {
                        ?>
                            <a href="#" onclick= "window.open('../prs/partPRS.php?partId=<?php echo $_GET['partId']; ?>&accessory=<?php //echo $_GET['accessory']; ?>','processs','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><img src = "../Common Data/Templates/buttons/printerIcon.png"  width = "50" height = "50"></a>
                        <?php
                        }
                        else				
                        {
                        ?>
                            <a href="#" onclick= "window.open('anthony_productProcessConverter.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>','processs','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><img src = "../Common Data/Templates/buttons/printerIcon.png"  width = "50" height = "50"></a>
                        <?php
                        }
                        ?>
                        <label><?php echo displayText('L85'); ?></label>
                    </td>
                    <?php
                    if($_GET['country']=='1')
                    {
                    ?>
                        <td class='w3-center'>
                            <a href="#" onclick= "window.open('rose_partProcessConverter.php?product=<?php echo $_GET['partId']; ?>&accessory=<?php //echo $_GET['accessory']; ?>&patternId=<?php echo $_GET['patternId']; ?>','processs','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><img src = "../Common Data/Templates/buttons/printerIcon.png"  width = "50" height = "50"></a>
                            <label><?php echo displayText('L86'); ?></label>
                        </td>
                    <?php
                    }
                    
                    //~ if($_GET['country']=='1' and ($_SESSION['idNumber']=='0276' or $_SESSION['idNumber']=='OJT 2018-004'))
                    /*if($_GET['country']=='1' and $_SESSION['idNumber']=='*0276')
                    {
                    ?>
                    <!-- ------------------------- Commented By Ace As Commanded By Shachou With Sir Mario On August 2 Because All Items Must Pass Through Sheetworks ------------ -->
                    <td class='w3-center'>
                        <a onclick="TINY.box.show({url:'anthony_checkRevisionForm.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:370,height:70,opacity:10,topsplit:6,animate:false,close:true})"><img src = "../Common Data/Templates/buttons/materialReturnIcon.png"  width = "50" height = "50"></a>
                        <label><?php echo displayText('L79'); ?></label>
                    </td>
                    
                    <!-- ------------------------- Commented By Ace As Commanded By Shachou With Sir Mario On August 2 Because All Items Must Pass Through Sheetworks ------------ -->
                    <td class='w3-center'>
                        <a onclick="TINY.box.show({url:'anthony_checkPartNumberRevisionForm.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:370,height:140,opacity:10,topsplit:6,animate:false,close:true})"><img src = "../Common Data/Templates/buttons/attendance.png"  width = "50" height = "50"></a>
                        <label><?php echo displayText('L83'); ?></label>
                    </td>
                    
                    <!-- ------------------------- Commented By Ace As Commanded By Shachou With Sir Mario On August 2 Because All Items Must Pass Through Sheetworks ------------ -->
                    <td class='w3-center'>
                        <a onclick="TINY.box.show({url:'anthony_cloneToThisPart.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:200,height:60,opacity:10,topsplit:6,animate:false,close:true})"><img src = "../Common Data/Templates/buttons/attendance.png"  width = "50" height = "50" title = "Clone Old Part to this Part"></a>
                        <label><?php echo displayText('L84'); ?></label>
                    </td>
                    <?php
                    }*/
                    ?>
                    <td class='w3-center'>
                        <a href="#" onclick= "window.open('paul_partlog.php?product=<?php echo $_GET['partId']; ?>','logss','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><img src = "../Common Data/Templates/buttons/calendarIcon.png"  width = "50" height = "50"></a>
                        <label><?php echo displayText('L80'); ?></label>
                    </td>
                    
                    <td class='w3-center'>
                        <a href="#" onclick= "window.open('anthony_programLogSummary.php?partId=<?php echo $_GET['partId']; ?>','logss','screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><img src = "../Common Data/Templates/buttons/calendarIcon.png"  width = "50" height = "50"></a>
                        <label><?php echo displayText('L81'); ?></label>
                    </td>
                    
                    <?php
                    if($_GET['country'] == '2')
                    { 
                    ?>
                        <td class='w3-center'>
                            <a href="#" onclick= "window.open('checkDrawingStatus.php?partId=<?php echo $_GET['partId']; ?>','drawingStat','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><img src = '../Common Data/Templates/images/drawingIcon.png'  width = '50' height = '50'></a>
                            <label><?php echo displayText('L82'); ?></label>
                        </td>
                    <?php 
                    } 
                    ?>
                    
                    <td class='w3-center'>
                    <?php
                    if(isset($_GET['duplicateFlag']) AND $_GET['duplicateFlag'] == 1)
                    {
                        echo "<p style = 'color:red; font-size:32px; text-align:center'>Revision is already exist!</p>";
                    }
                    else if(isset($_GET['duplicateFlag']) AND $_GET['duplicateFlag'] == 2)
                    {
                        echo "<p style = 'color:red; font-size:32px; text-align:center'>Part Number and Revision are already exist!</p>";
                    }
                    ?>
                    </td>
                </tr>
            </table>
			<?php
				if($_GET['country']=='1' and $_SESSION['idNumber']=='0276')
				{
					?>
					<table>
						<tr>
							<!-- ------------------------- Commented By Ace As Commanded By Shachou With Sir Mario On August 2 Because All Items Must Pass Through Sheetworks ------------ -->
							<td class='w3-center'>
								<a onclick="TINY.box.show({url:'anthony_checkRevisionForm.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:370,height:70,opacity:10,topsplit:6,animate:false,close:true})"><img src = "../Common Data/Templates/buttons/materialReturnIcon.png"  width = "50" height = "50"></a>
								<label><?php echo displayText('L79'); ?></label>
							</td>
							
							<!-- ------------------------- Commented By Ace As Commanded By Shachou With Sir Mario On August 2 Because All Items Must Pass Through Sheetworks ------------ -->
							<td class='w3-center'>
								<a onclick="TINY.box.show({url:'anthony_checkPartNumberRevisionForm.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:370,height:140,opacity:10,topsplit:6,animate:false,close:true})"><img src = "../Common Data/Templates/buttons/attendance.png"  width = "50" height = "50"></a>
								<label><?php echo displayText('L83'); ?></label>
							</td>
							
							<!-- ------------------------- Commented By Ace As Commanded By Shachou With Sir Mario On August 2 Because All Items Must Pass Through Sheetworks ------------ -->
							<td class='w3-center'>
								<a onclick="TINY.box.show({url:'anthony_cloneToThisPart.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:200,height:60,opacity:10,topsplit:6,animate:false,close:true})"><img src = "../Common Data/Templates/buttons/attendance.png"  width = "50" height = "50" title = "Clone Old Part to this Part"></a>
								<label><?php echo displayText('L84'); ?></label>
							</td>							
						</tr>
					</table>
					<?php
				}
			?>            
        </div>
    </div>
    <div class="container-fluid" id='main'> 
        <div class="row w3-padding-top">
            <div class="col-md-1 w3-center">
                <button class="openbtn w3-btn w3-round w3-blue" onclick="openNav()">☰</button>  
            </div>
            <div class="col-md-5">
				<div class="col-md-6">
					<select name = 'patternId' id = 'patternId' class='w3-input w3-border w3-pale-yellow'>
						<option value = '0' <?php if($_GET['patternId'] == 0){ echo "selected"; } ?>>Pattern 0 <?php if($partsQueryResult['patternId']==0) echo "(Default)";?></option>
						<option value = '1' <?php if($_GET['patternId'] == 1){ echo "selected"; } ?>>Pattern 1 <?php if($partsQueryResult['patternId']==1) echo "(Default)";?></option>
						<option value = '2' <?php if($_GET['patternId'] == 2){ echo "selected"; } ?>>Pattern 2 <?php if($partsQueryResult['patternId']==2) echo "(Default)";?></option>
					</select>
				</div>
				<div class="col-md-6">
					<?php
						if($_GET['patternId'] != $partsQueryResult['patternId'])
						{
							?>
							<button id='defaultPatternCheckId' class='w3-input w3-lime w3-samall'>Set this as Default Pattern</button>
							<?php
						} 
					?>
				</div>
            </div>  
            <div class="col-md-6">
				<a href="../2-8 Standard Time Input SoftwareV2/justin_standardTimeInputForm.php?editproduct=<?php echo $_GET['partId']; echo $pendingLocation; ?>&patternId=<?php echo $_GET['patternId']; ?>"><center><?php echo displayText('L256');//Standard Time?></center></a>
                <div class="w3-right">
                    <a onclick="openTinyBox('285','70','anthony_addProcessPattern.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>')"><img src = '../Common Data/Templates/images/edit1.png' width = '25' height = '25' style = 'float: right; margin-right: 18px;'></a>
                    <a onclick="openTinyBox('','','anthony_clone.php?partId=<?php echo $_GET['partId']; ?>&customerId=<?php echo $partsQueryResult['customerId']; ?>&patternId=<?php echo $_GET['patternId']; ?>')"><img src = "../Common Data/Templates/buttons/attendance.png"  width = "25" height = "25" style = "float: right; margin-right: 10px;"></a>
                </div>
            </div>
        </div>
        <div class="row w3-padding-top">
            <div class="col-md-12">
                <table class='table table-condensed table-bordered table-striped'>
                    <thead class='' style='text-transform:uppercase;'>
                        <?php
                        $sql = "SELECT processCode FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." ";
                        $getPartProcessColor = $db->query($sql);
                        $processColor = ($getPartProcessColor->num_rows > 0) ? "w3-light-green" : "w3-light-grey";
                        echo "<th class='w3-center ".$processColor."'><a class='' style='text-decoration:none;' href = 'anthony_editProduct.php?partId=".$_GET['partId']."&src=process".$pendingLocation."&patternId=".$_GET['patternId']."'>".displayText('L59')."</a></th>";
                        
                        $sql = "SELECT childId, quantity FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 1 ";
                        $getSubPartsColor = $db->query($sql);
                        $subpartsColor = ($getSubPartsColor->num_rows > 0) ? "w3-light-green" : "w3-light-grey";
                        echo "<th class='w3-center ".$subpartsColor."'><a class='' style='text-decoration:none;' href = 'anthony_editProduct.php?partId=".$_GET['partId']."&src=subparts".$pendingLocation."&patternId=".$_GET['patternId']."'>".displayText('L88')."</a></th>";
                        
                        $sql = "SELECT childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 2 ";
                        $getChildIdColor = $db->query($sql);
                        $childColor = ($getChildIdColor->num_rows > 0) ? "w3-light-green" : "w3-light-grey";
                        echo "<th class='w3-center ".$childColor."'><a class='' style='text-decoration:none;' href = 'anthony_editProduct.php?partId=".$_GET['partId']."&src=accessories".$pendingLocation."&patternId=".$_GET['patternId']."'>".displayText('L471')."</a></th>";
                                            
                        $sql = "SELECT subpartId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 8 ";
                        $getSuppliesIdColor = $db->query($sql);
                        $suppliesColor = ($getSuppliesIdColor->num_rows > 0) ? "w3-light-green" : "w3-light-grey";
                        echo "<th class='w3-center ".$suppliesColor."'><a class='' style='text-decoration:none;' href = 'anthony_editProduct.php?partId=".$_GET['partId']."&src=supplies".$pendingLocation."&patternId=".$_GET['patternId']."'>".displayText('L470')."</a></th>";
                                            
                        $sql = "SELECT parentId FROM cadcam_subparts WHERE childId = ".$_GET['partId']." AND identifier = 1 ";
                        $getParentIdColor = $db->query($sql);
                        $parentColor = ($getParentIdColor->num_rows > 0) ? "w3-light-green" : "w3-light-grey";
                        echo "<th class='w3-center ".$parentColor."'><a class='' style='text-decoration:none;' href = 'anthony_editProduct.php?partId=".$_GET['partId']."&src=mainparts".$pendingLocation."&patternId=".$_GET['patternId']."'>".displayText('L90')."</a></th>";
                        
                        $sql = "SELECT subconId, processCode FROM cadcam_subconlist WHERE partId = ".$_GET['partId']." ";
                        $getSubconListColor = $db->query($sql);
                        $subconColor = ($getSubconListColor->num_rows > 0) ? "w3-light-green" : "w3-light-grey";
                        echo "<th class='w3-center ".$subconColor."'><a class='' style='text-decoration:none;' href = 'anthony_editProduct.php?partId=".$_GET['partId']."&src=subcon".$pendingLocation."&patternId=".$_GET['patternId']."'>".displayText('L91')."</a></th>";
                        
                        $sql = "SELECT partId FROM engineering_programs WHERE partId = ".$_GET['partId']." ";
                        $getProgramsColor = $db->query($sql);
                        $programColor = ($getProgramsColor->num_rows > 0 OR $partsQueryResult['laserParameterId']>0) ? "w3-light-green" : "w3-light-grey";
                        echo "<th class='w3-center ".$programColor."'><a class='' style='text-decoration:none;' href = 'anthony_editProduct.php?partId=".$_GET['partId']."&src=programs".$pendingLocation."&patternId=".$_GET['patternId']."'>".displayText('L92')."</a></th>";

                        $sql = "SELECT partId FROM engineering_partstandard WHERE partId = ".$_GET['partId']." ";
                        $getSpecificationColor = $db->query($sql);
                        $specificationColor = ($getSpecificationColor->num_rows > 0) ? "w3-light-green" : "w3-light-grey";
                        echo "<th class='w3-center ".$specificationColor."'><a class='' style='text-decoration:none;' href = 'anthony_editProduct.php?partId=".$_GET['partId']."&src=specifications".$pendingLocation."&patternId=".$_GET['patternId']."'>".displayText('L1360')."</a></th>";
                        
                        $sql = "SELECT toollistId FROM engineering_toollist WHERE partId = ".$_GET['partId']." ";	
                        $getToolListColor = $db->query($sql);
                        $toolColor = ($getToolListColor->num_rows > 0) ? "w3-light-green" : "w3-light-grey";
                        echo "<th class='w3-center ".$toolColor."'><a onclick = \"window.open('../toollistTPP/toolistInputform.php?partId=".$_GET['partId']."&TOOLLIST=Make+TOOL+LIST&programId=".$_GET['programId']."', '_blank', 'width=1150,height=650,status=yes,resizable=yes,scrollbars=yes')\">".displayText('L93')."</a></th>";
                        
                        ?>
                        <th><a onclick="TINY.box.show({url:'rose_treediagram.php?product=<?php echo $_GET['partId']; ?>',width:'600',height:'',opacity:10,topsplit:6,animate:false,close:true})"><center><?php echo displayText('L136'); ?></center></a></th>
                    </thead>
                </table>
                <?php
                if($_GET['src'] == "process")
                {
					echo "<input type = 'button' name = 'delete' id = 'delete' value = 'Delete' style = 'float: left; display: none;' class = 'anthony_submit'>";
                    echo "<table class='table table-condensed table-bordered table-striped'>";
                        echo "<thead class='w3-indigo' style='text-transform:uppercase;'>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L58')."<img src = '".$src."' id = 'first' width = '25' height = '25' align = 'right'></th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L59')."</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L1367')."</th>";//L1367 Sub process
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L61')."</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L35')."</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L94')."</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>NEW TOOL</th>";//TAMANG
                            echo "<th style='vertical-align:middle;' class='w3-center'>MACHINE</th>";//TAMANG
                            echo "<th style='vertical-align:middle;' class='w3-center'>JIG</th>";//TAMANG
                            echo "<th style='vertical-align:middle;' class='w3-center'>1</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>2</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>3</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>4</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>5</th>";
                            // echo "<th style='vertical-align:middle;' class='w3-center'>6</th>";
                            // echo "<th style='vertical-align:middle;' class='w3-center'>7</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L1368')."</th>";//L1368 Standard
                            echo "<th style='vertical-align:middle;' class='w3-center'>";
                                echo "<a onclick=\"openTinyBox('','600','anthony_addProcessDetailForm.php?partId=".$_GET['partId']."&insert=1&patternId=".$_GET['patternId']."')\"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20'></a>";
                            echo "</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L1441')."</th>";//L1441 WI
                        echo "</thead>";
                        echo "<tbody>";
                            $sql = "SELECT processOrder, processCode, processSection, toolId, count, dataOne, dataTwo, dataThree, dataFour, dataFive, dataSix, dataSeven, itemHandlingFlag FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder ASC ";
                            $getPartProcess = $db->query($sql);
                            if($getPartProcess AND $getPartProcess->num_rows > 0)
                            {
                                while($getPartProcessResult = $getPartProcess->fetch_array())
                                {
                                    //EDITED BY TAMANG 2022-03-23
                                    $dataOne =  ($getPartProcessResult['dataOne'] != "") ? $getPartProcessResult['dataOne'] : "&nbsp;";
                                    $dataTwo = ($getPartProcessResult['dataTwo'] != "") ? $getPartProcessResult['dataTwo'] : "&nbsp;";
                                    $dataThree = ($getPartProcessResult['dataThree'] != "") ? $getPartProcessResult['dataThree'] : "&nbsp;";
                                    $dataFour = ($getPartProcessResult['dataFour'] != "") ? $getPartProcessResult['dataFour'] : "&nbsp;";
                                    $dataFive = ($getPartProcessResult['dataFive'] != "") ? $getPartProcessResult['dataFive'] : "&nbsp;";
                                    $dataSix = ($getPartProcessResult['dataSix'] != "") ? $getPartProcessResult['dataSix'] : "&nbsp;";
                                    $dataSeven = ($getPartProcessResult['dataSeven'] != "") ? $getPartProcessResult['dataSeven'] : "&nbsp;";
                                    //EDIT END 2022-03-23
                                    $sql = "SELECT processName, alternateProcessName FROM cadcam_process WHERE processCode = ".$getPartProcessResult['processCode']." ";
                                    $getProcessCode = $db->query($sql);
                                    $getProcessCodeResult = $getProcessCode->fetch_array();
                                    
                                    // -------------------------------- Gerald Code ------------------------------------------------- //
                                    $toolName="";
                                    if($getPartProcessResult['toolId']>0)
                                    {
                                    $sql = "SELECT toolName FROM cadcam_processtools WHERE toolId = ".$getPartProcessResult['toolId']." ";
                                    $getToolCode = $db->query($sql);
                                    $getToolCodeResult = $getToolCode->fetch_array();
                                    $toolName=$getToolCodeResult['toolName'];
                                    }
                                    
                                    $toolIdArray = array();
                                    $sql = "SELECT toolId FROM cadcam_processtoolsdetails WHERE partId = ".$_GET['partId']." AND processCode = ".$getPartProcessResult['processCode']." ";
                                    $queryTools = $db->query($sql);
                                    if($queryTools->num_rows > 0)
                                    {
                                        while($resultTools = $queryTools->fetch_array())
                                        {
                                            $toolIdArray[] = $resultTools['toolId'];
                                        }
                                    }
                                    
                                    $toolNames = '';
                                    $toolNameArray = array();
                                    $sql = "SELECT toolName FROM cadcam_processtools WHERE toolId IN(".implode(",",$toolIdArray).")";
                                    $queryTools = $db->query($sql);
                                    if($queryTools->num_rows > 0)
                                    {
                                        while($resultTools = $queryTools->fetch_array())
                                        {
                                            $toolNameArray[] = $resultTools['toolName'];
                                        }
                                    }
                                    if(count($toolNameArray) > 0)
                                    {
                                        $toolNames = implode("<br>",$toolNameArray);
                                    }
                                    $trigger = "onclick=\" openTinyBox('','','gerald_tinyBoxTools.php?type=viewTools','partId=".$_GET['partId']."&processCode=".$getPartProcessResult['processCode']."'); \"";
                                    //$toolNames .= "<br><span style='cursor:pointer;color:blue;font-size:10px;' ".$trigger.">Add Tool</span>";
                                    // ------------------------------ End Gerald Code ------------------------------------------------- //
                                    
                                    $sql = "SELECT sectionName FROM ppic_section WHERE sectionId = ".$getPartProcessResult['processSection']." ";
                                    $getSectionName = $db->query($sql);
                                    $getSectionNameResult = $getSectionName->fetch_array();
                                                                    
                                    $up = "";
                                    $down = "";
                                    $sql = "SELECT MIN(processOrder) AS minOrder FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." ORDER BY processOrder ASC ";
                                    $getMinOrder = $db->query($sql);
                                    $getMinOrderResult = $getMinOrder->fetch_array();
                                    if($getMinOrderResult['minOrder'] != $getPartProcessResult['processOrder']){
                                        $up = "<a href = 'anthony_changeProcessOrderSQL.php?id=".$getPartProcessResult['count']."&action=up&patternId=".$_GET['patternId']."'><img src = '../Common Data/Templates/buttons/upIcon.png' class = 'switch' width = '20' height = '20' align = 'right'></a>";
                                    }
                                    
                                    $sql = "SELECT MAX(processOrder) AS maxOrder FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." ORDER BY processOrder ASC ";
                                    $getMaxOrder = $db->query($sql);
                                    $getMaxOrderResult = $getMaxOrder->fetch_array();
                                    if($getMaxOrderResult['maxOrder'] != $getPartProcessResult['processOrder']){
                                        $down = "<a href = 'anthony_changeProcessOrderSQL.php?id=".$getPartProcessResult['count']."&action=down&patternId=".$_GET['patternId']."'><img src = '../Common Data/Templates/buttons/downIcon.png' class = 'switch' width = '20' height = '20' align = 'right'></a>";
                                    }
                                    
                                    echo "<tr>";
                                        echo "<td>";
                                            echo "<input type = 'checkbox' name = 'anthony[]' style = 'float: right;' value = '".$getPartProcessResult['count']."' class = 'checkBox'>".$getPartProcessResult['processOrder']." ".$down." ".$up;
                                            if($_SESSION['idNumber'] == '0276' or $_SESSION['idNumber'] == '0467' or $_SESSION['idNumber'] =='0727'  or $_SESSION['idNumber'] == '0063' or $_SESSION['idNumber'] == '0412')// diane
                                            {

                                            }
                                        echo "</td>";
                                        if ($languageFlag == 1) {
                                        	echo "<td class = 'tresult'>".$getProcessCodeResult['processName']."</td>";
                                        }
                                        else
                                        {
                                        	echo "<td class = 'tresult'>".$getProcessCodeResult['alternateProcessName']."</td>";
                                        }
                                        $subProcessOrder = 0;
                                        $subProcessName = $editSubProcessButton= "";
                                        $subProcessCodeArray = array();
                                        $subProcessNameArray = array();
                                        $sql = "SELECT processCode, processOrder FROM engineering_partsubprocess WHERE partId = ".$_GET['partId']." AND mainProcessCode = ".$getPartProcessResult['processCode']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder";
                                        $queryPartSubProcess = $db->query($sql);
                                        if($queryPartSubProcess AND $queryPartSubProcess->num_rows > 0)
                                        {
                                            while($resultPartSubProcess = $queryPartSubProcess->fetch_assoc())
                                            {
                                                //~ $subProcessCodeArray[] = $resultPartSubProcess['processCode'];
                                                $subProcessOrder = $resultPartSubProcess['processOrder'];
                                                
                                                //~ $sql = "SELECT processName FROM cadcam_process WHERE processCode IN(".implode(",",$subProcessCodeArray).")";
                                                $sql = "SELECT processName, alternateProcessName FROM cadcam_process WHERE processCode = ".$resultPartSubProcess['processCode']." LIMIT 1";
                                                $queryProcess = $db->query($sql);
                                                if($queryProcess AND $queryProcess->num_rows > 0)
                                                {
                                                    while($resultProcess = $queryProcess->fetch_assoc())
                                                    {
                                                        $buttonButton = "<a onclick=\"openTinyBox('250','300','ace_addNotesForm.php?partId=".$_GET['partId']."&processCode=".$getPartProcessResult['processCode']."&patternId=".$_GET['patternId']."')\"><img src = '../Common Data/Templates/images/add1.png' width = '15' height = '15' align='right' title='Add Note'></a>";
                                                        $sql = "SELECT patternId, remarks as processDetail FROM cadcam_partprocessnote WHERE processCode = ".$resultPartSubProcess['processCode']." and partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." LIMIT 1";
                                                        $processDetailQuery=$db->query($sql);
                                                        if($processDetailQuery AND $processDetailQuery->num_rows > 0)
                                                        {
                                                            $buttonButton .= "<a onclick=\"TINY.box.show({url:'ace_updateNotesForm.php?partId=".$_GET['partId']."&processCode=".$resultPartSubProcess['processCode']."&patternId=".$_GET['patternId']."',width:'280',height:'',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJSedit()}})\"><img src = '../Common Data/Templates/images/edit1.png' width = '15' height = '15' align='right' title='Edit Note'></a>";
                                                        }
                                                        if ($languageFlag == 1) 
                                                        {
                                                        	$subProcessNameArray[] = $resultProcess['processName'].$buttonButton;
                                                        }
                                                        else
                                                        {
                                                        	$subProcessNameArray[] = $resultProcess['alternateProcessName'].$buttonButton;
                                                        }
                                                        
                                                    }
                                                }
                                            }

                                            $subProcessName = implode("<br>",$subProcessNameArray);
                                            
                                            $editSubProcessButton = "<img onclick=\" openTinyBox('300','','".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."&src=".$_GET['src']."','tinyBoxType=editSubProcess&mainProcessCode=".$getPartProcessResult['processCode']."'); \" src='../Common Data/Templates/images/edit1.png' height='20' style='float:right'>";
                                        }
                                        
                                        $addSubProcessButton = "";
                                        $addSubProcessButton = "<img onclick=\" openTinyBox('200','','".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."&src=".$_GET['src']."','tinyBoxType=addSubProcess&mainProcessCode=".$getPartProcessResult['processCode']."&subProcessOrder=".$subProcessOrder."'); \" src='../Common Data/Templates/images/add1.png' height='20' style='float:right'>";
                                        
                                        echo "<td class = 'tresult'>".$subProcessName;
                                            echo "<hr><div>".$editSubProcessButton.$addSubProcessButton."</div>";
                                        echo "</td>";
                                        echo "<td class = 'tresult'>".$getSectionNameResult['sectionName']."</td>";
                                        $notesCounter = 0;
                                        echo "<td class = 'tresult'>";
                                            if($getPartProcessResult['dataOne'] != '')
                                                echo "<span style = 'font-size:12px;'>V-Size=".$getPartProcessResult['dataOne']."</span>";
                                                
                                            if($getPartProcessResult['dataTwo'] != '')
                                                echo "<br><span style = 'font-size:12px;'>PunchR=".$getPartProcessResult['dataTwo']."</span>";
                                                
                                            if($getPartProcessResult['dataThree'] != '')
                                                echo "<br><span style = 'font-size:12px;'>BD=".$getPartProcessResult['dataThree']."</span>";	
                                                
                                            if($getPartProcessResult['dataFour'] != '')
                                                echo "<br><span style = 'font-size:12px;'>SYheight=".$getPartProcessResult['dataFour']."</span>";	
                                                
                                            if($getPartProcessResult['dataFive'] != '')
                                                echo "<br><span style = 'font-size:12px;'>SYDistance=".$getPartProcessResult['dataFive']."</span>";
                                            
                                            echo "<ul style='list-style-type:square'>";	
                                                $processCodeTappingArray = array (122,123,124);									
                                                $sql = "SELECT noteId,patternId, remarks as processDetail, noteDetails, noteMultiplier FROM cadcam_partprocessnote WHERE processCode = ".$getPartProcessResult['processCode']." and partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." ORDER BY noteId";
                                                $processDetailQuery=$db->query($sql);
                                                while($processDetailQueryResult = $processDetailQuery->fetch_assoc())
                                                {
                                                    if(in_array($getPartProcessResult['processCode'], $processCodeTappingArray))
                                                    {
                                                        $tapSize = $tapCount = "";
                                                        if($processDetailQueryResult['noteDetails'] > "") $tapSize = "Tap Size : ".$processDetailQueryResult['noteDetails'];
                                                        if($processDetailQueryResult['noteMultiplier'] > 0) $tapCount = "Tap Count : ".$processDetailQueryResult['noteMultiplier'];

                                                        echo "<li>".$processDetailQueryResult['processDetail']." ".$tapSize." ".$tapCount."</li>";
                                                    }
                                                    else
                                                    {
                                                        echo "<li>".$processDetailQueryResult['processDetail']."</li>";
                                                    }
                                                    $notesCounter++;
                                                }
                                            echo "</ul>";
                                            ?>
                                                <a onclick="openTinyBox('250','300','ace_addNotesForm.php?partId=<?php echo $_GET['partId'];?>&processCode=<?php echo $getPartProcessResult['processCode']; ?>&patternId=<?php echo $_GET['patternId']; ?>')"><img src = '../Common Data/Templates/images/add1.png' width = '20' height = '20' align='right'></a>
                                            <?php										
                                            if($notesCounter>0)
                                            {
                                            ?>
                                                <a onclick="TINY.box.show({url:'ace_updateNotesForm.php?partId=<?php echo $_GET['partId']; ?>&processCode=<?php echo $getPartProcessResult['processCode']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:'280',height:'',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJSedit()}})"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20' align='right'></a>
                                            <?php
                                            }
                                        echo "</td>";
                                        echo "<td class = 'tresult'>";
                                            echo $toolName;
                                            
                                            echo "<ul style='list-style-type:square'>";

                                                $sql = "SELECT toolId FROM cadcam_processtoolsdetails WHERE processCode = ".$getPartProcessResult['processCode']." AND partId = ".$_GET['partId'];
                                                $processDetailQuery=$db->query($sql);
                                                while($processDetailQueryResult = $processDetailQuery->fetch_assoc())
                                                {
                                                    $toolId = $processDetailQueryResult['toolId'];
                                                    $sql = "SELECT * FROM cadcam_processtools WHERE toolId = ".$toolId;
                                                    $queryTool=$db->query($sql);
                                                    $resultTool=$queryTool->fetch_assoc();

                                                    $toolName = $resultTool['toolName'];
                                                
                                                    echo "<li>".$toolName."</li>";
                                                    $toolCounter++;
                                                }
                                            echo "</ul>";
                                            ?>
                                                <a onclick="openTinyBox('250','1000','carlo_addToolForm.php?partId=<?php echo $_GET['partId'];?>&processCode=<?php echo $getPartProcessResult['processCode']; ?>')"><img src = '../Common Data/Templates/images/add1.png' width = '20' height = '20' align='right'></a>
                                            <?php		
                                            if($toolCounter>0)
                                            {
                                            ?>
                                                <a onclick="TINY.box.show({url:'carlo_updateToolForm.php?partId=<?php echo $_GET['partId']; ?>&processCode=<?php echo $getPartProcessResult['processCode']; ?>',width:'280',height:'',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJSedit()}})"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20' align='right'></a>
                                            <?php
                                            }

                                            //TAMANG 2022-03-23
                                        echo "</td>";

                                        //TAMANG NEW TOOL START HERE
                                        echo "<td class = 'tresult'>";
                                        //echo $toolName;
                                            
                                            echo "<ul style='list-style-type:square'>";

                                                $sql = "SELECT toolId FROM cadcam_processtoolsdetails WHERE processCode = ".$getPartProcessResult['processCode']." AND partId = ".$_GET['partId']." AND identifier = '0'";
                                                $processDetailQuery=$db->query($sql);
                                                while($processDetailQueryResult = $processDetailQuery->fetch_assoc())
                                                {
                                                    $toolId = $processDetailQueryResult['toolId'];
                                                    $sql = "SELECT * FROM cadcam_processtools WHERE toolId = ".$toolId." AND identifier = 0";
                                                    $queryToolNew=$db->query($sql);
                                                    $resultToolNew=$queryToolNew->fetch_assoc();
                                                    $totalNewTools = $queryToolNew->num_rows;

                                                    $toolNameNew = $resultToolNew['toolName'];
                                                
                                                    echo "<li>".$toolNameNew."</li>";
                                                    //$toolCounter++;
                                                }
                                            echo "</ul>";
                                            ?>
                                                <a onclick="openTinyBox('250','580','carlo_addToolFormCopy.php?partId=<?php echo $_GET['partId'];?>&processCode=<?php echo $getPartProcessResult['processCode']; ?>&identifier=0')"><img src = '../Common Data/Templates/images/add1.png' width = '20' height = '20' align='right'></a>
                                            <?php		
                                            if($totalNewTools > 0)
                                            {
                                            ?>
                                                <a onclick="TINY.box.show({url:'carlo_updateToolForm.php?partId=<?php echo $_GET['partId']; ?>&processCode=<?php echo $getPartProcessResult['processCode']; ?>&identifier=0',width:'280',height:'',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJSedit()}})"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20' align='right'></a>
                                            <?php
                                            }
                                        echo "</td>";

                                        
                                        // NEW TOOL END HERE 
                                        // MACHINE STARTS HERE------------------------------------------------TAMANG 2022-03-23 START HERE---------------------------------------------------------------------------------
                                        echo "<td class = 'tresult'>";
                                            //echo $toolName;
                                            
                                            echo "<ul style='list-style-type:square'>";

                                                $sql = "SELECT toolId FROM cadcam_processtoolsdetails WHERE processCode = ".$getPartProcessResult['processCode']." AND partId = ".$_GET['partId'] ." AND identifier = '2'";
                                                $processDetailQuery1=$db->query($sql);
                                                while($processDetailQueryResult1 = $processDetailQuery1->fetch_assoc())
                                                {
                                                    $toolId1 = $processDetailQueryResult1['toolId'];
                                                    $sql = "SELECT * FROM cadcam_processtools WHERE toolId = ".$toolId1." AND identifier = 2";
                                                    $queryMachine=$db->query($sql);
                                                    $resultMachine=$queryMachine->fetch_assoc();
                                                    $totalMachine = $queryMachine->num_rows;

                                                    $toolName1 = $resultMachine['toolName'];
                                                    $machineIdentifier = $resultMachine['identifier'];

                                                
                                                    echo "<li>".$toolName1."</li>";
                                                    //$toolCounter++;
                                                }
                                            echo "</ul>";
                                            ?>
                                                <a onclick="openTinyBox('400','580','carlo_addToolFormCopy.php?partId=<?php echo $_GET['partId'];?>&processCode=<?php echo $getPartProcessResult['processCode']; ?>&identifier=2')"><img src = '../Common Data/Templates/images/add1.png' width = '20' height = '20' align='right'></a>
                                                <!-- <button type="button" name="machineBtn" id="machineBtn" onclick="machine()"><i class="fa fa-gear"></i></button> -->
                                            <?php		
                                            if($totalMachine > 0)
                                            {
                                            ?>
                                                <a onclick="TINY.box.show({url:'carlo_updateToolForm.php?partId=<?php echo $_GET['partId']; ?>&processCode=<?php echo $getPartProcessResult['processCode']; ?>&identifier=2',width:'280',height:'',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJSedit()}})"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20' align='right'></a>
                                            <?php
                                            }
                                        echo "</td>";
                                        //MACHINE END HERE-----------------------------------------------------------------------------------------------------------------------------

                                        //JIGS START HERE -------------------------------------------------------------------------------------------------------------------------
                                        echo "<td class = 'tresult'>";
                                            //echo $toolName;
                                            
                                            echo "<ul style='list-style-type:square'>";

                                                $sql = "SELECT toolId FROM cadcam_processtoolsdetails WHERE processCode = ".$getPartProcessResult['processCode']." AND partId = ".$_GET['partId']." AND identifier = 1";
                                                $processDetailQuery2=$db->query($sql);
                                                while($processDetailQueryResult2 = $processDetailQuery2->fetch_assoc())
                                                {
                                                    $toolId2 = $processDetailQueryResult2['toolId'];
                                                    $sql = "SELECT * FROM cadcam_processtools WHERE toolId = ".$toolId2." AND identifier = 1";
                                                    $queryJig=$db->query($sql);
                                                    $resultJig=$queryJig->fetch_assoc();
                                                    $totalJig = $queryJig->num_rows;

                                                    $toolName2 = $resultJig['toolName'];
                                                
                                                    echo "<li>".$toolName2."</li>";
                                                    //$toolCounter++;
                                                }
                                            echo "</ul>";
                                            ?>
                                                <a onclick="openTinyBox('250','580','carlo_addToolFormCopy.php?partId=<?php echo $_GET['partId'];?>&processCode=<?php echo $getPartProcessResult['processCode']; ?>&identifier=1')"><img src = '../Common Data/Templates/images/add1.png' width = '20' height = '20' align='right'></a>
                                            <?php		
                                            if($totalJig > 0)
                                            {
                                            ?>
                                                <a onclick="TINY.box.show({url:'carlo_updateToolForm.php?partId=<?php echo $_GET['partId']; ?>&processCode=<?php echo $getPartProcessResult['processCode']; ?>&identifier=1',width:'280',height:'',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJSedit()}})"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20' align='right'></a>
                                            <?php
                                            }
                                        echo "</td>";
                                        //JIGS END HERE----------------------------------------------------------------------------------------------------------------------------
                                        echo "<td class = 'w3-center'>";
                                            echo "<table class='table table-bordered table-condensed'>";
                                                echo "<thead class='w3-light-blue'>";
                                                    //echo "<th class='w3-center'>".dataParameter($getPartProcessResult['processCode'], 1)."</th>";
                                                    if(dataParameter($getPartProcessResult['processCode'], 1) == '')
                                                    {
                                                        echo "<th class='w3-center'>1</th>";
                                                    }
                                                    else
                                                    {
                                                        echo "<th class='w3-center'>".dataParameter($getPartProcessResult['processCode'], 1)."</th>";
                                                    }
                                                    
                                                echo "</thead>";
                                                echo "<tbody>";
                                                    echo "<tr>";
                                                        echo "<td class='w3-center'>".$dataOne."</td>";
                                                    echo "</tr>";
                                                echo "</tbody>";
                                            echo "</table>";
                                        echo "</td>";
                                        echo "<td class = 'w3-center'>";
                                            echo "<table class='table table-bordered table-condensed'>";
                                                echo "<thead class='w3-light-blue'>";
                                                    //echo "<th class='w3-center'>".dataParameter($getPartProcessResult['processCode'], 2)."</th>";
                                                    if(dataParameter($getPartProcessResult['processCode'], 2) == '')
                                                    {
                                                        echo "<th class='w3-center'>2</th>";
                                                    }
                                                    else
                                                    {
                                                        echo "<th class='w3-center'>".dataParameter($getPartProcessResult['processCode'], 2)."</th>";
                                                    }
                                                echo "</thead>";
                                                echo "<tbody>";
                                                    echo "<tr>";
                                                        echo "<td class='w3-center'>".$dataTwo."</td>";
                                                    echo "</tr>";
                                                echo "</tbody>";
                                            echo "</table>";
                                        echo "</td>";
                                        echo "<td class = 'w3-center'>";
                                            echo "<table class='table table-bordered table-condensed'>";
                                                echo "<thead class='w3-light-blue'>";
                                                    //echo "<th class='w3-center'>".dataParameter($getPartProcessResult['processCode'], 3)."</th>";
                                                    if(dataParameter($getPartProcessResult['processCode'], 3) == '')
                                                    {
                                                        echo "<th class='w3-center'>3</th>";
                                                    }
                                                    else
                                                    {
                                                        echo "<th class='w3-center'>".dataParameter($getPartProcessResult['processCode'], 3)."</th>";
                                                    }
                                                echo "</thead>";
                                                echo "<tbody>";
                                                    echo "<tr>";
                                                        echo "<td class='w3-center'>".$dataThree."</td>";
                                                    echo "</tr>";
                                                echo "</tbody>";
                                            echo "</table>";
                                        echo "</td>";
                                        echo "<td class = 'w3-center'>";
                                            echo "<table class='table table-bordered table-condensed'>";
                                                echo "<thead class='w3-light-blue'>";
                                                    //echo "<th class='w3-center'>".dataParameter($getPartProcessResult['processCode'], 4)."</th>";
                                                    if(dataParameter($getPartProcessResult['processCode'], 4) == '')
                                                    {
                                                        echo "<th class='w3-center'>4</th>";
                                                    }
                                                    else
                                                    {
                                                        echo "<th class='w3-center'>".dataParameter($getPartProcessResult['processCode'], 4)."</th>";
                                                    }
                                                echo "</thead>";
                                                echo "<tbody>";
                                                    echo "<tr>";
                                                        echo "<td class='w3-center'>".$dataFour."</td>";
                                                    echo "</tr>";
                                                echo "</tbody>";
                                            echo "</table>";
                                        echo "</td>";
                                        echo "<td class = 'w3-center'>";
                                            echo "<table class='table table-bordered table-condensed'>";
                                                echo "<thead class='w3-light-blue'>";
                                                    //echo "<th class='w3-center'>".dataParameter($getPartProcessResult['processCode'], 5)."</th>";
                                                    if(dataParameter($getPartProcessResult['processCode'], 5) == '')
                                                    {
                                                        echo "<th class='w3-center'>5</th>";
                                                    }
                                                    else
                                                    {
                                                        echo "<th class='w3-center'>".dataParameter($getPartProcessResult['processCode'], 5)."</th>";
                                                    }
                                                echo "</thead>";
                                                echo "<tbody>";
                                                    echo "<tr>";
                                                        echo "<td class='w3-center'>".$dataFive."</td>";
                                                    echo "</tr>";
                                                echo "</tbody>";
                                            echo "</table>";
                                        echo "</td>";
                                        // echo "<td class = 'w3-center'>";
                                        //     echo "<table class='table table-bordered table-condensed'>";
                                        //         echo "<thead class='w3-light-blue'>";
                                        //             echo "<th class='w3-center'>".dataParameter($getPartProcessResult['processCode'], 6)."</th>";
                                        //         echo "</thead>";
                                        //         echo "<tbody>";
                                        //             echo "<tr>";
                                        //                 echo "<td class='w3-center'>".$dataSix."</td>";
                                        //             echo "</tr>";
                                        //         echo "</tbody>";
                                        //     echo "</table>";
                                        // echo "</td>";
                                        // echo "<td class = 'w3-center'>";
                                        //     echo "<table class='table table-bordered table-condensed'>";
                                        //         echo "<thead class='w3-light-blue'>";
                                        //             echo "<th class='w3-center'>".dataParameter($getPartProcessResult['processCode'], 7)."</th>";
                                        //         echo "</thead>";
                                        //         echo "<tbody>";
                                        //             echo "<tr>";
                                        //                 echo "<td class='w3-center'>".$dataSeven."</td>";
                                        //             echo "</tr>";
                                        //         echo "</tbody>";
                                        //     echo "</table>";
                                        // echo "</td>";
                                        echo "<td class = 'tresult'>";
                                            $specIdCounter = 0;
                                            $sql = "SELECT specificationId FROM engineering_partprocessstandard WHERE partId = ".$_GET['partId']." AND processCode=".$getPartProcessResult['processCode']."";
                                            $querySpecId = $db->query($sql);
                                            if ($querySpecId AND $querySpecId->num_rows > 0)
                                            {
                                                echo "<ul style='list-style-type:disc'>";		
                                                while ($resultQuerySpecId = $querySpecId->fetch_assoc()) 
                                                {
                                                    $sql = "SELECT specificationNumber FROM engineering_specifications WHERE specificationId = ".$resultQuerySpecId['specificationId']."";
                                                    $querySpecNumber = $db->query($sql);
                                                    if($querySpecNumber AND $querySpecNumber->num_rows > 0)
                                                    {
                                                        $resultQuerySpecNumber = $querySpecNumber->fetch_assoc();
                                                        echo "<li>".$resultQuerySpecNumber['specificationNumber']."</li>";
                                                    }
                                                    $specIdCounter++;
                                                }
                                                echo "</ul>";	
                                            }
                                            $sql = "SELECT specificationNumber FROM engineering_specifications WHERE customerAlias LIKE '".$getCustomerResult['customerAlias']."'";
                                            $queryCountSpecNumber = $db->query($sql);
                                            if($queryCountSpecNumber AND $queryCountSpecNumber->num_rows > 0)
                                            {
                                                    ?>
                                                    <a onclick="TINY.box.show({url:'raymond_addStandardForm.php?partId=<?php echo $_GET['partId']; ?>&processCode=<?php echo $getPartProcessResult['processCode']; ?>&patternId=<?php echo $_GET['patternId']; ?>&customerAlias=<?php echo $getCustomerResult['customerAlias']; ?>',width:'250',height:'300',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJSedit()}})"><img src = '../Common Data/Templates/images/add1.png' width = '20' height = '20' align='right'></a>
                                                    
                                            <?php
                                                
                                            }
                                            if($specIdCounter > 0)
                                            {
                                            ?>
                                                <a onclick="TINY.box.show({url:'raymond_updateStandardForm.php?partId=<?php echo $_GET['partId']; ?>&processCode=<?php echo $getPartProcessResult['processCode']; ?>&patternId=<?php echo $_GET['patternId']; ?>&customerAlias=<?php echo $getCustomerResult['customerAlias']; ?>',width:'280',height:'',opacity:10,topsplit:6,animate:false,close:true})"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20' align='right'></a>
                                            <?php	
                                            }
                                        echo "</td>";
                                        echo "<td>";
                                            $processCodeBendingArray = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,31,32,33,34,46,47,48,261,262,283);
                                            $processCodeTappingArray = array (122,123,124);
                                            // in_array($getPartProcessResult['processCode'], $processCodeBendingArray)
                                            if($_SESSION['idNumber'] == '0412' OR in_array($getPartProcessResult['processCode'], $processCodeTappingArray))
                                            {	
                                                $tinyBoxWidth = '';
                                                
                                                if(in_array($getPartProcessResult['processCode'], $processCodeBendingArray))
                                                {
                                                    $tinyBoxWidth = '800';
                                                }
                                                echo "<a onclick=\"TINY.box.show({url:'raymond_updateProcessForm.php?partId=".$_GET['partId']."&processCode=".$getPartProcessResult['processCode']."&count=".$getPartProcessResult['count']."&patternId=".$_GET['patternId']."',width:'".$tinyBoxWidth."',height:'',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJSedit()}})\"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20'></a>";

                                            }
                                            else
                                            {
                                            ?>
                                                <a onclick="openTinyBox('','','anthony_updateProcessForm.php?partId=<?php echo $_GET['partId']; ?>&processCode=<?php echo $getPartProcessResult['processCode']; ?>&count=<?php echo $getPartProcessResult['count'] ?>&patternId=<?php echo $_GET['patternId']; ?>')"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20'></a>
                                            <?php
                                            }
                                            ?>
                                            <a onclick="TINY.box.show({url:'anthony_deleteProcessDetailForm.php?partId=<?php echo $_GET['partId']; ?>&processOrder=<?php echo $getPartProcessResult['processOrder']; ?>&count=<?php echo $getPartProcessResult['count'] ?>&patternId=<?php echo $_GET['patternId']; ?>',width:'',height:'',opacity:10,topsplit:6,animate:false,close:true})"><img src = '../Common Data/Templates/images/trash1.png' width = '20' height = '20'></a>
                                            <!-- <a onclick="TINY.box.show({url:'anthony_addProcessDetailForm.php?partId=<?php //echo $_GET['partId']; ?>&count=<?php //echo $getPartProcessResult['count']; ?>&insert=2',width:'',height:'',opacity:10,topsplit:6,animate:false,close:true})"><img src = '../images/add1.png' width = '20' height = '20'></a> -->
                                            <a onclick="openTinyBox('','600','anthony_addProcessDetailForm.php?partId=<?php echo $_GET['partId']; ?>&count=<?php echo $getPartProcessResult['count']; ?>&insert=2&patternId=<?php echo $_GET['patternId']; ?>')"><img src = '../Common Data/Templates/images/add1.png' width = '20' height = '20'></a>
                                            <?php  				
                                        echo "</td>";
                                        $dms = "../../Document Management System/Work Instruction Folder/".$getPartProcessResult['processCode']."_".$_GET['partId'].".pdf";
                                        $drawingButton = $backgroundColor = "";
                                        if(file_exists($dms) AND $_GET['partId'] != '')
                                        {																							
                                            $drawingButton = "<img style='width:20px; height:20px; cursor:pointer;' src='../Common Data/Templates/images/view1.png' onclick=\"window.open('".$dms."','newWin','left=20,top=20,width=1200,height=650,toolbar=1,resizable=0')\">";
                                            $backgroundColor = "style='background-color:lightgreen;'";
                                        }
                                        echo "<td class = 'tresult' ".$backgroundColor."><center>".$drawingButton."</center></td>";
                                    echo "</tr>";
                                }
                            }
                        echo "</tbody>";
                    echo "</table>";
                }
                else if($_GET['src'] == "specifications")
                {
                    echo "<label>".displayText('L1360', 'utf8', 0, 0, 1)."</label>";
                    echo "<table class='table table-condensed table-bordered table-striped'>";
                        echo "<thead class='w3-indigo' style='text-transform:uppercase;'>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L643')."</th>";//L643 Specification Number
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L1088')."</th>";//L1088 Detail Number
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('7-10','utf8',0,1,1)."</th>";//L215 Location
                            echo "<th style='vertical-align:middle;' class='w3-center' width=80 colspan=2>".displayText('L1120')."</th>";//L42 Action
                        echo "</thead>";
                        echo "<tbody>";
                                $sql = "SELECT listId, detailId,detailFlag FROM engineering_partstandard WHERE partId = ".$_GET['partId'];
                                $queryDetails = $db->query($sql);
                                if($queryDetails AND $queryDetails->num_rows > 0)
                                {
                                    while ($resultDetails = $queryDetails->fetch_assoc())
                                    {
                                        $detailId = $resultDetails['detailId'];
                                        $listId = $resultDetails['listId'];
                                        
                                        if($resultDetails['detailFlag'] == 0)
                                        {
                                            $detailFlag = "All";
                                        }
                                        else
                                        {
                                            $detailFlag = "Point";
                                        }

                                        $sql = "SELECT * FROM engineering_specificationdetail WHERE detailId = ".$detailId;
                                        $querySpecDetails = $db->query($sql);
                                        if($querySpecDetails AND $querySpecDetails->num_rows > 0)
                                        {
                                            $resultSPecDetails = $querySpecDetails->fetch_assoc();
                                            $detailNumber = $resultSPecDetails['detailNumber'];
                                            $specificationId = $resultSPecDetails['specificationId'];

                                            $sql = "SELECT specificationNumber FROM engineering_specifications WHERE specificationId = ".$specificationId." AND status = 0";
                                            $querySpecNum = $db->query($sql);
                                            if($querySpecNum AND $querySpecNum->num_rows > 0)
                                            {
                                                $resultSpecNum = $querySpecNum->fetch_assoc();
                                                $specificationNumber = $resultSpecNum['specificationNumber'];
                                            }
                                            echo "<tr>";
                                                echo "<td style='text-align:center;'>".$specificationNumber."</td>";
                                                echo "<td style='text-align:center;'>".$detailNumber."</td>";
                                                echo "<td style='text-align:center;'>".$detailFlag."</td>";
                                                echo "<td style='text-align:center;' width=40><img onclick=\"TINY.box.show({url:'raymond_specificationInputFormEdit.php?customerAlias=".$getCustomerResult['customerAlias']."&detailId=".$detailId."&specificationId=".$specificationId."&partId=".$_GET['partId']."',width:'auto',height:'auto',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJS()}})\" src='../Common Data/Templates/buttons/editIcon.png' width=25 height=25></center></td>";
                                                echo "<td style='text-align:center;' width=40><a href='raymond_updateSpecificationData.php?type=delete&listId=".$listId."&partId=".$_GET['partId']."&patternId=".$_GET['patternId']."'><img onclick=\"return confirm('displayText('L3174')');\" src='../Common Data/Templates/buttons/deleteIcon.png' width=25 height=25></a></td>";
                                            echo "</tr>";
                                        }
                                    }
                                }
                                
                                $sql = "SELECT noteId, patternId, remarks, remarksFlag FROM cadcam_partprocessnote WHERE processCode = 0 AND partId = ".$_GET['partId']." ORDER BY noteId";
                                $partStandardQuery = $db->query($sql);
                                if($partStandardQuery->num_rows > 0)
                                {
                                    while($partStandardQueryResult = $partStandardQuery->fetch_array())
                                    {
                                        $patternId = $partStandardQueryResult['patternId'];
                                        $remarks = $partStandardQueryResult['remarks'];
                                        $noteId = $partStandardQueryResult['noteId'];
                                        
                                        if($partStandardQueryResult['remarksFlag']==0)
                                        {
                                            $detailFlag = "All";
                                        }
                                        else
                                        {
                                            $detailFlag = "Point";
                                        }								
                                            
                                            echo "<tr>";
                                                echo "<td style='text-align:center;'>".$patternId."</td>";
                                                echo "<td style='text-align:center;'>".$remarks."</td>";
                                                echo "<td style='text-align:center;'>".$detailFlag."</td>";
                                                echo "<td style='text-align:center;' width=40><img onclick=\"TINY.box.show({url:'raymond_noteInputFormEdit.php?noteId=".$noteId."&partId=".$_GET['partId']."',width:'auto',height:'auto',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJS()}})\" src='../Common Data/Templates/buttons/editIcon.png' width=25 height=25></center></td>";
                                                echo "<td style='text-align:center;' width=40><a href='raymond_updateSpecificationData.php?type=deleteNote&noteId=".$noteId."&partId=".$_GET['partId']."&patternId=".$_GET['patternId']."'><img onclick=\"return confirm('displayText('L3174')');\" src='../Common Data/Templates/buttons/deleteIcon.png' width=25 height=25></a></td>";
                                            echo "</tr>";
                                    }
                                }
                                
                            echo "<tr>";
                                echo "<td colspan='5' class='w3-center'>";
                                    echo "<button class='w3-btn w3-tiny w3-round w3-indigo' onclick=\"TINY.box.show({url:'raymond_specificationInputForm.php?customerAlias=".$getCustomerResult['customerAlias']."',width:'auto',height:'auto',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJS()}})\"><i class='fa fa-plus'></i>&emsp;<b>".displayText('L1399', 'utf8', 0, 0, 1)."</b></button>&nbsp;";
                                    echo "<button class='w3-btn w3-tiny w3-round w3-indigo' onclick=\"TINY.box.show({url:'raymond_addNoteInputForm.php?partId=".$_GET['partId']."',width:'auto',height:'auto',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJS()}})\"><i class='fa fa-plus'></i>&emsp;<b>".displayText('L1400', 'utf8', 0, 0, 1)."</b></button>&nbsp;";
                                echo "</td>";
                            echo "</tr>";
                        echo "</tbody>";
                    echo "</table>";
                }
                else if($_GET['src'] == "subparts")
                {
                    echo "<label>".displayText('L88', 'utf8', 0, 0, 1)."</label>";
                    echo "<div class='w3-right'>";
                        ?>
                        <a onclick="window.open('ace_addAutomatedSubpartsForm.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>', 'Automated Subpart Input Form', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=700, width=600, height=550')"><img src = '../Common Data/Templates/buttons/materialReleasingIcon.png' width = '25' height = '25'></a>
                        <a onclick="TINY.box.show({url:'anthony_addSubpartsForm.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:380,height:150,opacity:10,topsplit:6,animate:false,close:true})"><img src = '../Common Data/Templates/images/add1.png' width = '25' height = '25'></a>
                        <?php
                    echo "</div>";
                    echo "<div class='w3-padding-top'></div>";
                    echo "<table class='table table-condensed table-bordered table-striped'>";
                        echo "<thead class='w3-indigo' style='text-transform:uppercase;'>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L28')."</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L1934')."</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L3446')."</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L31')."</th>";
                            echo "<th style='vertical-align:middle;' class='w3-center'>".displayText('L59')."</th>";
                        echo "</thead>";
                        echo "<tbody>";
                        $sql = "SELECT subpartId, childId, quantity FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 1 ";
                        $getSubParts = $db->query($sql);
                        if($getSubParts->num_rows > 0)
                        {
                            while($getSubPartsResult = $getSubParts->fetch_array())
                            {
                                $sql = "SELECT partId, partNumber, revisionId, partNote, customerId FROM cadcam_parts WHERE partId = ".$getSubPartsResult['childId']." ";
                                $getParts = $db->query($sql);
                                $getPartsResult = $getParts->fetch_array();
                                
                                // ************************ Check subpart if have a process ************************
                                $ifSubpartHasNoProcess = '';
                                $sql = "SELECT count FROM cadcam_partprocess WHERE partId = ".$getPartsResult['partId']." ";
                                $getCount = $db->query($sql);
                                if($getCount->num_rows < 1)
                                {
                                    $ifSubpartHasNoProcess = "style='background-color:pink;'"; // by sir mar
                                }
                                // ************************ End Check subpart if have a process ************************
                            
                                echo "<tr>";
                                    echo "<td class = 'tresult' width = '231' ".$ifSubpartHasNoProcess."><a href = 'anthony_editProduct.php?partId=".$getPartsResult['partId']."&src=process&patternId=".$_GET['patternId']."'>".$getPartsResult['partNumber']."</a></td>";
                                    echo "<td class = 'tresult'>".$getPartsResult['revisionId'];
                                    ?>
                                        <!--<a href="#" onclick= "window.open('anthony_productProcessConverter.php?partId=<?php echo $getSubPartsResult['childId']; ?>&patternId=<?php echo $_GET['patternId']; ?>','processs','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><img src = "../Common Data/Templates/buttons/printerIcon.png"  width = "10" height = "10"></a>-->
                                    <?php
                                    echo "</td>";
                                    echo "<td class = 'tresult'>".$getPartsResult['partNote']."</td>";
                                    echo "<td class = 'tresult'>".$getSubPartsResult['quantity'];
                                    ?>
                                    <div>
                                    <a href = 'anthony_deleteSubpartsFormSQL.php?partId=<?php echo $_GET['partId']; ?>&subId=<?php echo $getSubPartsResult['subpartId']; ?>&patternId=<?php echo $_GET['patternId']; ?>' class = 'delete' name = 'deleteSubparts'><img src = '../Common Data/Templates/images/trash1.png' width = '20' height = '20' style = "float:right;"></a>
                                    <a onclick="TINY.box.show({url:'anthony_updateSubpartsForm.php?partId=<?php echo $_GET['partId']; ?>&subId=<?php echo $getSubPartsResult['subpartId']; ?>&customerId=<?php echo $getPartsResult['customerId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:480,height:100,opacity:10,topsplit:6,animate:false,close:true})"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20' style = "float:right; margin-right: .2em;"></a>
                                    </div>
                                    <?php										
                                    
                                    echo "</td>";
                                    
                                    $editProcessButton = "";
                                    $subpartprocessQuantityTotal = 0;
                                    $subpartProcessName = "";
                                    $subpartProcessArray = array();
                                    $sql = "SELECT processCode, quantity FROM engineering_subpartprocesslink WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." AND childId = ".$getSubPartsResult['childId']." AND identifier = 1";
                                    $querySubPartProcessLink = $db->query($sql);
                                    if($querySubPartProcessLink AND $querySubPartProcessLink->num_rows > 0)
                                    {
                                        while($resultSubPartProcessLink = $querySubPartProcessLink->fetch_assoc())
                                        {
                                            $subpartProcessArray[] = $resultSubPartProcessLink['processCode'];
                                            $subpartprocessQuantityTotal += $resultSubPartProcessLink['quantity'];
                                        }
                                        $subpartProcessNameArray = array();
                                        $sql = "SELECT processName, alternateProcessName FROM cadcam_process WHERE processCode IN(".implode(",",$subpartProcessArray).")";
                                        $queryProcess = $db->query($sql);
                                        if($queryProcess AND $queryProcess->num_rows > 0)
                                        {
                                            while($resultProcess = $queryProcess->fetch_assoc())
                                            {
                                            	if ($languageFlag == 1) 
                                            	{
                                            		$subpartProcessNameArray[] = $resultProcess['processName'];
                                            	}
                                            	else
                                            	{
                                            		$subpartProcessNameArray[] = $resultProcess['alternateProcessName'];
                                            	}
                                                
                                            }
                                        }
                                        $subpartProcessName = implode("<br>",$subpartProcessNameArray);
                                        
                                        $editProcessButton = "<img onclick=\" openTinyBox('','','".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."&src=".$_GET['src']."','tinyBoxType=editSubpartProcess&childId=".$getSubPartsResult['childId']."&identifier=1'); \" src='../Common Data/Templates/images/edit1.png' height='20' style='float:right'>";
                                    }
                                    
                                    $addProcessButton = "";
                                    if($getSubPartsResult['quantity'] > $subpartprocessQuantityTotal)
                                    {
                                        $addProcessButton = "<img onclick=\" openTinyBox('','','".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."&src=".$_GET['src']."','tinyBoxType=addSubpartProcess&childId=".$getSubPartsResult['childId']."&identifier=1'); \" src='../Common Data/Templates/images/add1.png' height='20' style='float:right'>";
                                    }
                                    
                                    echo "<td class = 'tresult'>".$subpartProcessName;
                                    echo "<div>".$editProcessButton.$addProcessButton."</div>";

                                    echo "</td>";
                                echo "</tr>";
                            }
                        }
                        else
                        {
                            echo "<tr>";
                                echo "<td colspan = '5' class = 'tresult'><center>".displayText('L95')."</center></td>";
                            echo "</tr>"; 
                        }
                        echo "</tbody>";
                    echo "</table>";
                //-------------------------------- GET ACCESSORIES -----------------------------------
                }
                else if($_GET['src'] == "accessories")
                {
                    echo "<label>".displayText('L471', 'utf8', 0, 0, 1)."</label>";
                    echo "<div class='w3-right'>";
                    ?>
                        <a onclick="TINY.box.show({url:'raymond_addAccessoryForm.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:'',height:'',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJSAddAccessory()}})"><img src = '../Common Data/Templates/images/add1.png' width = '25' height = '25' style = 'float: right;'></a>
                    <?php
                    echo "</div>";
                    echo "<div class='w3-padding-top'></div>";
                    echo "<table class='table table-condensed table-bordered table-striped'>";
                        echo "<thead class='w3-indigo' style='text-transform:uppercase;'>";
                                echo "<th class = 'tname' width = '195'><center>".displayText('L96')."</center></th>";
                                echo "<th class = 'tname' width = '195'><center>".displayText('L97')."</center></th>";
                                echo "<th class = 'tname' width = '195'><center>".displayText('L303')."</center></th>";
                                echo "<th class = 'tname' width = '195'><center>rev/".displayText('L35')."</center></th>";
                                echo "<th class = 'tname' width = '195'><center>".displayText('L31')."</center></th>";
                                echo "<th class = 'tname' width = '195'><center>".displayText('L59')."</center></th>";
                        echo "</thead>";
                        echo "<tbody>";
                        $sql = "SELECT subpartId, childId, quantity, remarks FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 2 ";
                        $getChildId = $db->query($sql);
                        if($getChildId->num_rows > 0)
                        {
                            while($getChildIdResult = $getChildId->fetch_array())
                            {
                                $sql = "SELECT accessoryId, accessoryNumber, accessoryName, revisionId, accessoryDescription FROM cadcam_accessories WHERE accessoryId = ".$getChildIdResult['childId']." ";
                                $getAccessory = $db->query($sql);
                                $getAccessoryResult = $getAccessory->fetch_array();
                                $revOfAcc1="";
                                $revOfAcc="";
                                if(trim($getAccessoryResult['revisionId'])!=""){$revOfAcc1="[".trim($getAccessoryResult['revisionId'])."]"; $revOfAcc="rev. [".trim($getAccessoryResult['revisionId'])."]";}
                                echo "<tr>";
                                    echo "<td class = 'tresult' width = '195'>".$getAccessoryResult['accessoryNumber'].$revOfAcc1."</td>";
                                    echo "<td class = 'tresult' width = '195'>".$getAccessoryResult['accessoryName'];
                                    ?>
                                            <a href="#" onclick= "window.open('anthony_productProcessConverter.php?partId=<?php echo $getChildIdResult['childId']; ?>&identifier=2&patternId=<?php echo $_GET['patternId']; ?>','processs','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><img src = "../Common Data/Templates/buttons/printerIcon.png"  width = "10" height = "10"></a>
                                    <?php
                                    echo "</td>";
                                    echo "<td class = 'tresult' width = '195'>".$getAccessoryResult['accessoryDescription']."</td>";
                                    echo "<td class = 'tresult' width = '195'>".$revOfAcc."".$getChildIdResult['remarks']."</td>";
                                    echo "<td class = 'tresult' width = '195'>".$getChildIdResult['quantity'];
                                    
                                    echo "<div>";
                                    ?>
                                    <a href = 'anthony_deleteAccessoryFormSQL.php?partId=<?php echo $_GET['partId']; ?>&subId=<?php echo $getChildIdResult['subpartId']; ?>&patternId=<?php echo $_GET['patternId']; ?>' class = 'delete' name = 'deleteAccessory'><img src = '../Common Data/Templates/images/trash1.png' width = '20' height = '20' style = "float:right;"></a>
                                    <?php
                                    if($_SESSION['idNumber'] == true)
                                    {
                                    ?>
                                        <a onclick="TINY.box.show({url:'raymond_updateAccessoryForm.php?accessoryId=<?php echo $getAccessoryResult['accessoryId'];?>&partId=<?php echo $_GET['partId']; ?>&subId=<?php echo $getChildIdResult['subpartId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:'',height:'',opacity:10,topsplit:6,animate:false,close:true,openjs:function(){openJSAddAccessory()}})"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20' style = "float:right; margin-right: .2em;"></a>
                                    <?php
                                    }
                                    else
                                    {
                                    ?>
                                    <a onclick="TINY.box.show({url:'anthony_updateAccessoryForm.php?partId=<?php echo $_GET['partId']; ?>&subId=<?php echo $getChildIdResult['subpartId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:'',height:'',opacity:10,topsplit:6,animate:false,close:true})"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20' style = "float:right; margin-right: .2em;"></a>
                                    <?php
                                    }
                                    echo "</div>";										
                                    
                                    echo "</td>";
                                    
                                    $editProcessButton = "";
                                    $subpartprocessQuantityTotal = 0;
                                    $subpartProcessName = "";
                                    $subpartProcessArray = array();
                                    $sql = "SELECT processCode, quantity FROM engineering_subpartprocesslink WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." AND childId = ".$getChildIdResult['childId']." AND identifier = 2";
                                    $querySubPartProcessLink = $db->query($sql);
                                    if($querySubPartProcessLink AND $querySubPartProcessLink->num_rows > 0)
                                    {
                                        while($resultSubPartProcessLink = $querySubPartProcessLink->fetch_assoc())
                                        {
                                            $subpartProcessArray[] = $resultSubPartProcessLink['processCode'];
                                            $subpartprocessQuantityTotal += $resultSubPartProcessLink['quantity'];
                                        }
                                        $subpartProcessNameArray = array();
                                        $sql = "SELECT processName, alternateProcessName FROM cadcam_process WHERE processCode IN(".implode(",",$subpartProcessArray).")";
                                        $queryProcess = $db->query($sql);
                                        if($queryProcess AND $queryProcess->num_rows > 0)
                                        {
                                            while($resultProcess = $queryProcess->fetch_assoc())
                                            {
                                            	if ($languageFlag == 1) 
                                            	{
                                            		$subpartProcessNameArray[] = $resultProcess['processName'];
                                            	}
                                            	else
                                            	{
                                            		$subpartProcessNameArray[] = $resultProcess['alternateProcessName'];
                                            	}
                                                
                                            }
                                        }
                                        $subpartProcessName = implode("<br>",$subpartProcessNameArray);
                                        
                                        $editProcessButton = "<img onclick=\" openTinyBox('350','','".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."&src=".$_GET['src']."','tinyBoxType=editSubpartProcess&childId=".$getChildIdResult['childId']."&identifier=2'); \" src='../Common Data/Templates/images/edit1.png' height='20' style='float:right'>";
                                    }
                                    
                                    $addProcessButton = "";
                                    if($getChildIdResult['quantity'] > $subpartprocessQuantityTotal)
                                    {
                                        $addProcessButton = "<img onclick=\" openTinyBox('','','".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."&src=".$_GET['src']."','tinyBoxType=addSubpartProcess&childId=".$getChildIdResult['childId']."&identifier=2'); \" src='../Common Data/Templates/images/add1.png' height='20' style='float:right'>";
                                    }
                                    
                                    echo "<td class = 'tresult'>".$subpartProcessName;
                                    echo "<div>".$editProcessButton.$addProcessButton."</div>";

                                    echo "</td>";
                                echo "</tr>";
                            }
                        }
                        else
                        {
                            echo "<tr>";
                                echo "<td class = 'tresult' width = '235' colspan = '6'><center>".displayText('L98')."</center></td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                    echo "</table>";
                //-------------------------------- GET SUPPLIES -----------------------------------
                }
                else if($_GET['src'] == "supplies")
                {
                    echo "<label>".displayText('L470', 'utf8', 0, 0, 1)."</label>";
                    echo "<div class='w3-right'>";
                    ?>
                        <a onclick="TINY.box.show({url:'anthony_addSuppliesForm.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:350,height:'',opacity:10,topsplit:6,animate:false,close:true})"><img src = '../Common Data/Templates/images/add1.png' width = '25' height = '25' style = 'float: right;'></a>
                    <?php
                    echo "</div>";
                    echo "<div class='w3-padding-top'></div>";
                    echo "<table class='table table-condensed table-bordered table-striped'>";
                        echo "<thead class='w3-indigo' style='text-transform:uppercase;'>";
                                echo "<th class = 'tname' width = '195'><center>".displayText('L2107')."</center></th>";
                                echo "<th class = 'tname' width = '195'><center>".displayText('L218')."</center></th>";
                                echo "<th class = 'tname' width = '195'><center>".displayText('L31')."</center></th>";
                        echo "</thead>";
                        echo "<tbody>";
                        $sql = "SELECT subpartId, childId, quantity FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 8 ";
                        $getSupplies = $db->query($sql);
                        if($getSupplies->num_rows > 0)
                        {
                            while($getSuppliesResult = $getSupplies->fetch_array())
                            {
                                
                                $itemName = $itemDescription = '';
                                $sql = "SELECT itemName, itemDescription FROM purchasing_items WHERE itemId = ".$getSuppliesResult['childId']." ";
                                $getPurchasingItems = $db->query($sql);
                                if($getPurchasingItems->num_rows > 0)
                                {
                                    $getPurchasingItemsResult = $getPurchasingItems->fetch_array();
                                    $itemName = $getPurchasingItemsResult['itemName'];
                                    $itemDescription = $getPurchasingItemsResult['itemDescription'];
                                }
                                
                                echo "<tr>";
                                    echo "<td class = 'tresult' width = '195'>".$itemName."</td>";
                                    echo "<td class = 'tresult' width = '195'>".$itemDescription."</td>";
                                    echo "<td class = 'tresult' width = '195'>".$getSuppliesResult['quantity'];
                                    ?>
                                    <a href = 'anthony_updateSuppliesForm.php?partId=<?php echo $_GET['partId']; ?>&subpartId=<?php echo $getSuppliesResult['subpartId']; ?>&patternId=<?php echo $_GET['patternId']; ?>&src=delete' class = 'delete' name = 'deleteAccessory'><img src = '../Common Data/Templates/images/trash1.png' width = '20' height = '20' style = "float:right;"></a>
                                    <a onclick="TINY.box.show({url:'anthony_updateSuppliesForm.php?partId=<?php echo $_GET['partId']; ?>&subpartId=<?php echo $getSuppliesResult['subpartId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:350,height:'',opacity:10,topsplit:6,animate:false,close:true})"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20' style = "float:right; margin-right: .2em;"></a>
                                    <?php
                                    echo "</td>";
                                echo "</tr>";
                            }
                        }
                        else
                        {
                            echo "<tr>";
                                echo "<td class = 'tresult' colspan = '3'><center>".displayText('L219')."</center></td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                    echo "</table>";
                //-------------------------------- GET MAINPARTS -----------------------------------
                }
                else if($_GET['src'] == "mainparts")
                {
                    echo "<label>".displayText('L90', 'utf8', 0, 0, 1)."</label>";
                    echo "<table class='table table-condensed table-bordered table-striped'>";
                        echo "<thead class='w3-indigo' style='text-transform:uppercase;'>";
                            echo "<th class = 'tname' width = '195'><center>".displayText('L28')."</center></th>";
                            echo "<th class = 'tname' width = '195'><center>".displayText('L1934')."</center></th>";
                            echo "<th class = 'tname' width = '195'><center>".displayText('L3446')."</center></th>";
                        echo "</thead>";
                        echo "<tbody>";					
                        $sql = "SELECT parentId FROM cadcam_subparts WHERE childId = ".$_GET['partId']." and identifier=1 ";
                        $getParentId = $db->query($sql);
                        if($getParentId->num_rows > 0)
                        {
                            while($getParentIdResult = $getParentId->fetch_array())
                            {
                                $sql = "SELECT partId, partNumber, revisionId, partNote FROM cadcam_parts WHERE partId = ".$getParentIdResult['parentId']." ";
                                $getNumberRevision = $db->query($sql);
                                $getNumberRevisionResult = $getNumberRevision->fetch_array();
                                            
                                    echo "<tr>";
                                        echo "<td class = 'tresult' width = '195'><a href = 'anthony_editProduct.php?partId=".$getNumberRevisionResult['partId']."&src=process&patternId=".$_GET['patternId']."'>".$getNumberRevisionResult['partNumber']."</a></td>";
                                        echo "<td class = 'tresult' width = '195'>".$getNumberRevisionResult['revisionId']."</td>";
                                        echo "<td class = 'tresult' width = '195'>".$getNumberRevisionResult['partNote']."</td>";
                                    echo "</tr>";
                            }
                        }
                        else
                        {
                            echo "<tr>";
                                echo "<td class = 'tresult' width = '235' colspan = '2'><center>".displayText('L99')."</center></td>";
                            echo "</tr>";
                        }
                        
                        echo "</tbody>";
                    echo "</table>";
                }
                else if($_GET['src'] == "subcon")
                {
                    echo "<label>".displayText('L91', 'utf8', 0, 0, 1)."</label>";
                    echo "<div class='w3-right'>";
                    ?>
                        <a onclick="TINY.box.show({url:'anthony_addSubconForm.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:500,height:'auto',opacity:10,topsplit:6,animate:false,close:true})"><img src = '../Common Data/Templates/images/add1.png' width = '25' height = '25' style = 'float: right;'></a>
                    <?php
                    echo "</div>";
                    echo "<div class='w3-padding-top'></div>";
                    echo "<table class='table table-condensed table-bordered table-striped'>";
                        echo "<thead class='w3-indigo' style='text-transform:uppercase;'>";
                            echo "<th class = 'tname' width = '25'><center>".displayText('L1369')."</center></th>";//L1369 Subcon Order
                            echo "<th class = 'tname' width = '100'><center>".displayText('L100')."</center></th>";
                            echo "<th class = 'tname' width = '150'><center>".displayText('L59')."</center></th>";
                            if($_GET['country']=="1")
                            {
                                echo "<th class = 'tname' width = '100'><center>".displayText('L101')."</center></th>";									
                            }
                            else
                            {
                                echo "<th class = 'tname' width = '100'><center>".displayText('L35')."</center></th>";	
                            }							
                            echo "<th class = 'tname' width = '150'><center>".displayText('L91')."</center></th>";//L91 Subcon
                        echo "</thead>";
                        echo "<tbody>";
                        $sql = "SELECT a, partId, subconId, processCode, surfaceArea, subconOrder FROM cadcam_subconlist WHERE partId = ".$_GET['partId']." ORDER BY subconOrder, a";
                        $getSubconList = $db->query($sql);
                        if($getSubconList->num_rows > 0)
                        {
                            while($getSubconListResult = $getSubconList->fetch_array())
                            {
                                $sql = "SELECT treatmentId, treatmentName FROM engineering_treatment WHERE treatmentId = ".$getSubconListResult['processCode']." ";
                                $getProcessName = $db->query($sql);
                                $getProcessNameResult = $getProcessName->fetch_array();
                                
                                // ---------- this code will be deleted ---------- //
                                //~ $sql = "SELECT totalSurfaceClear, totalSurfacePrime, totalSurfacePassivation, totalSurfaceBrush, totalSurfaceBlackHard FROM cadcam_subcondimension WHERE partId = ".$getSubconListResult['partId']." ";
                                //~ $getSubcon = $db->query($sql);
                                //~ $getSubconResult = $getSubcon->fetch_array();
                                // -------- END this code will be deleted -------- //
                                                                                            
                                echo "<tr>";
                                        echo "<td class = 'tresult' width = '25'>".$getSubconListResult['subconOrder']."</td>";
                                        echo "<td class = 'tresult' width = '100'>";
                                            echo $getProcessNameResult['treatmentId'];
                                            ?>
                                            <a href = 'anthony_deleteSubconFormSQL.php?partId=<?php echo $_GET['partId']; ?>&subconId=<?php echo $getSubconListResult['a']; ?>&patternId=<?php echo $_GET['patternId']; ?>' class = 'delete' name = 'deleteSubcon'><img src = '../Common Data/Templates/images/trash1.png' width = '20' height = '20' style = "float:right;"></a>
                                            <!-- EDIT sub process... maybe wrong must select at engineering treatment!!!! // rose 2017-08-03 -->
                                            <!-- Revive by gerald 2017-08025 -->
                                            <a onclick="TINY.box.show({url:'anthony_updateSubconForm.php?partId=<?php echo $_GET['partId']; ?>&subconId=<?php echo $getSubconListResult['a']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:400,height:100,opacity:10,topsplit:6,animate:false,close:true})"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20' style = "float:right; margin-right: .2em;"></a>
                                            <?php
                                        echo "</td>";
                                        echo "<td class = 'tresult' width = '150'>".$getProcessNameResult['treatmentName']."</td>";
                                        if($_GET['country']=="1")
                                        {
                                            if($getSubconListResult['surfaceArea'] > 0)
                                            {
                                                echo "<td class = 'tresult' width = '100'>".$getSubconListResult['surfaceArea'];
                                            }
                                            else
                                            {
                                                echo "<td class = 'tresult' width = '100'>";
                                            }
                                            // ---------- this code will be deleted ---------- //												
                                            //~ if($getSubconListResult['processCode'] == 270)
                                            //~ {
                                                //~ echo "<td class = 'tresult' width = '100'>".$getSubconResult['totalSurfaceClear'];
                                            //~ }
                                            //~ else if($getSubconListResult['processCode'] == 272)
                                            //~ {
                                                //~ echo "<td class = 'tresult' width = '100'>".$getSubconResult['totalSurfacePrime'];
                                            //~ }
                                            //~ else if($getSubconListResult['processCode'] == 251)
                                            //~ {
                                                //~ echo "<td class = 'tresult' width = '100'>".$getSubconResult['totalSurfacePassivation'];
                                            //~ }
                                            //~ else if($getSubconListResult['processCode'] == 284)
                                            //~ {
                                                //~ echo "<td class = 'tresult' width = '100'>".$getSubconResult['totalSurfacePassivation'];
                                            //~ }
                                            //~ else if($getSubconListResult['processCode'] == 273)
                                            //~ {
                                                //~ echo "<td class = 'tresult' width = '100'>".$getSubconResult['totalSurfaceBrush'];
                                            //~ }
                                            //~ else if($getSubconListResult['processCode'] == 271)
                                            //~ {
                                                //~ echo "<td class = 'tresult' width = '100'>".$getSubconResult['totalSurfaceBlackHard'];
                                            //~ }
                                            //~ else
                                            //~ {
                                                //~ echo "<td class = 'tresult' width = '100'>";
                                            //~ }
                                            // -------- END this code will be deleted -------- //
                                            echo "(dm2)";
                                            echo "</td>";
                                        }
                                        else
                                        {
                                            // -------------------------------- Ace Sandoval: Process Detail --------------------------------------------------
                                            $notesCounter = 0;
                                            echo "<td class = 'tresult'>";
                                                echo "<ul style='list-style-type:square'>";										
                                                    $sql = "SELECT dataOne, dataTwo, dataThree FROM engineering_subconremarks WHERE subconListId = ".$getSubconListResult['a'];
                                                    $subconRemarksQuery=$db->query($sql);
                                                    while($subconRemarksQueryResult = $subconRemarksQuery->fetch_assoc())
                                                    {
                                                        echo "<li>".$subconRemarksQueryResult['dataOne']." - ".$subconRemarksQueryResult['dataTwo']." - ".$subconRemarksQueryResult['dataThree']."</li>";
                                                        $notesCounter++;
                                                    }										
                                                echo "</ul>";										
                                                ?>
                                                    <a onclick="TINY.box.show({url:'ace_addSubconNotesForm.php?partId=<?php echo $_GET['partId']; ?>&subconListId=<?php echo $getSubconListResult['a']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:'500',height:'',opacity:10,topsplit:6,animate:false,close:true})"><img src = '../Common Data/Templates/images/add1.png' width = '20' height = '20' align='right'></a>
                                                <?php										
                                                if($notesCounter>0)
                                                {
                                                ?>
                                                    <a onclick="TINY.box.show({url:'ace_updateSubconNotesForm.php?partId=<?php echo $_GET['partId']; ?>&subconListId=<?php echo $getSubconListResult['a']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:'550',height:'',opacity:10,topsplit:6,animate:false,close:true})"><img src = '../Common Data/Templates/images/edit1.png' width = '20' height = '20' align='right'></a>
                                                <?php
                                                }
                                            echo "</td>";
                                            // -------------------------------- End of Ace Sandoval: Process Detail ------------------------------------------------
                                        }
                                        
                                        $subconAliasArray = array();
                                        $sql = "SELECT listId, subconId FROM engineering_subconprocessor WHERE a = ".$getSubconListResult['a']."";
                                        $querySubconProcessor = $db->query($sql);
                                        if($querySubconProcessor AND $querySubconProcessor->num_rows > 0)
                                        {
                                            while($resultSubconProcessor = $querySubconProcessor->fetch_assoc())
                                            {
                                                $subconAlias = '';
                                                $sql = "SELECT subconAlias FROM purchasing_subcon WHERE subconId = ".$resultSubconProcessor['subconId']." LIMIT 1";
                                                $querySubcon = $db->query($sql);
                                                if($querySubcon AND $querySubcon->num_rows > 0)
                                                {
                                                    $resultSubcon = $querySubcon->fetch_assoc();
                                                    $subconAlias = $resultSubcon['subconAlias'];
                                                }
                                                
                                                $removeButton = "<a href='".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."&subconProcessorListId=".$resultSubconProcessor['listId']."'><img title='Remove ".$subconAlias."' src = '../Common Data/Templates/images/trash1.png' style='cursor:pointer;' width = '20' height = '20' align='right'></a>";
                                                
                                                $subconAliasArray[] = $subconAlias. " ".$removeButton;
                                            }
                                        }
                                        echo "<td class = 'tresult' width = '150'><img onclick=\" openTinyBox('','','".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."','tinyBoxType=addSubcon&a=".$getSubconListResult['a']."'); \" src = '../Common Data/Templates/images/add1.png' style='cursor:pointer;' width = '20' height = '20' align='right'>".implode(",",$subconAliasArray)."</td>";
                                echo "</tr>"; 
                            }
                        }else
                        {
                            echo "<tr>";
                                echo "<td class = 'tresult' colspan = '5'><center>".displayText('L102')."</center></td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                    echo "</table>";
                //-------------------------------- GET PROGRAMS -----------------------------------
                }
                else if($_GET['src'] == "programs")
                {
                    //~ list($TPP4Stat,$ErrorDetails)=TheStatusATC4($_GET['partId']);
                    //~ updateTheStatusATC4($_GET['partId']);
					list($howManyProgram,$progIds,$progNames)=checkMultiplePartProgram($_GET['partId']);
					$_GET['programId'] = (isset($_GET['programId'])) ? $_GET['programId'] : '0';
					
                    $links = "<span style='cursor:pointer;color:blue;text-decoration:underline;' onclick=\" window.open('../Others/Rose/IT/ATCsinglePart.php?part=".$_GET['partId']."&submit=Generate','myWindow3','left=50,screenX=40,screenY=60,resizable,scrollbars,status,width=1100,height=500'); return false; \"><b>VIEW DETAILS</b></span>";
                    $atcsearch = "<span style='cursor:pointer;color:green;text-decoration:underline;' onclick=\" window.open('../Others/nick/nick_systemATCView.php','myWindow3','left=50,screenX=40,screenY=60,resizable,scrollbars,status,width=1100,height=500'); return false; \"> view ATCTools</span>";
                    // $links="<a href='../Others/Rose/IT/ATCsinglePart.php?part=".$partId."&submit=Generate'><b>VIEW DETAILS</b></a>";
                    //~ echo "ATC4=".$TPP4Stat." ".$links." ".$atcsearch;
                    // echo "<label>".displayText('L92', 'utf8', 0, 0, 1)."</label>";
                    echo "<div class='row w3-padding-top'>";
                        echo "<div class='col-md-2'>";
                            echo "<label>".displayText('L1371', 'utf8', 0, 0, 1)."</label> : ";
                            echo "<input class='w3-input w3-border' type='text  name= x'>";
                        echo "</div>";
                        echo "<div class='col-md-2'>";
                            echo "<label>".displayText('L1372', 'utf8', 0, 0, 1)."</label> : ";
                            echo "<input class='w3-input w3-border' type='text name= y'>";
                        echo "</div>";
                        echo "<div class='col-md-2'>";
                            echo "<label>".displayText('L43', 'utf8', 0, 0, 1)."</label> : ";
                            echo "<input class='w3-input w3-border' type='text name=itemCount'>";
                        echo "</div>";
                    echo "</div>";
					if($howManyProgram==1){$sqlProg = "SELECT programCode, programX, programY, programI, programJ, programGZero, quantity FROM engineering_programs WHERE partId = ".$_GET['partId']."";$getPrograms = $db->query($sqlProg);}
					else if($howManyProgram>1 and $_GET['programId']==0){$sqlProg = "";$getPrograms=array();}
					else if($_GET['programId']>0){$sqlProg = "SELECT programCode, programX, programY, programI, programJ, programGZero, quantity FROM engineering_programs WHERE partId = ".$_GET['partId']." and programId = ".$_GET['programId'];$getPrograms = $db->query($sqlProg);}
                    
                    $sql = "SELECT programStatus, programmer, authorizer FROM engineering_programs WHERE partId = ".$_GET['partId']."";
                    $getProgrammer = $db->query($sql);
                    if($getProgrammer->num_rows > 0)
                    {
                        $getProgrammerResult = $getProgrammer->fetch_array();
                        echo "<label style = 'float: left; font-size: 12px;'>".displayText('L1378').": ".ucfirst($getProgrammerResult['programmer'])."</label><br>";//L1378 Programmer
                        echo "<label style = 'float: left; font-size: 12px;'>".displayText('L1379').": "; if($getProgrammerResult['programStatus'] == 0){ echo 'Pending'; }else{ echo ucfirst($getProgrammerResult['authorizer']); } echo "</label>";//L1379 Checker
                    }
                    
                    //~ $sql = "SELECT programCode, programX, programY, programI, programJ, programGZero FROM engineering_programs WHERE partId = ".$_GET['partId']." LIMIT 1 ";
                    //~ $getPrograms = $db->query($sql);
                    if($getPrograms->num_rows > 0)
                    {
                    ?>
                        <span style='float: right;'>
                        <a onclick = "window.open('anthony_programCodePreview.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>', '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=250 left=290, width=355, height=493')" style = "font-size: 12px;"><?php echo displayText('L1193');//Preview?></a>&nbsp;
                        <a onclick = "window.open('/<?php echo v; ?>/79 Program SimulatorBETA/gerald_tppProgramSimulationSingleV2.php?partId=<?php echo $_GET['partId']; ?>', '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=50 left=290, width=1000, height=800')" style = "font-size: 12px;"><?php echo displayText('L3891');//Program Simulator?></a>
                        </span>
                    <?php				
                    }
                    
					$laserStatus = '0';
					$idNumber = '';
					$sql = "SELECT status, idNumber, programName FROM engineering_laserprogram WHERE partId = ".$_GET['partId']." LIMIT 1";
					$queryLaserProgram = $db->query($sql);
					if($queryLaserProgram->num_rows > 0)
					{
						$resultLaserProgram = $queryLaserProgram->fetch_array();
						$laserStatus = $resultLaserProgram['status'];
						$idNumber = $resultLaserProgram['idNumber'];
						$programName = $resultLaserProgram['programName'];
					}
					
					$firstName = '';
					if(trim($idNumber)!="")
					{
					$sql = "SELECT firstName FROM hr_employee WHERE idNumber LIKE '".$idNumber."' LIMIT 1";
					$queryEmployee = $db->query($sql);
					if($queryEmployee->num_rows > 0)
					{
						$resultEmployee = $queryEmployee->fetch_array();
						$firstName = $resultEmployee['firstName'];
					}
					}
                    
                    $firstName = ($laserStatus!=0) ? $firstName : '';
                    
                    $statusCheck = ($laserStatus == '1') ? 'checked' : '';
                    echo "<form id='formSave' action='".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&src=programs&patternId=".$_GET['patternId']."&type=programName' method='POST'></form>";
                    echo "<div class='row w3-padding-top'>";
                        echo "<div class='col-md-12'>";
                            echo "<span style = 'float: left; font-size: 12px;'>".displayText('L1384')." : <input type='checkbox' ".$statusCheck." onchange=\" location.href='".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&src=programs&patternId=".$_GET['patternId']."&type=laserStatus&laserStatus=".$laserStatus."'; \">";//L1384 Laser Program
                            if($statusCheck == 'checked')
                            {
                                echo "<input required type='text' name='programName' value='".$programName."' form='formSave' autofocus placeholder='".displayText('L2146')."'> 
                                    <input type='submit' form='formSave' name='save' value='Save'>
                                        &emsp;";
                            }
                            echo "".$firstName."</span>";
                                
                            $remarksJoint = "";
                            $remarksCheck = "";
                            $ncData = 0;
                            $sql = "SELECT remarks, jointNote, ncData FROM engineering_partsCheck where partId=".$_GET['partId'];
                            $partListQuery = $db->query($sql);
                            if($partListQuery->num_rows > 0)
                            {
                                $partListQueryResult = $partListQuery->fetch_array();
                                $remarksJoint = " : ".$partListQueryResult['jointNote'];
                                $remarksCheck = " : ".$partListQueryResult['remarks'];	
                                $ncData = $partListQueryResult['ncData'];	
                            }
                            
                            $ncDataCheck = ($ncData==3) ? 'checked' : '';
                            
                            echo "<span style = 'float: left; font-size: 12px;'>&nbsp;".displayText('L4146')." : <input type='checkbox' ".$ncDataCheck." onchange=\" location.href='".$_SERVER['PHP_SELF']."?partId=".$_GET['partId']."&src=programs&patternId=".$_GET['patternId']."&type=ncData&ncData=".$ncData."'; \"></span>";//L4146 NC DATA
                        echo "</div>";
                    echo "</div>";
                    echo "<table class='table table-condensed table-bordered table-striped'>";
                        echo "<thead class='w3-indigo' style='text-transform:uppercase;'>";
                            echo "<th style='cursor:pointer;' class='w3-center' onclick=\"TINY.box.show({url:'rose_addJointForm.php?partId=".$_GET['partId']."',width:'',height:'',opacity:10,topsplit:6,animate:false,close:true})\">";
                            ?>
                            Common cut <?php echo $remarksJoint; ?>
                            <?php
                            echo "</th>";
							$laserParameterName ="";
									$sqlLaser = "SELECT materialId FROM system_laserparameter where listId=".$partsQueryResult['laserParameterId'];
									$partLaserQuery = $db->query($sqlLaser);
									if($partLaserQuery->num_rows > 0)
									{
										$partLaserQueryResult = $partLaserQuery->fetch_array();
										$laserParameterName = $partLaserQueryResult['materialId'];
											
									}                            
                            
                            //~ echo "<th style='cursor:pointer;' class='w3-center' onclick=\"TINY.box.show({url:'rose_addCheckForm.php?partId=".$_GET['partId']."',width:'',height:'',opacity:10,topsplit:6,animate:false,close:true})\">";
                            echo "<th style='cursor:pointer;' class='w3-center' onclick=\"TINY.box.show({url:'rose_addLaserMaterialForm.php?partId=".$_GET['partId']."',width:'',height:'',opacity:10,topsplit:6,animate:false,close:true})\">";
                            ?>
                            Laser material:<?php echo $laserParameterName; ?>
                            <?php
                            echo "</th>";
                        echo "</thead>";
                    echo "</table>";
                    if($getPrograms->num_rows > 0)
                    {
						if($howManyProgram>1)echo "<font size=5><a href='anthony_editProduct.php?partId=".$_GET['partId']."&src=programs&patternId=".$_GET['patternId']."'>Select other program</a></font><br>";
                        $getProgramsResult = $getPrograms->fetch_array();
                        $programCode = $getProgramsResult['programCode'];
                        echo "G98 "."&nbsp;X: <input type = 'text' class = 'float' name = 'x' id = 'x' value = '"; if($getProgramsResult['programX'] == ''){ echo '15'; }else{ echo $getProgramsResult['programX']; } echo "' style = 'width: 75px;' readonly form='programFormId'>&nbsp;&nbsp;&nbsp;";
                        echo "Y: <input type = 'text' class = 'float' name = 'y' id = 'y' value = '"; if($getProgramsResult['programY'] == ''){ echo '65'; }else{ echo $getProgramsResult['programY']; } echo "' style = 'width: 75px;' readonly form='programFormId'>&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo "I: <input type = 'text' class = 'float' name = 'i' id = 'i' value = '"; if($getProgramsResult['programI'] == ''){ echo ''; }else{ echo $getProgramsResult['programI']; } echo "' style = 'width: 75px;' readonly form='programFormId'>&nbsp;&nbsp;&nbsp;";
                        echo "J: <input type = 'text' class = 'float' name = 'j' id = 'j' value = '"; if($getProgramsResult['programJ'] == ''){ echo ''; }else{ echo $getProgramsResult['programJ']; } echo "' style = 'width: 75px;' readonly form='programFormId'>&nbsp;&nbsp;";				
                        echo "Produce Quantity: <input type = 'text' class = 'float' name = 'produceQuantity' id = 'produceQuantity' value = '"; if($getProgramsResult['quantity'] == ''){ echo ''; }else{ echo $getProgramsResult['quantity']; } echo "' style = 'width: 75px;' readonly form='programFormId'>&nbsp;&nbsp;";				
                        ?><br>
                        <?php echo displayText('L1386');//Exclude?> G00: <input type = 'checkbox' name = 'g' id = 'g' <?php if($getProgramsResult['programGZero'] == 1){ echo 'checked'; } ?> disabled form='programFormId'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label id = 'hide' hidden><?php echo displayText('L242');//Remarks?>: <input type = 'text' name = 'remarks' style = 'width: 300px;' required form='programFormId'></label>
                        <?php
                        echo "<table>";
                            echo "<tbody>";
                                echo "<tr>";
                                    echo "<td><textarea rows = '30.5' cols = '57' id = 'textArea' autofocus name = 'programCode' readonly form='programFormId'>".$getProgramsResult['programCode']."</textarea></td>";
                                echo "</tr>";
                            echo "</tbody>";
                        echo "</table>";
                    }
					else if($howManyProgram>1)
					{
						if($howManyProgram>1 and $_GET['programId']==0)
						{
							echo "Please select from ".count($progIds)." programs<br>";
							for($duts=0;$duts<count($progIds);$duts++)
							{
								echo "<font size=5><a href='anthony_editProduct.php?partId=".$_GET['partId']."&src=programs&patternId=".$_GET['patternId']."&programId=".$progIds[$duts]."'>".$progNames[$duts]."</a></font><br>";
							}									
						}
					}
                    else
                    {
                        echo "<table>";
                            echo "<tbody>";
                                echo "<tr>";
                                    echo "<td class = 'tresult' width = '235'><center>".displayText('L104')."<br>";

                                        ?>
                                        <a onclick="TINY.box.show({url:'anthony_addProgramCodeForm.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>',width:340,height:'',opacity:10,topsplit:6,animate:false,close:true})"><img src = '/<?php echo v;?>/Common Data/Templates/images/add1.png' width = '25' height = '25'></a>
                                        <!---
                                        <img id="addProgram" name="<?php echo $_GET['partId']; ?>" src = '../Common Data/Templates/images/add1.png' width = '25' height = '25'>
                                        -->
                                    <?php
                                    echo "</center></td>";
                                echo "</tr>";
                            echo "</tbody>";
                        echo "</table>";
                    }

                    if($getPrograms->num_rows > 0)
                    {
                        echo "<center><input type = 'button' name = 'updateProgramCode' value = '".displayText('L1387')."' class = 'anthony_submit' id = 'programCode' form='programFormId'></center>";
                    }
                }
                ?>
                <form action = "anthony_updateProgramCode.php?partId=<?php echo $_GET['partId']; ?>&programId=<?php echo $_GET['programId']; ?>" method = "POST" id='programFormId'>
            </div>
        </div>
    </div>
    <div id='modal-izi-update'><span class='izimodal-content-update'></span></div> 
</body>
</html>
<script src="/<?php echo v; ?>/Common Data/Libraries/Javascript/jQuery 3.1.1/jquery-3.1.1.js"></script>
<script src="/<?php echo v; ?>/Common Data/Libraries/Javascript/jQuery 3.1.1/jquery-ui.js"></script>
<script src="/<?php echo v; ?>/Common Data/Libraries/Javascript/jQuery 3.1.1/bootstrap.min.js"></script>
<link rel="stylesheet" href="/<?php echo v; ?>/Common Data/Libraries/Javascript/jquery-date-range-picker-master/dist/daterangepicker.min.css">
<script type="text/javascript" src="/<?php echo v; ?>/Common Data/Libraries/Javascript/jquery-date-range-picker-master/moment.min.js"></script>
<script type="text/javascript" src="/<?php echo v; ?>/Common Data/Libraries/Javascript/jquery-date-range-picker-master/dist/jquery.daterangepicker.min.js"></script>
<script src="/<?php echo v; ?>/Common Data/Libraries/Javascript/Super Quick Table/datatables.min.js"></script>
<link rel="stylesheet" href="/<?php echo v; ?>/Common Data/Libraries/Javascript/Bootstrap Multi-Select JS/dist/css/bootstrap-multiselect.css" type="text/css" media="all" />
<script src="/<?php echo v; ?>/Common Data/Libraries/Javascript/Bootstrap Multi-Select JS/dist/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="/<?php echo v; ?>/Common Data/Libraries/Javascript/iziModal-master/css/iziModal.css" />
<script src="/<?php echo v; ?>/Common Data/Libraries/Javascript/iziModal-master/js/iziModal.js"></script>
<link rel="stylesheet" href="/<?php echo v; ?>/Common Data/Libraries/Javascript/iziToast-master/dist/css/iziToast.css" />
<script src="/<?php echo v; ?>/Common Data/Libraries/Javascript/iziToast-master/dist/js/iziToast.js"></script>
<!--<script src='/<?php echo v; ?>/Common Data/Libraries/Javascript/checkProgramCode.js'></script>--><!--tangal muna 2020-09-23-->
<script type="text/javascript" src="/<?php echo v; ?>/Common Data/Libraries/Javascript/Tiny Box/tinybox.js"></script>
<link rel="stylesheet" href="/<?php echo v; ?>/Common Data/Libraries/Javascript/Tiny Box/stylebox.css" />
<script>
function openNav() {
  document.getElementById("mySidebar").style.width = "520px";
  document.getElementById("main").style.marginLeft = "520px";
}

function closeNav() {
  document.getElementById("mySidebar").style.width = "0";
  document.getElementById("main").style.marginLeft= "0";
}

function selectTextareaLine(tarea,lineNum) {
    lineNum--; // array starts at 0
    var lines = tarea.value.split("\n");

    // calculate start/end
    var startPos = 0, endPos = tarea.value.length;
    for(var x = 0; x < lines.length; x++) {
        if(x == lineNum) {
            break;
        }
        startPos += (lines[x].length+1);

    }

    var endPos = lines[lineNum].length+startPos;

    // do selection
    // Chrome / Firefox

    if(typeof(tarea.selectionStart) != "undefined") {
        tarea.focus();
        tarea.selectionStart = startPos;
        tarea.selectionEnd = endPos;
        return true;
    }

    // IE
    if (document.selection && document.selection.createRange) {
        tarea.focus();
        tarea.select();
        var range = document.selection.createRange();
        range.collapse(true);
        range.moveEnd("character", endPos);
        range.moveStart("character", startPos);
        range.select();
        return true;
    }

    return false;
}

$(document).ready(function(){
    openNav();

	$("#defaultPatternCheckId").click(function(){
		if(confirm('Are you sure you want to make this as a default pattern?'))
		{
            $.ajax({
                url 	: "<?php echo $_SERVER['PHP_SELF'];?>",
                data 	: {
                            ajax 		: 'updateDefaultPattern',
                            partId 		: '<?php echo $_GET['partId'];?>',
                            patternId  : '<?php echo $_GET['patternId'];?>'
                            },
                type 	: "POST",
                success : function(data){
                        location.reload();
                }
			});
		}
	});

    $("#buttonStatus").click(function(){
        var partStatus = $("#partStatus").val();
        var partId = "<?php echo $_GET['partId']?>";
        $.ajax({
            url 	: "raymond_updateStatus.php",
            data 	: {
                        partId 		: partId,
                        partStatus  : partStatus
                        },
            type 	: "POST",
            success : function(data){
                        console.log(data);
                        location.reload();
            }
        });
    });

    $("#patternId").change(function(){
        var loc = '<?php echo $_SERVER['PHP_SELF']; ?>?partId=<?php echo $_GET['partId']; ?>&src=<?php echo $_GET['src']; ?>&patternId='+$("#patternId").val();
        window.location = loc;
    });
    
    $("#first").click(function(){
        if($("#first").attr('src')=='../Common Data/Templates/images/edit1.png'){
            $("img.switch").show();
            $("#first").attr('src','../Common Data/Templates/images/close1.png');
            $('input.checkBox').hide();
        }
        else if($("#first").attr('src')=='../Common Data/Templates/images/close1.png'){
            $("img.switch").hide();
            $("#first").attr('src','../Common Data/Templates/images/edit1.png');
            $('input.checkBox').show();
        }
    });
    
    <?php
    if(isset($_SESSION['button']) AND $_SESSION['button'] == 'pending')
    { ?>
        $("#closeWindow").click(function(){
            window.close();
        });
    <?php
    } ?>
    
    $("a.ask").click(function(e){
        if($(this).attr('name')=='qcDel'){
            var question = "Are you sure you want to add QC-Delivery process?";
        }else if($(this).attr('name')=='qcDelS'){
            var question = "Are you sure you want to add QC-Delivery with Subcon process?";
        }else if($(this).attr('name')=='paint'){
            var question = "Are you sure you want to add Painting process?";
        }else if($(this).attr('name')=='print'){
            var question = "Are you sure you want to add Printing process?";
        }else if($(this).attr('name')=='paintPrint'){
            var question = "Are you sure you want to add Paint Printing process?";
        }else if($(this).attr('name')=='qcAssy'){
            var question = "Are you sure you want to add QC-Assembly with Subcon process?";
        }else if($(this).attr('name')=='primingProcess'){
            var question = "Are you sure you want to add Priming process?";
        }else if($(this).attr('name')=='jamco'){
            var question = "Are you sure you want to add JAMCO process?";
        }
            
        if(confirm(question) == false){
            e.preventDefault();
        }
    });
    
    $("a.delete").click(function(e){
        if($(this).attr('name') == 'deleteSubparts'){
            var del = "Are you sure you want to delete this subpart?";
        }else if($(this).attr('name') == 'deleteAccessory'){
            var del = "Are you sure you want to delete this accessory?";
        }else if($(this).attr('name') == 'deleteSubcon'){
            var del = "Are you sure you want to delete this subcon?";
        }
        
        if(confirm(del) == false){
            e.preventDefault();
        }
    });
    
    $("#textArea").blur(function(){
        var thisObj = $(this);
        var noError = checkProgramCode(thisObj);
        //~ if(noError==false && $("#programCode").val()=='Edit')
        if(noError==false && $("#programCode").val()=='<?php echo displayText('L1387'); ?>')
        {
            $("#programCode").click();
        }
        
        
        
        if($(this).val()!='')
        {
            $.ajax({
                url:'anthony_addProgramCodeFormSQL.php',
                type:'post',
                data:{
                    type:'checkAjax',
                    programCode:$(this).val()
                },
                success:function(data){
                    if(data.trim()!='')
                    {
                        //alert(data); // ROSEMIE PROGRAM TPP 2020-08-03 by shachou
                        var dataArray = data.split(" ");
                        var lineNumber = dataArray[(dataArray.length - 1)];
                        var tarea = document.getElementById('textArea');
                        selectTextareaLine(tarea,lineNumber);
                    }
                }
            });
        }
    });		
    
    $("#programCode").click(function(){
        //~ if($("#programCode").attr('value') == 'Edit'){
		if($("#programCode").attr('value') == '<?php echo displayText('L1387'); ?>'){
            //~ $("#programCode").attr('value', 'Update');
            $("#programCode").attr('value', '<?php echo displayText('L1054'); ?>');
            $("#x").attr('readonly', false);
            $("#y").attr('readonly', false);
            $("#i").attr('readonly', false);
            $("#j").attr('readonly', false);
            $("#g").attr('disabled', false);
            $("#produceQuantity").attr('readonly', false);
            $("#textArea").attr('readonly', false).focus();
            $("#hide").attr('hidden', false);
        }
        else
        {
            $("#programCode").attr('type', 'submit');
        }
    });
    
    $("#addProgram").click(function(){
        if(<?php echo $partsQueryResult['x'];?> > 0 && <?php echo $partsQueryResult['y'];?> > 0)
        {
            openTinyBox('','','anthony_addProgramCodeForm.php?partId='+$(this).attr('name'));
        }
        else
        {
            alert('Please input Item X/Y first!');
        }
    });
    
    $('input.float').keypress(function(event){
        if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)){
            event.preventDefault();
        }
    });
    
    $("#qcsub").click(function(e){
        var qcsubcon = prompt("How many Subcon?");			
        
        if(qcsubcon){
            TINY.box.show({url:'anthony_multipleSubcon.php?partId=<?php echo $_GET['partId']; ?>&source=qcsubcon&hm='+qcsubcon,width:'',height:'',opacity:10,topsplit:6,animate:false,close:true})
            e.preventDefault();
        }
    });
    
    $("#qcassysub").click(function(e){
        var qcassysubcon = prompt("How many Subcon?");			
        
        if(qcassysubcon){
            TINY.box.show({url:'anthony_multipleSubcon.php?partId=<?php echo $_GET['partId']; ?>&source=qcassysubcon&hm='+qcassysubcon,width:'',height:'',opacity:10,topsplit:6,animate:false,close:true})
            e.preventDefault();
        }
    });
    
    $('input.checkBox').change(function(){
        if($('input.checkBox').is(':checked'))
        {
            $('#delete').show();
        }
    });
    
    $('#delete').click(function(){
        if($('input.checkBox').is(':checked'))
        {
            var chk = 'input.checkBox[name="anthony[]"]:checked';
            //~ var chkSize = $(chk).size();
            var chkSize = $(chk).length;
            var chkVal = $(chk).map(function(){
                return $(this).val();
            }).get();
            TINY.box.show({url:'anthony_deleteForm.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>&count='+chkVal,width:'',height:'',opacity:10,topsplit:6,animate:false,close:true});
        }
        return false;
    });
    
    $("#openDrawing").click(function(){	<?php			
        if(file_exists($locationMasterFolder))
        { ?>
            window.open('<?php echo $locationMasterFolder; ?>', 'customer', 'toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=750, width=400, height=400'); <?php 
        } 
        
        if(file_exists($locationArktechFolder))
        { ?>
            window.open('<?php echo $locationArktechFolder; ?>', 'arktech', 'toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=250, width=400, height=400'); <?php 
        } ?>	
    });
    
    $("input.itemHandlingClass").change(function(){
        var itemHandlingFlag = ($(this).is(':checked')) ? 1 : 0;
        
        $.ajax({
            url:'<?php echo $_SERVER['PHP_SELF'];?>',
            type:'post',
            data:{
                ajaxType:'updateItemHandlingFlag',
                count:$(this).val(),
                itemHandlingFlag:itemHandlingFlag
            },
            success:function(data){
                if(data.trim()!='')
                {
                    alert(data);
                }
            }
        });
    });
    
    <?php
        if($_GET['src'] == "programs")
        {
            if($_GET['action']=='updateProgramCode')
            {
                ?>
                var response = confirm("Program edited. \nDo you want to update Tool List?");
                if(response)
                {
                    window.open('../toollistTPP/toolistInputform.php?partId=<?php echo $_GET['partId']; ?>&TOOLLIST=Make+TOOL+LIST&action=updateProgramCode', '_blank', 'width=1150,height=650,status=yes,resizable=yes,scrollbars=yes');
                }
                location.href = 'anthony_editProduct.php?partId=<?php echo $_GET['partId'];?>&programId=<?php echo $_GET['programId'];?>&src=programs';
                <?php
            }
            else
            {
                $programCodeLineArray = preg_split('/\s+/', $programCode);
                
                $programStationArray = array();
                $stationArray = array();
                foreach($programCodeLineArray as $line)
                {
                    if(strstr($line, '(')===FALSE)
                    {
                        $station = strstr($line, 'T');
                        $stationPart = explode("C",$station);
                        $programStationArray[] = $stationPart[0];
                    }
                }
                
                $programStationArray = array_values(array_unique(array_filter($programStationArray)));
                
                $sql = "SELECT station FROM engineering_toollist where partId = ".$_GET['partId']." LIMIT 1";
                $queryToolList = $db->query($sql);
                if($queryToolList->num_rows > 0)
                {
                    $resultToolList = $queryToolList->fetch_array();
                    $station = $resultToolList['station']; 
                }
                $toolListStationArray = explode("`", $station);
                
                $toolListStationArray = array_values(array_unique(array_filter($toolListStationArray)));
                
                //~ $programStationArray[] = 'T7';
                
                $resultArray1 = array_diff($programStationArray, $toolListStationArray);
                
                $resultArray2 = array_diff($toolListStationArray, $programStationArray);
                
                if(count($resultArray1) > 0 OR count($resultArray2) > 0)
                {
                    ?>
                    //alert('Stations in tool list not match in program');
                    //window.open('../toollistTPP/toolistInputform.php?partId=<?php echo $_GET['partId']; ?>&TOOLLIST=Make+TOOL+LIST&action=updateProgramCode', '_blank', 'width=1150,height=650,status=yes,resizable=yes,scrollbars=yes');
                    <?php
                }
            }
        }
    ?>
});
</script>
<script type="text/javascript">

//rosemie Start
$("td.processOrderClass").dblclick(function(){
	$(this).attr('contentEditable','true');
	$(this).focus();
});

$("td.processOrderClass").blur(function(){
	var processRemarks = $(this).text();
	//~ if(processRemarks.trim()!='')
	//~ {
		var workscheduleId = $(this).attr('id');
		//alert("rose"+workscheduleId);
		$.ajax({
			url		: "<?php echo $_SERVER['PHP_SELF'];?>",
			type	: "POST",
			data	:{
				ajax:'updateProcessOrder',
				workscheduleId:workscheduleId,
				processRemarks:processRemarks
			},
			success	: function(data){
				//~ location.reload();
			}
		});
	//~ }
	
	$(this).attr('contentEditable','false');
});
//rosemie end

function openTinyBox(w,h,url,post,iframe,html,left,top)
{
	TINY.box.show({
		url:url,width:w,height:h,post:post,html:html,opacity:20,topsplit:6,animate:false,close:true,iframe:iframe,left:left,top:top,
		boxid:'box',
		openjs:function(){
			var windowHeight = $(window).height() / 1.5;
			var tinyBoxHeight = $("#box").height();
			if(tinyBoxHeight > (windowHeight))
			{
				$("#tableDiv").css({'overflow-y':'scroll','overflow-x':'hidden','height':(windowHeight) + 'px'});
				$("#box").css('height',(windowHeight) +'px');
				$("#box").css('width',($("#box").width() + 20 ) +'px');
			}
			
			$("#txtAreaProgramCode").blur(function(){
				if($(this).val()!='')
				{
					$.ajax({
						url:'anthony_addProgramCodeFormSQL.php',
						type:'post',
						data:{
							type:'checkAjax',
							programCode:$(this).val()
						},
						success:function(data){
							if(data.trim()!='')
							{
								//alert(data);// ROSEMIE PROGRAM TPP 2020-08-03 by shachou
								var dataArray = data.split(" ");
								var lineNumber = dataArray[(dataArray.length - 1)];
								var tarea = document.getElementById('txtAreaProgramCode');
								selectTextareaLine(tarea,lineNumber);
							}
						}
					});
				}
			});
			
			$("img.editTools").click(function(){
				openTinyBox('','','gerald_tinyBoxTools.php?type=editTool','listId='+$(this).attr('name'));
			});
		}

		
	});
}
//ras
function openTinyBox(w,h,url,post,iframe,html,left,top)
{
	TINY.box.show({
		url:url,width:w,height:h,post:post,html:html,opacity:20,topsplit:6,animate:false,close:true,iframe:iframe,left:left,top:top,
		boxid:'box',
		openjs:function(){		

            $("#addNotesBTN").click(function(){
                $(this).hide();
                // $("#formNotes").submit();
            });

			var tapping = ['122','123','124'];
			var bending = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','31','32','33','34','46','47','48','261','262','283'];
			if($.inArray($("#processName").val(),bending) != -1)
			{
				$("#showMe").show();
			}
			else
			{
				$("#showMe").hide();
			}

			if($.inArray($("#processName").val(), tapping) !== -1)
			{
				$("#showTapp").show();
			}
			else
			{
				$("#showTapp").hide();
			}
			var counter = 2;
			$("#addButton").click(function(){
				if(counter>5){
					alert("Only 5 textboxes allow!");
					return false;
				}
				var newTextBoxDiv = $(document.createElement('div'))
					 .attr("id", 'TextBoxDiv' + counter);
				newTextBoxDiv.after().html(
					  '<center><input type="text" style="height:30px;width:230px" name="inputNote[]' + counter +
					  '" id="textbox' + counter + '" value="" ></center>');
				newTextBoxDiv.appendTo("#TextBoxesGroup");
				
				counter++;
				
			});

			var counter = 2;
			$("#addButton2").click(function(){
				if(counter>5){
					alert("Only 5 textboxes allow!");
					return false;
				}
				var newTextBoxDiv = $(document.createElement('div'))
					 .attr("id", 'TextBoxDiv2' + counter);
				newTextBoxDiv.after().html(
					  '<p align="left"><input type="text" style="height:30px;width:150px" name="inputNote[]' + counter +
					  '" id="textbox' + counter + '" value="" >&emsp;&emsp;<input id="" type="checkbox" name="commonNote[]'+counter+'" value="1"></p>');
				newTextBoxDiv.appendTo("#TextBoxesGroup2");
				// CHICHA EDIT LATER

				// var newTextBoxDiv2 = $(document.createElement('div'))
				// 	 .attr("id", 'TextBoxDiv3' + counter);
				// newTextBoxDiv2.after().html(
				// 	  '<tr><td><input id="" type="checkbox" onchange=""></td></tr>');
				// newTextBoxDiv2.appendTo("#TextBoxesGroup3");
				
				counter++;
				
			});
			
						
			
			$("#processName").change(function(){				
				$.ajax({
					url		: "anthony_addProcessDetailFormAjax.php",
					type	: "post",
					data	:	{type:'processSection',
								 processCode:$('#processName').val(),partId:$('#partId').val()
								},
					success	: function(data){
						$("#sectionName").html(data);
					}
				});
								
				var processNameCode = $("#processName").val();
				
				if($.inArray(processNameCode,bending) != -1)
				{
					$("#showMe").show();
				}
				else
				{
					$("#showMe").hide();
				}
			});
			
			$("#partId").change(function(){
				$.ajax({
					url		: "anthony_cloneAjax.php",
					type	: "post",
					data	:	{type:'cloneProcess',
								 partId:$('#partId').val()
								},
					success	: function(data){
						$("#pattern").html(data);
					}
				});
			});
			
			$("#patternprocess").change(function(){
				$.ajax({
					url		: 'anthony_checkExistingProcess.php',
					type	: 'POST',
					data	: {
								partId		: <?php echo $_GET['partId']; ?>,
								patternId		: <?php echo $_GET['patternId']; ?>,
								patternProcess 	: $("#patternprocess").val()
							  },
					success	: function(data){
						if(data == '1')
						{
							alert("Duplicate Process Detected! Try to Select Other Pattern to Avoid Jumble of Process Order!");
							$("#disabled_button").prop('disabled', true);
						}
						else
						{
							$("#disabled_button").prop('disabled', false);
						}
					}
				});
			});

			$('#subprocessSelect').multiselect({
		            maxHeight: 300,
		            includeSelectAllOption: true,
		            buttonClass:'btn btn-default',
		            buttonWidth: '165px',
		            nonSelectedText : 'Select Sub Process',
		            numberDisplayed: 0,
		            onSelectAll: function(event) {
		                event.preventDefault();
		            },
		            onDeselectAll: function(event) {
		                event.preventDefault();
		            },
		            onChange: function(event) {
		                event.preventDefault();
		            }
	      	  });	

	      	$("#subProcessCheckAll").change(function(){
				if($(this).is(':checked'))
				{
					$("input.subProcessCheck").prop('checked',true);
				}
				else
				{
					$("input.subProcessCheck").prop('checked',false);
				}
			});
			
	      	$("#deleteSelectedSubProcess").click(function(){
				if($('input.subProcessCheck').is(':checked'))
				{
					var chk = 'input.subProcessCheck:checked';
					//~ var chkSize = $(chk).size();
					var chkSize = $(chk).length;
					var chkVal = $(chk).map(function(){
						return $(this).val();
					}).get();
					TINY.box.show({url:'<?php echo "anthony_deleteProcessDetailForm.php?partId=".$_GET['partId']."&processOrder=".$processOrder."&patternId=".$_GET['patternId']."";?>',post:'subProcessListIdArray='+chkVal,width:'',height:'',opacity:10,topsplit:6,animate:false,close:true});
				}
				return false;
			});			
		}
	});
}

function openJS()
{

	$("#addNote").click(function(){
		var partId = $("#partId").val();
		var noteDetail = $("#noteDetail").val();
		var noteNumber = $("#noteNumber").val();
		var patternId = '<?php echo $_GET['patternId']; ?>';
		var point = 0;

		if($("#point").is(':checked'))
		{
			point = 1;
		}

		$.ajax({
				url		: 'raymond_updateSpecificationData.php?type=addNote',
				type	: 'POST',
				data	: { 
							noteDetail 		: noteDetail,
							noteNumber		: noteNumber,
							point 			: point,
							partId 			: partId
						  },
			  	success : function(data)
						 {
						 	// alert(data);
					 		location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?partId="+partId+"&src=specifications&patternId="+patternId;
						 }
		});
	});

	$("#updateNote").click(function(){
		var partId = '<?php echo $_GET['partId']; ?>';
		var noteDetail = $("#noteDetail").val();
		var noteNumber = $("#noteNumber").val();
		var noteId = $("#noteId").val();
		var patternId = '<?php echo $_GET['patternId']; ?>';
		var point = 0;

		if($("#point").is(':checked'))
		{
			point = 1;
		}

		$.ajax({
				url		: 'raymond_updateSpecificationData.php?type=updateNote',
				type	: 'POST',
				data	: { 
							noteDetail 		: noteDetail,
							noteNumber		: noteNumber,
							point 			: point,
							noteId 			: noteId
						  },
			  	success : function(data)
						 {
						 	// alert(data);
					 		location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?partId="+partId+"&src=specifications&patternId="+patternId;
						 }
		});
	});

	$('#addSpecifications').click(function(){
		var detailNumber = $("#detailNumber").val();
		var specificationId = $("#specificationId").val();
		var partId = '<?php echo $_GET['partId']; ?>';
		var patternId = '<?php echo $_GET['patternId']; ?>';
		var point = 0;
		if($("#point").is(':checked'))
		{
			point = 1;
		}

		$.ajax({
				url		: 'raymond_errorCheckingData.php',
				type	: 'POST',
				data	: { 
							detailNumber 	: detailNumber,
							specificationId	: specificationId,
							partId			: partId,
							point 			: point
						  },
				success : function(result)
						 {
						 	if(result == "success")
						 	{
						 		location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?partId="+partId+"&src=specifications&patternId="+patternId;
						 	}
						 	else if(result == "exist")
						 	{
						 		alert("Detail Number Already Exist!");
						 	}
						 	else
						 	{
						 		alert("Detail Number Does Not Exist in database!")
						 	}
						 }
		});
	});

	$('#updateSpecifications').click(function(){
		var detailId = $("#detailId").val();
		var specificationId = $("#specificationId").val();
		var detailNumber = $("#detailNumber").val();
		var point = 0;
		var partId = '<?php echo $_GET['partId']; ?>';
		var patternId = '<?php echo $_GET['patternId']; ?>';

		if($("#point").is(':checked'))
		{
			point = 1;
		}


		$.ajax({
				url		: 'raymond_updateSpecificationData.php?type=update',
				type	: 'POST',
				data	: { 
							detailNumber 	: detailNumber,
							specificationId	: specificationId,
							detailId		: detailId,
							point 			: point,
							partId 			: partId
						  },
			  	success : function(data)
						 {
						 	if(data == "success")
						 	{
						 		location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?partId="+partId+"&src=specifications&patternId="+patternId;
						 	}
						 	else
						 	{
						 		alert("Detail Number Already Exist!");
						 	}
						 }
		});
	});
	
	$("#materialType").change(function(){//;cadcam_materialspecs;
		var materialTypeId = $(this).val();
		$.ajax({
			url:'anthony_addParts.php',
			type:'post',
			data:{
				ajaxType:'getThickness',
				materialTypeId:materialTypeId
			},
			success:function(data){
				//~ alert('asd');
				$("#metalThickness").html(data);
				$("#matSpecsId").val('0');
			}
		});
	});
	
	$("#metalThickness").change(function(){//;cadcam_materialspecs;
		$.ajax({
			url:'anthony_addParts.php',
			type:'post',
			data:{
				ajaxType:'getMaterialSpecId',
				metalThickness:$(this).val(),
				materialTypeId:$("#materialType").val()
			},
			success:function(data){
				$("#matSpecsId").val(data);
			}
		});
	});	
}

function openJSAddAccessory(){
	$(document).ready(function(){
		$("#accessoryName").on("change",function(){
			var accessoryId = $(this).val();
			if(accessoryId != '')
			{
				$(".thead").css("width", "100%");
				// $(".tbody").css("height", "1%");
			}
			else
			{
				$(".thead").css("width", "calc(100% - 1.3em)");
				// $(".tbody").css("height", "200px");
			}

			$.ajax({
				url 	: 'raymond_accessoryDataAJAX.php',
				type 	: 'POST',
				data 	: {accessoryId 	: accessoryId},
				success : function(result){
						  // console.log(result);
						$("#ajaxResult").html(result);
				}
			});
		});

		var accessoryId = $("#accessoryName").val();
		if(accessoryId != '')
		{
			$(".thead").css("width", "100%");
			// $(".tbody").css("height", "1%");
		}
		else
		{
			$(".thead").css("width", "calc(100% - 1.3em)");
			// $(".tbody").css("height", "200px");
		}

		$.ajax({
			url 	: 'raymond_accessoryDataAJAX.php',
			type 	: 'POST',
			data 	: {accessoryId 	: accessoryId},
			success : function(result){
					  // console.log(result);
					$("#ajaxResult").html(result);
			}
		});
	});
}

function openJSedit(){
	$(document).ready(function() {
		var tapping = ['122','123','124'];
		var bending = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','31','32','33','34','46','47','48','261','262','283'];
		if($.inArray($("#processName").val(),bending) != -1)
		{
			$("#showBend").show();
		}
		else
		{
			$("#showBend").hide();
		}

		if($.inArray($("#processName").val(), tapping) !== -1)
		{
			$("#showTapp").show();
		}
		else
		{
			$("#showTapp").hide();
		}
		
		$("#processName").change(function(){				
			$.ajax({
				url		: "anthony_addProcessDetailFormAjax.php",
				type	: "post",
				data	:	{type:'processSection',
							 processCode:$('#processName').val(),partId:$('#partId').val()
							},
				success	: function(data){
					$("#sectionName").html(data);
				}
			});
							
			var processNameCode = $("#processName").val();
			
			if($.inArray(processNameCode,bending) != -1)
			{
				$("#showBend").show();
			}
			else
			{
				$("#showBend").hide();
			}

			if($.inArray($("#processName").val(), tapping) !== -1)
			{
				$("#showTapp").show();
			}
			else
			{
				$("#showTapp").hide();
			}
		});

		$("#addRow").click(function(){
	        var addRowValue = "<tr><td align='center' style='width:;'><input form='formUpdate' type='text' name = 'tapSize[]' value=''></td><td align='center' style='width:;'><input form='formUpdate' type='number' step = 'any' name = 'tapCount[]' min = '0.1' value=''></td><td align='center'></td></tr>";
	        $("table .tbody").append(addRowValue);
	        $("table .tbody").stop().animate({ scrollTop: $(document).height() }, 500);
	    });

	    $("tr.removeRow").each(function() {
	    	var index = $(this).index();
	    	var noteId = $(this).prop('id');
	    	console.log(noteId);
	    	$(".deleteRow"+index).click(function(){
	    		var res = confirm("Are you sure?");
				if(res)
				{
		    		$.ajax({
	    				url 	: 'raymond_deleteProcessNoteAJAX.php?type=tap',
	    				type 	: 'POST',
	    				data 	: {
	    							noteId : noteId 
	    						  },
	    				success : function(data)
	    						{
	    							console.log(data);
	    							$("tr.removeRow").eq(index).remove();
	    						}
	    			});
				}
			});
		});

		$("#addRowBending").click(function(){
	        var addRowValue = "<tr>"+
	        					  "<td align='center' style='width:;'><input form='formUpdate' type='number' step = 'any' min = '0.1' name = 'vSize[]' value=''></td>"+
	        				　　　　　"<td align='center' style='width:;'><input form='formUpdate' type='number' step = 'any' min = '0.1' name = 'punchR[]'value=''></td>"+
	        				　　　　　"<td align='center' style='width:;'><input form='formUpdate' type='number' step = 'any' min = '0.1' name = 'bendDeduct[]'value=''></td>"+
	        				　　　　　"<td align='center' style='width:;'><input form='formUpdate' type='number' step = 'any' min = '0.1' name = 'superyagenHeight[]'value=''></td>"+
	        				　　　　　"<td align='center' style='width:;'><input form='formUpdate' type='number' step = 'any' min = '0.1' name = 'superyagenDistance[]'value=''></td>"+
	        					  "<td align='center'></td>"+
	        				  "</tr>";
	        $("table .tbodyBending").append(addRowValue);
	        $("table .tbodyBending").stop().animate({ scrollTop: $(document).height() }, 500);
	    });

		$("tr.removeRowBending").each(function() {
	    	var index = $(this).index();
	    	var noteId = $(this).prop('id');
	    	console.log(noteId);
	    	$(".deleteRowBending"+index).click(function(){
	    		var res = confirm("Are you sure?");
				if(res)
				{
		    		$.ajax({
	    				url 	: 'raymond_deleteProcessNoteAJAX.php?type=bend',
	    				type 	: 'POST',
	    				data 	: {
	    							noteId : noteId 
	    						  },
	    				success : function(data)
	    						{
	    							console.log(data);
	    							$("tr.removeRowBending").eq(index).remove();
	    						}
	    			});
				}
			});
		});
		$('.deleteValue').click(function(){
			var noteId = $(this).attr("noteId");
			var deleteCounter = $(this).attr("delete-counter");
			// alert(deleteCounter);
			$.ajax({
				url		: 'princess_deleteNotesFormSQL.php',
				type 	: 'POST',
				data	: {
							noteId		: 	noteId,
						  },
				success	: function(data){
					console.log(data);
					$("#deleteVal"+deleteCounter).hide();
				}
			});
		});
		//-------------------------
		$('.deleteValue').click(function(){
			var listId = $(this).attr("listId");
			var deleteCount = $(this).attr("delete-counter");
			// alert(deleteCounter);
			$.ajax({
				url		: 'carlo_deleteToolFormSQL.php',
				type 	: 'POST',
				data	: {
							listId		: 	listId,
						  },
				success	: function(data){
					console.log(data);
					$("#deleteVal"+deleteCounter).hide();
				}
			});
		});
		//-------------------------
		var counter = 1;
		$('.addStandard').click(function(){
			// alert('aaaa');
			var standardSpecId = $("#standard").val();
			var standardVal = $("#standard").find(':selected').attr('data-value');
			if(counter>5){
					alert("Only 5 textboxes allowed!");
					return false;
				}
				var newTextBoxDiv2 = $(document.createElement('div'))
					 .attr("id", 'TextBoxDiv3' + counter);
				newTextBoxDiv2.after().html(
					  '<center><input type="text" style="height:20px;width:100px" name="addStandard[]'+counter+
					  '" id="textbox'+counter+'"value='+standardVal+'><input type="hidden" name="standardSpecId[]'+counter+'" id="textbox'+ counter+'" value='+standardSpecId+'></center>');
				newTextBoxDiv2.appendTo("#TextBoxesGroup3");
				counter++;
		});
	});
}

//TAMANG UPDATE PARTS COMMENT START HERE
function partsComment()
    {
        $("#modal-izi-update").iziModal({
            title                   : '<i class="fa fa-edit"></i>&nbsp;Edit',
            headerColor             : '#1F4788',
            subtitle                : '<b><?php //echo strtoupper(date('F d, Y'));?></b>',
            width                   : 400,
            fullscreen              : false,
            transitionIn            : 'comingIn',
            transitionOut           : 'comingOut',
            padding                 : 20,
            radius                  : 0,
            top                     : 100,
            restoreDefaultContent   : true,
            closeOnEscape           : true,
            closeButton             : true,
            overlayClose            : false,
            onOpening               : function(modal){
                                        modal.startLoading();
                                        // alert(assignedTo);
                                        $.ajax({
                                            url         : 'jhon_updatePartsComment.php',
                                            type        : 'POST',
                                            data        : {
                                                
                                                partId           :'<?php echo $_GET['partId'];?>',
        
                                            },
                                            success     : function(data){
                                                            $( ".izimodal-content-update" ).html(data);
                                                            modal.stopLoading();
                                            }
                                        });
                                    },
            onClosed                : function(modal){
                                        $("#modal-izi-update").iziModal("destroy");
                        } 
        });

        $("#modal-izi-update").iziModal("open");
    }
//TAMANG UPDATE PARTS COMMENT END HERE
</script>
