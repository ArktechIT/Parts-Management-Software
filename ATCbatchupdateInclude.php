<?php
//include("../../../Common Data/PHP Modules/rose_prodfunctions.php");
//include("../../../Common Data/PHP Modules/mysqliConnection.php");
//include('../Common Data/PHP Modules/mysqliConnection.php');
//include('../Common Data/PHP Modules/anthony_retrieveText.php');
$test= isset($_POST['test']) ? $_POST['test']: "";
$remarks= isset($_POST['remarks']) ? $_POST['remarks']: "";
if (isset($_GET['partId']))
{
	$getPartId = $_GET['partId'];
}




	$sql = "SELECT a.partId, a.partNumber, a.revisionId, a.partNote,
			b.station, b.angle, b.punchtype, b.punchsize, b.clearance,
			c.programCode 
			FROM cadcam_parts AS a
			INNER JOIN engineering_toollist AS b ON a.partId = b.partId
			INNER JOIN engineering_programs AS c ON b.partId = c.partId
			WHERE a.customerId =45 and a.partId = ".$getPartId."";

	// $sql = "SELECT a.partId, a.partNumber, a.revisionId, a.partNote,and a.partId = 5731
			// b.station, b.angle, b.punchtype, b.punchsize, b.clearance,
			// c.programCode
			// FROM cadcam_parts AS a
			// INNER JOIN engineering_toollist AS b ON a.partId = b.partId
			// INNER JOIN engineering_programs AS c ON b.partId = c.partId
			// WHERE a.customerId =45 and a.partId in (3513,3514,3515)";		

	$queryPartNumber = $db->query($sql);
   // echo "<table border=1>";
	if($queryPartNumber->num_rows > 0)
	{
		while($resultPartNumber = $queryPartNumber->fetch_assoc())
		{
	//	echo "<tr>";
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
					
						//-------------------START-----------------------
                    

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
		//echo "<td>".$errorFlag.$errorSuggest."<td>";
		//echo "<td>".$clearance."<td>";
		
		//echo "<td>".$programCode."<td>";
		//echo "</tr>";
		}
	}
//echo "</table>";

?>
