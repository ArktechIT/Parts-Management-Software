<?php 
include('../Common Data/Templates/mysqliConnection.php'); 
require('../Common Data/Libraries/PHP/FPDF/fpdf.php');

// ---------------------------------- Fetch Data -------------------------------
$partId = $_GET['partId'];
$identifier = $_GET['identifier'];
if($identifier!=2)
{
	$partNumber = $partName = $revisionId = $customerId = $materialSpecId = $pvc = $treatmentId = '';
	$sql = "SELECT partNumber, partName, revisionId, customerId, materialSpecId, PVC, treatmentId FROM cadcam_parts WHERE partId = ".$partId." ";
	$getPart = $db->query($sql);
	if($getPart->num_rows > 0)
	{
		$getPartResult = $getPart->fetch_array();
		$partNumber = $getPartResult['partNumber'];
		$partName = $getPartResult['partName'];
		$revisionId = $getPartResult['revisionId'];
		$customerId = $getPartResult['customerId'];
		$materialSpecId = $getPartResult['materialSpecId'];
		$pvc = $getPartResult['PVC'];
		$treatmentId = $getPartResult['treatmentId'];
	}
	if($pvc == 1)
	{
		$pvc = 'PVC';
	}

	$treatmentName = '';
	$sql = "SELECT treatmentName FROM cadcam_treatmentprocess WHERE treatmentId = ".$treatmentId." ";
	$getTreatmentName = $db->query($sql);
	$getTreatmentNameResult = $getTreatmentName->fetch_array();
	$treatmentName = $getTreatmentNameResult['treatmentName'];

	$customerName = '';
	$sql = "SELECT customerName FROM sales_customer WHERE customerId = ".$customerId." ";
	$getCustomerName = $db->query($sql);
	$getCustomerNameResult = $getCustomerName->fetch_array();
	$customerName = $getCustomerNameResult['customerName'];

	//;cadcam_materialspecs;
	$metalType = $metalThickness = $metalLength = $metalWidth = $materialName = '';
	$sql = "SELECT materialTypeId, metalType, metalThickness, metalLength, metalWidth, materialName FROM cadcam_materialspecs WHERE materialSpecId = ".$materialSpecId." ";
	$getSpecs = $db->query($sql);
	$getSpecsResult = $getSpecs->fetch_array();
	$materialTypeId = $getSpecsResult['materialTypeId'];
	$metalType = $getSpecsResult['metalType'];
	$metalThickness = $getSpecsResult['metalThickness'];
	$metalLength = $getSpecsResult['metalLength'];
	$metalWidth = $getSpecsResult['metalWidth'];
	$materialName = $getSpecsResult['materialName'];

	$sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$materialTypeId." LIMIT 1";
	$queryMaterialType = $db->query($sql);
	if($queryMaterialType AND $queryMaterialType->num_rows > 0)
	{
		$resultMaterialType = $queryMaterialType->fetch_assoc();
		$metalType = $resultMaterialType['materialType'];
	}
	//;cadcam_materialspecs;
}
else
{
  $partNumber = $partName = $revisionId = $customerId = $materialSpecId = $pvc = $treatmentId = '';
	$sql = "SELECT accessoryNumber, accessoryName, revisionId FROM cadcam_accessories WHERE accessoryId = ".$partId." ";
	$getPart = $db->query($sql);
	if($getPart->num_rows > 0)
	{
		$getPartResult = $getPart->fetch_array();
		$partNumber = $getPartResult['accessoryNumber'];
		$partName = $getPartResult['accessoryName'];
		$revisionId = $getPartResult['revisionId'];
		//$customerId = $getPartResult['customerId'];
		//$materialSpecId = $getPartResult['materialSpecId'];
		//$pvc = $getPartResult['PVC'];
		//$treatmentId = $getPartResult['treatmentId'];
	}
}


$processCode = $processDetail = $setupTime = $cycleTime = '';
$sql = "SELECT processCode, processDetail, setupTime, cycleTime FROM cadcam_partprocess WHERE partId = ".$partId." AND patternId = ".$_GET['patternId']." ORDER BY processOrder ASC ";
$getPartProcess = $db->query($sql);

$pdf = new FPDF('P','mm','A4');
$pdf->SetLeftMargin(8);
$pdf->SetTopMargin(19.5);
$pdf->SetAutoPageBreak(on);
$pdf->AddPage();
$pdf->SetFont('Arial');
$pdf->SetFontSize(9);

