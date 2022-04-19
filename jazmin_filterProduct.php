<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	// $path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	set_include_path($path);
	include('PHP Modules/mysqliConnection.php');
	include('PHP Modules/gerald_functions.php');
	include('PHP Modules/anthony_retrieveText.php');
	ini_set("display_errors","on");
	
	$sqlFilter = isset($_POST['sqlFilter']) ? $_POST['sqlFilter'] : "";
	$lastValue = (isset($_POST['lastValue'])) ? $_POST['lastValue'] : '';
	$showOpenPOCheckData = (isset($_POST['showOpenPOCheckData'])) ? $_POST['showOpenPOCheckData'] : '';
	
	$_POST = json_decode(str_replace("'",'"',$_POST['filterDataPost']),true);
	$_GET = json_decode(str_replace("'",'"',$_POST['filterDataGet']),true);
	
    function createFilterInput($sqlFilter,$column,$value)
	{
		include('PHP Modules/mysqliConnection.php');
		
		if(!in_array($column, ['status', 'partTypeFlag' ]))
		{
			$return = "<option value=''>".displayText('L490')."</option>";
		}

		if($column == 'process' OR $column == 'group')
		{
			$processCodeArray = $partIdArray = Array ();
			$sql = "SELECT partId FROM cadcam_parts ".$sqlFilter."";
			$queryParts = $db->query($sql);
			if($queryParts AND $queryParts->num_rows > 0)
			{
				while($resultParts = $queryParts->fetch_assoc())
				{
					$partIdArray[] = $resultParts['partId'];
				}
			}

			$sql = "SELECT DISTINCT processCode FROM cadcam_partprocess WHERE partId IN (".implode(", ",$partIdArray).")";
			$queryProcess = $db->query($sql);
			if($queryProcess AND $queryProcess->num_rows >0)
			{
				while($resultProcess = $queryProcess->fetch_assoc())
				{
					$processCodeArray[] = $resultProcess['processCode'];
				}
			}

			if($processCodeArray != NULL)
			{
				$processSectionArray = Array();
				$sql = "SELECT processCode, processName, processSection FROM cadcam_process WHERE processCode IN (".implode(", ",$processCodeArray).") ORDER BY processName";
				$queryName = $db->query($sql);
				if($queryName AND $queryName->num_rows > 0)
				{
					while($resultName = $queryName->fetch_assoc())
					{
						$processCode = $resultName['processCode'];
						$processSectionArray[] = $resultName['processSection'];
						$processName = $resultName['processName'];

						if($column == 'process')
						{
							$selected = ($value == $processCode) ? 'selected' : '';
							$return .= "<option value='".$processName."' ".$selected.">".$processName."</option>";
						}
					}
				}

				if($processSectionArray != NULL AND $column == 'group')
				{
					$processSectionArray = array_unique($processSectionArray);
					$sql = "SELECT sectionId, sectionName FROM ppic_section WHERE sectionId IN (".implode(", ",$processSectionArray).") ORDER BY sectionName";
					$querySection = $db->query($sql);
					if($querySection AND $querySection->num_rows > 0)
					{
						while ($resultSection = $querySection->fetch_assoc()) 
						{
							$sectionId = $resultSection['sectionId'];
							$sectionName = $resultSection['sectionName'];
							
							$selected = ($value == $sectionId) ? 'selected' : '';
							$return .= "<option value='".$sectionId."' ".$selected.">".$sectionName."</option>";
						}
					}
				}
			}
		}
		else
		{
			// $sql = "SELECT DISTINCT ".$column." FROM cadcam_parts ".$sqlFilter." ORDER BY ".$column."";
			$sql = "SELECT DISTINCT ".$column." FROM cadcam_parts ".$sqlFilter."";
			if($column=='customerId')
			{
				$materialTypeIdArray = array();
				$sql = "SELECT DISTINCT customerId FROM cadcam_parts ".$sqlFilter."";
				$query = $db->query($sql);
				if($query->num_rows > 0)
				{
					while($result = $query->fetch_array())
					{
						$customerIdArray[] = $result['customerId'];
					}
				}
				
				if(count($customerIdArray) > 0)
				{
					$sql = "SELECT customerId, customerName FROM sales_customer WHERE customerId IN(".implode(",",$customerIdArray).") ORDER BY customerName";
				}
			}
			else if($column=='materialType' OR $column=='metalThickness')
			{
				$materialSpecIdArray = array();
				$sql = "SELECT DISTINCT materialSpecId FROM cadcam_parts ".$sqlFilter."";
				$query = $db->query($sql);
				if($query->num_rows > 0)
				{
					while($result = $query->fetch_array())
					{
						$materialSpecIdArray[] = $result['materialSpecId'];
					}
				}
				
				if(count($materialSpecIdArray) > 0)
				{
					$sql = "SELECT DISTINCT metalThickness FROM cadcam_materialspecs WHERE materialSpecId IN(".implode(",",$materialSpecIdArray).") ORDER BY metalThickness";
					if($column=='materialType')
					{
						$sql = "SELECT DISTINCT materialType FROM engineering_materialtype WHERE materialTypeId IN(SELECT materialTypeId FROM cadcam_materialspecs WHERE materialSpecId IN(".implode(",",$materialSpecIdArray).")) ORDER BY materialType";
					}
				}
			}
			//~ echo $sql;
			$query = $db->query($sql);
			if($query->num_rows > 0)
			{
				while($result = $query->fetch_array())
				{
					$valueCaption = $result[$column];
					if($column=='status')
					{
						if($value=='')
						{
							$value = '-1';
						}
						if($valueCaption==0)
						{
							$valueCaption = "Active";
							$result[$column] = 0;
						}
						else if($valueCaption==1)
						{
							$valueCaption = "Inactive";
							$result[$column] = 1;
						}
						else if($valueCaption==2)
						{
							$valueCaption = "Pending";
							$result[$column] = 2;
							// continue;
						}
						else if($valueCaption==3)
						{
							$valueCaption = "For Check Rev";
							$result[$column] = 3;
						}
						$selected = (in_array($result[$column],$value)) ? 'selected' : '';
						// $selected = ($value==$result[$column]) ? 'selected' : '';
					}
					else if($column=='partTypeFlag')
					{
						if($result[$column] == 0) $valueCaption = "ASSY";
						if($result[$column] == 1) $valueCaption = "SUBPART";
						if($result[$column] == 2) $valueCaption = "SINGLE";

						$selected = (in_array($result[$column],$value)) ? 'selected' : '';
					}
					else
					{
						$selected = ($value==$result[$column]) ? 'selected' : '';
					}
					
					
					if($column=='customerId')	$valueCaption = $result['customerName'];
					
					$return .= "<option value='".$result[$column]."' ".$selected.">".$valueCaption."</option>";
				}
			}
		}
		return $return;
	}
	
	$customerName = (isset($_POST['customerName'])) ? $_POST['customerName'] : '';
	$customerId = (isset($_POST['customerId'])) ? $_POST['customerId'] : '';
	$partNumber = (isset($_POST['partNumber'])) ? $_POST['partNumber'] : '';
	$partName = (isset($_POST['partName'])) ? $_POST['partName'] : '';
	$partx = (isset($_POST['partx'])) ? $_POST['partx'] : '';
	$party = (isset($_POST['party'])) ? $_POST['party'] : '';
	$partl = (isset($_POST['partl'])) ? $_POST['partl'] : '';
	$partw = (isset($_POST['partw'])) ? $_POST['partw'] : '';
	$parth = (isset($_POST['parth'])) ? $_POST['parth'] : '';
	$statusPart = (isset($_POST['statusPart'])) ? $_POST['statusPart'] : '';
	$materialType = (isset($_POST['materialType'])) ? $_POST['materialType'] : '';
	$metalThickness = (isset($_POST['metalThickness'])) ? $_POST['metalThickness'] : '';
	$sheetWorksFlag = (isset($_POST['sheetWorksFlag'])) ? $_POST['sheetWorksFlag'] : '';
	$firstPODate = (isset($_POST['firstPODate'])) ? $_POST['firstPODate'] : '';
	$lastPODate = (isset($_POST['lastPODate'])) ? $_POST['lastPODate'] : '';
	$processCode = (isset($_POST['process'])) ? $_POST['process'] : '';
	$processGroup = (isset($_POST['processGroup'])) ? $_POST['processGroup'] : '';
	$partTypeFlag = (isset($_POST['partTypeFlag'])) ? $_POST['partTypeFlag'] : '';

