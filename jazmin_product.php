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

Class PartsMasterCurrentFilter extends DisplayCurrentFilter
{
	private function rangeFilter($operator,$fromValue,$toValue='')
	{
		if($operator=='RANGE')	return $fromValue." - ".$toValue;

		return $operator." ".$fromValue;		
	}

	protected function readableValue($key,$value)
	{
		if($key=='customerId')
		{
			$sql = "SELECT customerAlias FROM sales_customer WHERE customerId IN(".implode(",",$value).")";
			$result = TableDB::fetchAll($sql);

			$customerArray = array_map(function($val) {
				return $val->customerAlias;
			},$result);

			return implode(",",$customerArray);
		}

		if($key=='statusPart')
		{
			$statusArray = ['Active','Inactive','Pending'];

			$newStatusArray = array_map(function($val) use ($statusArray){
				return $statusArray[$val];
			},$value);

			return implode(",",$newStatusArray);
		}

		if($key=='partTypeFlag')
		{
			$partTypeArray = ['ASSY','SUBPART','SINGLE'];

			$newPartTypeArray = array_map(function($val) use ($partTypeArray){
				return $partTypeArray[$val];
			},$value);

			return implode(",",$newPartTypeArray);
		}

		if($key=='processGroup')
		{
			$sql = "SELECT sectionName FROM ppic_section WHERE sectionId = {$value} LIMIT 1";
			$querySection = $this->db->query($sql);
			if($querySection AND $querySection->num_rows > 0)
			{
				$resultSection = $querySection->fetch_assoc();
				return $resultSection['sectionName'];
			}
		}

		if($key=='partl')
		{
			return $this->rangeFilter($this->data['lengthOperator'],$value);
		}

		if($key=='partw')
		{
			return $this->rangeFilter($this->data['widthOperator'],$value);
		}

		if($key=='parth')
		{
			return $this->rangeFilter($this->data['heightOperator'],$value);
		}

		if($key=='itemxFromFilter')
		{
			return $this->rangeFilter($this->data['itemxFilter'],$value,$this->data['itemxToFilter']);
		}

		if($key=='itemyFromFilter')
		{
			return $this->rangeFilter($this->data['itemyFilter'],$value,$this->data['itemyToFilter']);
		}

		if($key=='itemWeightFromFilter')
		{
			return $this->rangeFilter($this->data['itemWeightFilter'],$value,$this->data['itemWeightToFilter']);
		}

		if($key=='itemLengthFromFilter')
		{
			return $this->rangeFilter($this->data['itemLengthFilter'],$value,$this->data['itemLengthToFilter']);
		}

		if($key=='itemWidthFromFilter')
		{
			return $this->rangeFilter($this->data['itemWidthFilter'],$value,$this->data['itemWidthToFilter']);
		}

		if($key=='itemHeightFromFilter')
		{
			return $this->rangeFilter($this->data['itemHeightFilter'],$value,$this->data['itemHeightToFilter']);
		}

		if($key=='showOpenPO')
		{
			return ($value==1) ? 'Yes' : 'No';
		}

		if(is_array($value))
		{
			return implode(",",$value);
		}

		return $value;
	}
}

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

//TAMANG
echo $checkBox = (isset($_POST['checkBox'])) ? $_POST['checkBox'] : '';

//TAMANG
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

