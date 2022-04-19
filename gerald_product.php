<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	// $path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	set_include_path($path);
	include('PHP Modules/mysqliConnection.php');
	include('PHP Modules/anthony_retrieveText.php');
	ini_set("display_errors","on");

	function createFilterInput($sqlFilter,$column,$value)
	{
		include('PHP Modules/mysqliConnection.php');
		
		if($column!='status')
		{
			$return = "<option value=''>".displayText('L490')."</option>";
		}
		
		$sql = "SELECT DISTINCT ".$column." FROM cadcam_parts ".$sqlFilter." ORDER BY ".$column."";
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
					echo $sql = "SELECT DISTINCT materialType FROM engineering_materialtype WHERE materialTypeId IN(SELECT materialTypeId FROM cadcam_materialspecs WHERE materialSpecId IN(".implode(",",$materialSpecIdArray).")) ORDER BY materialType";
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
				else
				{
					$selected = ($value==$result[$column]) ? 'selected' : '';
				}
				
				
				if($column=='customerId')	$valueCaption = $result['customerName'];
				
				$return .= "<option value='".$result[$column]."' ".$selected.">".$valueCaption."</option>";
			}
		}
		return $return;
	}
	
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
	
	$sqlFilter = "";
	$sqlFilterArray = $sqlFilterMaterialSpecsArray = array();
	
	if($customerId!='')		$sqlFilterArray[] = "customerId = ".$customerId." ";
	if($partNumber!='')		$sqlFilterArray[] = "partNumber LIKE '%".$partNumber."%' ";
	if($partName!='')		$sqlFilterArray[] = "partName LIKE '%".$partName."%' ";
	if($partx!='')			$sqlFilterArray[] = "x LIKE '".$partx."%' ";
	if($party!='')			$sqlFilterArray[] = "y LIKE '".$party."%' ";
	if($partl!='')			$sqlFilterArray[] = "itemLength LIKE '".$partl."%' ";
	if($partw!='')			$sqlFilterArray[] = "itemWidth LIKE '".$partw."%' ";
	if($parth!='')			$sqlFilterArray[] = "itemHeight LIKE '".$parth."%' ";
	if($statusPart!='')		$sqlFilterArray[] = "status IN (".implode(", ",$statusPart).")";
	if($materialType!='')		$sqlFilterMaterialSpecsArray[] = "materialTypeId IN(SELECT materialTypeId FROM engineering_materialtype WHERE materialType LIKE '".$materialType."')";
	if($metalThickness!='')	$sqlFilterMaterialSpecsArray[] = "metalThickness LIKE '".$metalThickness."'";	
	if(count($sqlFilterMaterialSpecsArray) > 0)	$sqlFilterArray[] = "materialSpecId IN(SELECT materialSpecId FROM cadcam_materialspecs WHERE ".implode(" AND ",$sqlFilterMaterialSpecsArray).")";
	$sqlFilter = "WHERE partId > 0";
	if(count($sqlFilterArray) > 0)
	{
		$sqlFilter .= " AND ".implode(" AND ",$sqlFilterArray)." ";
	}
	
	$sql = "SELECT partId FROM cadcam_parts ".$sqlFilter;
	$queryParts = $db->query($sql);
	$totalRecords = ($queryParts AND $queryParts->num_rows > 0) ? $queryParts->num_rows : 0;
	
	//~ echo createFilterInput($sqlFilter,'materialType',$materialType)	
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo displayText('L187'); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/Bootstrap 3.3.7/css/bootstrap.css">
	<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/Font Awesome/css/font-awesome.css">
	<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/Bootstrap 3.3.7/Roboto Font/roboto.css">
	<link rel="stylesheet" href="../Common Data/Templates/api.css">
	
	<script src="/Common Data/Templates/api.js"></script>
