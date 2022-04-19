<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/gerald_functions.php');
include('PHP Modules/anthony_retrieveText.php');
include("PHP Modules/anthony_wholeNumber.php");
include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");

function checkOpenParts()
{
	include('PHP Modules/mysqliConnection.php');
	$partIdArray = Array();
	$sql = "SELECT DISTINCT partId FROM system_lotlist";
	$queryLotlist = $db->query($sql);
	if($queryLotlist AND $queryLotlist->num_rows > 0)
	{
		while($resultLotlist = $queryLotlist->fetch_assoc())
		{
			$partId = $resultLotlist['partId'];
	
			list($partIdSUB,$quantitySUB,$identifierSUB) = partIdTree($partId);
			
			foreach($partIdSUB AS $key)
			{
				$partIdArray[] = $key;
			}
		}
	}

	return $partIdArray;
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
$sheetWorksFlag = (isset($_POST['sheetWorksFlag'])) ? $_POST['sheetWorksFlag'] : '';
$firstPODate = (isset($_POST['firstPODate'])) ? $_POST['firstPODate'] : '';
$lastPODate = (isset($_POST['lastPODate'])) ? $_POST['lastPODate'] : '';
$showOpenPO = (isset($_POST['showOpenPO'])) ? $_POST['showOpenPO'] : 1;
$dataValue = (isset($_POST['dataValue'])) ? $_POST['dataValue'] : "poQuantity";
$order = (isset($_POST['order'])) ? $_POST['order'] : "DESC";
$process = (isset($_POST['process'])) ? $_POST['process'] : "";
$processGroup = (isset($_POST['processGroup'])) ? $_POST['processGroup'] : "";
$partTypeFlag = (isset($_POST['partTypeFlag'])) ? $_POST['partTypeFlag'] : "";
$xOperator = (isset($_POST['xOperator'])) ? $_POST['xOperator'] : '';
$yOperator = (isset($_POST['yOperator'])) ? $_POST['yOperator'] : '';
$lengthOperator = (isset($_POST['lengthOperator'])) ? $_POST['lengthOperator'] : '=';
$widthOperator = (isset($_POST['widthOperator'])) ? $_POST['widthOperator'] : '=';
$heightOperator = (isset($_POST['heightOperator'])) ? $_POST['heightOperator'] : '=';
$showPrice = (isset($_POST['showPrice'])) ? $_POST['showPrice'] : '';
$partsComment = (isset($_POST['partsComment'])) ? $_POST['partsComment'] : '';

$added = "";
if($process != '')
{
	$sql = "SELECT processCode FROM cadcam_process WHERE processName = '".$process."'";
	$queryName = $db->query($sql);
	if($queryName AND $queryName->num_rows > 0)
	{
		$resultName = $queryName->fetch_assoc();
		$process = $resultName['processCode'];
		$added .= " AND processCode = ".$process;
	}
}

if($processGroup != '')
{
	$added .= " AND processSection = ".$processGroup;
}

$sqlFilter = "";
$sqlFilterArray = $sqlFilterMaterialSpecsArray = array();

if($customerId!='')				$sqlFilterArray[] = "customerId = ".$customerId." ";
if($partName!='')				$sqlFilterArray[] = "partName LIKE '".$partName."%' ";
if($partNumber!='')				$sqlFilterArray[] = "partNumber LIKE '%".$partNumber."%' ";
if($partsComment!='')			$sqlFilterArray[] = "partsComment LIKE '%".$partsComment."%' ";
if($partx!='')					$sqlFilterArray[] = "x ".$xOperator." '".$partx."' ";
if($party!='')					$sqlFilterArray[] = "y ".$yOperator." '".$party."' ";
if($partl!='')					$sqlFilterArray[] = "itemLength ".$lengthOperator." '".$partl."' ";
if($partw!='')					$sqlFilterArray[] = "itemWidth ".$widthOperator." '".$partw."' ";
if($parth!='')					$sqlFilterArray[] = "itemHeight ".$heightOperator." '".$parth."' ";
if($firstPODate!='')			$sqlFilterArray[] = "firstPODate BETWEEN '".str_replace(" to ","' AND '",$firstPODate)."'";
if($lastPODate!='')				$sqlFilterArray[] = "lastPODate BETWEEN '".str_replace(" to ","' AND '",$lastPODate)."'";
if($statusPart!='')				$sqlFilterArray[] = "status IN (".implode(", ",$statusPart).")";
if($partTypeFlag!='')			$sqlFilterArray[] = "partTypeFlag IN (".implode(", ",$partTypeFlag).")";
if($materialType!='')			$sqlFilterMaterialSpecsArray[] = "materialTypeId IN(SELECT materialTypeId FROM engineering_materialtype WHERE materialType IN ('".implode("', '",$materialType)."'))";
if($metalThickness!='')			$sqlFilterMaterialSpecsArray[] = "metalThickness LIKE '".$metalThickness."'";	

if(count($sqlFilterMaterialSpecsArray) > 0)	$sqlFilterArray[] = "materialSpecId IN(SELECT materialSpecId FROM cadcam_materialspecs WHERE ".implode(" AND ",$sqlFilterMaterialSpecsArray).")";
if($sheetWorksFlag!='')
{
	$partIdArray = '';
	$sql = "SELECT partId FROM engineering_sheetworksdata WHERE identifier = 1 AND partId > 0";
	$querySheetWorksData = $db->query($sql);
	if($querySheetWorksData AND $querySheetWorksData->num_rows > 0)
	{
		while($resultSheetWorksData = $querySheetWorksData->fetch_assoc())
		{
			$partIdArray[] = $resultSheetWorksData['partId'];
		}
	}

	if($process != '' OR $processGroup != '')
	{
		$sqlFilter .= "partId IN (SELECT DISTINCT partId FROM cadcam_partprocess WHERE partId IN(".implode(",",$partIdArray).") ".$added.")";
	}
	else
	{
		$sqlFilterArray[] = "partId IN(".implode(",",$partIdArray).")";
	}
}

$sqlFilter = "WHERE partId > 0";
if(count($sqlFilterArray) > 0)
{
	$sqlFilter .= " AND ".implode(" AND ",$sqlFilterArray)." ";
}

$lastValue = "checked";
$showOpenPOCheckData = "checked";

if( !isset($_POST['showOpenPO']) )
{           
	if(isset($_POST['lastValue']))
	{                       
		$lastValue = $_POST['lastValue'];          
		if($lastValue == "checked")
		{
			$lastValue = "unchecked";
		}           
	}   
}

if($lastValue == "unchecked")
{
	$showOpenPO = 0; 
	$showOpenPOCheckData = "";   
}
//echo $lastValue;

if( $showOpenPO == 1 )
{
	$partIds = checkOpenParts();
	if($process != '' OR $processGroup != '')
	{
		$sqlFilter .= " AND partId IN (SELECT DISTINCT partId FROM cadcam_partprocess WHERE partId IN (".implode(", ",$partIds).") ".$added.")" ;
	}
	else
	{
		$sqlFilter .= " AND partId IN (".implode(", ",$partIds).")";
	}
}
else
{
	if($process != '' OR $processGroup != '')
	{
		$sqlFilter .= " AND partId IN (SELECT DISTINCT partId FROM cadcam_partprocess WHERE partId > 0 ".$added.")";
	}
}

$totalRecords = 0;

$colorFirstPO = $colorLastPO = $colorCountPO = $colorQuantityPO = 'w3-green';
$sortFirstPOClass = $sortLastPOClass = $sortCountPOClass = $sortQuantityPOClass = "";
if($dataValue == "")
{
	$sqlFilter .= " ORDER BY partId DESC";
}
else
{	

	if($dataValue == 'firstPOdate')
	{
		$sqlFilter .= " ORDER BY firstPODate ".$order;
		$colorFirstPO = 'w3-pink';
	
		$sortFirstPOClass = "<i class='fa fa-sort-amount-desc'></i>&emsp;";
		if($order == 'ASC') $sortFirstPOClass = "<i class='fa fa-sort-amount-asc'></i>&emsp;";
	}
	
	if($dataValue == 'lastPOdate')
	{
		$sqlFilter .= " ORDER BY lastPOdate ".$order;
		$colorLastPO = 'w3-pink';
	
		$sortLastPOClass = "<i class='fa fa-sort-amount-desc'></i>&emsp;";
		if($order == 'ASC') $sortLastPOClass = "<i class='fa fa-sort-amount-asc'></i>&emsp;";
	}

	if($dataValue == 'poCount')
	{
		$sqlFilter .= " ORDER BY orderCount ".$order;
		$colorCountPO = 'w3-pink';
	
		$sortCountPOClass = "<i class='fa fa-sort-amount-desc'></i>&emsp;";
		if($order == 'ASC') $sortCountPOClass = "<i class='fa fa-sort-amount-asc'></i>&emsp;";
	}

	if($dataValue == 'poQuantity')
	{
		$sqlFilter .= " ORDER BY totalQuantity ".$order;
		$colorQuantityPO = 'w3-pink';
	
		$sortQuantityPOClass = "<i class='fa fa-sort-amount-desc'></i>&emsp;";
		if($order == 'ASC') $sortQuantityPOClass = "<i class='fa fa-sort-amount-asc'></i>&emsp;";
	}

}

$sql = "SELECT * FROM cadcam_parts ".$sqlFilter;
// if($_SESSION['idNumber'] == "0412") echo $sql;
$sqlData = $sql;
//$sqlFilter = trim(preg_replace('/\s+/', ' ', $sqlFilter));
$query = $db->query($sql);

if($query AND $query->num_rows > 0)
{
	$totalRecords = $query->num_rows;
}

$title = displayText("2-C", "utf8", 0, 1)." v5.5";
PMSTemplates::includeHeader($title, 1);
$displayId = "2-C"; # RO LIST
$version = "v2.0";
$prevousLink = "";
createHeader($displayId, $version, $prevousLink);
?>
<style>
	.dropdown {
		position: relative;
		display: inline-block;
	}

	.dropdown-content, .dropdown-content-filter {
		display: none;
		position: absolute;
		z-index: 100;
	}

	.dropdown:hover .dropdown-content {
		display: block;
	}
	.fontGe td {
		font-size:<?php echo $fontSize;?>px!important;
	}
	/* body{
		font-family: Roboto;
	} */
	body
	{
		font-size: 11px;
		/* background-color: whitesmoke; */
	}
	td
	{
		white-space: nowrap;
	}
	.text-wrap{
		white-space:normal;
	}
	.width-180{
		width:90px;
	}

	.dataTables_scrollBody{
		overflow-x:hidden !important;
	}
</style>
<form id='exportFormId' action='jazmin_productAjax.php' method='POST'></form>
<form id='formFilter' action='<?php echo $_SERVER['PHP_SELF'];?>' method='POST'></form>
<input type='hidden' name='sqlData' value="<?php echo $sqlData;?>" form='exportFormId'>    
<div class="container-fluid">
	<div class="row w3-padding-top">
		<div class="col-md-12">
			<a target = '_blank' href="https://210.172.85.77/dwginf30/dwg/index.asp?uid=3DUP009&uadrs=3Dnagano@arktech.co.jp%22%3E" ><button class='w3-btn w3-tiny w3-round w3-green' style='width:200px'><i class='fa fa-hand-pointer-o'></i>&emsp;<b>CLICK me first (KCO Webware)</b></button></a>
			<!-- &emsp;<label>SORT BY:</label> -->
			<!-- <button id='type' class='w3-btn w3-tiny w3-round w3-indigo'><b>TYPE</b></button> -->
			<!-- <button id='thicnkess' class='w3-btn w3-tiny w3-round w3-indigo'><b>THICKNESS</b></button> -->
			<!-- <button id='firstPOdate' class='w3-btn w3-tiny w3-round <?php echo $colorFirstPO; ?>'><?php echo $sortFirstPOClass; ?><b>FIRST PO DATE</b></button> -->
			<!-- <button id='lastPOdate' class='w3-btn w3-tiny w3-round <?php echo $colorLastPO; ?>'><?php echo $sortLastPOClass; ?><b>LAST PO DATE</b></button> -->
			<!-- <button id='poCount' class='w3-btn w3-tiny w3-round <?php echo $colorCountPO; ?>'><?php echo $sortCountPOClass; ?><b>PO COUNT</b></button> -->
			<!-- <button id='poQuantity' class='w3-btn w3-tiny w3-round <?php echo $colorQuantityPO; ?>'><?php echo $sortQuantityPOClass; ?><b>PO QUANTITY</b></button> -->
			<input type='hidden' id='dataValue' name='dataValue' form='formFilter' value='<?php echo $dataValue; ?>'>
			<input type='hidden' id='order' name='order' form='formFilter' value='<?php echo $order; ?>'>
			<?php
			if(isset($_POST))
			{
				foreach ($_POST as $key => $value) 
				{
					if(!in_array($key, ['showOpenPO', 'lastValue', 'dataValue', 'order', 'materialType', 'statusPart', 'partTypeFlag']))
					{
						if(!is_array($value) AND trim($value) != "")
						{
							echo "<input type='hidden' name='".$key."' value='".$value."' form='formFilter'>";
						}
						else if(is_array($value))
						{
							foreach ($value as $data) 
							{
								echo "<input type='hidden' class='".$key."' name='".$key."[]' value='".$data."' form='formFilter'>";
							}
						}
					}
				}
			}
			?>
			<div class='w3-right'>
				<button class="w3-btn w3-tiny w3-round w3-pink dropdown-filter"><i class='fa fa-list'></i>&emsp;<b><?php echo displayText('B7', 'utf8', 0, 0, 1);?></b></button>
				<button class="w3-btn w3-tiny w3-round w3-blue dropdown-filter" id="addProcessTools"><i class='fa fa-plus'></i>&emsp;<b><?php echo displayText('B4', 'utf8', 0, 0, 1);?></b></button>
				<div class='w3-dropdown-hover'>
					<button class='w3-btn w3-tiny w3-round w3-indigo'><i class='fa fa-gear'></i>&emsp;<b><?php echo displayText('L435', 'utf8', 0, 0, 1); ?></b></button>
					<div class="w3-dropdown-content" style='z-index:9999999;'>
						<button style='width:100%;' class='w3-btn w3-round w3-blue w3-hover-pink' onclick= "window.open('../2-1 Product Development Software/anthony_newItemList.php','BBC','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><b><?php echo displayText('2-1', 'utf8', 0, 1, 1); ?></b></button>
						<!-- <div class="w3-padding-top"></div> -->
						<!-- <button style='width:100%;' class='w3-btn w3-round w3-blue w3-hover-pink' onclick= "window.open('/V3/Engineering Data Management Software/Process Review Software/raymond_processReview.php','BBC','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=1200,height=800'); return false;"><b><?php echo displayText('89', 'utf8', 0, 2, 1); ?></b></button> -->
						<?php
						// if($_GET['country']=="1")
						// {
						?>
							<div class="w3-padding-top"></div>
							<button style='width:100%;' class='w3-btn w3-round w3-blue w3-hover-pink' onclick= "window.open('../Engineering Data Management Software/Program Management Software/Pending Program List/anthony_pendingProgramSummary.php','logss','screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><b><?php echo displayText('L1344', 'utf8', 1, 0, 1); ?></b></button>
						<?php
						// }
						?>
						<div class="w3-padding-top"></div>
						<button style='width:100%;' class='w3-btn w3-round w3-blue w3-hover-pink' name='exportFlag' value='1' form='exportFormId'><b><?php echo displayText('L487'); ?></b></button>
						<div class="w3-padding-top"></div>
						<button style='width:100%;' class='w3-btn w3-round w3-blue w3-hover-pink' onclick= "window.open('/<?php echo v; ?>/PHP%20CURL/gerald_checkRevision.php','logss','screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><b><?php echo displayText('14-6', 'utf8', 0, 1, 1); ?></b></button>
					</div>
				</div>
				<button class='w3-btn w3-tiny w3-round w3-green' onclick="location.href='';"><i class='fa fa-refresh'></i>&emsp;<b><?php echo displayText('L436', 'utf8', 0, 0, 1);?></b></button>
			</div>
		</div>
	</div>
	<div class="row w3-padding-top">
		<div class='col-md-12'>
			<label><?php echo displayText('L41'); ?> : <span><?php echo $totalRecords; ?></span></label>
			<table id='mainTableId' style='' class="table table-bordered table-striped table-condensed" data-counter='-1' data-detail-type='left'>
				<thead class='w3-indigo' style='text-transform:uppercase;'>
					<th class='w3-center' style='vertical-align:middle;'></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L843');?></th>
<!--
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L52');?></th>
-->
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L24');?></th>                    
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L28');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L226');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L3446');?></th>
					<!-- <th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L396');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L397');?></th> -->
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L30');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L111');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L184');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L398');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L399');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L76');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L75');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L74');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L72');?></th>
					<?php
					if($showPrice == 1)
					{
					?>
						<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L267');?></th>
					<?php	
					}
					?>
					<th class='w3-center <?php echo $colorFirstPO; ?> firstPOdateClass '  data-id='firstPOdate' style='cursor:pointer; vertical-align:middle;'><?php echo $sortFirstPOClass; ?><?php echo displayText('L4162');?></th>
					<th class='w3-center <?php echo $colorLastPO; ?> lastPOdateClass' data-id='lastPOdate' style='cursor:pointer; vertical-align:middle;'><?php echo $sortLastPOClass; ?><?php echo displayText('L4163');?></th>
					<th class='w3-center <?php echo $colorCountPO; ?> poCountClass' data-id='poCount' style='cursor:pointer; vertical-align:middle;'><?php echo $sortCountPOClass; ?><?php echo displayText('L4164');?></th>
					<th class='w3-center <?php echo $colorQuantityPO; ?> poQuantityClass' data-id='poQuantity' style='cursor:pointer; vertical-align:middle;'><?php echo $sortQuantityPOClass; ?><?php echo displayText('L1003');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L188');?></th>
