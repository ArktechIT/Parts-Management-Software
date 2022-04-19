<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');
?>
<style>
div 
{
	line-height: 15px;
}

label
{
	float: left;
	width: 6em;
	margin-right: 1em;
	text-align: right;
}
</style>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
</head>
<body>
<form action = "anthony_addSubpartsFormSQL.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>" method = "POST">
	<div>
		<label for = "partNumber"><?php echo displayText('L28');//Part Number ?>:</label><select name = "partNumber">
												<?php
												$sql = "SELECT subpartId, childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 1 ";
												$getId = $db->query($sql);
												if($getId->num_rows > 0)
												{
													$sql = "SELECT customerId FROM cadcam_parts WHERE partId = ".$_GET['partId']." ";
													$getCustomerId = $db->query($sql);
													$getCustomerIdResult = $getCustomerId->fetch_array();
													if($getCustomerIdResult['customerId']==37)
													{
														$sql = "SELECT partId, partNumber, revisionId, partNote FROM cadcam_parts WHERE partId != ".$_GET['partId']." AND partId NOT IN (SELECT childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 1) AND (customerId = 37 or customerId = 28) AND status IN (0,2,3) ORDER BY partNumber ASC ";
													}
													else if($getCustomerIdResult['customerId']==11)
													{
														$sql = "SELECT partId, partNumber, revisionId, partNote FROM cadcam_parts WHERE partId != ".$_GET['partId']." AND partId NOT IN (SELECT childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 1) AND (customerId IN (11,10,85)) AND status IN (0,2,3) ORDER BY partNumber ASC ";
													}
													else if($getCustomerIdResult['customerId']==10)
													{
														$sql = "SELECT partId, partNumber, revisionId, partNote FROM cadcam_parts WHERE partId != ".$_GET['partId']." AND partId NOT IN (SELECT childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 1) AND (customerId IN (10,9,11)) AND status IN (0,2,3) ORDER BY partNumber ASC ";
													}
													else
													{													
														$sql = "SELECT partId, partNumber, revisionId, partNote FROM cadcam_parts WHERE partId != ".$_GET['partId']." AND partId NOT IN (SELECT childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 1) AND customerId = ".$getCustomerIdResult['customerId']." AND status IN (0,2,3) ORDER BY partNumber ASC ";
													}
													$getParts = $db->query($sql);
													while($getPartsResult = $getParts->fetch_array())
													{
														if($getPartsResult['revisionId'] != '')
														{
															echo "<option value = '".$getPartsResult['partId']."'>".$getPartsResult['partNumber']." [ ".$getPartsResult['revisionId']." ]"." ".$getPartsResult['partNote']."</option>";
														}
														else
														{
															echo "<option value = '".$getPartsResult['partId']."'>".$getPartsResult['partNumber']." ".$getPartsResult['partNote']."</option>";
														}
													}
												}
												else
												{
													$sql = "SELECT customerId FROM cadcam_parts WHERE partId = ".$_GET['partId']." ";
													$getCustomerId = $db->query($sql);
													$getCustomerIdResult = $getCustomerId->fetch_array();
													
													if($getCustomerIdResult['customerId']==37)
													{
														$sql = "SELECT partId, partNumber, revisionId, partNote FROM cadcam_parts WHERE partId != ".$_GET['partId']." AND (customerId = 37 or customerId = 28) AND status IN (0,2,3) ORDER BY partNumber ASC ";
													}
													else if($getCustomerIdResult['customerId']==11)
													{
														$sql = "SELECT partId, partNumber, revisionId, partNote FROM cadcam_parts WHERE partId != ".$_GET['partId']." AND partId NOT IN (SELECT childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 1) AND (customerId IN (11,10,85)) AND status IN (0,2,3) ORDER BY partNumber ASC ";
													}
													else if($getCustomerIdResult['customerId']==10)
													{
														$sql = "SELECT partId, partNumber, revisionId, partNote FROM cadcam_parts WHERE partId != ".$_GET['partId']." AND partId NOT IN (SELECT childId FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." AND identifier = 1) AND (customerId IN (10,9,11)) AND status IN (0,2,3) ORDER BY partNumber ASC ";
													}
													else
													{
														$sql = "SELECT partId, partNumber, revisionId, partNote FROM cadcam_parts WHERE partId != ".$_GET['partId']." AND customerId = ".$getCustomerIdResult['customerId']." AND status IN (0,2,3) ORDER BY partNumber ASC ";
													}
													$getParts = $db->query($sql);
													while($getPartsResult = $getParts->fetch_array())
													{
														if($getPartsResult['revisionId'] != '')
														{
															echo "<option value = '".$getPartsResult['partId']."'>".$getPartsResult['partNumber']." [ ".$getPartsResult['revisionId']." ]"." ".$getPartsResult['partNote']."</option>";
														}
														else
														{
															echo "<option value = '".$getPartsResult['partId']."'>".$getPartsResult['partNumber']." ".$getPartsResult['partNote']."</option>";
														}
													}
												}
												?>
												   </select>
	</div><br>	
	
	<div>
		<label for = "quantity"><?php echo displayText('L31');//Quantity ?>:</label><input type = "number" name = "quantity" min = "1">
	</div><br>
	<?php
		//~ if($_SESSION['idNumber']=='0346')
		//~ {
			?>
			<div>
				<label for = "process"><?php echo displayText('L59');//Process ?>:</label>
				<select name = 'processCode' id = 'process' required>
					<option value=''>Select Process</option>
					<?php
						$sql = "SELECT processCode FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder";
						$queryPartProcess = $db->query($sql);
						if($queryPartProcess AND $queryPartProcess->num_rows > 0)
						{
							while($resultPartProcess = $queryPartProcess->fetch_assoc())
							{
								$processCode = $resultPartProcess['processCode'];
								
								$processName = '';
								$sql = "SELECT processName FROM cadcam_process WHERE processCode = ".$processCode." LIMIT 1";
								$queryProcess = $db->query($sql);
								if($queryProcess AND $queryProcess->num_rows > 0)
								{
									$resultProcess = $queryProcess->fetch_assoc();
									$processName = $resultProcess['processName'];
								}
								
								echo "<option value='".$processCode."'>".$processName."</option>";
							}
						}
					?>
				</select>
			</div><br>	
			<?php
		//~ }
	?>
	
	<center>
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "submit" value = "<?php echo displayText('B4');//Add ?>" class="art-button">
			</div>
			</span>
			</div>
	</center>		
</form>										  
</body>
</html>
