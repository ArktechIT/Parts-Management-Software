<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	// $path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	$javascriptLib = "/Common Data/Libraries/Javascript/";
	$templates = "/Common Data/Templates/";	
	set_include_path($path);
	include('Templates/mysqliConnection.php');
	ini_set("display_errors", "on");
	
	function insertData($processCode,$partId)
	{
		include('PHP Modules/mysqliConnection.php');
		
		$processOrder= 0;
		$sqlPO = "SELECT processOrder FROM `cadcam_partprocess` WHERE partid = ".$partId." AND patternId = 0 ORDER BY processOrder DESC LIMIT 1";
		$queryPO = $db->query($sqlPO);
		if($queryPO AND $queryPO->num_rows > 0)
		{
			$resultPO = $queryPO->fetch_assoc();
			$processOrder = $resultPO['processOrder'];
		}
		$pO = $processOrder+1;
		
		$sqlSection = "SELECT processSection  FROM `cadcam_process` WHERE `processCode` = ".$processCode." LIMIT 1";
		$querySection = $db->query($sqlSection);
		if($querySection AND $querySection->num_rows > 0)
		{
			$resultSection = $querySection->fetch_assoc();
			$processSection = $resultSection['processSection'];
		}
		
		$insql = "INSERT INTO `cadcam_partprocess`(`partId`, `processOrder`, `processCode`, `processSection`, `patternId`) VALUES (".$partId.",".$pO.",".$processCode.",".$processSection.",0)";
		// echo "<br>".$insql;
		$insquery = $db->query($insql);
	}
	
	function getprocessCode($processCodeArray,$codeArray)
	{
		
		foreach($processCodeArray as $processCode)//$processCodeArray = array(61,62,63,64,65,378);
		{
			
			if(in_array($processCode, $codeArray))
			{
				$count;
			}
			else
			{
				$count++;
			}
			
			if($count==1)
			{
				//~ $codeForQueryArray[] = $processCode;
				return $processCode;
				break;
			}
			
		}
		//~ return $codeForQueryArray;
	}
	
	$gotPartId = $_GET['partId'];
	$gotPartNumber = $_GET['partNumber'];
	$gotPartNumber = $_GET['partNumber'];
	$fileArray = array();
	if(isset($_GET['partId']) AND isset($_GET['partNumber']));
	{
		$destination = "Sheetworks Raw Data csv/";
		
		$fileFlag = 1;
		if(fopen("../58 Sheetworks Data Import Software/Temporary Folder/PartInfo.csv", "r") == FALSE)
		{
			
			$fileArray[] = "PartInfo";
			$fileFlag = 0;
		}
		if(fopen("../58 Sheetworks Data Import Software/Temporary Folder/UnfoldDrawingInfo.csv", "r") == FALSE)
		{
			
			$fileArray[] = "UnfoldDrawingInfo";
			$fileFlag = 0;
		}
		if(fopen("../58 Sheetworks Data Import Software/Temporary Folder/FormingSpecial.csv", "r") == FALSE)
		{
			
			$fileArray[] = "FormingSpecial";
			$fileFlag = 0;
		}
		if(fopen("../58 Sheetworks Data Import Software/Temporary Folder/Processes.csv", "r") == FALSE)
		{
			
			$fileArray[] = "Processes";
			$fileFlag = 0;
		}
		if(fopen("../58 Sheetworks Data Import Software/Temporary Folder/HoleMaster.csv", "r") == FALSE)
		{
		
			$fileArray[] = "HoleMaster";
			$fileFlag = 0;
		}
		if(fopen("../58 Sheetworks Data Import Software/Temporary Folder/Bends.csv", "r") == FALSE)
		{
			$fileArray[] = "Bends";
			$fileFlag = 0;
		}
		
		if($fileFlag != 0)
		{
			$rowOne = 0;
			if(($handle = fopen("../58 Sheetworks Data Import Software/Temporary Folder/PartInfo.csv", "r")) !== FALSE){
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					if($rowOne > 0)
					{
						$dataOne['partId'][$rowOne] = $data[0];
						$dataOne['partNumber'][$rowOne] = $data[1];
						$dataOne['materialType'][$rowOne] = $data[2];
						$dataOne['thickness'][$rowOne] = $data[3];
					}
					$rowOne++;
				}
				fclose($handle);
			}
			
			$rowTwo = 0;
			if(($handle = fopen("../58 Sheetworks Data Import Software/Temporary Folder/UnfoldDrawingInfo.csv", "r")) !== FALSE){
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					if($rowTwo > 0)
					{
						$dataTwo['unfoldId'][$rowTwo] = $data[0];
						$dataTwo['partId'][$rowTwo] = $data[1];
						$dataTwo['itemX'][$rowTwo] = $data[6];
						$dataTwo['itemY'][$rowTwo] = $data[7];
						$dataTwo['surfaceArea'][$rowTwo] = $data[9];
						$dataTwo['weight'][$rowTwo] = $data[11];
					}
					$rowTwo++;
				}
				fclose($handle);
			}
			
			$rowThree;
			if(($handle = fopen("../58 Sheetworks Data Import Software/Temporary Folder/FormingSpecial.csv", "r")) !== FALSE){
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					if($rowThree > 0)
					{
						$dataThree['formSpclId'][$rowThree] = $data[0];
						$dataThree['unfoldId'][$rowThree] = $data[1];
						$dataThree['formSpclName'][$rowThree] = $data[2];
					}
					$rowThree++;
				}
				fclose($handle);
			}
			
			$rowFour = 0;
			if(($handle = fopen("../58 Sheetworks Data Import Software/Temporary Folder/Processes.csv", "r")) !== FALSE){
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					if($rowFour > 0)
					{
						$dataFour['formSpclId'][$rowFour] = $data[1];
						$dataFour['holeType'][$rowFour] = $data[3];
						$dataFour['length'][$rowFour] = $data[4];
						$dataFour['width'][$rowFour] = $data[5];
					}
					$rowFour++;
				}
				fclose($handle);
			}
			
			$rowFive = 0;
			if(($handle = fopen("../58 Sheetworks Data Import Software/Temporary Folder/HoleMaster.csv", "r")) !== FALSE){
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					if($rowFive > 0)
					{
						$dataFive['holeType'][$rowFive] = $data[0];
						$dataFive['holeTypeName'][$rowFive] = $data[1];
					}
					$rowFive++;
				}
				fclose($handle);
			}
			
			$rowSix = 0;
			if(($handle = fopen("../58 Sheetworks Data Import Software/Temporary Folder/Bends.csv", "r")) !== FALSE){
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					if($rowSix > 0)
					{
						$dataSix['unfoldId'][$rowSix] = $data[1];
						$dataSix['bendType'][$rowSix] = $data[2];
						$dataSix['angle'][$rowSix] = $data[4];
						$dataSix['innerR'][$rowSix] = $data[5];
						$dataSix['vwidth'][$rowSix] = $data[6];
						$dataSix['bendLineLength'][$row] = $data[9];
					}
					$rowSix++;
				}
				fclose($handle);
			}
			
			$partIdArray = array();
			$partNumberArr = array();
			$errorPartNumber = array();
			for($a=1;$a<$rowOne;$a++)
			{
				for($b=1;$b<$rowTwo;$b++)
				{
					if($dataOne['partId'][$a] == $dataTwo['partId'][$b])
					{
						
						$sqlParts = "SELECT partId, partNumber, x, y, itemWeight, itemArea, materialSpecId, itemLength, itemWidth, itemHeight FROM cadcam_parts WHERE partNumber LIKE '".$dataOne['partNumber'][$a]."'";
						$queryParts = $db->query($sqlParts);
						if($queryParts AND $queryParts->num_rows == 0)
						{
							$explodedPartNumber = explode("-", $dataOne['partNumber'][$a]);
							
							$partNumber = "";
							for($x=0;$x<COUNT($explodedPartNumber)-1;$x++)
							{
								if($x==0)
								{
									$partNumber = $explodedPartNumber[$x];
								}
								else
								{
									$partNumber = $partNumber."-".$explodedPartNumber[$x];
								}						
							}
							
							$revisionId = $explodedPartNumber[COUNT($explodedPartNumber)-1];
							
							$sqlParts = "SELECT partId, partNumber, x, y, itemWeight, itemArea, materialSpecId FROM cadcam_parts WHERE trim(partNumber) LIKE '".trim($partNumber)."' AND trim(revisionId) LIKE '".trim($revisionId)."'";
							$queryParts = $db->query($sqlParts);
						}
						if($queryParts AND $queryParts->num_rows > 0)
						{
							WHILE($resultParts = $queryParts->fetch_assoc())
							{
								
								$partId = $resultParts['partId'];
								$partBilang = $resultParts['partNumber'];
								$itemX = $resultParts['x'];
								$itemY = $resultParts['y'];
								$itemWeight = $resultParts['itemWeight'];
								$itemWidth = $resultParts['itemWidth'];
								$itemHeight = $resultParts['itemHeight'];
								$itemLength = $resultParts['itemLength'];
								$itemArea = $resultParts['itemArea'];
								$materialSpecId = $resultParts['materialSpecId'];
								
								
								if($partBilang == $gotPartNumber AND $partId == $gotPartId)
								{
									
									if($materialSpecId == 0)
									{
										$materialTypeId = "X";	
										
										$sqlTypeId = "SELECT materialTypeId FROM engineering_materialtype WHERE materialType = '".$dataOne['materialType'][$a]."' LIMIT 1";
										$queryTypeId = $db->query($sqlTypeId);
										if($queryTypeId AND $queryTypeId->num_rows == 0)
										{
											$materialTypeArray = explode("-",$dataOne['materialType'][$a]);
											$asd = array();
											
											for($x=0;$x<(COUNT($materialTypeArray)-1);$x++)
											{
												$asd[] = $materialTypeArray[$x];
											}
											
											$materialTypeId = implode("-",$asd);
											$sqlTypeId = "SELECT materialTypeId FROM engineering_materialtype WHERE materialType = '".$materialTypeId."' LIMIT 1";
											$queryTypeId = $db->query($sqlTypeId);
										}
										if($queryTypeId AND $queryTypeId->num_rows > 0)
										{
											
											$resultTypeId = $queryTypeId->fetch_assoc();
											$materialTypeId = $resultTypeId['materialTypeId'];
										}
										else if(strpos($dataOne['materialType'][$a], 'A2024-T3P') !== false)
										{										
											$materialTypeId = 57;																				
										}									
										else if(strpos($dataOne['materialType'][$a], 'A5052H32H') !== false)									
										{
											$materialTypeId = 56;
										}
										else if(strpos($dataOne['materialType'][$a], 'A5052H34H') !== false)
										{
											$materialTypeId = 59;
										}
										else if(strpos($dataOne['materialType'][$a], 'A6082-T6') !== false)
										{
											$materialTypeId = 33;
										}
										
										
										//------------------------------------------------------------------MATERIAL TYPE ID CHECKING ----------------------------------------------------
										$sql = "SELECT materialSpecId FROM cadcam_materialspecs WHERE materialTypeId = ".$materialTypeId." AND metalThickness = ".$dataOne['thickness'][$a];
										// echo $sql."<br>";
										$materialSpecIdQuery = $db->query($sql);
										if($materialSpecIdQuery->num_rows>0)
										{
											echo "SUCCESS";
											$materialSpecIdQueryResult = $materialSpecIdQuery->fetch_assoc();
											
											$partNumberArr[]=$dataOne['partNumber'][$a];
											
											$sql = "UPDATE cadcam_parts SET materialSpecId = ".$materialSpecIdQueryResult['materialSpecId']." WHERE partId = ".$partId;
											// echo $sql."<br>";
											$updateQuery = $db->query($sql);
											
											if($itemX == 0)
											{
												$sql = "UPDATE cadcam_parts SET x = ".$dataTwo['itemX'][$b]." WHERE partId = ".$partId." LIMIT 1";
												// echo $sql."<br>";
												$updateQuery = $db->query($sql);
											}
											
											if($itemY == 0)
											{
												$sql = "UPDATE cadcam_parts SET y = ".$dataTwo['itemY'][$b]." WHERE partId = ".$partId." LIMIT 1";
												//echo $sql."<br>";
												$updateQuery = $db->query($sql);
											}
											
											if($itemWeight == 0)
											{
												$sql = "UPDATE cadcam_parts SET itemWeight = ".$dataTwo['weight'][$b]." WHERE partId = ".$partId." LIMIT 1";
												// echo $sql."<br>";
												$updateQuery = $db->query($sql);
											}
											
											if($itemArea == 0)
											{
												$sql = "UPDATE cadcam_parts SET itemArea = ".(($dataTwo['surfaceArea'][$b]*2)/10000)." WHERE partId = ".$partId." LIMIT 1";
												// echo $sql."<br>";
												$updateQuery = $db->query($sql);
											}
										}
										else
										{
											$errorPartNumber[] =  $dataOne['partNumber'][$a];
											continue;
										}
									}
									
									//---------------------------------------------------------- DATA CHECKING ------------------------------------------------------------
									$rubStripFlag = 0;
									if(strpos($dataOne['materialType'][$a], 'JMS') !== false)
									{
										$rubStripFlag = 1;
									}
									else
									{
										$flagForms = 0;
										if($rowThree<=1)
										{
											$flagForms = 1;
										}
										else
										{
											for($c=1;$c<$rowThree;$c++)
											{
												if($dataThree['formSpclName'][$c] != 'NULL' OR trim($dataThree['formSpclName'][$c]) != "")
												{
													$flagForms = 1;
												}
											}
										}
										
										$flagBends = 0;
										if($rowSix <=1)
										{
											$flagBends = 1;
										}
										else
										{
											for($f=1;$f<$rowSix;$f++)
											{
												if($dataSix['unfoldId'][$c] != 'NULL' OR trim($dataSix['unfoldId'][$c]) != "")
												{
													$flagBends = 1;
												}
											}
										}
									}
									//---------------------------------------------------------- END OF DATA CHECKING ------------------------------------------------------------
									if($rubStripFlag == 1 OR $flagBends != 1)
									{
										if($itemHeight == 0)
										{
											echo"<br>".$sql = "UPDATE cadcam_parts SET itemHeight = ".$dataOne['thickness'][$a]." WHERE partId = ".$partId." LIMIT 1";
											//echo $sql."<br>";
											$updateQuery = $db->query($sql);
										}
										
										if($itemWidth == 0)
										{
											echo"<br>".$sql = "UPDATE cadcam_parts SET itemWidth = ".$dataTwo['itemY'][$b]." WHERE partId = ".$partId." LIMIT 1";
											//echo $sql."<br>";
											$updateQuery = $db->query($sql);
										}
										
										if($itemLength == 0)
										{
											echo"<br>".$sql = "UPDATE cadcam_parts SET itemLength = ".$dataTwo['itemX'][$b]." WHERE partId = ".$partId." LIMIT 1";
											//echo $sql."<br>";
											$updateQuery = $db->query($sql);
										}
									}
									
									//-------------------------------------------CHECKING EXISTING PROCESS --------------------------------
									$sqlZ = "SELECT partId from `cadcam_partprocess` WHERE partId = ".$partId."";
									$queryZ = $db->query($sqlZ);
									if($queryZ AND $queryZ->num_rows > 0)
									{
										$partIdArray[] = $partId;

										continue;
									}
									
									if($rubStripFlag == 1 OR $flagForms != 1)
									{
										
										//-------------------------- INSERT DEFAULT PROCESS ----------------------------------------------
										$processCodeArray = array(392,310,59,56,99,311,91,187,181,94,144);
										foreach($processCodeArray AS $processCode)
										{
											insertData($processCode,$partId);
										}
									}
									
									if($flagForms == 1)
									{
										
										// echo "<hr><br> SPECIAL PROCESS";
										$formSpclIdArray = array();
										$formSpclNameArray = array();

										for($c=1;$c<$rowThree;$c++)
										{
											if($dataThree['unfoldId'][$c] == $dataTwo['unfoldId'][$b])
											{
												
												if($dataThree['formSpclName'][$c] != 'NULL')
												{
													$formSpclNameArray[] = $dataThree['formSpclName'][$c];
													$formSpclIdArray[] = $dataThree['formSpclId'][$c];
													// echo $dataThree['formSpclName	'][$c]."<br>";
												}
											}
										}
										
										$formSpclNameArray1 = array_unique($formSpclNameArray);								
										foreach($formSpclNameArray1 as $formSpclName)
										{
											$codeArray = array();
											$sqlCode = "SELECT processCode FROM `cadcam_partprocess` WHERE partId = ".$partId."";
											$queryCode = $db->query($sqlCode);
											if($queryCode AND $queryCode->num_rows > 0)
											{
												WHILE($resultCode = $queryCode->fetch_assoc())
												{
													$codeArray[] = $resultCode['processCode']; 
												}
											}
											
											if($formSpclName == 'EMBOSS-D10')
											{	
												$codeForQueryArray = array();
												echo "<br><b>emboss</b>";
												$count=0;
												$processCodeArray = array(61,62,63,64,65,378);
												
												$codeForQueryArray[] = getprocessCode($processCodeArray,$codeArray);
												
												foreach ($codeForQueryArray AS $processCode)
												{
													insertData($processCode,$partId);
												}
											}
											elseif($formSpclName == 'M3-TAP')
											{
												echo "<br><b>3-tap</b>";
												$codeForQueryArray = array();
												$count=0;
												$processCodeArray = array(122, 123, 124);
												
												$codeForQueryArray[] = processCode($processCodeArray,$codeArray);
												
												foreach ($codeForQueryArray AS $processCode)
												{
													insertData($processCode,$partId);
												}
											}
											elseif($formSpclName == 'M3BR-TAP')
											{	
												echo "<br><b>3br-tap</b>";
												$codeForQueryArray = array();
												$count=0;
												$processCodeArray = array(101, 102, 103);
												
												$codeForQueryArray[] = getprocessCode($processCodeArray,$codeArray);
												
												foreach ($codeForQueryArray AS $processCode)
												{
													insertData($processCode,$partId);
												}
												
												$count=0;
												$codeForQueryArray = array();
												$processCodeArray = array(122, 123, 124);
												
												$codeForQueryArray[] = getprocessCode($processCodeArray,$codeArray);
												
												foreach ($codeForQueryArray AS $processCode)
												{
													insertData($processCode,$partId);
												}
											}
											elseif($formSpclName == 'M4BR-TAP')
											{
												$codeForQueryArray = array();
												echo "<br><b>4br-tap</b>";
												$count=0;
												$processCodeArray = array(101, 102, 103);
												
												$codeForQueryArray[] = getprocessCode($processCodeArray,$codeArray);
												
												foreach ($codeForQueryArray AS $processCode)
												{
													insertData($processCode,$partId);
												}
											
												$codeForQueryArray = array();
												$count=0;
												$processCodeArray = array(122, 123, 124);
												
												$codeForQueryArray[] = getprocessCode($processCodeArray,$codeArray);
												
												foreach ($codeForQueryArray AS $processCode)
												{
													insertData($processCode,$partId);
												}
											}
											elseif($formSpclName == 'HC6-90DEG')
											{	
												$codeForQueryArray = array();
												echo "<br><b>deg</b>";
												$count=0;
												$processCodeArray = array(76, 77, 193, 337, 399);

												$codeForQueryArray[] = getprocessCode($processCodeArray,$codeArray);
												foreach ($codeForQueryArray AS $processCode)
												{
													insertData($processCode,$partId);
												}
											}
										}

										echo "<hr><br> PARTHOLES";
										
										if($dataFour['holeType'][$d]== 0)		{ $holeTypeName = "Undefined"; }
										elseif($dataFour['holeType'][$d]==1)	{ $holeTypeName = "RO"; }
										elseif($dataFour['holeType'][$d]==2)    { $holeTypeName = "SQ"; }
										elseif($dataFour['holeType'][$d]==3)    { $holeTypeName = "OB"; }
										elseif($dataFour['holeType'][$d]==4)    { $holeTypeName = "RE"; }
										elseif($dataFour['holeType'][$d]==5)    { $holeTypeName = "CP"; }
										elseif($dataFour['holeType'][$d]==6)    { $holeTypeName = "DD"; }
										elseif($dataFour['holeType'][$d]==7)    { $holeTypeName = "SD"; }
										elseif($dataFour['holeType'][$d]==8)    { $holeTypeName = "RR"; }
										elseif($dataFour['holeType'][$d]==9)    { $holeTypeName = "EXT"; }
										elseif($dataFour['holeType'][$d]==10)   { $holeTypeName = "1 Char.MK"; }
										elseif($dataFour['holeType'][$d]==11)   { $holeTypeName = "AI MK"; }
										elseif($dataFour['holeType'][$d]==12)   { $holeTypeName = "Laser"; }
										elseif($dataFour['holeType'][$d]==13)   { $holeTypeName = "Ellipse"; }
										elseif($dataFour['holeType'][$d]==14)   { $holeTypeName = "Deformed Hole"; }
										elseif($dataFour['holeType'][$d]==15)   { $holeTypeName = "FM"; }
										elseif($dataFour['holeType'][$d]==16)   { $holeTypeName = "SP"; }
											
										$sql = "INSERT INTO `engineering_partholes`(`partId`, `holeType`, `holeDataOne`, `holeDataTwo`) VALUES (".$partId.",'".$holeTypeName."',".$dataFour['length'][$d].",".$dataFour['width'][$d].")";
										// echo "<br>".$sql;
										$query = $db->query($sql);	

									}
									
									if($flagBends == 1)
									{
										echo "<hr>";
										
										$flag = 0;
										$processCode = 0;
										for($f=1;$f<$rowSix;$f++)
										{
											if($dataTwo['unfoldId'][$b] == $dataSix['unfoldId'][$f])
											{
												$flag = 1;
												if($dataSix['bendType'][$f] == 1)
												{
													$bendTypeName = "V Bend";
												}
												elseif($dataSix['bendType'][$f] == 2)
												{
													$bendTypeName = "RR Bend";
												}
												elseif($dataSix['bendType'][$f] == 3)
												{
													$bendTypeName = "FR Bend";
												}
												elseif($dataSix['bendType'][$f] == 4)
												{
													$bendTypeName = "Curling";
												}
												elseif($dataSix['bendType'][$f] == 5)
												{
													$bendTypeName = "Continuous R";
												}
												elseif($dataSix['bendType'][$f] == 6)
												{
													$bendTypeName = "Internal Tab";
												}
												elseif($dataSix['bendType'][$f] == 7)
												{
													$bendTypeName = "Hemming";
												}
												elseif($dataSix['bendType'][$f] == 8)
												{
													$bendTypeName = "Z-Bend";
												}
												elseif($dataSix['bendType'][$f] == 9)
												{
													$bendTypeName = "Sp. Z-Bend";
												}
												
												$processCode++;
												$processOrder= 0;
												$sqlPO = "SELECT processOrder FROM `cadcam_partprocess` WHERE partid = ".$partId." AND patternId = 0 ORDER BY processOrder DESC LIMIT 1";
												$queryPO = $db->query($sqlPO);
												if($queryPO AND $queryPO->num_rows > 0)
												{
													$resultPO = $queryPO->fetch_assoc();
													$processOrder = $resultPO['processOrder'];
												}
												
												$sqlSection = "SELECT processSection  FROM `cadcam_process` WHERE `processCode` = ".$processCode." LIMIT 1";
												$querySection = $db->query($sqlSection);
												if($querySection AND $querySection->num_rows > 0)
												{
													$resultSection = $querySection->fetch_assoc();
													$processSection = $resultSection['processSection'];
												}
												
												$pO = $processOrder+1;
												echo "<hr><b>BEND-".$processCode."</b>";
												$bendSql = "INSERT INTO `cadcam_partprocess` (`partId`,`processOrder`,`processCode`,`processSection`,`patternId`) VALUES (".$partId.", ".$pO.", ".$processCode.", ".$processSection.", 0)";
												// echo "<br>".$bendSql;
												$bendQuery = $db->query($bendSql);
												// echo $procesCode;
											
												if($dataSix['vwidth'][$d] != 0)
												{
													$bendSql = "INSERT INTO `cadcam_partprocessnote` (`partId`,`processCode`,`patternId`,`noteDetails`,`noteIdentifier`) VALUES (".$partId.", ".$processCode.", 0, ".$dataSix['vwidth'][$f].", 0)";
													// echo "<br>".$bendSql;
													$bendQuery = $db->query($bendSql);
												}
												if($dataSix['innerR'][$d] != 0)
												{
													$bendSql = "INSERT INTO `cadcam_partprocessnote` (`partId`,`processCode`,`patternId`,`noteDetails`,`noteIdentifier`) VALUES (".$partId.", ".$processCode.", 0, ".$dataSix['vwidth'][$f].", 1)";
													// echo "<br>".$bendSql;
													$bendQuery = $db->query($bendSql);
												}
												if($dataSix['angle'][$d] != 0)
												{
													$bendSql = "INSERT INTO `cadcam_partprocessnote` (`partId`,`processCode`,`patternId`,`noteDetails`,`noteIdentifier`) VALUES (".$partId.", ".$processCode.", 0, ".$dataSix['vwidth'][$f].", 7)";
													// echo "<br>".$bendSql;
													$bendQuery = $db->query($bendSql);
												}
												if($dataSix['bendLineLength'][$d] != 0)
												{
													$bendSql = "INSERT INTO `cadcam_partprocessnote` (`partId`,`processCode`,`patternId`,`noteDetails`,`noteIdentifier`) VALUES (".$partId.", ".$processCode.", 0, ".$dataSix['bendLineLength'][$f].", 8)";
													// echo "<br>".$bendSql;
													$bendQuery = $db->query($bendSql);
												}
												if($dataSix['bendType'][$d] != 0)
												{
													$bendSql = "INSERT INTO `cadcam_partprocessnote` (`partId`,`processCode`,`patternId`,`noteDetails`,`noteIdentifier`) VALUES (".$partId.", ".$processCode.", 0, ".$bendTypeName.", 9)";
													// echo "<br>".$bendSql;
													$bendQuery = $db->query($bendSql);
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
				
				// echo implode(",", $partNumberArr);
				// $partNumberrArray = array_unique($partNumberArr);
				// if(COUNT($partNumberrArray) != 0)
				// {
					// echo COUNT($partNumberrArray)."<br>";
					// echo "<center><h1>PartNumber(s) '".implode("','",$partNumberrArray)."' have been successfully Processed.</h1></center>";
				// }
				// if(COUNT($errorPartNumber) != 0)
				// {
					// echo "<center><h1>ERROR: ".implode(",", array_unique($errorPartNumber))." have no material specs.</h1></center>";
				// }
				// if(COUNT($partIdArray) != 0)
				// {
					// echo "<center><h1>The Data with Part Id ".implode(",", $partIdArray)." have existing process data.</h1></center>";	
				// }
				
				// $date = date("Y-m-d H-i-s");
				
				// echo "/Sheetworks Raw Data csv/PartInfo(".$date.").csv";
				// rename("../58 Sheetworks Data Import Software/Temporary Folder/PartInfo.csv", "Sheetworks Raw Data csv/PartInfo(".$date.").csv");
				// rename("../58 Sheetworks Data Import Software/Temporary Folder/UnfoldDrawingInfo.csv", "Sheetworks Raw Data csv/UnfoldDrawingInfo(".$date.").csv");
				// rename("../58 Sheetworks Data Import Software/Temporary Folder/FormingSpecial.csv", "Sheetworks Raw Data csv/FormingSpecial(".$date.").csv");
				// rename("../58 Sheetworks Data Import Software/Temporary Folder/Processes.csv", "Sheetworks Raw Data csv/Processes(".$date.").csv");
				// rename("../58 Sheetworks Data Import Software/Temporary Folder/HoleMaster.csv", "Sheetworks Raw Data csv/HoleMaster(".$date.").csv");
				// rename("../58 Sheetworks Data Import Software/Temporary Folder/Bends.csv", "Sheetworks Raw Data csv/Bends(".$date.").csv");
				// exit(0);
				header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
		}
		else
		{
			echo count($fileArray);
			if(count($fileArray) == 6)
			{
				echo "<center><h1>ERROR: Files not found.</h1></center>";
			}
			else
			{
				echo "<center><h1>ERROR: ".implode(",", $fileArray)." file(s) is/are not Found.</h1></center>";
			}
			
		}
	}
?>