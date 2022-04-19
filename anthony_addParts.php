<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');

if(isset($_POST['ajaxType']))//;cadcam_materialspecs;
{
	if($_POST['ajaxType']=='getThickness')
	{
		$materialTypeId = $_POST['materialTypeId'];
		
		echo "<option value=''>".displayText('L184')."</option>";
		$sql = "SELECT DISTINCT metalThickness FROM cadcam_materialspecs WHERE materialTypeId = ".$materialTypeId." ORDER BY metalThickness";
		$queryMaterialSpecs = $db->query($sql);
		if($queryMaterialSpecs AND $queryMaterialSpecs->num_rows > 0)
		{
			while($resultMaterialSpecs = $queryMaterialSpecs->fetch_assoc())
			{
				$metalThickness = $resultMaterialSpecs['metalThickness'];
				echo "<option value = '".$metalThickness."' >".$metalThickness."</option>";
			}
		}
	}
	else if($_POST['ajaxType']=='getMaterialSpecId')
	{
		$materialTypeId = $_POST['materialTypeId'];
		$metalThickness = $_POST['metalThickness'];
		
		$materialSpecId = 0;
		$sql = "SELECT materialSpecId FROM cadcam_materialspecs WHERE materialTypeId = ".$materialTypeId." AND metalThickness = ".$metalThickness." LIMIT 1";
		$queryMaterialSpecs = $db->query($sql);
		if($queryMaterialSpecs AND $queryMaterialSpecs->num_rows > 0)
		{
			$resultMaterialSpecs = $queryMaterialSpecs->fetch_assoc();
			$materialSpecId = $resultMaterialSpecs['materialSpecId'];
		}
		echo $materialSpecId;
	}
	exit(0);
}

$data = explode('`', $_GET['data']);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
	<link rel = 'stylesheet' type = 'text/css' href = '../Common Data/anthony.css'>