$itemxFilter = isset($_POST['itemxFilter']) ? $_POST['itemxFilter'] : '>=';
$itemxFromFilter = isset($_POST['itemxFromFilter']) ? $_POST['itemxFromFilter'] : '';
$itemxToFilter = isset($_POST['itemxToFilter']) ? $_POST['itemxToFilter'] : '';
$itemyFilter = isset($_POST['itemyFilter']) ? $_POST['itemyFilter'] : '>=';
$itemyFromFilter = isset($_POST['itemyFromFilter']) ? $_POST['itemyFromFilter'] : '';
$itemyToFilter = isset($_POST['itemyToFilter']) ? $_POST['itemyToFilter'] : '';
$itemWeightFilter = isset($_POST['itemWeightFilter']) ? $_POST['itemWeightFilter'] : '>=';
$itemWeightFromFilter = isset($_POST['itemWeightFromFilter']) ? $_POST['itemWeightFromFilter'] : '';
$itemWeightToFilter = isset($_POST['itemWeightToFilter']) ? $_POST['itemWeightToFilter'] : '';
$itemLengthFilter = isset($_POST['itemLengthFilter']) ? $_POST['itemLengthFilter'] : '>=';
$itemLengthFromFilter = isset($_POST['itemLengthFromFilter']) ? $_POST['itemLengthFromFilter'] : '';
$itemLengthToFilter = isset($_POST['itemLengthToFilter']) ? $_POST['itemLengthToFilter'] : '';
$itemWidthFilter = isset($_POST['itemWidthFilter']) ? $_POST['itemWidthFilter'] : '>=';
$itemWidthFromFilter = isset($_POST['itemWidthFromFilter']) ? $_POST['itemWidthFromFilter'] : '';
$itemWidthToFilter = isset($_POST['itemWidthToFilter']) ? $_POST['itemWidthToFilter'] : '';
$itemHeightFilter = isset($_POST['itemHeightFilter']) ? $_POST['itemHeightFilter'] : '>=';
$itemHeightFromFilter = isset($_POST['itemHeightFromFilter']) ? $_POST['itemHeightFromFilter'] : '';
$itemHeightToFilter = isset($_POST['itemHeightToFilter']) ? $_POST['itemHeightToFilter'] : '';

$added = $added2 = "";
if($process != '')
{
	$processArray = [];
	// $sql = "SELECT processCode FROM cadcam_process WHERE processName = '".$process."'";
	$sql = "SELECT processCode FROM cadcam_process WHERE processName IN('".implode("','",$process)."')";
	$queryName = $db->query($sql);
	if($queryName AND $queryName->num_rows > 0)
	{
		while($resultName = $queryName->fetch_assoc())
		{
			$processArray[] = $resultName['processCode'];
			// $process = $resultName['processCode'];
			// $added .= " AND processCode = ".$process;
		}
	}

	if(count($processArray) > 0)
	{
		$added .= " AND processCode IN(".implode(",",$processArray).")";
		$added2 = " AND EXISTS(SELECT partId FROM cadcam_partprocess WHERE cadcam_partprocess.partId = cadcam_parts.partId AND cadcam_partprocess.processCode IN(".implode(",",$processArray).") GROUP BY cadcam_partprocess.partId, cadcam_partprocess.patternId HAVING COUNT(processCode) = ".count($processArray).")";
	}
}

if($processGroup != '')
{
	$added .= " AND processSection = ".$processGroup;
}

$sqlFilter = "";
$sqlFilterArray = $sqlFilterMaterialSpecsArray = array();

if($customerId!='')				$sqlFilterArray[] = "customerId IN(".implode(",",$customerId).") ";
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

$additionalField = '';
$poQuantityField = 'totalQuantity';
$poCountField = 'orderCount';
if($lastPODate!='')
{
	$additionalField = "
		IFNULL((
			SELECT
				SUM(ppic_lotlist.workingQuantity)
			FROM
				ppic_lotlist
				INNER JOIN
					sales_polist
				ON
					sales_polist.poId = ppic_lotlist.poId
			WHERE
				ppic_lotlist.partId = cadcam_parts.partId AND
				ppic_lotlist.identifier = 1 AND
				sales_polist.poDate BETWEEN '".str_replace(" to ","' AND '",$lastPODate)."'
			GROUP BY
				ppic_lotlist.partId
		),0) poQuantity
	";
	$additionalField .= ",
		IFNULL((
			SELECT
				COUNT(ppic_lotlist.partId)
			FROM
				ppic_lotlist
				INNER JOIN
					sales_polist
				ON
					sales_polist.poId = ppic_lotlist.poId
			WHERE
				ppic_lotlist.partId = cadcam_parts.partId AND
				ppic_lotlist.identifier = 1 AND
				sales_polist.poDate BETWEEN '".str_replace(" to ","' AND '",$lastPODate)."'
			GROUP BY
				ppic_lotlist.partId
		),0) poCount
	";
	$poQuantityField = 'poQuantity';
	$poCountField = 'poCount';
	$additionalField = trim(preg_replace('/\s+/', ' ', $additionalField));
}

