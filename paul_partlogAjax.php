<?php
	include("../Common Data/PHP Modules/mysqliConnection.php");
	ini_set("display_errors", "on");
	$queryLimit = 50;
	
	if($_POST)
	{
	
		$inputPartId = (isset($_POST['inputPartId'])) ? $_POST['inputPartId'] : "";
		$filterQuery = (isset($_POST['filterQuery'])) ? $_POST['filterQuery'] : "";

		$group_number = filter_var($_POST["group_no"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
		
		//throw HTTP error if group number is not valid
		if(!is_numeric($group_number))
		{
			header('HTTP/1.1 500 Invalid number!');
			exit();
		}
		
		//get current starting point of records
		$queryPosition = ($group_number * $queryLimit);
			
		//Limit our results within a specified range.
		
		$i=0;
		
		$z = 0;
		$sql = "SELECT partlogId, partId, date, query, field, oldValue, newValue, details, ip, user, userRemarks FROM system_partlog where partId = ".$inputPartId." ".$filterQuery." ORDER BY date DESC LIMIT ".$queryPosition.", ".$queryLimit."";
		$partLogQuery = $db->query($sql);
		if($partLogQuery AND $partLogQuery->num_rows > 0)
		{
			while($partLogQueryResult = $partLogQuery->fetch_assoc())
			{
				$partlogId[$z]=$partLogQueryResult['partlogId'];
				$partId[$z]=$partLogQueryResult['partId'];
				
				$sql = "SELECT partNumber, partName, revisionId, customerId FROM cadcam_parts WHERE partId = ".$partId[$z];
				$partsQuery = $db->query($sql);		
				if($partsQuery->num_rows > 0) 
				{
					$partsQueryResult = $partsQuery->fetch_assoc();
					$partNumber[$z] = $partsQueryResult['partNumber'];
					$partName[$z] = $partsQueryResult['partName'];
					$revision[$z] = $partsQueryResult['revisionId'];
					
					$sql = "SELECT customerAlias FROM sales_customer WHERE customerId = ".$partsQueryResult['customerId'];
					$customerQuery = $db->query($sql);			
					if($customerQuery->num_rows > 0) 
					{
						$customerQueryResult = $customerQuery->fetch_assoc();
						$customerName[$z] = $customerQueryResult['customerAlias'];
					}
				}
				
				$daterz='';
				$daterz=$partLogQueryResult['date'];
				$daterz=explode("-",$daterz);
				$dater2 = str_split($daterz[2]);
				$date[$z]=$daterz[0]."-".$daterz[1]."-".$dater2[0].$dater2[1];
				$query[$z]=$partLogQueryResult['query']; 
				if($query[$z]=='1') //Insert
				{
					$query[$z]='Add';
				}
				else if($query[$z]=='2') //Update
				{
					$query[$z]='Edit';
				}
				else if($query[$z]=='3') //Delete
				{
					$query[$z]='Delete';
				}
				else //Copy
				{
					$query[$z]='Copy';
				}
				$field[$z]=$partLogQueryResult['field'];
				
				if($field[$z]=='1'){$field[$z]='PartNumber';}
				else if($field[$z]=='2'){$field[$z]='PartName';}
				else if($field[$z]=='3'){$field[$z]='revision';}
				else if($field[$z]=='4'){$field[$z]='customerId';}
				else if($field[$z]=='5'){$field[$z]='Qty/Sheet';}
				else if($field[$z]=='6'){$field[$z]='Material Specs';}
				else if($field[$z]=='7'){$field[$z]='Material Details';}
				else if($field[$z]=='8'){$field[$z]='Drawing';}
				else if($field[$z]=='9'){$field[$z]='Work Instruction';}
				else if($field[$z]=='10'){$field[$z]='Status';}
				else if($field[$z]=='11'){$field[$z]='Subpart';}
				else if($field[$z]=='12'){$field[$z]='Subpart Acc.';}
				else if($field[$z]=='13'){$field[$z]='Subpart Qty';}
				else if($field[$z]=='14'){$field[$z]='Subpart ID';}
				else if($field[$z]=='15'){$field[$z]='Process Order';}
				else if($field[$z]=='16'){$field[$z]='Process';}
				else if($field[$z]=='17'){$field[$z]='Process Details';}
				else if($field[$z]=='18'){$field[$z]='Subcon';}
				else if($field[$z]=='19'){$field[$z]='Subcon Process';}
				else if($field[$z]=='20'){$field[$z]='Subcon PartID';}
				else if($field[$z]=='21'){$field[$z]='Material Specs';}
				else if($field[$z]=='22'){$field[$z]='AccessoryID';}
				else if($field[$z]=='23'){$field[$z]='AccessoryNumber';}
				else if($field[$z]=='24'){$field[$z]='AccessoryName';}
				else if($field[$z]=='25'){$field[$z]='price-delivery';}
				else if($field[$z]=='26'){$field[$z]='itemXY';}
				else if($field[$z]=='27'){$field[$z]='TreatmentId';}
				else if($field[$z]=='28'){$field[$z]='Clear';}
				else if($field[$z]=='29'){$field[$z]='Prime';}
				else if($field[$z]=='30'){$field[$z]='Passivation';}
				else if($field[$z]=='31'){$field[$z]='Process Tool Id';}
				else if($field[$z]=='32'){$field[$z]='Process Section';}
				else if($field[$z]=='33'){$field[$z]='Tool Name';}
				else if($field[$z]=='34'){$field[$z]='Alternate Material';}
				else if($field[$z]=='35'){$field[$z]='New Qty Per Sheet';}
				else if($field[$z]=='36'){$field[$z]='Cutting Condition';}
				else if($field[$z]=='37'){$field[$z]='engineering_subconprocessor';}
				else if($field[$z]=='38'){$field[$z]='engineering_subpartprocesslink';}
				else if($field[$z]=='40'){$field[$z]='Laser Material Name';}
				else if($field[$z]=='41'){$field[$z]='Lot Size';}
				
				
				$oldValue[$z]=$partLogQueryResult['oldValue']; 
				$newValue[$z]=$partLogQueryResult['newValue'];
				
				// ----------------------------------------- Changes In Material Specification --------------------------------------------
				if($partLogQueryResult['field']=='6' OR $partLogQueryResult['field']=='21')
				{
					// ----------------------------------------- Old Value -------------------------------------------------------------------
					//~ $sql = "SELECT metalType, metalThickness from cadcam_materialspecs where materialSpecId=".$oldValue[$z];
					//~ $materialSpecificationQuery = $db->query($sql);			
					//~ if($materialSpecificationQuery->num_rows > 0) 
					//~ {
						//~ $materialSpecificationQueryResult = $materialSpecificationQuery->fetch_assoc();
						//~ $metalType = $materialSpecificationQueryResult['metalType'];
						//~ $metalThickness = $materialSpecificationQueryResult['metalThickness']; 
						//~ $mats='';
						//~ if($metalType!='')
						//~ {
							//~ $mats = $metalType;
						//~ }
						//~ if($metalThickness!='0.00')
						//~ {
							//~ if($mats!='')
							//~ {
								//~ $mats=$mats.' t'.$metalThickness;
							//~ }
							//~ else
							//~ {
								//~ $mats=$metalThickness; 
							//~ }
						//~ }				
						//~ $oldValue[$z]=$mats;				
					//~ }
					
					//;cadcam_materialspecs;
					$sql = "SELECT materialTypeId, metalThickness from cadcam_materialspecs where materialSpecId=".$oldValue[$z];
					$materialSpecificationQuery = $db->query($sql);			
					if($materialSpecificationQuery->num_rows > 0) 
					{
						$materialSpecificationQueryResult = $materialSpecificationQuery->fetch_assoc();
						$materialTypeId = $materialSpecificationQueryResult['materialTypeId'];
						$metalThickness = $materialSpecificationQueryResult['metalThickness'];
						
						$metalType = '';
						$sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$materialTypeId." LIMIT 1";
						$queryMaterialType = $db->query($sql);
						if($queryMaterialType AND $queryMaterialType->num_rows > 0)
						{
							$resultMaterialType = $queryMaterialType->fetch_assoc();
							$metalType = $resultMaterialType['materialType'];
						}
						 
						$mats='';
						if($metalType!='')
						{
							$mats = $metalType;
						}
						if($metalThickness!='0.00')
						{
							if($mats!='')
							{
								$mats=$mats.' t'.$metalThickness;
							}
							else
							{
								$mats=$metalThickness; 
							}
						}				
						$oldValue[$z]=$mats;				
					}
					//;cadcam_materialspecs;
					// ----------------------------------------- End Of Old Value -------------------------------------------------------------------
					
					// ----------------------------------------- New Value --------------------------------------------------------------------------
					//~ $sql = "SELECT metalType, metalThickness from cadcam_materialspecs where materialSpecId=".$newValue[$z];
					//~ $materialSpecificationQuery = $db->query($sql);			
					//~ if($materialSpecificationQuery->num_rows > 0) 
					//~ {
						//~ $materialSpecificationQueryResult = $materialSpecificationQuery->fetch_assoc();
						//~ $metalType = $materialSpecificationQueryResult['metalType'];
						//~ $metalThickness = $materialSpecificationQueryResult['metalThickness']; 
						//~ $mats='';
						//~ if($metalType!='')
						//~ {
							//~ $mats = $metalType;
						//~ }
						//~ if($metalThickness!='0.00')
						//~ {
							//~ if($mats!='')
							//~ {
							//~ $mats=$mats.' t'.$metalThickness;
							//~ }
							//~ else
							//~ {
								//~ $mats=$metalThickness; 
							//~ }
						//~ }				
						//~ $newValue[$z]=$mats;				
					//~ }
					
					//;cadcam_materialspecs;
					$sql = "SELECT materialTypeId, metalThickness from cadcam_materialspecs where materialSpecId=".$newValue[$z];
					$materialSpecificationQuery = $db->query($sql);			
					if($materialSpecificationQuery->num_rows > 0) 
					{
						$materialSpecificationQueryResult = $materialSpecificationQuery->fetch_assoc();
						$materialTypeId = $materialSpecificationQueryResult['materialTypeId'];
						$metalThickness = $materialSpecificationQueryResult['metalThickness']; 
						
						$metalType = '';
						$sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$materialTypeId." LIMIT 1";
						$queryMaterialType = $db->query($sql);
						if($queryMaterialType AND $queryMaterialType->num_rows > 0)
						{
							$resultMaterialType = $queryMaterialType->fetch_assoc();
							$metalType = $resultMaterialType['materialType'];
						}						
						
						$mats='';
						if($metalType!='')
						{
							$mats = $metalType;
						}
						if($metalThickness!='0.00')
						{
							if($mats!='')
							{
							$mats=$mats.' t'.$metalThickness;
							}
							else
							{
								$mats=$metalThickness; 
							}
						}				
						$newValue[$z]=$mats;				
					}
					//;cadcam_materialspecs;
					// -------------------------------------- End Of New Value ---------------------------------------------------------------------
				}
				// ---------------------------------- End of Changes In Material Specification --------------------------------------------
				// ------------------------------------- Changes In Accessories -----------------------------------------------------------
				else if($partLogQueryResult['field']=='22')
				{
					// -------------------------------------- Old Value --------------------------------------------------------
					$sql = "SELECT accessoryNumber from cadcam_accessories where accessoryId=".$oldValue[$z];
					$accessoryQuery = $db->query($sql);			
					if($accessoryQuery->num_rows > 0) 
					{
						$accessoryQueryResult = $accessoryQuery->fetch_assoc();			
						$oldValue[$z] = $accessoryQueryResult['accessoryNumber'];
					}
					// --------------------------------------- End Of Old Value -----------------------------------------------------
					
					// ---------------------------------------- New Value ----------------------------------------------------
					$sql = "SELECT accessoryNumber from cadcam_accessories where accessoryId=".$newValue[$z];
					$accessoryQuery = $db->query($sql);			
					if($accessoryQuery->num_rows > 0) 
					{
						$accessoryQueryResult = $accessoryQuery->fetch_assoc();				
						$newValue[$z] = $accessoryQueryResult['accessoryNumber'];
					}
					// --------------------------------------- End Of New Value ----------------------------------------------------										
				}
				// ------------------------------------ End of Changes In Accessories ----------------------------------------------------
				// ------------------------------------ Changes In Subpart ----------------------------------------------------------------
				else if($partLogQueryResult['field']=='11')
				{
					// -------------------------------- Old Value -------------------------------
					$sql = "SELECT partNumber from cadcam_parts where partId=".$oldValue[$z];
					$subpartQuery = $db->query($sql);			
					if($subpartQuery->num_rows > 0) 
					{
						$subpartQueryResult = $subpartQuery->fetch_assoc();
						$oldValue[$z] = $subpartQueryResult['partNumber'];
					}
					// ----------------------------- End of Old Value ----------------------------
					
					// ----------------------------- New Value -----------------------------------			
					$sql = "SELECT partNumber from cadcam_parts where partId=".$newValue[$z];
					$subpartQuery = $db->query($sql);			
					if($subpartQuery->num_rows > 0) 
					{
						$subpartQueryResult = $subpartQuery->fetch_assoc();
						$newValue[$z] = $subpartQueryResult['partNumber'];
					}
					// -------------------------- End of New Value -------------------------------			
				}
				// ------------------------------------ End Of Changes In Subpart -------------------------------------------------------
				// ------------------------------------- Changes In Subpart Accessories -------------------------------------------------
				else if($partLogQueryResult['field']=='12')
				{
					// ---------------------------------- Old Value ----------------------------------------------------------------------
					$sql = "SELECT accessoryNumber from cadcam_accessories where accessoryId=".$oldValue[$z];
					$accessorySubpartQuery = $db->query($sql);			
					if($accessorySubpartQuery->num_rows > 0) 
					{
						$accessorySubpartQueryResult = $accessorySubpartQuery->fetch_assoc();				
						$oldValue[$z]= $accessorySubpartQueryResult['accessoryNumber'];
					}
					// ---------------------------------- End of Old Value ---------------------------------------------------------------
					
					// ---------------------------------- New Value ----------------------------------------------------------------
					$sql = "SELECT accessoryNumber from cadcam_accessories where accessoryId=".$newValue[$z];
					$accessorySubpartQuery = $db->query($sql);			
					if($accessorySubpartQuery->num_rows > 0) 
					{
						$accessorySubpartQueryResult = $accessorySubpartQuery->fetch_assoc();				
						$newValue[$z]= $accessorySubpartQueryResult['accessoryNumber'];
					}
					// ---------------------------------- End Of New Value ----------------------------------------------
				}
				// ------------------------------- End of Changes In Subpart Accessories --------------------------------
				// ------------------------------- Changes In Process (Update) ------------------------------------------
				else if($partLogQueryResult['field']=='16' AND $partLogQueryResult['query']=='2')
				{
					// ---------------------------------- Old Value -----------------------------------------------------
					$sql = "SELECT processName from cadcam_process where processCode=".$oldValue[$z];
					$processQuery = $db->query($sql);			
					if($processQuery->num_rows > 0) 
					{	
						$processQueryResult = $processQuery->fetch_assoc();		
						$oldValue[$z]= $processQueryResult['processName']." (".$partLogQueryResult['details'].")";				
					}
					// --------------------------------- End Of Old Value ------------------------------------------------
					
					// --------------------------------- New Value -------------------------------------------------------
					$sql = "SELECT processName from cadcam_process where processCode=".$newValue[$z];
					$processQuery = $db->query($sql);			
					if($processQuery->num_rows > 0) 
					{	
						$processQueryResult = $processQuery->fetch_assoc();		
						$newValue[$z]= $processQueryResult['processName']." (".$partLogQueryResult['details'].")";				
					}
					// -------------------------------- End Of New Value -------------------------------------------------
				}
				// ------------------------------- End of Changes In Process (Update) ------------------------------------
				// ------------------------------- Canges In Process (Delete) -------------------------------------------
				else if($partLogQueryResult['field']=='16' && $partLogQueryResult['query']=='3')
				{
					// ------------------------------- Old Value ---------------------------------------------
					$oldValue[$z]= $partLogQueryResult['details'];	
					$od=explode("`",$partLogQueryResult['oldValue']);
					$nw=explode("`",$partLogQueryResult['newValue']); $mrk=''; $v1='';
					for($e=0;$e<count($od);$e++)
					{
						if($od[$e]!=$nw[$e] && $mrk=='')
						{
							$vl=$od[$e];
							$mrk='1';
						}
					}
					
					$oldValue[$z]=$vl;			
					$sql = "SELECT processName from cadcam_process where processCode=".$oldValue[$z];
					$processQuery = $db->query($sql);			
					if($processQuery->num_rows > 0)
					{
						$processQueryResult = $processQuery->fetch_assoc();	
						$oldValue[$z]= $processQueryResult['processName'];
					}
					// ------------------------------- End of Old Value -------------------------------------------
					
					// ------------------------------- New Value --------------------------------------------------
					$sql = "SELECT processName from cadcam_process where processCode=".$newValue[$z];
					$processQuery = $db->query($sql);			
					if($processQuery->num_rows > 0)
					{
						$processQueryResult = $processQuery->fetch_assoc();	
						$newValue[$z]= $processQueryResult['processName'];
					}
					// ------------------------------- End of New Value -------------------------------------------
				}
				// ------------------------------- End Of Changes In Process (Delete) -------------------------------------------
				// ------------------------------- Changes In Process (Insert) -------------------------------------------
				else if($partLogQueryResult['field']=='16' && $partLogQueryResult['query']=='1')
				{
					// --------------------------------- Old Value ---------------------------------------------------------
					$oldValue[$z]= $partLogQueryResult['details'];
					// --------------------------------- End of Old Value ---------------------------------------------------
					
					// --------------------------------- New Value ------------------------------------------------------
					$sql = "SELECT processName from cadcam_process where processCode=".$newValue[$z];
					$processQuery = $db->query($sql);			
					if($processQuery->num_rows > 0)
					{
						$processQueryResult = $processQuery->fetch_assoc();				
						$newValue[$z]= $processQueryResult['processName'];
					}
					// --------------------------------- End of New Value --------------------------------------------------			
				}
				// ------------------------------- End Of Changes In Process (Insert) -------------------------------------------
				else if($partLogQueryResult['field']=='16')
				{
					$oldValue[$z]='';
					$newValue[$z]='';
				}
				// -------------------------------- Changes In Subcon Process --------------------------------------------------
				else if($partLogQueryResult['field']=='19') //PAUL
				{
					// ---------------------------------- Old Value --------------------------------------------------------
					$sql = "SELECT treatmentName from engineering_treatment WHERE treatmentId = ".$oldValue[$z];
					$treatmentQuery = $db->query($sql);			
					if($treatmentQuery->num_rows > 0)
					{
						$treatmentQueryResult = $treatmentQuery->fetch_assoc();
						$oldValue[$z]= $treatmentQueryResult['treatmentName'];			
					}
					// --------------------------------- End Of Old Value --------------------------------------------------
					// --------------------------------- New Value ---------------------------------------------------------
					$sql = "SELECT treatmentName from engineering_treatment WHERE treatmentId = ".$newValue[$z];
					$treatmentQuery = $db->query($sql);			
					if($treatmentQuery->num_rows > 0)
					{
						$treatmentQueryResult = $treatmentQuery->fetch_assoc();
						$newValue[$z]= $treatmentQueryResult['treatmentName'];			
					}
					// --------------------------------- End Of New Value -------------------------------------------------
				}
				// -------------------------------- End Of Changes In Subcon Process --------------------------------------------------
				else if($partLogQueryResult['field'] == '27') //Paul Treatment Id (Treatment)
				{
					$sql = "SELECT treatmentName from cadcam_treatmentprocess where treatmentId = ".$newValue[$z];
					$treatmentQuery = $db->query($sql);			
					if($treatmentQuery->num_rows > 0)
					{
						$treatmentQueryResult = $treatmentQuery->fetch_assoc();			
						$newValue[$z]=  $treatmentQueryResult['treatmentName'];	
					}
				}		
				else if($partLogQueryResult['field'] == '37') //Paul Treatment Id (Treatment)
				{
					$sql = "SELECT subconAlias from purchasing_subcon where subconId = ".$newValue[$z];
					$querySubcon = $db->query($sql);			
					if($querySubcon->num_rows > 0)
					{
						$resultSubcon = $querySubcon->fetch_assoc();			
						$newValue[$z]=  $resultSubcon['subconAlias'];	
					}
				}		
				
				$ip[$z]=$partLogQueryResult['ip'];
				$user[$z]=$partLogQueryResult['user'];
				$userRemarks[$z]=$partLogQueryResult['userRemarks'];
				$z++;
			}
		}
		
		for($x=0; $x < $z; $x++)
		{
			?>
			<tr>			
				<td width='50' align = "right"><?php echo $date[$x];?></td>
				<td width='60'><?php echo $query[$x];?></td>
				<td width='100'><?php echo $field[$x];?></td>            
				<td width='100'><?php echo $oldValue[$x];?></td>
				<td width='100'><?php echo $newValue[$x];?></td>
				<td width='100'><?php echo $userRemarks[$x];?></td>
				<td width='100'><?php echo $user[$x];?></td>
			</tr>
			<?php 
		}
	}
	?>
