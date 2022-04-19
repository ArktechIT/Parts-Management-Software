<?php
//include("../../../Common Data/PHP Modules/rose_prodfunctions.php");
//include("../../../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/mysqliConnection.php');
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
    echo "<table border=1>";
    echo "<tr>
    <th>Shape</th>
    <th>".displayText('L94')."</th>
    <th>".displayText('L3480')."</th>
    </tr>";
    echo "<tr>";
	if($queryPartNumber->num_rows > 0)
	{
		while($resultPartNumber = $queryPartNumber->fetch_assoc())
		{
			$counter = $counter + 1;
		//echo "<tr>";
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

		/*   $punchExplodeStation=array();
		   $punchExplodeStation=explode("`",$station);
		   $arrlength = count($punchExplodeStation);

           for($x = 0; $x < $arrlength; $x++) {
           echo $punchExplodeStation[$x];
           }*/
			


       
		//echo "<td>".$partId."<td>";
		//echo "<td>".$partNumber."<td>";
		//echo "<td>".$revisionId."<td>";
		//echo "<td>".$partNote."<td>";
		//echo "<tr><td>".$station."</td>";
		//echo "<td>".$angle."<td>";
		//echo "<td>".$punchtype."<td>";
		$errorFlag="GOOD";
		$errorSuggest="";

			//echo "<td>";
			//echo $punchsize;
			$punchExplode=array();
			$punchExplode=explode("`",$punchsize);
			$punchTypeExplode=array();
			$punchTypeExplode=explode("`",$punchtype);
			$arrlength = count(array_filter($punchTypeExplode));

		
         

			if(count($punchExplode)>0)
			{
				
					for($x = 0; $x < $arrlength; $x++) {
              //echo $punchTypeExplode[$x];
              echo "<td>".$punchTypeExplode[$x]."</td>";
              //echo "<br>";
           }


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
						// while($resultLotNumber = $queryLot->fetch_assoc())
						// {
						$resultLotNumber = $queryLot->fetch_assoc();
						$punchSizeMatch =  $resultLotNumber['punchSize'];
                 
						echo "<td>".$punchSizeMatch."</td>";
						// }
					}
					else
					{
						echo "<font color=red>";
						//-------------------START-----------------------
                    

                    if (stristr($punchTypeExplode[$a], "round") and $errorSuggest == 'SUGGEST')
                    {
					
					    $sqlSuggestHigh = "SELECT (MIN( CAST( punchSize AS DECIMAL(10,3)) )) AS sizeHigh FROM system_atctools WHERE CAST(punchSize AS DECIMAL(10,3)) > ".trim($punchExplode[$a])." AND punchType =  'round' ";
					    $querySuggestHigh = $db->query($sqlSuggestHigh);
					    $resultSuggestHigh = $querySuggestHigh->fetch_assoc();
					    $suggestHigh = $resultSuggestHigh['sizeHigh'];
					    	 /*$max=min($suggestHigh);*/
					    	 /*$array1 = array($suggestHigh);*/
                        /*$resultSuggestHigh = $querySuggestHigh->fetch_assoc();*/
                       //$suggestHigh = array();
                        $sqlSuggestLow = "SELECT (MAX( CAST( punchSize AS DECIMAL(10,3)))) AS sizeLow FROM system_atctools WHERE CAST(punchSize AS DECIMAL(10,3)) < ".trim($punchExplode[$a])." AND punchType =  'round' ";

					    $querySuggestLow = $db->query($sqlSuggestLow);
                        $resultSuggestLow = $querySuggestLow->fetch_assoc();
                        $suggestLow = $resultSuggestLow['sizeLow'];

                        echo "<font color=red>";
                        echo "<td>Try to use :".(float)$suggestLow." displayText('L2064') ".(float)$suggestHigh."</td>";
						echo "</font>";
						$errorFlag="";
                    
					}
					else
					{
						echo "<font color=red>";
						echo "<td>ERROR!:".trim($punchExplode[$a])."</td>";
						echo "</font>";
						$errorFlag="";
					}	
				
					}
					}
				}
			}
			echo "<td>";
		if($errorFlag=="GOOD")
		{

			$errorSuggest='';
		}
        //echo "<td>".$errorFlag.$errorSuggest."</td>";
		//echo "<td>".$clearance."<td>";
		
		//echo "<td>".$programCode."<td>";
		//echo "</tr>";
		}
	}

echo "</table>";

?>