if(count($sqlFilterMaterialSpecsArray) > 0)	$sqlFilterArray[] = "materialSpecId IN(SELECT materialSpecId FROM cadcam_materialspecs WHERE ".implode(" AND ",$sqlFilterMaterialSpecsArray).")";
if($sheetWorksFlag!='')
{
	$partIdArray = '';
	$sql = "SELECT partId FROM engineering_sheetworksdatanew WHERE identifier = 1 AND partId > 0";
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

if($itemxFilter == "RANGE")
{
    if($itemxFromFilter != "" AND $itemxToFilter != "")
    {
        $sqlFilter .= " AND x BETWEEN '".$itemxFromFilter."' AND '".$itemxToFilter."'"; 
    }
}
else
{
    if($itemxFromFilter != "")
    {
        $sqlFilter .= " AND x ".$itemxFilter." '".$itemxFromFilter."'";
    }
}

if($itemyFilter == "RANGE")
{
    if($itemyFromFilter != "" AND $itemyToFilter != "")
    {
        $sqlFilter .= " AND y BETWEEN '".$itemyFromFilter."' AND '".$itemyToFilter."'"; 
    }
}
else
{
    if($itemyFromFilter != "")
    {
        $sqlFilter .= " AND y ".$itemyFilter." '".$itemyFromFilter."'";
    }
}

if($itemWeightFilter == "RANGE")
{
    if($itemWeightFromFilter != "" AND $itemWeightToFilter != "")
    {
        $sqlFilter .= " AND itemWeight BETWEEN '".$itemWeightFromFilter."' AND '".$itemWeightToFilter."'"; 
    }
}
else
{
    if($itemWeightFromFilter != "")
    {
        $sqlFilter .= " AND itemWeight ".$itemWeightFilter." '".$itemWeightFromFilter."'";
    }
}

if($itemLengthFilter == "RANGE")
{
    if($itemLengthFromFilter != "" AND $itemLengthToFilter != "")
    {
        $sqlFilter .= " AND itemLength BETWEEN '".$itemLengthFromFilter."' AND '".$itemLengthToFilter."'"; 
    }
}
else
{
    if($itemLengthFromFilter != "")
    {
        $sqlFilter .= " AND itemLength ".$itemLengthFilter." '".$itemLengthFromFilter."'";
    }
}

if($itemWidthFilter == "RANGE")
{
    if($itemWidthFromFilter != "" AND $itemWidthToFilter != "")
    {
        $sqlFilter .= " AND itemWidth BETWEEN '".$itemWidthFromFilter."' AND '".$itemWidthToFilter."'"; 
    }
}
else
{
    if($itemWidthFromFilter != "")
    {
        $sqlFilter .= " AND itemWidth ".$itemWidthFilter." '".$itemWidthFromFilter."'";
    }
}

if($itemHeightFilter == "RANGE")
{
    if($itemHeightFromFilter != "" AND $itemHeightToFilter != "")
    {
        $sqlFilter .= " AND itemHeight BETWEEN '".$itemHeightFromFilter."' AND '".$itemHeightToFilter."'"; 
    }
}
else
{
    if($itemHeightFromFilter != "")
    {
        $sqlFilter .= " AND itemHeight ".$itemHeightFilter." '".$itemHeightFromFilter."'";
    }
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
		if($_SESSION['idNumber']==true)
		{
			$sqlFilter .= " AND partId IN (".implode(", ",$partIds).") AND partId = (SELECT partId FROM cadcam_partprocess WHERE cadcam_partprocess.partId = cadcam_parts.partId ".$added." LIMIT 1)" ;
		}
		else
		{
			$sqlFilter .= " AND partId IN (SELECT DISTINCT partId FROM cadcam_partprocess WHERE partId IN (".implode(", ",$partIds).") ".$added.")" ;
		}
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

$sqlFilter .= $added2;

$totalRecords = 0;

$colorFirstPO = $colorLastPO = $colorCountPO = $colorQuantityPO = $colorItemX = $colorItemY = $colorItemWeight = $colorItemHeight = $colorItemWidth = $colorItemLength = 'w3-green';
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
		$sqlFilter .= " ORDER BY ".$poCountField." ".$order;
		$colorCountPO = 'w3-pink';
	
		$sortCountPOClass = "<i class='fa fa-sort-amount-desc'></i>&emsp;";
		if($order == 'ASC') $sortCountPOClass = "<i class='fa fa-sort-amount-asc'></i>&emsp;";
	}

	if($dataValue == 'poQuantity')
	{
		$sqlFilter .= " ORDER BY ".$poQuantityField." ".$order;
		$colorQuantityPO = 'w3-pink';
	
		$sortQuantityPOClass = "<i class='fa fa-sort-amount-desc'></i>&emsp;";
		if($order == 'ASC') $sortQuantityPOClass = "<i class='fa fa-sort-amount-asc'></i>&emsp;";
	}

	if($dataValue == 'itemX')
	{
		$sqlFilter .= " ORDER BY x ".$order;
		$colorItemX = 'w3-pink';
	
		$sortItemXClass = "<i class='fa fa-sort-amount-desc'></i>&emsp;";
		if($order == 'ASC') $sortItemXClass = "<i class='fa fa-sort-amount-asc'></i>&emsp;";
	}

	if($dataValue == 'itemY')
	{
		$sqlFilter .= " ORDER BY y ".$order;
		$colorItemY = 'w3-pink';
	
		$sortItemYClass = "<i class='fa fa-sort-amount-desc'></i>&emsp;";
		if($order == 'ASC') $sortItemYClass = "<i class='fa fa-sort-amount-asc'></i>&emsp;";
	}

	if($dataValue == 'itemHeight')
	{
		$sqlFilter .= " ORDER BY itemHeight ".$order;
		$colorItemHeight = 'w3-pink';
	
		$sortItemHeightClass = "<i class='fa fa-sort-amount-desc'></i>&emsp;";
		if($order == 'ASC') $sortItemHeightClass = "<i class='fa fa-sort-amount-asc'></i>&emsp;";
	}

	if($dataValue == 'itemWidth')
	{
		$sqlFilter .= " ORDER BY itemWidth ".$order;
		$colorItemWidth = 'w3-pink';
	
		$sortItemWidthClass = "<i class='fa fa-sort-amount-desc'></i>&emsp;";
		if($order == 'ASC') $sortItemWidthClass = "<i class='fa fa-sort-amount-asc'></i>&emsp;";
	}

	if($dataValue == 'itemLength')
	{
		$sqlFilter .= " ORDER BY itemLength ".$order;
		$colorItemLength = 'w3-pink';
	
		$sortItemLengthClass = "<i class='fa fa-sort-amount-desc'></i>&emsp;";
		if($order == 'ASC') $sortItemLengthClass = "<i class='fa fa-sort-amount-asc'></i>&emsp;";
	}

	if($dataValue == 'itemWeight')
	{
		$sqlFilter .= " ORDER BY itemWeight ".$order;
		$colorItemWeight = 'w3-pink';
	
		$sortItemWeightClass = "<i class='fa fa-sort-amount-desc'></i>&emsp;";
		if($order == 'ASC') $sortItemWeightClass = "<i class='fa fa-sort-amount-asc'></i>&emsp;";
	}

}