?>
<input type='hidden' name='type' value='' form='formFilter'>
	<table cellpadding="0" cellspacing="0" style='width:100%; background-color:#ffb54d!important;' border='0'> 
		<tr style='font-size:12px;'>
			<td style='width:10%' align='center' ><?php echo displayText('L24'); ?></td>
			<td style='width:10%' align='center' ><?php echo displayText('L30'); ?></td>
			<td style='width:10%' align='center' ><?php echo displayText('L28'); ?></td>
		</tr>
		<tr>
			<td align='center'><select name='customerId' class='api-form' style='width:80%;' value='<?php echo $customerId;?>' form='formFilter'><?php echo createFilterInput($sqlFilter,'customerId',$customerId);?></select><input type='image' src='/Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;<?php if($customerId!='') echo 'background-color:red';?>'></td>
			<td align='center'>
				<input list='partName' name='partName' class='api-form' style='width:80%;' value='<?php echo $partName;?>' form='formFilter'>
				<datalist id = 'partName'>
				<?php echo createFilterInput($sqlFilter,'partName',$partName);?>
				</datalist>
				&emsp;&nbsp;
				<input type='image' onclick='this.form.submit()' src='../Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;margin-left:-18px;margin-bottom:4px;<?php if($partName!='') echo 'background-color:red';?>'>
			</td>
			<td align='center'>
				<input list='partNumber' name='partNumber' class='api-form' style='width:80%;' value='<?php echo $partNumber;?>' form='formFilter'>
				<datalist id = 'partNumber'>
				<?php echo createFilterInput($sqlFilter,'partNumber',$partNumber);?>
				</datalist>
				&emsp;&nbsp;
				<input type='image' onclick='this.form.submit()' src='../Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;margin-left:-18px;margin-bottom:4px;<?php if($partNumber!='') echo 'background-color:red';?>'>
			</td>
		</tr>
		<tr style='font-size:12px;'>
			<td style='width:10%' align='center' ><?php echo displayText('L70'); ?></td>
			<td style='width:10%' align='center' ><?php echo displayText('L71'); ?></td>
			<td style='width:10%' align='center' ><?php echo displayText('L111'); ?></td>
		</tr>
		<tr>
			<td align='center'><input list='partx' name='partx' class='api-form' style='width:80%;' value='<?php echo $partx;?>' form='formFilter'>&emsp;&nbsp;<input type='image' onclick='this.form.submit()' src='/Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;margin-left:-18px;margin-bottom:4px;<?php if($partx!='') echo 'background-color:red';?>'></td>
			<td align='center'><input list='party' name='party' class='api-form' style='width:80%;' value='<?php echo $party;?>' form='formFilter'>&emsp;&nbsp;<input type='image' onclick='this.form.submit()' src='/Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;margin-left:-18px;margin-bottom:4px;<?php if($party!='') echo 'background-color:red';?>'></td>
			<td align='center'>
				<select id='partTypeFlag' name='partTypeFlag[]' class='api-form' multiple='multiple' style='width:80%;' value='<?php echo $partTypeFlag;?>' form='formFilter'>
					<?php
					for ($i=0; $i <=2 ; $i++) 
					{ 
						$selected = (in_array($i,$partTypeFlag)) ? 'selected' : '';
						if($i == 0) $valueCaption = "ASSY";
						if($i == 1) $valueCaption = "SUBPART";
						if($i == 2) $valueCaption = "SINGLE";

						echo "<option value=".$i." ".$selected.">".$valueCaption."</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr style='font-size:12px;'>
			<td style='width:10%' align='center' ><?php echo displayText('L74'); ?></td>
			<td style='width:10%' align='center' ><?php echo displayText('L75'); ?></td>
			<td style='width:10%' align='center' ><?php echo displayText('L76'); ?></td>
		</tr>
		<tr>
			<td align='center'><input list='partl' name='partl' class='api-form' style='width:80%;' value='<?php echo $partl;?>' form='formFilter'>&emsp;&nbsp;<input type='image' onclick='this.form.submit()' src='/Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;margin-left:-18px;margin-bottom:4px;<?php if($partl!='') echo 'background-color:red';?>'></td>
			<td align='center'><input list='partw' name='partw' class='api-form' style='width:80%;' value='<?php echo $partw;?>' form='formFilter'>&emsp;&nbsp;<input type='image' onclick='this.form.submit()' src='/Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;margin-left:-18px;margin-bottom:4px;<?php if($partw!='') echo 'background-color:red';?>'></td>
			<td align='center'><input list='parth' name='parth' class='api-form' style='width:80%;' value='<?php echo $parth;?>' form='formFilter'>&emsp;&nbsp;<input type='image' onclick='this.form.submit()' src='/Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;margin-left:-18px;margin-bottom:4px;<?php if($parth!='') echo 'background-color:red';?>'></td>
		</tr>
		<tr style='font-size:12px;'>
			<td style='width:10%' align='center' ><?php echo displayText('L111'); ?></td>
			<td style='width:10%' align='center' ><?php echo displayText('L184'); ?></td>
			<td style='width:10%' align='center' ><?php echo displayText('L172'); ?></td>
		</tr>
		<tr>
			<td align='center'><select name='materialType' class='api-form' style='width:80%;' value='<?php echo $materialType;?>' form='formFilter'><?php echo createFilterInput($sqlFilter,'materialType',$materialType);?></select><input type='image' src='/Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;<?php if($materialType!='') echo 'background-color:red';?>'></td>
			<td align='center'><select name='metalThickness' class='api-form' style='width:80%;' value='<?php echo $metalThickness;?>' form='formFilter'><?php echo createFilterInput($sqlFilter,'metalThickness',$metalThickness);?></select><input type='image' src='/Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;<?php if($metalThickness!='') echo 'background-color:red';?>'></td>
			<td align='center'><select name='statusPart[]' id='statusPart' multiple='multiple' class='api-form' value='<?php echo $statusPart;?>' form='formFilter'><?php echo createFilterInput($sqlFilter,'status',$statusPart);?>
							<!-- <option></option>
							<option value='0,2'><?php echo displayText('L549');?></option>
							<option value='1'><?php echo displayText('L1058');?></option> -->
						</select>
					<!-- <input type='text' name='statusPart' class='api-form' value='<?php echo $statusPart;?>' form='formFilter'> -->
					<input type='image' src='/Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;<?php if($statusPart!='') echo 'background-color:red';?>'></td>
		</tr>
		<tr style='font-size:12px;'>
			<td style='width:10%' align='center' ><?php echo displayText('L4162'); ?></td>
			<td style='width:10%' align='center' ><?php echo displayText('L4163'); ?></td>
			<td style='width:10%' align='center' ><label>SHOW OPEN PO</label></td>
		</tr>
		<tr style='font-size:12px;'>
			<td style='width:10%' align='center' >
				<input list='firstPoDate' name='firstPODate' class='api-form' style='width:80%;' value='<?php echo $firstPODate;?>' form='formFilter'>
				<datalist id = 'firstPoDate'>
				<?php echo createFilterInput($sqlFilter,'firstPODate',$firstPODate);?>
				</datalist>
				&emsp;&nbsp;
				<input type='image' onclick='this.form.submit()' src='../Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;margin-left:-18px;margin-bottom:4px;<?php if($firstPODate!='') echo 'background-color:red';?>'>
			</td>
			<td style='width:10%' align='center' >
			<input list='lastPoDate' name='lastPODate' class='api-form' style='width:80%;' value='<?php echo $lastPODate;?>' form='formFilter'>
				<datalist id = 'lastPoDate'>
				<?php echo createFilterInput($sqlFilter,'lastPODate',$lastPODate);?>
				</datalist>
				&emsp;&nbsp;
				<input type='image' onclick='this.form.submit()' src='../Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;margin-left:-18px;margin-bottom:4px;<?php if($firstPODate!='') echo 'background-color:red';?>'>
			</td>
			<td style='width:10%' align='center' >
				<input form='formFilter' type='checkbox' name='showOpenPO' value='1' <?php echo $showOpenPOCheckData; ?>>
				<input type='hidden' name='lastValue' value='<?php echo $lastValue; ?>' form='formFilter'>
			</td>
		</tr>
		<tr style='font-size:12px;'>
			<td style='width:10%' align='center' ><?php echo displayText('L59'); ?></td>
			<td style='width:10%' align='center' ><?php echo displayText('L61'); ?></td>
		</tr>
		<tr>
			<td align='center'>
				<input list='process' name='process' class='api-form' style='width:80%;' value='<?php echo $processCode;?>' form='formFilter'>
				<datalist id = 'process'>
				<?php echo createFilterInput($sqlFilter,'process',$processCode);?>
				</datalist>
				&emsp;&nbsp;
				<input type='image' onclick='this.form.submit()' src='../Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;margin-left:-18px;margin-bottom:4px;<?php if($partName!='') echo 'background-color:red';?>'>
			</td>
			<td align='center'><select name='processGroup' class='api-form' style='width:80%;' value='<?php echo $processGroup;?>' form='formFilter'><?php echo createFilterInput($sqlFilter,'group',$processGroup);?></select><input type='image' src='/Common Data/Templates/images/submitBtn.png' width=15 title='Filter' form='formFilter' style='border:1px solid blue;<?php if($processGroup!='') echo 'background-color:red';?>'></td>
		</tr>
		<tr>
			<td style='width:10%' align='center' ><label><input type='checkbox' name='sheetWorksFlag' value='1' <?php echo ($sheetWorksFlag==1) ? 'checked' : ''?> form='formFilter'>Sheetworks</label></td>
		</tr>
		<tr>
			<td colspan='2'>
				<center><button type='submit' class='api-btn' onclick="location.href='';" data-api-title='<?php echo displayText('B7', 'utf8', 0, 1);?>' <?php echo toolTip('L437');?> form='formFilter'></button></center>			
			</td>
		</tr>
	</table>
