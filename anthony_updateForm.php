<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');

$sql = "SELECT partName, customerId, materialSpecId, materialSpecDetail, PVC, x, y, itemWeight, itemArea, itemLength, itemWidth, itemHeight, treatmentId, bendRobot, weldRobot FROM cadcam_parts WHERE partId = ".$_GET['partId']." ";
$getParts = $db->query($sql);
$getPartsResult = $getParts->fetch_array();

$sql = "SELECT materialTypeId, metalType, metalThickness, metalLength, metalWidth FROM cadcam_materialspecs WHERE materialSpecId = ".$getPartsResult['materialSpecId']." ";
$getCurrentSpecs = $db->query($sql);
$getCurrentSpecsResult = $getCurrentSpecs->fetch_array();

$sql = "SELECT metalType, metalThickness FROM cadcam_materialspecs WHERE materialSpecId NOT IN (SELECT materialSpecId FROM cadcam_parts WHERE partId = ".$_GET['partId'].") GROUP BY metalType ORDER BY metalType ASC ";
$getSpecs = $db->query($sql);

$sql = "SELECT DISTINCT materialSpecDetail FROM cadcam_parts WHERE materialSpecDetail NOT IN (SELECT materialSpecDetail FROM cadcam_parts WHERE partId = ".$_GET['partId'].") ORDER BY materialSpecDetail ASC ";
$selectMaterialSpecDetail = $db->query($sql);

// ---------- this code will be deleted ---------- //
//~ $sql = "SELECT totalSurfaceClear, totalSurfacePrime, totalSurfacePassivation FROM cadcam_subcondimension WHERE partId = ".$_GET['partId']." ";
//~ $getSubcon = $db->query($sql);
//~ $getSubconResult = $getSubcon->fetch_array();
// -------- END this code will be deleted -------- //

$sql = "SELECT treatmentId, treatmentName FROM cadcam_treatmentprocess WHERE treatmentId = ".$getPartsResult['treatmentId']." ";
$getCurrentTreatment = $db->query($sql);
$getCurrentTreatmentResult = $getCurrentTreatment->fetch_array();

$sql = "SELECT treatmentId, treatmentName FROM cadcam_treatmentprocess WHERE treatmentId NOT IN (SELECT treatmentId FROM cadcam_parts WHERE treatmentId = ".$getPartsResult['treatmentId'].") ORDER BY treatmentName ASC ";
$getTreatmentProcess = $db->query($sql);
?>
<style>
div {
	line-height: 30px;
}

