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
	
	$partId= isset($_GET['partId']) ? $_GET['partId']: "";
	
	$sql = "SELECT lotNumber FROM qc_fai2 WHERE lotNumber IN (select lotNumber from ppic_lotlist where partId =".$partId." and identifier=1 order by lotNumber)";
			$queryFAI = $db->query($sql);
			if($queryFAI AND $queryFAI->num_rows > 0)
			{				
				while($resultFAI = $queryFAI->fetch_assoc() and $roseFlag==0)
				{
				$lotNumber = "";
				$poId = "";
				
				$lotNumber = $resultFAI['lotNumber'];
				$FAIview = "FAI";
				
					$sql = "SELECT poId from ppic_lotlist where lotNumber like '".$lotNumber."' and identifier=1";
					$queryFAI2 = $db->query($sql);
					if($queryFAI2 AND $queryFAI2->num_rows > 0)
					{				
						while($resultFAI2 = $queryFAI2->fetch_assoc() and $roseFlag==0)
						{
						$poId = $resultFAI2['poId'];
						}
				//<td onclick=\"window.open('../prs/james_faiJamcoAllViewNewSignature.php?poId=".$poId."&cocONLY=1&LOT=".$lotNumber."', 'pp','left=50,screenX=700,screenY=20,resizable,scrollbars,status,width=700,height=500'); return false;\">".$FAIview."</td>
					
					//echo "<a href='../prs/james_faiJamcoAllViewNewSignature.php?poId=".$poId."&cocONLY=1&LOT=".$lotNumber."'>".$lotNumber."</a><br>";
					echo "<a onclick=\"window.open('../prs/james_faiJamcoAllViewNewSignature.php?poId=".$poId."&cocONLY=1&LOT=".$lotNumber."', 'pp3','left=50,screenX=100,screenY=20,resizable,scrollbars,status,width=700,height=500'); return false;\">".$lotNumber." view_FAI</a><br>";
					}
				//$roseFlag=1;
				//break;
				}
			} 
			
					
?>