$fields = ($additionalField!='') ? '*,'.$additionalField : '*';

$sql = "SELECT $fields FROM cadcam_parts ".$sqlFilter; //if($_SESSION['idNumber'] == "0346") echo $sql;
$sqlData = $sql;
//$sqlFilter = trim(preg_replace('/\s+/', ' ', $sqlFilter));
$query = $db->query($sql);

if($query AND $query->num_rows > 0)
{
	$totalRecords = $query->num_rows;
}

//TAMANG

	if(isset($_POST['myPartsComment']))
	{
		// $sql="SELECT partId FROM cadcam_parts WHERE partId = '".$_POST['checkBox']."'";
		// $queryPC = $db->query($sql);
		// $resultPC = $queryPC->fetch_assoc();

		// $partId = $resultPC['partId'];
		echo $partId = $_POST['myPartsComment'];
	}


//


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
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="myPartsForm"></form>
<input type="hidden" name='myPartsComment[]' id ='myPartsComment' form="myPartsForm">    
<div class="container-fluid">
	<div class="row w3-padding-top">
		<div class="col-md-8">
			<div class='w3-right'>
				<h3>
					<?php
						if($process!='') echo "(".displayText('L59', 'utf8', 0, 0, 1)." : ".implode(",",$process).") : ".$totalRecords;
					?>
				</h3>
			</div>
		</div>
		<div class="col-md-4">
			<!--
			<a target = '_blank' href="https://210.172.85.77/dwginf30/dwg/index.asp?uid=3DUP009&uadrs=3Dnagano@arktech.co.jp%22%3E" ><button class='w3-btn w3-tiny w3-round w3-green' style='width:200px'><i class='fa fa-hand-pointer-o'></i>&emsp;<b>CLICK me first (KCO Webware)</b></button></a>
			-->
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
					if(!in_array($key, ['showOpenPO', 'lastValue', 'dataValue', 'order', 'materialType', 'statusPart', 'partTypeFlag', 'process', 'customerId']))
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
				<!-- TAMANG -->
				
				
				<button type="button" class='w3-btn w3-tiny w3-round w3-purple' name="partsComment" id="partsComment" onclick="partsComment()"><i class='fa fa-plus'></i>&emsp;<b>PARTS COMMENT</i></button>
				<!-- TAMANG -->
				<button class='w3-btn w3-tiny w3-round w3-green' onclick="location.href='';"><i class='fa fa-refresh'></i>&emsp;<b><?php echo displayText('L436', 'utf8', 0, 0, 1);?></b></button>
			</div>
		</div>
	</div>
	<div class="row w3-padding-top">
		<div class='col-md-12'>
			<label><?php echo displayText('L41'); ?> : <span><?php echo $totalRecords; ?></span></label>
			<div class='w3-right'>
				<?php
					if($_SESSION['idNumber']==true)
					{
						$currFilter = new PartsMasterCurrentFilter($_POST);
						$changeKeyNameArray = [
							'customerId' => 'L24',
							'partName' => 'L30',
							'partNumber' => 'L28',
							'partTypeFlag' => 'L111',
							'partl' => 'L74',
							'partw' => 'L75',
							'parth' => 'L76',
							'materialType' => 'L566',
							'metalThickness' => 'L184',
							'statusPart' => 'L172',
							'firstPODate' => 'L4162',
							'lastPODate' => 'L4163',
							// 'process' => 'L59',
							'processGroup' => 'L61',
							'partsComment' => 'L636',
							'itemxFromFilter' => 'L70',
							'itemyFromFilter' => 'L71',
							'itemWeightFromFilter' => 'L72',
							'itemLengthFromFilter' => 'L74',
							'itemWidthFromFilter' => 'L75',
							'itemHeightFromFilter' => 'L76',
							'showOpenPO' => 'L4569',
						];
						foreach($changeKeyNameArray as $key=>$displayId)
						{
							$currFilter->changeKeyName($key,displayText($displayId, 'utf8', 0, 0, 1));
						}

						$currFilter->changedKeyOnly()->displayData();
					}				
				?>	
			</div>		
			<table id='mainTableId' style='' class="table table-bordered table-striped table-condensed" data-counter='-1' data-detail-type='left'>
				<thead class='w3-indigo' style='text-transform:uppercase;'>
					<th class='w3-center' style='vertical-align:middle;'></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L843');?></th>