</head>
<body>
<form action = 'anthony_addPartsSQL.php' method = 'POST'>
<center>
<?php if($_GET['error'] == '1'){ echo "<font color = 'red'>Data Already Exist!</font>"; } ?>
<table style = 'width: 80%;'>
	<tr>
		<td style = 'width: 35%; text-align: right;'><?php echo displayText('L269');//Customer Name ?>:</td>
		<td style = 'width: 45%; padding: 3px;'>
			<select style = 'width: 46%;' name = 'customer'>
			<?php
			$sql = "SELECT customerId, customerName FROM sales_customer WHERE status = 1 ORDER BY customerName ASC ";
			$getCustomer = $db->query($sql);
			while($getCustomerResult = $getCustomer->fetch_array())
			{
				echo "<option value = '".$getCustomerResult['customerId']."'"; if($getCustomerResult['customerId'] == $data[0]){ echo 'selected'; } echo ">".$getCustomerResult['customerName']."</option>";
			}
			?>
			</select>
			<?php if($_GET['error'] == '1'){ echo "<font color = 'red'> !</font>"; } ?>
		</td>
	</tr>

	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L28');//Part Number ?>:</td>
		<td style = 'width: 45%; padding: 3px;'><input type = 'text' name = 'partNumber' value = '<?php echo $data[1]; ?>' required><?php if($_GET['error'] == '1'){ echo "<font color = 'red'> !</font>"; } ?></td>
	</tr>
	
	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L30');//Part Name ?>:</td>
		<td style = 'width: 45%; padding: 3px;'><input type = 'text' name = 'partName' value = '<?php echo $data[2]; ?>'></td>
	</tr>
	
	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L1934');//Revision ?>:</td>
		<td style = 'width: 45%; padding: 3px;'><input type = 'text' name = 'revision' value = '<?php echo $data[3]; ?>' required><?php if($_GET['error'] == '1'){ echo "<font color = 'red'> !</font>"; } ?></td>
	</tr>

	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L3446');//Part Note ?>:</td>
		<td style = 'width: 45%; padding: 3px;'><input type = 'text' name = 'partNote' value = '<?php echo $data[4]; ?>'></td>
	</tr>
	
	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L57');//Material Specs ?>:</td>
		<td style = 'width: 45%; padding: 3px;'>
			<?php
			//~ if($_SESSION['idNumber']!='0346')
			//~ {
				//~ echo "<select style = 'width: 46%; padding: 3px;' name = 'matSpecs'>";
				//~ $sql = "SELECT materialSpecId, metalType, metalThickness FROM cadcam_materialspecs GROUP BY metalType ORDER BY metalType ASC ";
				//~ $getSpecs = $db->query($sql);
				//~ while($getSpecsResult = $getSpecs->fetch_array())
				//~ {
					//~ echo "<option value = '".$getSpecsResult['metalType']."'"; if($getSpecsResult['materialSpecId'] == $data[5]){ echo 'selected'; } echo ">".$getSpecsResult['metalType']."</option>";
				//~ }
				//~ echo "</select>";
				//~ echo "<input type = 'text' name = 'thickness' value = '' style = 'width: 50px;'>";
			//~ }
			//~ else
			//~ {
				//;cadcam_materialspecs;
				echo "<select id='materialType' style = 'width: 46%; padding: 3px;' name = 'matSpecs'>";
				$sql = "SELECT `materialTypeId`, `materialType` FROM `engineering_materialtype` ORDER BY materialType";
				$queryMaterialType = $db->query($sql);
				if($queryMaterialType AND $queryMaterialType->num_rows > 0)
				{
					while($resultMaterialType = $queryMaterialType->fetch_assoc())
					{
						$materialTypeId = $resultMaterialType['materialTypeId'];
						$materialType = $resultMaterialType['materialType'];
						echo "<option value = '".$materialTypeId."' >".$materialType."</option>";
					}
				}
				echo "</select>";
				
				echo "
					<select id='metalThickness' name = 'thickness'>
						<option value=''>".displayText('L184')."</option>
					</select>
				";
				
				echo "<input type='hidden' id='matSpecsId' name = 'matSpecsId' value='0'>";
				echo "<br><span style='cursor:pointer;color:blue;text-decoration:underline;' onclick=\" window.open('../2-E%20Material%20List%20V2/gerald_materialList.php','BBC','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=1000,height=600'); return false; \">".displayText('L1351')."</span>";//L1351 Add New Material Specs
				//;cadcam_materialspecs;
			//~ }
			?>
		</td>
	</tr>
	
	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L1348');//Material Remarks ?>:</td>
		<td style = 'width: 45%; padding: 3px;'>
			<select style = 'width: 46%;' name = 'matRemarks'>
			<?php
			$sql = "SELECT DISTINCT materialSpecDetail FROM cadcam_parts ORDER BY materialSpecDetail ASC ";
			$selectMaterialSpecDetail = $db->query($sql);
			while($selectMaterialSpecDetailResult = $selectMaterialSpecDetail->fetch_array())
			{
				echo "<option value = '".$selectMaterialSpecDetailResult['materialSpecDetail']."'"; if($selectMaterialSpecDetailResult['materialSpecDetail'] == $data[6]){ echo 'selected'; } echo ">".$selectMaterialSpecDetailResult['materialSpecDetail']."</option>";
			}
			?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L67');//Treatment Name ?>:</td>
		<td style = 'width: 45%; padding: 3px;'>
			<select style = 'width: 46%;' name = 'treatmentName'>
				<option value = '0'>Raw</option>
			<?php
			$sql = "SELECT treatmentId, treatmentName FROM cadcam_treatmentprocess ORDER BY treatmentName ASC ";
			$getTreatmentProcess = $db->query($sql);
			while($getTreatmentProcessResult = $getTreatmentProcess->fetch_array())
			{
				echo "<option value = '".$getTreatmentProcessResult['treatmentId']."'"; if($getTreatmentProcessResult['treatmentId'] == $data[7]){ echo 'selected'; } echo ">".$getTreatmentProcessResult['treatmentName']."</option>";
			}
			?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L1349');//With PVC ?>:</td>
		<td style = 'width: 45%; padding: 3px;'><input type = 'checkbox' name = 'pvc' <?php if($data[8] == 1){ echo 'checked'; } ?>></td>
	</tr>
	
	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L70');//Item X ?>:</td>
		<td style = 'width: 45%; padding: 3px;'><input type = 'number' name = 'itemX' step='any' min='0' value = '<?php echo $data[9]; ?>' required></td>
	</tr>
	
	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L71');//Item Y ?>:</td>
		<td style = 'width: 45%; padding: 3px;'><input type = 'number' name = 'itemY' step='any' min='0' value = '<?php echo $data[10] ?>' required></td>
	</tr>
	
	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L72');//Weight ?>:</td>
		<td style = 'width: 45%; padding: 3px;'><input type = 'number' name = 'weight' step='any' min='0' value = '<?php echo $data[11] ?>' required></td>
	</tr>
	
	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L73');//Area ?>:</td>
		<td style = 'width: 45%; padding: 3px;'><input type = 'number' name = 'area' step='any' min='0' value = '<?php echo $data[12] ?>' required></td>
	</tr>
	
	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L74');//Length ?>:</td>
		<td style = 'width: 45%; padding: 3px;'><input type = 'number' name = 'length' step='any' min='0' value = '<?php echo $data[13] ?>' required></td>
	</tr>
	
	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L75');//Width ?>:</td>
		<td style = 'width: 45%; padding: 3px;'><input type = 'number' name = 'width' step='any' min='0' value = '<?php echo $data[14] ?>' required></td>
	</tr>
	
	<tr>
		<td style = 'width: 30%; text-align: right;'><?php echo displayText('L76');//Height ?>:</td>
		<td style = 'width: 45%; padding: 3px;'><input type = 'number' name = 'height' step='any' min='0' value = '<?php echo $data[15] ?>' required></td>
	</tr>
	
	<tr>
		<td style = 'width: 45%; padding: 3px;' colspan = 2><center><input type = 'submit' value = '<?php echo displayText('L1052', 'utf8', 0, 1);//Save ?>' class = 'anthony_submit'></center></td>
	</tr>
</table>
</center>
</form>
</body>
</html>
