<?php
// ---------------------- Ace Sandoval : Revised mysql to mysqli ------------------------------------
// ---------------------- Program Needs Revision - Use Recursive Function Instead -------------------

ini_set('display_errors','on');
session_start();
include("../Common Data/PHP Modules/mysqliConnection.php");
include("../Common Data/PHP Modules/anthony_retrieveText.php");
include("../Common Data/PHP Modules/rose_prodfunctions.php");
$product= isset($_GET['product']) ? $_GET['product'] : "";
//echo $product;
function partNumberCall($partId,$identifier)
{
	include("../Common Data/PHP Modules/mysqliConnection.php");
	
	$showPartId = '';
	if($_SESSION['idNumber']=='0346')
	{
		$showPartId = "(".$partId.")";
	}
	
	if($identifier==1)
	{
		$sql = "SELECT partNumber, partName, revisionId FROM cadcam_parts where partId=".$partId;		
		$partsQuery = $db->query($sql);
		if($partsQuery->num_rows > 0)
		{
			$partsQueryResult = $partsQuery->fetch_assoc();
			return $partsQueryResult['partNumber']." ".$showPartId."`".$partsQueryResult['partName']."`".$partsQueryResult['revisionId'];
		}
	}
	else
	{
		$sql = "SELECT accessoryName,accessoryNumber,revisionId from cadcam_accessories where accessoryId=".$partId;
		$accessoryQuery = $db->query($sql);
		if($accessoryQuery->num_rows > 0)
		{
			$accessoryQueryResult = $accessoryQuery->fetch_assoc();			
			return $accessoryQueryResult['accessoryNumber']." ".$showPartId."`".$accessoryQueryResult['accessoryName']."`".$accessoryQueryResult['revisionId'];
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<a href="#" onclick= "window.open('converterTree.php?source=tree&product=<?php echo $product; ?>','pdftree','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><center><img src='images/printpdf.png' width='40' height='30' alt='-PRINT as PDF-' title='-PRINT as PDF-' ></a>
</head>
<body>
<?php
$r=0;
$s=0;

$sql = "SELECT parentId FROM cadcam_subparts where childId=".$product." and identifier=1";
$subpartQuery = $db->query($sql);
while($subpartQueryResult = $subpartQuery->fetch_assoc())
{
	$parentId_1[]=$subpartQueryResult['parentId'];

	$r++;
}

for($y=0;$y<$r;$y++)
{
	$parentId[]= $parentId_1[$y];
	$remember =	$parentId_1[$y];
	for($x=$s;$x<count($parentId);$x++)
	{
		$sql = "SELECT parentId FROM cadcam_subparts where childId=".$parentId[$x]." and identifier=1";
		$subpartQuery = $db->query($sql);
		while($subpartQueryResult = $subpartQuery->fetch_assoc()) 
		{ 
			echo $parentId[]=$subpartQueryResult['parentId']; 
			$remember =	$subpartQueryResult['parentId'];
			$s++;
		}
	}
	$id[]=$remember;
}
	
if(count($id)>0)
{
	echo "Found <b>".count($id)."</b> Assembly<br>";
}
else
{
	$id[]=$product;
}

echo "<center><b><h1>ASSEMBLY TREE DIAGRAM </h1></b><table border=1>";

for($z=0; $z<count($id); $z++)
{ 
	echo "<tr><td colspan=6><center>ASSEMBLY no.".($z+1)."</center></td></tr>";
	echo "<tr><td><center><b>".displayText('L28')."</b></center></td>
			  <td><center><b>".displayText('L30')."</b></center></td>
			  <td><center><b>".displayText('L226')."</b></center></td>
			  <td><center><b>".displayText('L31')."</b></center></td>	
			  <td><center><b>".displayText('L52')."</b></center></td>
			  <td><center><b>".displayText('L111')."</b></center></td>
			  <td><center><b>".displayText('L1256')."</b></center></td>
			  ";	
	echo "</tr>";

	$main=explode("`",partNumberCall($id[$z],1));
	
	if($product==$id[$z])
	{
		$highlight='bgcolor=lightblue';
	}
	else
	{
		$highlight='';
	}
	list ($partNumber_F,$partName_F,$revisionId_F,$metalThickness_F,$item_x_F,$item_y_F,$metalType_F)=identifierdetails($product,1,1);
	echo "<tr ".$highlight." ><td>".$main[0]."</td><td>".$main[1]."</td><td>".$main[2]."</td><td></td><td>".$product."</td><td></td><td>".$metalType_F." ".$metalThickness_F."</td>";
	echo "</tr>";	
	
	$sql = "SELECT count(subpartId) AS countId FROM cadcam_subparts WHERE parentId = ".$id[$z];
	$subpartQuery = $db->query($sql);
	$subpartQueryResult = $subpartQuery->fetch_assoc(); 
	$countId = $subpartQueryResult['countId'];	

	$sql = "SELECT * FROM cadcam_subparts WHERE parentId = ".$id[$z]." ORDER BY orderNumber ASC";
	$firstLevelSubpartQuery = $db->query($sql);
	while($firstLevelSubpartQueryResult = $firstLevelSubpartQuery->fetch_assoc()) 
	{
		$childId = $firstLevelSubpartQueryResult['childId'];
		$sql = "SELECT materialSpecId FROM cadcam_parts WHERE partId = ".$firstLevelSubpartQueryResult['childId']." ";
		$getMaterialSpecId = $db->query($sql);
		$getMaterialSpecIdResult = $getMaterialSpecId->fetch_assoc();
		$roseType="";
		if($firstLevelSubpartQueryResult['identifier']==1){ $roseType="part"; } else{ $roseType="acc"; }
		if($firstLevelSubpartQueryResult['identifier']==1)
		{
			//~ $sql = "SELECT metalType, metalThickness FROM cadcam_materialspecs WHERE materialSpecId = ".$getMaterialSpecIdResult['materialSpecId']." ";
			//~ $getMaterialSpecs = $db->query($sql);
			//~ $getMaterialSpecsResult = $getMaterialSpecs->fetch_assoc();
		}
		$part1=explode("`",partNumberCall($firstLevelSubpartQueryResult['childId'],$firstLevelSubpartQueryResult['identifier']));
		if($product==$firstLevelSubpartQueryResult['childId'])
		{
			$highlight='bgcolor=lightblue';
		}
		else
		{
			$highlight='';
		}		
		echo "<tr ".$highlight.">
			<td><font color=white>---</font><font face='Arial' color=blue size=5><b>L </b></font>".$part1[0]."</td>
			<td>".$part1[1]."</td>
			<td>".$part1[2]."</td>
			<td align = 'center'>".$firstLevelSubpartQueryResult['quantity']."</td>
			<td>".$childId."</td>
			<td>".$roseType."</td>			
			";
			list ($partNumber_F,$partName_F,$revisionId_F,$metalThickness_F,$item_x_F,$item_y_F,$metalType_F)=identifierdetails($childId,$firstLevelSubpartQueryResult['identifier'],1);
			echo "<td>".$metalType_F." ".$metalThickness_F."</td>";
			/*
			<td>";
				if($firstLevelSubpartQueryResult['orderNumber'] > 1)
				{
					echo "<a href = 'anthony_updateOrderNumber.php?mainId=".$firstLevelSubpartQueryResult['subpartId']."&action=up'><img src = '/Common Data/Templates/buttons/upIcon.png' height = '20' width = '20'></a>&emsp;";
				}
				
				if($firstLevelSubpartQueryResult['orderNumber'] < $countId)
				{
					echo "<a href = 'anthony_updateOrderNumber.php?mainId=".$firstLevelSubpartQueryResult['subpartId']."&action=down'><img src = '/Common Data/Templates/buttons/downIcon.png' height = '20' width = '20' align = 'right'></a>";
				}
			echo "</td>";
			*/
		if($firstLevelSubpartQueryResult['identifier']==1)
		{
			$sql = "SELECT childId, quantity, identifier FROM cadcam_subparts where parentId=".$firstLevelSubpartQueryResult['childId'];
			$secondLevelSubpartQuery = $db->query($sql);
			while($secondLevelSubpartQueryResult = $secondLevelSubpartQuery->fetch_assoc())
			{
				$sql = "SELECT materialSpecId FROM cadcam_parts WHERE partId = ".$secondLevelSubpartQueryResult['childId']." ";
				$getMaterialSpecId1 = $db->query($sql);
				$getMaterialSpecIdResult1 = $getMaterialSpecId1->fetch_assoc();
				
				if($secondLevelSubpartQueryResult['identifier']==1)
				{
					//~ $sql = "SELECT metalType, metalThickness FROM cadcam_materialspecs WHERE materialSpecId = ".$getMaterialSpecIdResult1['materialSpecId']." ";
					//~ $getMaterialSpecs1 = $db->query($sql);
					//~ $getMaterialSpecsResult1 = $getMaterialSpecs1->fetch_assoc();
				}
				
				$part2=explode("`",partNumberCall($secondLevelSubpartQueryResult['childId'],$secondLevelSubpartQueryResult['identifier']));
				if($product==$secondLevelSubpartQueryResult['childId'])
				{ 
					$highlight='bgcolor=lightblue';
				} 
				else 
				{
					$highlight='';
				}
				$roseType="";
				if($secondLevelSubpartQueryResult['identifier']==1){ $roseType="part"; } else{ $roseType="acc"; }
				echo "<tr ".$highlight."><td><font color=white>------</font><font face='Arial' color=green size=5><b>L </b></font>".$part2[0]."</td><td>".$part2[1]."</td><td>".$part2[2]."</td><td align = 'center'>".$secondLevelSubpartQueryResult['quantity']."</td><td align = 'center'>".$secondLevelSubpartQueryResult['childId']."</td><td align = 'center'>".$roseType."</td>";
				list ($partNumber_F,$partName_F,$revisionId_F,$metalThickness_F,$item_x_F,$item_y_F,$metalType_F)=identifierdetails($secondLevelSubpartQueryResult['childId'],$secondLevelSubpartQueryResult['identifier'],1);
				echo "<td>".$metalType_F." ".$metalThickness_F."</td>";
		
				if($secondLevelSubpartQueryResult['identifier']==1)
				{
					$sql = "SELECT childId, quantity, identifier FROM cadcam_subparts where parentId=".$secondLevelSubpartQueryResult['childId'];
					$thirdLevelSubpartQuery = $db->query($sql);				
					while($thirdLevelSubpartQueryResult = $thirdLevelSubpartQuery->fetch_assoc())
					{
						$sql = "SELECT materialSpecId FROM cadcam_parts WHERE partId = ".$thirdLevelSubpartQueryResult['childId']." ";
						$getMaterialSpecId2 = $db->query($sql);
						$getMaterialSpecIdResult2 = $getMaterialSpecId2->fetch_assoc();
						
						if($thirdLevelSubpartQueryResult['identifier']==1)
						{
							//~ $sql = "SELECT metalType, metalThickness FROM cadcam_materialspecs WHERE materialSpecId = ".$getMaterialSpecIdResult2['materialSpecId']." ";
							//~ $getMaterialSpecs2 = $db->query($sql);
							//~ $getMaterialSpecsResult2 = $getMaterialSpecs2->fetch_assoc();
						}
						
						$part3=explode("`",partNumberCall($thirdLevelSubpartQueryResult['childId'],$thirdLevelSubpartQueryResult['identifier'])); 
						if($product==$thirdLevelSubpartQueryResult['childId'])
						{
							$highlight='bgcolor=lightblue';
						}
						else
						{
							$highlight='';
						}
						$roseType="";
						if($thirdLevelSubpartQueryResult['identifier']==1){ $roseType="part"; } else{ $roseType="acc"; }
						echo "<tr ".$highlight."><td><font color=white>---------</font><font face='Arial' color=orange size=5><b>L </b></font>".$part3[0]."</td><td>".$part3[1]."</td><td>".$part3[2]."</td><td align = 'center'>".$thirdLevelSubpartQueryResult['quantity']."</td><td align = 'center'>".$thirdLevelSubpartQueryResult['childId']."</td><td align = 'center'>".$roseType."</td>";
						list ($partNumber_F,$partName_F,$revisionId_F,$metalThickness_F,$item_x_F,$item_y_F,$metalType_F)=identifierdetails($thirdLevelSubpartQueryResult['childId'],$thirdLevelSubpartQueryResult['identifier'],1);
						echo "<td>".$metalType_F." ".$metalThickness_F."</td>";
						
						if($thirdLevelSubpartQueryResult['identifier']==1)
						{
							$sql = "SELECT childId, quantity, identifier FROM cadcam_subparts where parentId=".$thirdLevelSubpartQueryResult['childId'];
							$fourthLevelSubpartQuery = $db->query($sql);	
							while($fourthLevelSubpartQueryResult = $fourthLevelSubpartQuery->fetch_assoc())
							{
								$sql = "SELECT materialSpecId FROM cadcam_parts WHERE partId = ".$fourthLevelSubpartQueryResult['childId']." ";
								$getMaterialSpecId3 = $db->query($sql);
								$getMaterialSpecIdResult3 = $getMaterialSpecId3->fetch_assoc();
								
								if($fourthLevelSubpartQueryResult['identifier']==1)
								{
									//~ $sql = "SELECT metalType, metalThickness FROM cadcam_materialspecs WHERE materialSpecId = ".$getMaterialSpecIdResult3['materialSpecId']." ";
									//~ $getMaterialSpecs3 = $db->query($sql);
									//~ $getMaterialSpecsResult3 = $getMaterialSpecs3->fetch_assoc();
								}
								
								$part4=explode("`",partNumberCall($fourthLevelSubpartQueryResult['childId'],$fourthLevelSubpartQueryResult['identifier']));
								if($product==$fourthLevelSubpartQueryResult['childId'])
								{
									$highlight='bgcolor=lightblue';
								}
								else
								{
									$highlight='';
								}
								
								$roseType="";
								if($fourthLevelSubpartQueryResult['identifier']==1){ $roseType="part"; } else{ $roseType="acc"; }
								echo "<tr ".$highlight." ><td><font color=white>------------</font><font face='Arial' color=red size=5><b>L </b></font>".$part4[0]."</td><td>".$part4[1]."</td><td>".$part4[2]."</td><td align = 'center'>".$fourthLevelSubpartQueryResult['quantity']."</td><td align = 'center'>".$fourthLevelSubpartQueryResult['childId']."</td><td align = 'center'>".$roseType."</td>";
								list ($partNumber_F,$partName_F,$revisionId_F,$metalThickness_F,$item_x_F,$item_y_F,$metalType_F)=identifierdetails($fourthLevelSubpartQueryResult['childId'],$fourthLevelSubpartQueryResult['identifier'],1);
								echo "<td>".$metalType_F." ".$metalThickness_F."</td>";
							
								if($fourthLevelSubpartQueryResult['identifier']==1)
								{
									$sql = "SELECT childId, quantity, identifier FROM cadcam_subparts where parentId=".$fourthLevelSubpartQueryResult['childId'];
									$fifthLevelSubpartQuery = $db->query($sql);
									while($fifthLevelSubpartQueryResult = $fifthLevelSubpartQuery->fetch_assoc())
									{								
										$sql = "SELECT materialSpecId FROM cadcam_parts WHERE partId = ".$fifthLevelSubpartQueryResult['childId']." ";
										$getMaterialSpecId4 = $db->query($sql);
										$getMaterialSpecIdResult4 = $getMaterialSpecId4->fetch_assoc();
										
										if($fifthLevelSubpartQueryResult['identifier']==1)
										{
											//~ $sql = "SELECT metalType, metalThickness FROM cadcam_materialspecs WHERE materialSpecId = ".$getMaterialSpecIdResult4['materialSpecId']." ";
											//~ $getMaterialSpecs4 = $db->query($sql);
											//~ $getMaterialSpecsResult4 = $getMaterialSpecs4->fetch_assoc();
										}
										
										$part5=explode("`",partNumberCall($fifthLevelSubpartQueryResult['childId'],$fifthLevelSubpartQueryResult['identifier']));
										if($product==$fifthLevelSubpartQueryResult['childId'])
										{
											$highlight='bgcolor=lightblue';
										}
										else
										{
											$highlight='';
										}
										
										$roseType="";
										if($fifthLevelSubpartQueryResult['identifier']==1){ $roseType="part"; } else{ $roseType="acc"; }
										echo "<tr $highlight ><td><font color=white>---------------</font><font face='Arial' color=brown size=5><b>L </b></font>".$part5[0]."</td><td>".$part5[1]."</td><td>".$part5[2]."</td><td align = 'center'>".$fifthLevelSubpartQueryResult['quantity']."</td><td align = 'center'>".$fifthLevelSubpartQueryResult['childId']."</td><td align = 'center'>".$roseType."</td>";
										list ($partNumber_F,$partName_F,$revisionId_F,$metalThickness_F,$item_x_F,$item_y_F,$metalType_F)=identifierdetails($fifthLevelSubpartQueryResult['childId'],$fifthLevelSubpartQueryResult['identifier'],1);
										echo "<td>".$metalType_F." ".$metalThickness_F."</td>";
										
										if($fifthLevelSubpartQueryResult['identifier']==1)
										{
											$sql = "SELECT childId, quantity, identifier FROM cadcam_subparts where parentId=".$fifthLevelSubpartQueryResult['childId'];
											$sixthLevelSubpartQuery = $db->query($sql);
											while($sixthLevelSubpartQueryResult = $sixthLevelSubpartQuery->fetch_assoc())
											{
												$sql = "SELECT materialSpecId FROM cadcam_parts WHERE partId = ".$sixthLevelSubpartQueryResult['childId']." ";
												$getMaterialSpecId5 = $db->query($sql);
												$getMaterialSpecIdResult5 = $getMaterialSpecId5->fetch_assoc();
												
												if($sixthLevelSubpartQueryResult['identifier']==1)
												{
													//~ $sql = "SELECT metalType, metalThickness FROM cadcam_materialspecs WHERE materialSpecId = ".$getMaterialSpecIdResult5['materialSpecId']." ";
													//~ $getMaterialSpecs5 = $db->query($sql);
													//~ $getMaterialSpecsResult5 = $getMaterialSpecs5->fetch_assoc();
												}
												
												$part6=explode("`",partNumberCall($sixthLevelSubpartQueryResult['childId'],$sixthLevelSubpartQueryResult['identifier']));
												if($product==$sixthLevelSubpartQueryResult['childId'])
												{
													$highlight='bgcolor=lightblue';
												}
												else
												{
													$highlight='';
												}
												$roseType="";
												if($sixthLevelSubpartQueryResult['identifier']==1){ $roseType="part"; } else{ $roseType="acc"; }
												echo "<tr $highlight ><td><font color=white>------------------</font><font face='Arial' color=black size=5><b>L </b></font>".$part6[0]."</td><td>".$part6[1]."</td><td>".$part6[2]."</td><td align = 'center'>".$sixthLevelSubpartQueryResult['quantity']."</td><td align = 'center'>".$sixthLevelSubpartQueryResult['childId']."</td><td align = 'center'>".$roseType."</td>";
												list ($partNumber_F,$partName_F,$revisionId_F,$metalThickness_F,$item_x_F,$item_y_F,$metalType_F)=identifierdetails($sixthLevelSubpartQueryResult['childId'],$sixthLevelSubpartQueryResult['identifier'],1);
												echo "<td>".$metalType_F." ".$metalThickness_F."</td>";
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	
}
echo "</table></center>";
?>
</body>

</html>