<!--				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L52');?></th>-->
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L24');?></th>                    
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L28');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L226');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L3446');?></th>
					<!-- <th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L396');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L397');?></th> -->
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L30');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L111');?></th>
					<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L184');?></th>
					<!-- <th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L398');?></th> -->
					<!-- <th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L399');?></th> -->
					<th class='w3-center <?php echo $colorItemX; ?> itemXClass '  data-id='itemX' style='cursor:pointer; vertical-align:middle;'><?php echo $sortItemXClass; ?><?php echo displayText('L398');?></th>
					<th class='w3-center <?php echo $colorItemY; ?> itemYClass '  data-id='itemY' style='cursor:pointer; vertical-align:middle;'><?php echo $sortItemYClass; ?><?php echo displayText('L399');?></th>
					<!-- <th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L76');?></th> -->
					<!-- <th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L75');?></th> -->
					<!-- <th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L74');?></th> -->
					<th class='w3-center <?php echo $colorItemHeight; ?> itemHeightClass '  data-id='itemHeight' style='cursor:pointer; vertical-align:middle;'><?php echo $sortItemHeightClass; ?><?php echo displayText('L76');?></th>
					<th class='w3-center <?php echo $colorItemWidth; ?> itemWidthClass '  data-id='itemWidth' style='cursor:pointer; vertical-align:middle;'><?php echo $sortItemWidthClass; ?><?php echo displayText('L75');?></th>
					<th class='w3-center <?php echo $colorItemLength; ?> itemLengthClass '  data-id='itemLength' style='cursor:pointer; vertical-align:middle;'><?php echo $sortItemLengthClass; ?><?php echo displayText('L74');?></th>
					<!-- <th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L72');?></th> -->
					<th class='w3-center <?php echo $colorItemWeight; ?> itemWeightClass '  data-id='itemWeight' style='cursor:pointer; vertical-align:middle;'><?php echo $sortItemWeightClass; ?><?php echo displayText('L72');?></th>
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
<!--				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L188');?></th>-->
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
<!--					<th class='w3-center' style='vertical-align:middle;'></th>-->
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
<!--					<th class='w3-center' style='vertical-align:middle;'></th>-->
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