$pdf->Image('../Common Data/Templates/images/arkLogo.gif',7,5,38);

$pdf->Cell(50,4,'Date: '.date('m/d/Y'),0,0);
$pdf->SetFont('Arial','B','15');
$pdf->Cell(93,4,'Process Record Sheet',0,0,'C');
$pdf->SetFont('Arial','','10');
$pdf->Cell(50,4,'Lot Number: ',0,0);
$pdf->Ln();

$pdf->SetFont('Arial','','10');
$pdf->Ln(0.5);

// ---------------------------------- Line Separator --------------------------------
$pdf->Cell(193,0,'',1,0);
$pdf->Ln(0.8);

// ------------------------ Customer --------------------------
$pdf->Cell(10,4,'',0,0);
$pdf->Cell(25,4,'Customer',0,0);
$pdf->Cell(3,4,':',0,0);
$pdf->SetFont('Arial','B','10');
$pdf->Cell(90,4,$customerName,0,0);

$pdf->SetFont('Arial','','10');
$pdf->Cell(25,4,'PO Number',0,0);
$pdf->Cell(3,4,':',0,0);
$pdf->Cell(37,4,'',0,0);
$pdf->Ln();

// ------------------------ Part Number --------------------------
$pdf->Cell(10,4,'',0,0);
$pdf->Cell(25,4,'Part Number',0,0);
$pdf->Cell(3,4,':',0,0);
$pdf->SetFont('Arial','B','10');
$pdf->Cell(90,4,$partNumber,0,0);

$pdf->SetFont('Arial','','10');
$pdf->Cell(25,4,'Order Number',0,0);
$pdf->Cell(3,4,':',0,0);
$pdf->Cell(37,4,'',0,0);
$pdf->Ln();

// ------------------------ Part Name --------------------------
$pdf->Cell(10,4,'',0,0);
$pdf->Cell(25,4,'Part Name',0,0);
$pdf->Cell(3,4,':',0,0);
$pdf->SetFont('Arial','B','10');
$pdf->Cell(90,4,$partName,0,0);

$pdf->SetFont('Arial','','10');
$pdf->Cell(25,4,'PO Quantity',0,0);
$pdf->Cell(3,4,':',0,0);
$pdf->Cell(37,4,'',0,0);
$pdf->Ln();

// ------------------------ Revision --------------------------
$pdf->Cell(10,4,'',0,0);
$pdf->Cell(25,4,'Revision',0,0);
$pdf->Cell(3,4,':',0,0);
$pdf->SetFont('Arial','B','10');
$pdf->Cell(90,4,$revisionId,0,0);

$pdf->SetFont('Arial','','10');
$pdf->Cell(25,4,'Working Quantity',0,0);
$pdf->Cell(3,4,':',0,0);
$pdf->Cell(37,4,'',0,0);
$pdf->Ln(4.5);

// ---------------------------------- Line Separator --------------------------------
$pdf->Cell(193,0,'',1,0);
$pdf->Ln(0.8);

