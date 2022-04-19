<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	// $path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	$javascriptLib = "/".v."/Common Data/Libraries/Javascript/";
	$templates = "/".v."/Common Data/Templates/";
	set_include_path($path);	
	include('PHP Modules/mysqliConnection.php');
	include('PHP Modules/anthony_retrieveText.php');
	ini_set("display_errors", "on");
	$queryLimit = 50;
	
	$inputPartId = (isset($_GET['product'])) ? $_GET['product'] : "";
	
	$dates = (isset($_POST['date'])) ? $_POST['date'] : ""; //Date
	$actions = (isset($_POST['action'])) ? $_POST['action'] : "";
	$fields = (isset($_POST['field'])) ? $_POST['field'] : "";
	$userRemarkss = (isset($_POST['userRemarks'])) ? $_POST['userRemarks'] : "";
	$users = (isset($_POST['user'])) ? $_POST['user'] : "";
	
	$filterQuery = $disabled = "";
	$filterQueryArray = array();
	//~ if($userRemarkss == '') $disabled = "disabled";
	if($dates != '') $filterQueryArray[] = "date LIKE '".$dates."%'";
	if($actions != '') $filterQueryArray[] = "query = ".$actions."";
	if($fields != '') $filterQueryArray[] = "field = ".$fields."";
	if($userRemarkss != '') $filterQueryArray[] = "userRemarks LIKE '".$userRemarkss."'";
	if($users != '') $filterQueryArray[] = "user LIKE '".$users."'";
	
	if(COUNT($filterQueryArray) > 0)
	{
		$filterQuery = " AND ".implode(" AND ", $filterQueryArray);
	}