<div id='modal-izi-add'><span class='izimodal-content-add'></span></div> 
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
	
	$(document).ready(function() 
	{
		$(document).on('click','.drawingViewerClass',function(e){
			const lotNumber = $(this).data('lotNumber');
			const lotPartid = $(this).data('lotPartid');
			const drawingViewerClass = document.querySelectorAll('.drawingViewerClass');
			drawingViewerClass.forEach(drawing => {
				drawing.dataset.clicked='no'
			})
			e.target.dataset.clicked='yes';
			window.open(`/<?php echo v;?>/20 Document Management System/raymond_drawingViewer.php?lotNumber=${lotNumber}&partId=${lotPartid}&dwg=2`,'cc','left=50,screenX=700,screenY=20,resizable,scrollbars,status,width=1000,height=700')
		});
	});
</script>
<script>
	$(function(){
        //~ var sqlFilter = "<?php echo str_replace("\n"," ",$sqlFilter); ?>";
        var width = screen.width;
        var cond ="";        console.log(width);
         if (width >= 1281)         //~ if (width >= 1600)
            {
            	cond=false;
            }
            else
            {
            	cond=true;
            }        //~ cond=true;    
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
                    $(".mainTableId-error").html("");                    //$("#mainTableId").append('<tbody class="mainTableId-error"><tr><th colspan="3">No data found in the server'+totalRecords+'</th></tr></tbody>');
                    $("#mainTableId").append('<tbody class="mainTableId-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#mainTableId_processing").css("display","none");
                    console.log(data);
                }
            },
			
			"createdRow": function( row, data, index ) {
				var partId = data[0];
				//ROSEMIE
				$(row).addClass("w3-hover-dark-grey rowClass");
                $(row).attr('id', partId);
                $(row).click(function(){
                    $(".rowClass").removeClass("w3-deep-orange");
                    $(this).addClass("w3-deep-orange");
                });
				//ROSEMIE
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
			// "deferLoading": 57,
            scrollY    	: 555,
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

        $('.firstPOdateClass, .lastPOdateClass, .poCountClass, .poQuantityClass, .itemXClass, .itemYClass, .itemWeightClass, .itemHeightClass, .itemWidthClass, .itemLengthClass').click(function(){
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
<script>
	function myPartsComment(partId)
	{
		
		var myParts = document.getElementById('myPartsComment');
		myParts.value += partId+" ";
	}
	
</script>
<script>
	function partsComment()
    {
		
		var partId = document.getElementById('myPartsComment');
		
		//	alert(partId.value);

        $("#modal-izi-add").iziModal({
            title                   : '<i class="fa fa-plus"></i>&nbsp;ADD',
            headerColor             : '#1F4788',
            subtitle                : '<b><?php //echo strtoupper(date('F d, Y'));?></b>',
            width                   : 400,
            fullscreen              : false,
            transitionIn            : 'comingIn',
            transitionOut           : 'comingOut',
            padding                 : 20,
            radius                  : 0,
            top                     : 100,
            restoreDefaultContent   : true,
            closeOnEscape           : true,
            closeButton             : true,
            overlayClose            : false,
            onOpening               : function(modal){
                                        modal.startLoading();
                                        // alert(assignedTo);
                                        $.ajax({
                                            url         : 'jhon_addPartsComment.php',
                                            type        : 'POST',
                                            data        : {
                                                
                                                	partId           : partId.value
        
                                            },
                                            success     : function(data){
                                                            $( ".izimodal-content-add" ).html(data);
                                                            modal.stopLoading();
                                            }
                                        });
                                    },
            onClosed                : function(modal){
                                        $("#modal-izi-add").iziModal("destroy");
                        } 
        });

        $("#modal-izi-add").iziModal("open");
    }
//TAMANG UPDATE PARTS COMMENT END HERE
</script>