// ----------------------------------- Print Data --------------------------------
while($getPartProcessResult = $getPartProcess->fetch_array() and $identifier!=2)
{
	
	
	$processCode = $getPartProcessResult['processCode'];
	$processDetail = $getPartProcessResult['processDetail'];
	$setupTime = $getPartProcessResult['setupTime'];
	$cycleTime = $getPartProcessResult['cycleTime'];
	
	$sql = "SELECT remarks FROM cadcam_partprocessnote WHERE partId = ".$partId." AND patternId = ".$_GET['patternId']." AND processCode = ".$processCode;
	//echo $sql;
	$processDetailQuery = $db->query($sql);
	if($processDetailQuery->num_rows > 0)
	{
		$processDetailQueryResult = $processDetailQuery->fetch_array();
		$processDetail = $processDetailQueryResult['remarks'];
	}
	
	$processName = '';
	$sql = "SELECT processName FROM cadcam_process WHERE processCode = ".$processCode." ";
	$getProcessName = $db->query($sql);
	$getProcessNameResult = $getProcessName->fetch_array();
	$processName = $getProcessNameResult['processName'];
	
	$pdf->SetFont('Arial','B','10');
	
	// ----------------------------------- Insert Material Withdrawal on top of TPP ----------------------------------
	if($processCode == 86)
	{
		$pdf->Cell(13,4,++$i,0,0);
		$pdf->Cell(180,4,'Material Withdrawal',0,0);
		$pdf->Ln();
		
		$pdf->Cell(193,4,'',0,0);
		$pdf->Ln();
		
		$pdf->SetFont('Arial','','10');
		$pdf->Cell(18,4,'',0,0);
		$pdf->Cell(25,4,'Material Detail',0,0);
		$pdf->Cell(3,4,':',0,0);
		$pdf->SetFont('Arial','B','10');
		$pdf->Cell(50,4,$materialName,0,0);
		$pdf->Ln();
		
		$pdf->SetFont('Arial','','10');
		$pdf->Cell(18,4,'',0,0);
		$pdf->Cell(25,4,'Material Specs',0,0);
		$pdf->Cell(3,4,':',0,0);
		$pdf->SetFont('Arial','B','10');
		$pdf->Cell(50,4,$metalType.' t'.$metalThickness.' X '.$metalLength.' X '.$metalWidth.' '.$treatmentName.' '.$pvc,0,0);
		$pdf->Ln();
		
		// ---------------------------------- Line Separator --------------------------------
		$pdf->Cell(193,0,'',1,0);
		$pdf->Ln(0.8);
		$pdf->SetFont('Arial','B','10');
	}
	
	$pdf->Cell(13,4,++$i,0,0);
	$pdf->Cell(180,4,$processName,0,0);
	$pdf->Ln();
	
	$pdf->Cell(138,4,'',0,0);
	$pdf->Cell(12,4,'','LTR',0);
	$pdf->Ln();
	
	// ---------------------------- Incharge -----------------------------
	$pdf->SetFont('Arial','','10');
	$pdf->Cell(18,4,'',0,0);
	$pdf->Cell(25,4,'Incharge',0,0);
	$pdf->Cell(3,4,':',0,0);
	$pdf->Cell(30,4,'',0,0);
	
	// ---------------------------- Actual Date -----------------------------
	$pdf->Cell(22,4,'Actual Date: ',0,0);
	$pdf->Cell(30,4,'',0,0);
	
	// ---------------------------- Quantity -----------------------------
	$pdf->Cell(10,4,'QTY: ',0,0);
	$pdf->Cell(12,4,'','LRB',0);
	$pdf->Ln();
	
	$pdf->Cell(18,4,'',0,0);
	$pdf->Cell(25,4,'Details',0,0);
	$pdf->Cell(3,4,':',0,0);
	$pdf->SetFont('Arial','B','10');
	$pdf->Cell(100,4,$processDetail,0,0);
	$pdf->Ln();
	
	// ---------------------------------- Line Separator --------------------------------
	$pdf->Cell(193,0,'',1,0);
	$pdf->Ln(0.8);
}
if($identifier==2)
{
	$pdf->Cell(13,4,"1",0,0);
	$pdf->Cell(180,4,"Item Withdrawal",0,0);
	$pdf->Ln();
	
	$pdf->Cell(138,4,'',0,0);
	$pdf->Cell(12,4,'','LTR',0);
	$pdf->Ln();
	
	// ---------------------------- Incharge -----------------------------
	$pdf->SetFont('Arial','','10');
	$pdf->Cell(18,4,'',0,0);
	$pdf->Cell(25,4,'Incharge',0,0);
	$pdf->Cell(3,4,':',0,0);
	$pdf->Cell(30,4,'',0,0);
	
	// ---------------------------- Actual Date -----------------------------
	$pdf->Cell(22,4,'Actual Date: ',0,0);
	$pdf->Cell(30,4,'',0,0);
	
	// ---------------------------- Quantity -----------------------------
	$pdf->Cell(10,4,'QTY: ',0,0);
	$pdf->Cell(12,4,'','LRB',0);
	$pdf->Ln();
	
	$pdf->Cell(18,4,'',0,0);
	$pdf->Cell(25,4,'Details',0,0);
	$pdf->Cell(3,4,':',0,0);
	$pdf->SetFont('Arial','B','10');
	$pdf->Cell(100,4,"",0,0);
	$pdf->Ln();
	
	// ---------------------------------- Line Separator --------------------------------
	$pdf->Cell(193,0,'',1,0);
	$pdf->Ln(0.8);
}

$pdf->Output();
?>
