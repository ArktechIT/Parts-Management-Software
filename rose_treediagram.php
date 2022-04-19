<?php
// ---------------------- Ace Sandoval : Revised mysql to mysqli ------------------------------------
// ---------------------- Program Needs Revision - Use Recursive Function Instead -------------------
?>

<style>
	div.separator
	{		
		height: 28px;
		vertical-align: middle;
		line-height: 25px;
	}	
		
	label
	{
		display: inline-block;
		width: 38%;
	}
	
	select
	{
		height: 20px;
		width: 60%;
	}

	#scroll_edit
	{
		height: 500px;
		overflow-y:scroll;
	}

	.css_button 
	{
		font-size: 14px;
		width: 32%;
		font-family: Arial;
		font-weight: normal;
		text-decoration: inherit;
		-webkit-border-radius: 8px 8px 8px 8px;
		-moz-border-radius: 8px 8px 8px 8px;
		border-radius: 8px 8px 8px 8px;
		border: 1px solid #3866A3;
		padding: 9px 18px;
		text-shadow: 1px 1px 0px #5E5E5E;
		-webkit-box-shadow: inset 1px 1px 0px 0px #BEE2F9;
		-moz-box-shadow: inset 1px 1px 0px 0px #BEE2F9;
		box-shadow: inset 1px 1px 0px 0px #BEE2F9;
		cursor: pointer;
		color: #FFFFFF;
		display: inline-block;
		background: -webkit-linear-gradient(90deg, #468ccf 5%, #63b8ee 100%);
		background: -moz-linear-gradient(90deg, #468ccf 5%, #63b8ee 100%);
		background: -ms-linear-gradient(90deg, #468ccf 5%, #63b8ee 100%);
		background: linear-gradient(180deg, #63b8ee 5%, #468ccf 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#63b8ee",endColorstr="#468ccf");
	}

	.css_button:hover 
	{
		background: -webkit-linear-gradient(90deg, #63b8ee 5%, #468ccf 100%);
		background: -moz-linear-gradient(90deg, #63b8ee 5%, #468ccf 100%);
		background: -ms-linear-gradient(90deg, #63b8ee 5%, #468ccf 100%);
		background: linear-gradient(180deg, #468ccf 5%, #63b8ee 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#468ccf",endColorstr="#63b8ee");
	}

	.css_button:active 
	{
		position:relative;
		top: 1px;
	}
</style>

<?php
ini_set('display_errors','on');
session_start();
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');
$product= isset($_GET['product']) ? $_GET['product'] : "";

function partNumberCall($partId,$identifier)
{
	include("../Common Data/PHP Modules/mysqliConnection.php");
	
	if($identifier==1)
	{	
		$ST = "blue";		
		$sql = "SELECT partId from cadcam_standardtime where partId = ".$partId;		
		$standardTimeQuery = $db->query($sql);
		if($standardTimeQuery->num_rows > 0)
		{
			$ST = "red";	
		}
		
		$sql = "SELECT partNumber, partName, revisionId FROM cadcam_parts where partId=".$partId;		
		$partsQuery = $db->query($sql);
		if($partsQuery->num_rows > 0)
		{
			$partsQueryResult = $partsQuery->fetch_assoc();
			return $partsQueryResult['partNumber']."`".$partsQueryResult['partName']."`".$partsQueryResult['revisionId']."``".$ST;
		}
	}
	else
	{
		$sql = "SELECT accessoryName,accessoryNumber from cadcam_accessories where accessoryId=".$partId;
		$accessoryQuery = $db->query($sql);
		if($accessoryQuery->num_rows > 0)
		{
			$accessoryQueryResult = $accessoryQuery->fetch_assoc();			
			return $accessoryQueryResult['accessoryNumber']."`".$accessoryQueryResult['accessoryName']."``".$ST;
		}	
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
			$parentId[]=$subpartQueryResult['parentId']; 
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

echo "<div id = 'scroll_edit'>";	
echo "<table border=1 width=600>";
	
	for($z=0; $z<count($id); $z++)
	{ 
		echo "<tr><td colspan=6><center>".displayText('L1475')." no.".($z+1)."</center></td></tr>";//L1475 ASSEMBLY
		echo "<tr><td colspan=2><center><b>".displayText('L28')."</b></center></td>
				  <td><center><b>".displayText('L30')."</b></center></td>
				  <td><center><b>".displayText('L226')."</b></center></td>
				  <td><center><b>".displayText('L31')."</b></center></td>
				  <td><center><b>".displayText('L130')."</b></center></td>";
		echo "</tr>";//L28 Part Number, L30 Part Name, L226 Rev, L31 Qty, L130 ST

		$main=explode("`",partNumberCall($id[$z],1));

		if($product==$id[$z])
		{
			$highlight='bgcolor=lightblue';
		}
		else
		{
			$highlight='';
		}
		
		echo "<tr ".$highlight." ><td colspan=2><a href='anthony_editProduct.php?partId=".$id[$z]."&src=process&patternId=0'>".$main[0]."</a></td><td>".$main[1]."</td><td>".$main[2]."</td><td></td><td bgcolor=".$main[3]."></td>";	
		echo "</tr>";
		
		$sql = "SELECT count(subpartId) AS countId FROM cadcam_subparts WHERE parentId = ".$id[$z];
		$subpartQuery = $db->query($sql);
		$subpartQueryResult = $subpartQuery->fetch_assoc(); 
		$countId = $subpartQueryResult['countId'];	
		
		$sql = "SELECT * FROM cadcam_subparts WHERE parentId = ".$id[$z]." ORDER BY orderNumber ASC";
		$firstLevelSubpartQuery = $db->query($sql);
		while($firstLevelSubpartQueryResult = $firstLevelSubpartQuery->fetch_assoc()) 
		{
			$part1=explode("`",partNumberCall($firstLevelSubpartQueryResult['childId'],$firstLevelSubpartQueryResult['identifier']));
			
			if($product==$firstLevelSubpartQueryResult['childId'])
			{
				$highlight='bgcolor=lightblue';
			}
			else
			{
				$highlight='';
			}			
			echo "<tr ".$highlight.">";
				
			if($firstLevelSubpartQueryResult['identifier']==1)
			{
				echo "<td colspan=2><a href='anthony_editProduct.php?partId=".$firstLevelSubpartQueryResult['childId']."&src=process&patternId=0'><font color=white>---</font><font face='Arial' color=blue size=5><b>L </b></font>".$part1[0]."</a></td>";
			}
			else
			{
				echo "<td colspan=2><font color=white>---</font><font face='Arial' color=blue size=5><b>L </b></font>".$part1[0]."</td>";
			}
			
			echo "<td>".$part1[1]."</td>
				<td>".$part1[2]."</td>
				<td align = 'center'>".$firstLevelSubpartQueryResult['quantity']."</td>";
				echo "<td bgcolor=".$part1[3]."></td>";
					
			
			if($firstLevelSubpartQueryResult['identifier']==1)
			{
				$sql = "SELECT childId, quantity, identifier FROM cadcam_subparts where parentId=".$firstLevelSubpartQueryResult['childId'];
				$secondLevelSubpartQuery = $db->query($sql);
				while($secondLevelSubpartQueryResult = $secondLevelSubpartQuery->fetch_assoc())
				{								
					$part2=explode("`",partNumberCall($secondLevelSubpartQueryResult['childId'],$secondLevelSubpartQueryResult['identifier']));
					if($product==$secondLevelSubpartQueryResult['childId'])
					{ 
						$highlight='bgcolor=lightblue';
					} 
					else 
					{
						$highlight='';
					}
					
					echo "<tr ".$highlight.">";
					if($secondLevelSubpartQueryResult['identifier']==1)
					{
						echo "<td colspan=2><a href='anthony_editProduct.php?partId=".$secondLevelSubpartQueryResult['childId']."&src=process&patternId=0'><font color=white>------</font><font face='Arial' color=green size=5><b>L </b></font>".$part2[0]."</a></td>";
					}
					else
					{
						echo "<td colspan=2><font color=white>------</font><font face='Arial' color=green size=5><b>L </b></font>".$part2[0]."</td>";
					}
					echo "<td>".$part2[1]."</td><td>".$part2[2]."</td><td align = 'center'>".$secondLevelSubpartQueryResult['quantity']."</td><td align = 'center' bgcolor=".$part2[3]."></td>";
					
			
					if($secondLevelSubpartQueryResult['identifier']==1)
					{
						$sql = "SELECT childId, quantity, identifier FROM cadcam_subparts where parentId=".$secondLevelSubpartQueryResult['childId'];
						$thirdLevelSubpartQuery = $db->query($sql);				
						while($thirdLevelSubpartQueryResult = $thirdLevelSubpartQuery->fetch_assoc())
						{						
							$part3=explode("`",partNumberCall($thirdLevelSubpartQueryResult['childId'],$thirdLevelSubpartQueryResult['identifier'])); 
							if($product==$thirdLevelSubpartQueryResult['childId'])
							{
								$highlight='bgcolor=lightblue';
							}
							else
							{
								$highlight='';
							}
							
							echo "<tr ".$highlight." >";
							if($thirdLevelSubpartQueryResult['identifier']==1)
							{
								echo "<td colspan=2><a href='anthony_editProduct.php?partId=".$thirdLevelSubpartQueryResult['childId']."&src=process&patternId=0'><font color=white>---------</font><font face='Arial' color=orange size=5><b>L </b></font>".$part3[0]."</a></td>";
							}
							else
							{
								echo "<td colspan=2><font color=white>---------</font><font face='Arial' color=orange size=5><b>L </b></font>".$part3[0]."</td>";
							}
							echo "<td>".$part3[1]."</td><td>".$part3[2]."</td><td align = 'center'>".$thirdLevelSubpartQueryResult['quantity']."</td><td align = 'center'>".$part3[3]."</td>";
													
							if($thirdLevelSubpartQueryResult['identifier']==1)
							{
								$sql = "SELECT childId, quantity, identifier FROM cadcam_subparts where parentId=".$thirdLevelSubpartQueryResult['childId'];
								$fourthLevelSubpartQuery = $db->query($sql);	
								while($fourthLevelSubpartQueryResult = $fourthLevelSubpartQuery->fetch_assoc())
								{							
									$part4=explode("`",partNumberCall($fourthLevelSubpartQueryResult['childId'],$fourthLevelSubpartQueryResult['identifier']));
									if($product==$fourthLevelSubpartQueryResult['childId'])
									{
										$highlight='bgcolor=lightblue';
									}
									else
									{
										$highlight='';
									}
									
									echo "<tr ".$highlight." >";
									if($fourthLevelSubpartQueryResult['identifier']==1)
									{
										echo "<td colspan=2><a href='anthony_editProduct.php?partId=".$fourthLevelSubpartQueryResult['childId']."&src=process&patternId=0'><font color=white>------------</font><font face='Arial' color=red size=5><b>L </b></font>".$part4[0]."</a></td>";
									}
									else
									{
										echo "<td colspan=2><font color=white>------------</font><font face='Arial' color=red size=5><b>L </b></font>".$part4[0]."</td>";
									}
									echo "<td>".$part4[1]."</td><td>".$part4[2]."</td><td align = 'center'>".$fourthLevelSubpartQueryResult['quantity']."</td><td align = 'center'>".$part4[3]."</td>";
									
									if($fourthLevelSubpartQueryResult['identifier']==1)
									{
										$sql = "SELECT childId, quantity, identifier FROM cadcam_subparts where parentId=".$fourthLevelSubpartQueryResult['childId'];
										$fifthLevelSubpartQuery = $db->query($sql);
										while($fifthLevelSubpartQueryResult = $fifthLevelSubpartQuery->fetch_assoc())
										{										
											$part5=explode("`",partNumberCall($fifthLevelSubpartQueryResult['childId'],$fifthLevelSubpartQueryResult['identifier']));
											if($product==$fifthLevelSubpartQueryResult['childId'])
											{
												$highlight='bgcolor=lightblue';
											}
											else
											{
												$highlight='';
											}
											echo "<tr ".$highlight." >";
											if($fifthLevelSubpartQueryResult['identifier']==1)
											{
												echo "<td colspan=2><a href='anthony_editProduct.php?partId=".$fifthLevelSubpartQueryResult['childId']."&src=process&patternId=0'><font color=white>---------------</font><font face='Arial' color=brown size=5><b>L </b></font>".$part5[0]."</a></td>";
											}
											else
											{
												echo "<td colspan=2><font color=white>---------------</font><font face='Arial' color=brown size=5><b>L </b></font>".$part5[0]."</td>";
											}
											echo "<td>".$part5[1]."</td><td>".$part5[2]."</td><td align = 'center'>".$fifthLevelSubpartQueryResult['quantity']."</td><td align = 'center'>".$part5[3]."</td>";
											
											if($fifthLevelSubpartQueryResult['identifier']==1)
											{
												$sql = "SELECT childId, quantity, identifier FROM cadcam_subparts where parentId=".$fifthLevelSubpartQueryResult['childId'];
												$sixthLevelSubpartQuery = $db->query($sql);
												while($sixthLevelSubpartQueryResult = $sixthLevelSubpartQuery->fetch_assoc())
												{												
													
													$part6=explode("`",partNumberCall($sixthLevelSubpartQueryResult['childId'],$sixthLevelSubpartQueryResult['identifier']));
													if($product==$sixthLevelSubpartQueryResult['childId'])
													{
														$highlight='bgcolor=lightblue';
													}
													else
													{
														$highlight='';
													}
													echo "<tr ".$highlight." >";
													
													if($sixthLevelSubpartQueryResult['identifier']==1)
													{
														echo "<td colspan=2><a href='anthony_editProduct.php?partId=".$sixthLevelSubpartQueryResult['childId']."&src=process&patternId=0'><font color=white>------------------</font><font face='Arial' color=black size=5><b>L </b></font>".$part6[0]."</a></td>";
													}
													else
													{
														echo "<td colspan=2><font color=white>------------------</font><font face='Arial' color=black size=5><b>L </b></font>".$part6[0]."</td>";
													}
													echo "<td>".$part6[1]."</td><td>".$part6[2]."</td><td align = 'center'>".$sixthLevelSubpartQueryResult['quantity']."</td><td align = 'center'>".$part6[3]."</td>";
													
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
echo "</div>";
?>
</body>
</html>