label
{
    display: inline-block;
    width: 125px;
}
input[type='text']
{
	background: #FFFF99;
	border-radius: 3px;
}
select
{
	background: #FFFF99;
	width: 57%;
	border-radius: 3px;
}
</style>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
</head>
<body>
<form action = "anthony_updateSQL.php?partId=<?php echo $_GET['partId']; if(isset($_GET['source']) AND $_GET['source'] == 'pending'){ echo '&source=pending'; }?>&patternId=<?php echo $_GET['patternId']; ?>" method = "POST">
		<div>
			<label for = "partName"><?php echo displayText('L30');//Part Name?>:</label><input type = "text" name = "partName" value = "<?php echo $getPartsResult['partName']; ?>">
		</div>
		
		<div>
			<?php
				//~ if($_SESSION['idNumber']=='0346')
				//~ {
					//;cadcam_materialspecs;
					echo "<label for = 'matSpecs'>".displayText('L57').":</label><select id='materialType' style = 'width: 46%; padding: 3px;' name = 'matSpecs'>";
					$sql = "SELECT `materialTypeId`, `materialType` FROM `engineering_materialtype` ORDER BY materialType";
					$queryMaterialType = $db->query($sql);
					if($queryMaterialType AND $queryMaterialType->num_rows > 0)
					{
						while($resultMaterialType = $queryMaterialType->fetch_assoc())
						{
							$materialTypeId = $resultMaterialType['materialTypeId'];
							$materialType = $resultMaterialType['materialType'];
							
							$selected = ($getCurrentSpecsResult['materialTypeId']==$materialTypeId) ? 'selected' : '';
							
							echo "<option value = '".$materialTypeId."' ".$selected.">".$materialType."</option>";
						}
					}
					echo "</select>";
					
					echo "
						<select id='metalThickness' name = 'inputThickness' style='width:50px;' required>
							<option value=''>".displayText('L184')."</option>
					";
					$sql = "SELECT DISTINCT metalThickness FROM cadcam_materialspecs WHERE materialTypeId = ".$getCurrentSpecsResult['materialTypeId']." ORDER BY metalThickness";
					$queryMaterialSpecs = $db->query($sql);
					if($queryMaterialSpecs AND $queryMaterialSpecs->num_rows > 0)
					{
						while($resultMaterialSpecs = $queryMaterialSpecs->fetch_assoc())
						{
							$metalThickness = $resultMaterialSpecs['metalThickness'];
							
							$selected = ($getCurrentSpecsResult['metalThickness']==$metalThickness) ? 'selected' : '';
							echo "<option value = '".$metalThickness."' ".$selected.">".$metalThickness."</option>";
						}
					}
					echo "</select>";
					
					echo "<input type='hidden' id='matSpecsId' name = 'matSpecsId' value='".$getPartsResult['materialSpecId']."'>";
					echo "<br><span style='cursor:pointer;color:blue;text-decoration:underline;' onclick=\" window.open('../2-E%20Material%20List%20V2/gerald_materialList.php','BBC','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=1000,height=600'); return false; \">".displayText('L1351')."</span>";//L1351 Add New Material Specs
					//;cadcam_materialspecs;
				/*}
				else
				{
					?>
					<label for = "matSpecs">材料仕様書Specs:</label><select name = "matSpecs" style = 'width: 100px;'>
														  <?php
															echo "<option value = '".$getCurrentSpecsResult['metalType']."'>".$getCurrentSpecsResult['metalType']."</option>";
														  while($getSpecsResult = $getSpecs->fetch_array()){
															echo "<option value = '".$getSpecsResult['metalType']."'>".$getSpecsResult['metalType']."</option>";
														  }
														  ?>
														  </select>
					t <input type = 'text' name = 'metalThickness' style = 'width: 50px;' value = '<?php echo $getCurrentSpecsResult['metalThickness']; ?>'>
					<?php
				}*/
			?>
		</div>
		
		<input type = "hidden" name = "type" value = "<?php echo $getCurrentSpecsResult['metalType']; ?>">
		<input type = "hidden" name = "thickness" value = "<?php echo $getCurrentSpecsResult['metalThickness']; ?>">
		<input type = "hidden" name = "length" value = "<?php echo $getCurrentSpecsResult['metalLength']; ?>">
		<input type = "hidden" name = "width" value = "<?php echo $getCurrentSpecsResult['metalWidth']; ?>">
		
		<div>
			<label for = "matDetail"><?php echo displayText('L1348');//Material Remarks ?>:</label>
																<?php
																/*
																	<select name = "matDetail">
																	echo "<option value = '".$getPartsResult['materialSpecDetail']."'>".$getPartsResult['materialSpecDetail']."</option>";
																	while($selectMaterialSpecDetailResult = $selectMaterialSpecDetail->fetch_array())
																	{
																		echo "<option value = '".$selectMaterialSpecDetailResult['materialSpecDetail']."'>".$selectMaterialSpecDetailResult['materialSpecDetail']."</option>";
																	}
																	</select>
																*/								
																echo "<input type = 'text' list = 'matDetail' name = 'matDetail' value = '".$getPartsResult['materialSpecDetail']."'>";
																echo "<datalist id = 'matDetail'>";
																while($selectMaterialSpecDetailResult = $selectMaterialSpecDetail->fetch_array())
																{
																	echo "<option value = '".$selectMaterialSpecDetailResult['materialSpecDetail']."'>";
																}
																echo "</datalist>";
																?>
														     
		</div>
		
		<div>
			<label for = "treatmentName"><?php echo displayText('L67');//Treatment Name ?></label><select name = "treatmentName">
																<?php
																if($getPartsResult['treatmentId'] == 0)
																{
																	echo "<option value = '0'>Raw</option>";
																}
																else
																{
																	echo "<option value = '".$getCurrentTreatmentResult['treatmentId']."'>".$getCurrentTreatmentResult['treatmentName']."</option>";
																}
																while($getTreatmentProcessResult = $getTreatmentProcess->fetch_array())
																{
																	echo "<option value = '".$getTreatmentProcessResult['treatmentId']."'>".$getTreatmentProcessResult['treatmentName']."</option>";
																}
																?>
														     </select>
		</div>
		
		<div>
			<label for = "pvc"><?php echo displayText('L1349');//With PVC ?>:</label><input type = "checkbox" name = "pvc" <?php if($getPartsResult['PVC']==1){ echo 'checked'; } ?> >
		</div>
		
		<!--
		<div>
			<label for = "matX"><font size=2>Material X:</font></label><input type = "text" name = "matX" value = "<?php echo $getCurrentSpecsResult['metalLength']; ?>">
		</div>
		
		<div>
			<label for = "matY"><font size=2>Material Y:</font></label><input type = "text" name = "matY" value = "<?php echo $getCurrentSpecsResult['metalWidth']; ?>">
		</div>
		-->
		
		<div>
			<label for = "itemX"><?php echo displayText('L70');//Item X ?></label><input type = "text" name = "itemX" value = "<?php echo $getPartsResult['x']; ?>">mm
		</div>
		
		<div>
			<label for = "itemY"><?php echo displayText('L71');//Item Y ?></label><input type = "text" name = "itemY" value = "<?php echo $getPartsResult['y']; ?>">mm
		</div>
		
		<div>
			<label for = "itemY"><?php echo displayText('L72');//Weight ?>:</label><input type = "number" step = "any" name = "weight" value = "<?php echo $getPartsResult['itemWeight']; ?>">g
		</div>
		
		<div>
			<label for = "itemY"><?php echo displayText('L73');//Area ?>:</label><input type = "number" step = "any" name = "area" value = "<?php echo $getPartsResult['itemArea']; ?>">dm2
		</div>
		
		<div>
			<label for = "itemY"><?php echo displayText('L74');//Length ?>:</label><input type = "number" step = "any" name = "length" value = "<?php echo $getPartsResult['itemLength']; ?>">mm
		</div>
		
		<div>
			<label for = "itemY"><?php echo displayText('L75');//Width ?>:</label><input type = "number" step = "any" name = "width" value = "<?php echo $getPartsResult['itemWidth']; ?>">mm
		</div>
		
		<div>
			<label for = "itemY"><?php echo displayText('L76');//Height ?>:</label><input type = "number" step = "any" name = "height" value = "<?php echo $getPartsResult['itemHeight']; ?>">mm
		</div>
		<div>
		<label for = "itemY"><?php echo displayText('L4558'); ?>:</label>
		<?php
		$bendRobot="";
		$weldRobot="";
		if($getPartsResult['bendRobot']==1){$bendRobot="checked";}
		if($getPartsResult['weldRobot']==1){$weldRobot="checked";}
		?>
			<font color=green><?php echo displayText('L4556').":"; ?></font>
			<input type="checkbox" name="BendRobot" value="1" <?php echo $bendRobot; ?>>
			&emsp;&emsp;
			<font color=green><?php echo displayText('L4557').":"; ?></font>
			<input type="checkbox" name="WeldRobot" value="1" <?php echo $weldRobot; ?>>
		</div>
<center>
		<span class="art-button-wrapper">
		<span class="art-button-l"> </span>
		<span class="art-button-r"> </span>
		<div id="submitButton">
			<input type ="submit" name = "update" value = "<?php echo displayText('L1054');//Update ?>" class="art-button">
		</div>
		</span>
</center>
</form>
</body>
</html>