?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
		<title>Parts Change Log</title>
		
		<link href="../Common Data/Templates/tableDesign.css" rel="stylesheet" media="screen" />
		<link id='link' rel="stylesheet" href="../Common Data/anthony.css" type="text/css" media="screen" />
		
		<style>
		input.textboxFilter, select
		{
			width: 13.5em;
			height: 2.5;
		}
		
		#filterHeader tr td
		{
			border: none;
			vertical-align: middle;
			text-align: right;
		}
		
		.fancyTable tr td, .fancyTable tr th
		{
			vertical-align: middle;
		}
		</style>

		<script src="../Common Data/Libraries/Javascript/Table with Fixed Header/jquery.min.js"></script>
		<script type="text/javascript" src="../Common Data/Libraries/Javascript/Quick Table/jquery-1.9.0.min.js"></script>				
	</head>

	<?php
		$sql = "SELECT partlogId, partId, date, query, field, oldValue, newValue, details, ip, user, userRemarks FROM system_partlog WHERE partId = ".$inputPartId." ".$filterQuery."";
		$query = $db->query($sql);
		$totalRecords = $query->num_rows;
		$total_groups = ceil($totalRecords / $queryLimit);
	?>
	
	<script type="text/javascript">
	$(document).ready(function(){
		var track_load = 0; //total loaded record group(s)
		var loading  = false; //to prevents multipal ajax loads
		var total_groups = "<?php echo $total_groups; ?>"; //total record group(s)
		var filterQuery = "<?php echo $filterQuery; ?>";
		var inputPartId = "<?php echo $inputPartId; ?>";
		// ---------------------------------------------------- Load First Group -----------------------------------------------------
		$('#results').load("paul_partlogAjax.php", {'group_no':track_load, 'filterQuery':filterQuery, 'inputPartId':inputPartId}, function() {track_load++;}); 
		// ---------------------------------------------------- Detect Page Scroll ---------------------------------------------------
		$('.fht-tbody').scroll(function() 
		{ 
			//alert($('.fht-tbody').scrollTop()+" : "+$('.fht-tbody').height()+" : "+$(document).height())
			if($(window).scrollTop() + $(window).height() == $(document).height())  //user scrolled to bottom of the page?
			{
				if(track_load <= total_groups && loading==false) //there's more data to load
				{
					loading = true; //prevent further ajax loading
					$('.animation_image').show(); //show loading image
					
					//load data from the server using a HTTP POST request
					$.post('paul_partlogAjax.php',{'group_no':track_load, 'filterQuery':filterQuery, 'inputPartId':inputPartId}, function(data)
					{
						$("#results").append(data); //append received data into the element

						//hide loading image
						$('.animation_image').hide(); //hide loading image once data is received
						
						track_load++; //loaded group increment
						loading = false; 
					
					}).fail(function(xhr, ajaxOptions, thrownError){ //any errors?
						
						alert(thrownError); //alert with HTTP error
						$('.animation_image').hide(); //hide loading image
						loading = false;
					});
				}
			}
		});
	});
	</script>

	<style>
	body,td,th {font-family: Georgia, Times New Roman, Times, serif;font-size: 15px;}
	.animation_image {background: #F9FFFF;border: 1px solid #E1FFFF;padding: 10px;width: 500px;margin-right: auto;margin-left: auto;}
	#results{width: 500px;margin-right: auto;margin-left: auto;}
	#resultst ol{margin: 0px;padding: 0px;}
	#results li{margin-top: 20px;border-top: 1px dotted #E1FFFF;padding-top: 20px;}
	</style>

	<?php include('../Common Data/Templates/bodytop.php') ?>
	<h2><center>Parts Change Log</center></h2>
	
	<table border = 1>
		<tr>
			<?php
				$selectedCustomer = "";
				$sql = "SELECT DISTINCT partId FROM system_partlog WHERE partId = ".$inputPartId." ".$filterQuery." LIMIT 1";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					$result = $query->fetch_assoc();
					$partId = $result['partId'];
					
					$sql = "SELECT customerId, partNumber, revisionId FROM cadcam_parts WHERE partId = ".$partId." LIMIT 1";
					$queryCustomerId = $db->query($sql);
					if($queryCustomerId AND $queryCustomerId->num_rows > 0)
					{
						$resultCustomerId = $queryCustomerId->fetch_assoc();
						$customerId = $resultCustomerId['customerId'];
						$partNumber = $resultCustomerId['partNumber'];
						$revisionId = $resultCustomerId['revisionId'];
						
						$sql = "SELECT customerAlias FROM sales_customer WHERE customerId = ".$customerId." LIMIT 1";
						$queryAlias = $db->query($sql);
						if($queryAlias AND $queryAlias->num_rows > 0)
						{
							$resultAlias = $queryAlias->fetch_assoc();
							$customerAlias = $resultAlias['customerAlias'];
						}
					}
					
				}
				?>
			<td>Customer: <b><?php echo $customerAlias; ?></b></td>
			<td width = 10></td>
			<td>Part: <b><?php echo $partNumber; ?></b></td>
			<td width = 10></td>
			<td>Rev: <b><?php echo $revisionId; ?></b></td>
		</tr>
	</table>
	
	<p></p>
	
	<form id = "formFilter" method = "POST" action = "<?php echo $_SERVER['PHP_SELF']."?product=".$inputPartId.""; ?>"></form>
	<input type = "hidden" name = "filter" value = "filter" form = "formFilter">
	<table border = 1>
		<thead>
			<tr align = "center" bgcolor = "Cornflowerblue">
				<td><?php echo displayText('L292');?></td>
				<td><?php echo displayText('L1120');?></td>
				<td>Fields</td>
				<td>User Remarks</td>
				<td><?php echo displayText('L577');?></td>
			</tr>
		</thead>
		
		<tbody>
				<td>
					<select name = "date" onchange = "this.form.submit();" form = "formFilter">
						<option></option>
						<?php
						$selectedDate = "";
						$dateArray = array();
						$sql = "SELECT DISTINCT date FROM system_partlog WHERE partId = ".$inputPartId." ".$filterQuery."";
						$query = $db->query($sql);
						if($query AND $query->num_rows > 0)
						{
							while($result = $query->fetch_assoc())
							{
								$dateTime = $result['date'];
								$dateTimeExploded = explode(" ", $dateTime);
								$dateArray[] = $dateTimeExploded[0]; //Date
							}
							
							$valArray = array_unique($dateArray);
							$val = array_values($valArray);
							for($i=0; $i < COUNT($val); $i++)
							{
								$value = $val[$i];
								$selectedDate = ($dates == $value) ? "selected" : "";
								 
								echo "<option value = '".$value."' ".$selectedDate.">".$value."</option>";
							}
							//~ while($result = $query->fetch_assoc())
							//~ {
								//~ $dateTime = $result['date'];
								//~ $dateTimeExploded = explode(" ", $dateTime);
								//~ $date = $dateTimeExploded[0]; //Date
								//~ $time = $dateTimeExploded[1]; //Time
								//~ 
								//~ $selectedDate = ($dates == $date) ? "selected" : "";
								//~ echo "<option value = '".$date."' ".$selectedDate.">".$date."</option>";
							//~ }
						}	
						?>
					</select>					
				</td>
				
				<td>
					<select name = "action" onchange = "this.form.submit();" form = "formFilter">
						<option></option>
						<?php
							$selectedAction = $queryName = "";
							$sql = "SELECT DISTINCT query FROM system_partlog WHERE partId = ".$inputPartId." ".$filterQuery."";
							$query = $db->query($sql);
							if($query AND $query->num_rows > 0)
							{
								while($result = $query->fetch_assoc())
								{
									$queryId = $result['query'];
									if($queryId == 1) $queryName = "Add";
									else if($queryId == 2) $queryName = "Edit";
									else if($queryId == 3) $queryName = "Delete";
									else $queryName = "Copy";
									
									$selectedAction = ($actions == $queryId) ? "selected" : "";
									echo "<option value = ".$queryId." ".$selectedAction.">".$queryName."</option>";
								}
							}
						?>
					</select>
				</td>
				
				<td>
					<select name = "field" onchange = "this.form.submit();" form = "formFilter">
							<option></option>
							<?php
								$fieldDesc = "";
								$sql = "SELECT DISTINCT field FROM system_partlog WHERE partId = ".$inputPartId." ".$filterQuery."";
								$query = $db->query($sql);
								if($query AND $query->num_rows > 0)
								{
									while($result = $query->fetch_assoc())
									{
										$field = $result['field'];
										if($field == 1) 		$fieldDesc = 'PartNumber';
										else if($field == 2) 	$fieldDesc = 'PartName';
										else if($field == 3) 	$fieldDesc = 'revision';
										else if($field == 4)	$fieldDesc = 'customerId';
										else if($field == 5)	$fieldDesc = 'Qty/Sheet';
										else if($field == 6) 	$fieldDesc = 'Material Specs';
										else if($field == 7) 	$fieldDesc = 'Material Details';
										else if($field == 8) 	$fieldDesc = 'Drawing';
										else if($field == 9) 	$fieldDesc = 'Work Instruction';
										else if($field == 10)   $fieldDesc = 'Status';
										else if($field == 11)   $fieldDesc = 'Subpart';
										else if($field == 12)   $fieldDesc = 'Subpart Acc.';
										else if($field == 13)   $fieldDesc = 'Subpart Qty';
										else if($field == 14)   $fieldDesc = 'Subpart ID';
										else if($field == 15)   $fieldDesc = 'Process Order';
										else if($field == 16)   $fieldDesc = 'Process';
										else if($field == 17)   $fieldDesc = 'Process Details';
										else if($field == 18)   $fieldDesc = 'Subcon';
										else if($field == 19)   $fieldDesc = 'Subcon Process';
										else if($field == 20)   $fieldDesc = 'Subcon PartID';
										else if($field == 21)   $fieldDesc = 'Material Specs';
										else if($field == 22)   $fieldDesc = 'AccessoryID';
										else if($field == 23)   $fieldDesc = 'AccessoryNumber';
										else if($field == 24)   $fieldDesc = 'AccessoryName';
										else if($field == 25)   $fieldDesc = 'price-delivery';
										else if($field == 26)   $fieldDesc = 'itemXY';
										else if($field == 27)   $fieldDesc = 'TreatmentId';
										else if($field == 28)   $fieldDesc = 'Clear';
										else if($field == 29)   $fieldDesc = 'Prime';
										else if($field == 30)   $fieldDesc = 'Passivation';
										else if($field == 31)   $fieldDesc = 'Process Tool Id';
										else if($field == 32)   $fieldDesc = 'Process Section';
										else if($field == 33)   $fieldDesc = 'Tool Name';
										else if($field == 34)   $fieldDesc = 'Alternate Material';
										
										$selectedField = "";
										$selectedField = ($fields == $field) ? "selected" : "";
										echo "<option value = ".$field." ".$selectedField.">".$fieldDesc."</option>";
									}
								}
							?>	
					</select>
				</td>
				
				<td>
					<select name = "userRemarks" onchange = "this.form.submit();" form = "formFilter" <?php echo $disabled; ?>>
						<option></option>
						<?php
							$selectedRemarks = "";
							$sql = "SELECT DISTINCT userRemarks FROM system_partlog WHERE partId = ".$inputPartId." ".$filterQuery."";
							$query = $db->query($sql);
							if($query AND $query->num_rows > 0)
							{
								while($result = $query->fetch_assoc())
								{
									$userRemarks = $result['userRemarks'];
									
									$selectedRemarks = ($userRemarkss == $userRemarks) ? "selected" : "";
									echo "<option value = '".$userRemarks."' ".$selectedRemarks.">".$userRemarks."</option>";
								}
							}
						?>
					</select>
				</td>

				<td>
					<select name = "user" onchange = "this.form.submit();" form = "formFilter">
						<option></option>
						<?php
							$selectedUser = "";
							$sql = "SELECT DISTINCT user FROM system_partlog WHERE partId = ".$inputPartId." ".$filterQuery."";
							$query = $db->query($sql);
							if($query AND $query->num_rows > 0)
							{
								while($result = $query->fetch_assoc())
								{
									$user = $result['user'];
									
									$selectedUser = ($users == $user) ? "selected" : "";
									echo "<option value = '".$user."' ".$selectedUser.">".$user."</option>";
								}
							}
						?>						
					</select>
				</td>
			</tr>
		</tbody>
	</table>

	<span>Records: <?php echo $totalRecords; ?></span>
	
	<div class="grid_8 height400">
		<table border = 1 class="fancyTable" id="myTable02" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td width = 50><?php echo displayText('L292');?></td>
					<td width = 60><?php echo displayText('L1120');?></td>
					<td width = 100>Fields</td>
					<td width = 100><?php echo displayText('L3250');?></td>
					<td width = 100>New Value</td>
					<td width = 100>User Remarks</td>
					<td width = 100><?php echo displayText('L577');?></td>
				</tr>
			</thead>
			
			<tbody id = "results">
			</tbody>
			
			<!-- <tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot> -->
		</table>
	</div>
	
	<div class="clear"></div>
	
	<script type="text/javascript" src="../Common Data/Libraries/Javascript/Tiny Box/tinybox.js"></script>
	<link rel="stylesheet" href="../Common Data/Libraries/Javascript/Tiny Box/stylebox.css"/>
	<script src="../Common Data/Libraries/Javascript/Table with Fixed Header/jquery.fixedheadertable.js"></script>
	
	<script>
		$('#myTable02').fixedHeaderTable({
			footer: true,
			altClass: 'odd',		
		});	
	</script>