</head>
<body>
	<div class="api-row">
		<div class="api-top api-col api-left-buttons" style='width:30%'>
			<button class='api-btn api-btn-home' onclick="location.href='/<?php echo v; ?>/dashboard.php';" data-api-title='<?php echo displayText('L434'); ?>'></button>
			<?php echo helpMenu(1); ?>
			<a target = '_blank' href="https://210.172.85.77/dwginf30/dwg/index.asp?uid=3DUP009&uadrs=3Dnagano@arktech.co.jp%22%3E" ><button class='api-btn api-btn-website' style='width:200px' data-api-title='CLICK me first (KCO Webware)'></button></a>
			<button class='api-btn api-btn-export' id = "exports" style='width:33%' data-api-title='<?php echo displayText('L487'); ?>'></button>
		</div>
		
		<div class="api-top api-col api-title" style='width:40%;'>
			<h2><?php echo displayText('L187'); ?></h2>
		</div>

		<div class="api-top api-col api-right-buttons" style='width:30%'>
			<button class='api-btn api-btn-view' onclick= "window.open('../2-1 Product Development Software/anthony_newItemList.php','BBC','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;" style='width:33%' data-api-title='<?php echo displayText('2-1','utf8',0,1,1); ?>'></button>
			<?php
			if($_GET['country']=="1")
			{
			?>
				<button class='api-btn api-btn-view' onclick= "window.open('../Engineering Data Management Software/Program Management Software/Pending Program List/anthony_pendingProgramSummary.php','logss','screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;" style='width:33%' data-api-title='<?php echo displayText('L1344'); ?>'></button>
			<?php
			}
			?>
				
			<button class='api-btn api-btn-add' onclick="openModalBox('anthony_addParts.php',jsFunctions)" style='width:33%' data-api-title='<?php echo displayText('B4'); ?>'></button>
			<button class='api-btn api-btn-refresh' onclick="location.href='';" style='width:33%' data-api-title='<?php echo displayText('L436'); ?>'></button>
		</div>
		
		<div class="api-col" style='width:100%;height:88vh;'>
			<!-------------------- Filters -------------------->
			<form action='' method='post' id='formFilter' autocomplete="off"></form>	
			<table cellpadding="0" cellspacing="0" border="0" style='width:100%;'>
				<tr style='font-size:12px;'>
					<td align='center' style='width:10%;'><?php echo displayText('L24'); ?></td>
					<td align='center' style='width:15%;'><?php echo displayText('L28'); ?></td>
					<td align='center' style='width:10%;'><?php echo displayText('L30'); ?></td>
					<td align='center' style='width:8%;'><?php echo displayText('L70'); ?></td>
					<td align='center' style='width:8%;'><?php echo displayText('L71'); ?></td>
					<td align='center' style='width:8%;'><?php echo displayText('L74'); ?></td>
					<td align='center' style='width:8%;'><?php echo displayText('L75'); ?></td>
					<td align='center' style='width:8%;'><?php echo displayText('L76'); ?></td>
					<td align='center' style='width:8%;'><?php echo displayText('L111'); ?></td>
					<td align='center' style='width:8%;'><?php echo displayText('L184'); ?></td>
					<td align='center' style='width:20%;'><?php echo displayText('L172'); ?></td>
					<td rowspan='2' align='center' style=''>
						<button type='submit' class='api-btn' onclick="location.href='';" style='font-size:1.2em;' data-api-title='<?php echo displayText('B7'); ?>' form='formFilter'></button>
					</td>
				</tr>
				<tr>
					<td><select name='customerId' class='api-form' value='<?php echo $customerId;?>' form='formFilter'><?php echo createFilterInput($sqlFilter,'customerId',$customerId);?></select></td>
					<td><input list='partNumber' name='partNumber' class='api-form' value='<?php echo $partNumber;?>' form='formFilter'><datalist id='partNumber' class='classDataList'><?php echo createFilterInput($sqlFilter,'partNumber',$partNumber);?></datalist></td>
					<td><input list='partName' name='partName' class='api-form' value='<?php echo $partName;?>' form='formFilter'><datalist id='partName' class='classDataList'><?php echo createFilterInput($sqlFilter,'partName',$partName);?></datalist></td>
					<td><input type='text' name='partx' class='api-form' value='<?php echo $partx;?>' form='formFilter'></td>
					<td><input type='text' name='party' class='api-form' value='<?php echo $party;?>' form='formFilter'></td>
					<td><input type='text' name='partl' class='api-form' value='<?php echo $partl;?>' form='formFilter'></td>
					<td><input type='text' name='partw' class='api-form' value='<?php echo $partw;?>' form='formFilter'></td>
					<td><input type='text' name='parth' class='api-form' value='<?php echo $parth;?>' form='formFilter'></td>
					<td><select name='materialType' class='api-form' value='<?php echo $materialType;?>' form='formFilter'><?php echo createFilterInput($sqlFilter,'materialType',$materialType);?></select></td>
					<td><select name='metalThickness' class='api-form' value='<?php echo $metalThickness;?>' form='formFilter'><?php echo createFilterInput($sqlFilter,'metalThickness',$metalThickness);?></select></td>
					<td align="center">
						<select name='statusPart[]' id='statusPart' multiple='multiple' class='api-form' value='<?php echo $statusPart;?>' form='formFilter'><?php echo createFilterInput($sqlFilter,'status',$statusPart);?>
							<!-- <option></option>
							<option value='0,2'><?php echo displayText('L549');?></option>
							<option value='1'><?php echo displayText('L1058');?></option> -->
						</select>
					<!-- <input type='text' name='statusPart' class='api-form' value='<?php echo $statusPart;?>' form='formFilter'> -->
					</td>
				</tr>
			</table>
			<!------------------ End Filters ------------------>
			
			<!-------------------- Contents -------------------->
			Records : <span><?php echo $totalRecords; ?></span>
			<div style='height: 89%;'><!-- Adjust height if browser had a vertical scroll -->
				<table class="api-table-design1" id="myTable02" cellpadding="0" cellspacing="0">
				<?php
					// --------------------------------------- Table Header ------------------------------------------
					echo "
						<thead>
							<tr>
								<th width='35'>.displayText('L843').</th>
								<th width='35'>".displayText('L52')."</th>
								<th width='230'>".displayText('L24')."</th>                    
								<th width='230'>".displayText('L28')."</th>
								<th width='35'>".displayText('L226')."</th>
								<th width='35'>".displayText('L396')."</th>
								<th width='35'>".displayText('L397')."</th>
								<th width='230'>".displayText('L30')."</th>
								<th width='100'>".displayText('L111')."</th>
								<th width='35'>".displayText('L184')."</th>
								<th width='35'>".displayText('L398')."</th>
								<th width='35'>".displayText('L399')."</th>
								<th width='35'>".displayText('L188')."</th>
								<th width='35'>".displayText('L77')."</th>
								<th width='35'>".displayText('L1346')."</th>
							</tr>
						</thead>
					";
					// ------------------------------------- End of Table Header ------------------------------				
				?>
				<tbody id='results' data-group-no='0'>
					
				</tbody>
				<tfoot>
					<tr><th colspan='14'></th></tr>
				</tfoot>
				</table>
			</div>
			<!------------------ End Contents ------------------>			
			
		</div>
	</div>