<!--
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L188');?></th>
-->
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L77');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L636');?></th>
					<!-- <th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L1346');?></th> -->
				</thead>
				<tbody class='w3-center'>
					
				</tbody>
				<tfoot class='w3-indigo' style='text-transform: uppercase;'>
					<tr>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
<!--
						<th class='w3-center' style='vertical-align:middle;'></th>
-->
						<th class='w3-center' style='vertical-align:middle;'></th>                    
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<!-- <th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th> -->
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<?php
						if($showPrice == 1)
						{
						?>
							<th class='w3-center' style='vertical-align:middle;'></th>
						<?php	
						}
						?>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
<!--
						<th class='w3-center' style='vertical-align:middle;'></th>
-->
						<th class='w3-center' style='vertical-align:middle;'></th>
						<th class='w3-center' style='vertical-align:middle;'></th>
						<!-- <th class='w3-center' style='vertical-align:middle;'></th> -->
					</tr>
				</tfoot>
			</table>
		</div>			
	</div>
</div>
<div id='modal-izi'><span class='izimodal-content'></span></div>
<?php
PMSTemplates::includeFooter();
?>
<script>
	function openJSCustom() 
	{
		$('#statusPart, #partTypeFlag').multiselect({
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
	}
</script>
<script>
	$(function(){
        //~ var sqlFilter = "<?php echo str_replace("\n"," ",$sqlFilter); ?>";
        var width = screen.width;
        var cond ="";
        console.log(width);
         if (width >= 1281)
         //~ if (width >= 1600)
            {
            	cond=false;
            }
            else
            {
            	cond=true;
            }
        //~ cond=true;    
        var sqlFilter = "<?php echo $sqlFilter; ?>";
        var sqlData = "<?php echo $sqlData; ?>";
        var totalRecords = "<?php echo $totalRecords; ?>";
		var showPrice = "<?php echo $showPrice; ?>";
        var dataTable = $('#mainTableId').DataTable( {
            "searching"    : false,
            "processing"    : true,
            "ordering"      : false,
            "serverSide"    : true,
            "bInfo" : false,
            "ajax":{
                url     :"jazmin_productAjax.php", // json datasource
                type    : "post",  // method  , by default get
                data    : {
                            "sqlData"     		: sqlData,
                            "showPrice"     	: showPrice,
                            "totalRecords"     	: totalRecords,
                            "sqlFilter"     	: sqlFilter
                         },
                error: function(data){  // error handling
                    $(".mainTableId-error").html("");
                    //$("#mainTableId").append('<tbody class="mainTableId-error"><tr><th colspan="3">No data found in the server'+totalRecords+'</th></tr></tbody>');
                    $("#mainTableId").append('<tbody class="mainTableId-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#mainTableId_processing").css("display","none");
                    console.log(data);
                }
            },
			"createdRow": function( row, data, index ) {
				var partId = data[0];
				$('td:eq(20)', row).dblclick(function(){
					$(this).attr("contenteditable", true);
					$(this).addClass("w3-pale-yellow");
					$(this).focus();
				});

				$('td:eq(20)', row).blur(function(){
					var comments = $(this).text();
					$.ajax({
						url 	: 'raymond_partCommentAJAX.php',
						type 	: 'POST',
						data 	: {
									partId 		: partId,
									comments 	: comments
						},
						success : function(data){
									$('td:eq(20)', row).attr("contenteditable", false);
									$('td:eq(20)', row).removeClass("w3-pale-yellow");
						}
					});
					
				});
			},
            "columnDefs": [
                        { 
                            "targets"   : [ 0 ],
							"visible"	: false
                        },
						{
                            render: function (data, type, full, meta) {
                                return "<div class='text-wrap width-180 w3-center' style='text-align:left;'>" + data + "</div>";
                            },
                            targets: [ 2, 6 ]
                        }
            ],
            fixedColumns: true,
            deferRender: true,
            scrollY    	: 505,
            scrollX		: cond,
            scroller    : {
                loadingIndicator    : true
            },
            stateSave   : false
        });
        
        // $('#type, #thicnkess, #firstPOdate, #lastPOdate, #poCount, #poQuantity').click(function(){
        /* $('#firstPOdate, #lastPOdate, #poCount, #poQuantity').click(function(){
			var id = $(this).prop("id");
			var valueText = $("#dataValue").val();
			var order = "<?php echo $order; ?>";

			if(id != valueText) 
            {
            	$("#order").val("");
            	var order = $("#order").val();
            }
        
			if(order == "") $("#order").val("ASC");
			if(order == "ASC") $("#order").val("DESC");
			if(order == "DESC") $("#order").val("ASC");

			$("#dataValue").val(id);
			$("#formFilter").submit();
		}); */

        $('.firstPOdateClass, .lastPOdateClass, .poCountClass, .poQuantityClass').click(function(){
			var id = $(this).attr("data-id");
			var valueText = $("#dataValue").val();
			var order = "<?php echo $order; ?>";

			if(id != valueText) 
            {
            	$("#order").val("");
            	var order = $("#order").val();
            }
        
			if(order == "") $("#order").val("DESC");
			if(order == "ASC") $("#order").val("DESC");
			if(order == "DESC") $("#order").val("ASC");

			$("#dataValue").val(id);
			$("#formFilter").submit();
		});

        $('#exportId').click(function(){
			var sqlFilter = "<?php echo $sqlFilter;  ?>";
			//~ alert(sqlFilter);
			window.location = "jazmin_productExport.php?type=exportId&sqlFilter="+sqlFilter;
		});


        $(".dropdown-filter").on('click', function (event) {
			localStorage.clear();
			var filterDataPost = "<?php echo str_replace('"',"'",json_encode($_POST));?>";
			var filterDataGet = "<?php echo str_replace('"',"'",json_encode($_GET));?>";
			var lastValue = "<?php echo $lastValue; ?>";
			var showOpenPOCheckData = "<?php echo $showOpenPOCheckData; ?>";
			var id = "<?php echo $_SESSION['idNumber']; ?>";

			$("#modal-izi").iziModal({
				title                   : '<i class="fa fa-flash"></i> FILTER',
				headerColor             : '#1F4788',
				subtitle                : '<b><?php echo strtoupper(date('F d, Y'));?></b>',
				width                   : 1200,
				fullscreen              : false,
				transitionIn            : 'comingIn',
				transitionOut           : 'comingOut',
				padding                 : 20,
				radius                  : 0,
				top                     : 10,
				restoreDefaultContent   : true,
				closeOnEscape           : true,
				closeButton             : true,
				overlayClose            : false,
				onOpening               : function(modal){
											modal.startLoading();
											$.ajax({
												url         : 'raymond_filterData.php',
												type        : 'POST',
												data        : {
																sqlData             : sqlData,
																filterDataPost		: filterDataPost,
																filterDataGet 		: filterDataGet,
																sqlFilter 			: sqlFilter,
																lastValue 			: lastValue,
																showOpenPOCheckData : showOpenPOCheckData
												},
												success     : function(data){
																$( ".izimodal-content" ).html(data);
																modal.stopLoading();
												}
											});
										},
					onClosed            : function(modal){
											$("#modal-izi").iziModal("destroy");
							}
			});

			$("#modal-izi").iziModal("open");
		});

		$("#addProcessTools").on('click', function (event) {
			localStorage.clear();
			// var addDataPost = "<?php echo str_replace('"',"'",json_encode($_POST));?>";
			// var addDataGet = "<?php echo str_replace('"',"'",json_encode($_GET));?>";

			TINY.box.show({
							url:'anthony_addParts.php',
							post:"",
							width:'350',
							height:'500',
							//boxid:'frameless',
							maskid:'bluemask',
							opacity:10,
							topsplit:6,
							fixed:true,
							animate:false,
							close:true,
							openjs:function(){openJSCustom()}
						});	
			});            
	});
</script>
</html>