</body>
<script src="../Common Data/Libraries/Javascript/jQuery 3.1.1/jquery-3.1.1.js"></script>
<script src="../Common Data/Libraries/Javascript/jQuery 3.1.1/jquery-ui.js"></script>
<script src="../Common Data/Libraries/Javascript/jQuery 3.1.1/bootstrap.min.js"></script>
<script src="../Common Data/Templates/jquery.js"></script>
<link href="../Common Data/Libraries/Javascript/Table with Fixed Header/css/defaultTheme.css" rel="stylesheet" media="screen" />
<script src="../Common Data/Libraries/Javascript/Table with Fixed Header/jquery.fixedheadertable.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/Bootstrap Multi-Select JS/dist/css/bootstrap-multiselect.css" type="text/css" media="all" />
<script src="../Common Data/Libraries/Javascript/Bootstrap Multi-Select JS/dist/js/bootstrap-multiselect.js"></script>
<script>
	function loadData(url) {
		var groupNo = parseFloat($("#results").attr("data-group-no"));
		$.post(url,{'groupNo': groupNo,'sqlFilter':"<?php echo $sqlFilter; ?>"}, function(data){
			$("#results").append(data);
			$("#results").attr("data-group-no",groupNo+1);
			loading = false;
		}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
			loading = false;
		});		
	}
	 
	
	$(function(){
		$('#myTable02').fixedHeaderTable({footer: true});
		
		loading=false;
		loadData('gerald_productAjax.php');
		
		$('.fht-tbody').scroll(function(){
			var thisObj = $(this);
			if(thisObj.scrollTop() + thisObj.innerHeight() >= (thisObj[0].scrollHeight/1.2))
			{
				if(parseFloat($("#results").attr("data-group-no")) <= parseFloat('<?php echo $totalRecords/50;?>') && loading==false)
				{
					loading = true;
					loadData('gerald_productAjax.php');
				}
			}
		});
		$('#exports').click(function(){
			var sqlFilter = "<?php echo $sqlFilter;  ?>";
			//~ alert(sqlFilter);
			window.location = "gerald_productAjax.php?type=export&sqlFilter="+sqlFilter;
		});

		$('#statusPart').multiselect({
			maxHeight: 300,
			includeSelectAllOption: true,
			buttonClass:'api-form',
			buttonWidth: '105px',
			nonSelectedText : 'Select',
			numberDisplayed: 0,
			onSelectAll: function(event) {
				event.preventDefault();
            },
			onDeselectAll: function(event) {
				event.preventDefault();
            },
			onChange: function(event) {
				event.preventDefault();
            }
		});
	 });
	
	//  -------------------------------------------------- For Modal Box Javascript Code -------------------------------------------------- //
	function jsFunctions(){
		$("#materialType").change(function(){//;cadcam_materialspecs;
			var materialTypeId = $(this).val();
			$.ajax({
				url:'anthony_addParts.php',
				type:'post',
				data:{
					ajaxType:'getThickness',
					materialTypeId:materialTypeId
				},
				success:function(data){
					$("#metalThickness").html(data);
					$("#matSpecsId").val('0');
				}
			});
		});
		
		$("#metalThickness").change(function(){//;cadcam_materialspecs;
			$.ajax({
				url:'anthony_addParts.php',
				type:'post',
				data:{
					ajaxType:'getMaterialSpecId',
					metalThickness:$(this).val(),
					materialTypeId:$("#materialType").val()
				},
				success:function(data){
					$("#matSpecsId").val(data);
				}
			});
		});
	}	
	//  ------------------------------------------------ END For Modal Box Javascript Code ------------------------------------------------ //
</script>
</html>
