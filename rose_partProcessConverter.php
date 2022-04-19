<?php 
include('../Common Data/PHP Modules/mysqliConnection.php'); 
session_start();
require('../Common Data/Libraries/PHP/FPDF/fpdf.php'); 
ini_set('display_errors','on');

$partId = $_GET['product'];
$patternId = $_GET['patternId'];

$processCodeArray = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,31,32,33,34,46,47,48,261,262,283);
//--------------------------------------------------------------------------------
$pdf=new FPDF('P','mm','A4');
$spi = 0;
$ai = 0;

$pdf->AddPage();
$pdf->SetTopMargin(10);
$pdf->AliasNbPages();
$pdf->Image('../Common Data/Templates/images/arkLogo.jpg',9,1,33);
//--------------------------------------------------------------------------------

$sql = "SELECT partNumber, partName, revisionId, customerId, materialSpecId, materialSpecDetail, treatmentId FROM cadcam_parts where partId = ".$partId;	
$partsQuery = $db->query($sql);

if($partsQuery->num_rows > 0)
{
	$partsQueryResult = $partsQuery->fetch_array();
	
	$partNumber = $partsQueryResult['partNumber'];
	$partName = $partsQueryResult['partName'];
	$revision = $partsQueryResult['revisionId'];
			
	// ------------------------------------ Retrieve Customer Name ---------------------------------------
	$sql = "SELECT customerName FROM sales_customer where customerId = ".$partsQueryResult['customerId'];
	$customerQuery = $db->query($sql);
	if($customerQuery->num_rows > 0) 
	{
		$customerQueryResult = $customerQuery->fetch_array();
		$customerName = $customerQueryResult['customerName']; 
	}
	// ------------------------------------ End Of Retrieve Customer Name ----------------------------------		
	
	// --------------------------------------- Retrieve Material Specification -------------------------------------------------
	//~ $sql = "SELECT metalType, metalThickness from cadcam_materialspecs where materialSpecId=".$partsQueryResult['materialSpecId'];
	//~ $materialSpecificationQuery = $db->query($sql);
	//~ if($materialSpecificationQuery->num_rows > 0) 
	//~ {		
		//~ $materialSpecificationQueryResult = $materialSpecificationQuery->fetch_array();
		//~ $metalType = $materialSpecificationQueryResult['metalType'];
		//~ $metalThickness = $materialSpecificationQueryResult['metalThickness'];		
	//~ }
	
	//;cadcam_materialspecs;
	$metalType = '';
	$sql = "SELECT materialTypeId, metalThickness from cadcam_materialspecs where materialSpecId=".$partsQueryResult['materialSpecId'];
	$materialSpecificationQuery = $db->query($sql);
	if($materialSpecificationQuery->num_rows > 0) 
	{		
		$materialSpecificationQueryResult = $materialSpecificationQuery->fetch_array();
		$materialTypeId = $materialSpecificationQueryResult['materialTypeId'];
		$metalThickness = $materialSpecificationQueryResult['metalThickness'];		
		
		$sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$materialTypeId." LIMIT 1";
		$queryMaterialType = $db->query($sql);
		if($queryMaterialType AND $queryMaterialType->num_rows > 0)
		{
			$resultMaterialType = $queryMaterialType->fetch_assoc();
			$metalType = $resultMaterialType['materialType'];
		}
	}
	//;cadcam_materialspecs;
	
		// ------------------------------- Format Material Specification -------------------------------------------
		$materialSpecification='';
		if($metalType!='')
		{ 
			$materialSpecification=$metalType; 
		}
		
			// ----------------------- Execute When Thickness Is Not 0 ------------------------------------------------
			if($metalThickness!='0.00')
			{ 
				if($materialSpecification!='')
				{
					$materialSpecification = $materialSpecification.' x '.$metalThickness; 
				}
				else 
				{
					$materialSpecification=$metalThickness;
				}
			}
			// ----------------------- End of Execute When Thickness Is Not 0 ------------------------------------------------
		
		// -------------------------------- End of Format Material Specification -------------------------------------

	$materialDetail = $partsQueryResult['materialSpecDetail'];
	// --------------------------------------- End of Retrieve Material Specification -------------------------------------------------
	
	// ---------------------------------------- Retrieve Treatment Process -----------------------------------------------------------
	$sql = "SELECT treatmentName FROM cadcam_treatmentprocess WHERE treatmentId = ".$partsQueryResult['treatmentId'];
	$treatmentQuery = $db->query($sql);
	$treatmentQueryResult = $treatmentQuery->fetch_array();
	$treatmentName = $treatmentQueryResult['treatmentName'];
	// ---------------------------------------- End of Retrieve Treatment Process -----------------------------------------------------------
	
	// ---------------------------------------- Retrieve Parts Developer ------------------------------------------------------------
	$sql = "SELECT cadUpdate, cadEmpId FROM sales_popending where partId = ".$partId;
	$pendingListQuery = $db->query($sql);		
	if($pendingListQuery->num_rows > 0) 
	{ 
		$pendingListQueryResult = $pendingListQuery->fetch_array();
		$pendingUpdateDate = $pendingListQueryResult['cadUpdate']; 
		$partsDeveloper = $pendingListQueryResult['cadEmpId']; 
	}
	// --------------------------------------- End Of Retrieve Parts Developer ------------------------------------------------------
}

// ------------------------------------------------------ Header -------------------------------------------------
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,5,"Date: ".date("m/d/Y"));
$pdf->Cell(80,5,"");
// ------------------------- Execute When Update Date Is Not 0000-00-00 ----------------------------------------
if($pendingListQuery->num_rows > 0 AND $pendingUpdateDate!="0000-00-00 00:00:00")
{
	$pdf->Cell(30,5,"Updated:".$pendingUpdateDate." ".$partsDeveloper);
}
// ------------------------- End Of Execute When Update Date Is Not 0000-00-00 ----------------------------------------

$pdf->Ln();
$pdf->Cell(30,5,"",0,0);
$pdf->SetFont('Arial','B',13);
$pdf->Cell(125,5,'Product Process',0,0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',10); $pdf->Cell(40,5,'',0,0,'C'); $pdf->Cell(35,5,'Customer:',1,0,'L'); $pdf->SetFont('Arial','',10); $pdf->Cell(90,5,$customerName,1,0,'L'); $pdf->Ln();
$pdf->SetFont('Arial','B',10); $pdf->Cell(40,5,'',0,0,'C'); $pdf->Cell(35,5,'Part Number:',1,0,'L'); $pdf->SetFont('Arial','',10); $pdf->Cell(90,5,$partNumber,1,0,'L'); $pdf->Ln();
$pdf->SetFont('Arial','B',10); $pdf->Cell(40,5,'',0,0,'C'); $pdf->Cell(35,5,'Part Name:',1,0,'L'); $pdf->SetFont('Arial','',10); $pdf->Cell(90,5,$partName,1,0,'L'); $pdf->Ln();
$pdf->SetFont('Arial','B',10); $pdf->Cell(40,5,'',0,0,'C'); $pdf->Cell(35,5,'Revision:',1,0,'L'); $pdf->SetFont('Arial','',10); $pdf->Cell(90,5,$revision,1,0,'L'); $pdf->Ln();
$pdf->SetFont('Arial','B',10); $pdf->Cell(40,5,'',0,0,'C'); $pdf->Cell(35,5,'Material:',1,0,'L'); $pdf->SetFont('Arial','',10); $pdf->Cell(90,5,$materialSpecification." ".$materialDetail,1,0,'L'); $pdf->Ln();
$pdf->SetFont('Arial','B',10); $pdf->Cell(40,5,'',0,0,'C'); $pdf->Cell(35,5,'Treatment:',1,0,'L'); $pdf->SetFont('Arial','',10); $pdf->Cell(90,5,$treatmentName,1,0,'L'); $pdf->Ln();
$pdf->SetFont('Arial','B',10); $pdf->Cell(40,5,'',0,0,'C'); $pdf->Cell(35,5,'',0,0,'L'); $pdf->SetFont('Arial','',10); $pdf->Cell(90,5,'',0,0,'L'); $pdf->Ln();

	$pdf->Cell(3,5,'',0,0,'C');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(8,5,"#",1,0,'C');		
	$pdf->Cell(95,5,'PROCESS',1,0,'C');	
	$pdf->Cell(85,5,'DETAIL',1,0,'C');

$pdf->Ln();

// ------------------------------------------- Retrieve And Display Part Process -------------------------------------------------------
$processOrder=1;
$sql = "SELECT * FROM cadcam_partprocess where partId = '".$partId."' AND patternId = ".$patternId." ORDER BY processOrder ASC";
$partProcessQuery = $db->query($sql);		
while($partProcessQueryResult = $partProcessQuery->fetch_array())
{
	$remarks = '';
	$sql = "SELECT remarks FROM cadcam_partprocessnote WHERE partId = ".$partId." AND processCode = ".$partProcessQueryResult['processCode']." ";
	$getRemarks = $db->query($sql);
	if($getRemarks->num_rows > 0)
	{
		$getRemarksResult = $getRemarks->fetch_array();
		$remarks = $getRemarksResult['remarks'];
	}
	
	// ----------------------- Execute When Process Is Bending -------------------------------------
	if(in_array($partProcessQueryResult['processCode'],$processCodeArray))
	{
		if($remarks == '')
		{
			if($partProcessQueryResult['dataOne'] != '')
			{
				$remarks .= "V-Size=".$partProcessQueryResult['dataOne']."; ";
			}
			
			if($partProcessQueryResult['dataTwo'] != '')
			{
				$remarks .= "Punch-R=".$partProcessQueryResult['dataTwo']."; ";
			}
			
			if($partProcessQueryResult['dataThree'] != '')
			{
				$remarks .= "BD=".$partProcessQueryResult['dataThree'];
			}
		}
		else
		{
			if($partProcessQueryResult['dataOne'] != '')
			{
				$remarks .= "; V-Size=".$partProcessQueryResult['dataOne'];
			}
			
			if($partProcessQueryResult['dataTwo'] != '')
			{
				$remarks .= "; Punch-R=".$partProcessQueryResult['dataTwo'];
			}
			
			if($partProcessQueryResult['dataThree'] != '')
			{
				$remarks .= "; BD=".$partProcessQueryResult['dataThree'];
			}
		}
	}
	// ----------------------- End Of Execute When Process Is Bending -------------------------------------
	
	$sql = "SELECT processName from cadcam_process where processCode = ".$partProcessQueryResult['processCode'];
	$processNameQuery = $db->query($sql);		
	while($processNameQueryResult = $processNameQuery->fetch_array())
	{ 
		// ----------------------------------- Execute When Process Is Option Paint, Print Paint and Print ---------------------------------------------
		if($partProcessQueryResult['processCode']==255 or $partProcessQueryResult['processCode']==258 or $partProcessQueryResult['processCode']==259)
		{			
			if($partProcessQueryResult['processCode']==255)
			{
				$processcodez[0]=198;
				$processcodez[1]=200;
				$processcodez[2]=149;
				$processcodez[3]=218;
				$processcodez[4]=204;
				$processcodez[5]=205;
			}
			else if($partProcessQueryResult['processCode']==258)
			{
				$processcodez[0]=219;
				$processcodez[1]=160;
				$processcodez[2]=216;
				$processcodez[3]=220;
			}
			else if($partProcessQueryResult['processCode']==259)
			{
				$processcodez[0]=198;
				$processcodez[1]=200;
				$processcodez[2]=149;
				$processcodez[3]=218;
				$processcodez[4]=204;
				$processcodez[5]=205;
				$processcodez[6]=219;
				$processcodez[7]=160;
				$processcodez[8]=216;
				$processcodez[9]=220;
			}
					
			
			for($u=0;$u<count($processcodez);$u++)
			{
				$sql = "SELECT processName from cadcam_process where processCode=".$processcodez[$u];
				$optionProcessNameQuery = $db->query($sql);		
				$optionProcessNameQueryResult = $optionProcessNameQuery->fetch_array();
				
				$pdf->Cell(3,5,'',0,0,'C');
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(8,5,$processOrder.". ",1);		
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(95,5,$optionProcessNameQueryResult['processName'],1);
				$pdf->SetFont('Arial','I',8);
				$pdf->Cell(85,5,'',1);					
				$pdf->Ln();	
				$processcodez[$u]='';
				$processOrder++;
			}
		}
		// ----------------------------------- End Of Execute When Process Is Option Paint, Print Paint and Print ---------------------------------------------
		else
		{
			$pdf->Cell(3,5,'',0,0,'C');
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(8,5,$processOrder.". ",1);		
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(95,5,$processNameQueryResult['processName'],1);				
			$pdf->SetFont('Arial','I',8);				
			$pdf->Cell(85,5,$remarks,1);				
			$pdf->Ln();	
			$processOrder++;
		}
	}
}	
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

// *********************************** Anthony ****************************************
// *********************************** Subparts ****************************************
$pdf->SetFont('Arial','',10);
$sql = "SELECT childId, quantity FROM cadcam_subparts WHERE parentId = ".$partId." AND identifier = 1 ";
$getSubParts = $db->query($sql);
if($getSubParts->num_rows > 0)
{
	$pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(3,5,"",0,0,'R');
	$pdf->Cell(188,5,"Subparts",1,0,'C');
	$pdf->Ln();

	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(3,5,"",0,0,'R');
	$pdf->Cell(8,5,"#",1,0,'C');
	$pdf->Cell(65,5,"Part Number",1,0,'C');
	$pdf->Cell(65,5,"Revision",1,0,'C');
	$pdf->Cell(50,5,"Quantity",1,0,'C');
	$pdf->Ln();
	
	while($getSubPartsResult = $getSubParts->fetch_array())
	{
		$partNumber = $revisionId = '';
		$sql = "SELECT partNumber, revisionId FROM cadcam_parts WHERE partId = ".$getSubPartsResult['childId']." ";
		$getParts = $db->query($sql);
		if($getParts->num_rows > 0)
		{
			$getPartsResult = $getParts->fetch_array();
			$partNumber = $getPartsResult['partNumber'];
			$revisionId = $getPartsResult['revisionId'];
		}
		
		$pdf->Cell(3,5,"",0,0,'R');
		$pdf->Cell(8,5,++$spi,1,0,'C');
		$pdf->Cell(65,5,$partNumber,1,0,'C');
		$pdf->Cell(65,5,$revisionId,1,0,'C');
		$pdf->Cell(50,5,$getSubPartsResult['quantity'],1,0,'C');
		$pdf->Ln();
	}
	$spi = 0;
}
// *********************************** End of Subparts ****************************************

// *********************************** Accessory ****************************************
$pdf->SetFont('Arial','',10);
$sql = "SELECT childId, quantity FROM cadcam_subparts WHERE parentId = ".$partId." AND identifier = 2 ";
$getChildId = $db->query($sql);
if($getChildId->num_rows > 0)
{
	$pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(3,5,"",0,0,'R');
	$pdf->Cell(188,5,"Accessories",1,0,'C');
	$pdf->Ln();

	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(3,5,"",0,0,'R');
	$pdf->Cell(8,5,"#",1,0,'C');
	$pdf->Cell(65,5,"Accessory Number",1,0,'C');
	$pdf->Cell(65,5,"Accessory Name",1,0,'C');
	$pdf->Cell(25,5,"Rev",1,0,'C');
	$pdf->Cell(25,5,"Quantity",1,0,'C');
	$pdf->Ln();
	
	while($getChildIdResult = $getChildId->fetch_array())
	{
		$accessoryNumber = $accessoryName = '';
		$sql = "SELECT accessoryNumber, accessoryName, revisionId FROM cadcam_accessories WHERE accessoryId = ".$getChildIdResult['childId']."";
		$getAccessory = $db->query($sql);
		if($getAccessory->num_rows > 0)
		{
			$getAccessoryResult = $getAccessory->fetch_array();
			$accessoryNumber = $getAccessoryResult['accessoryNumber'];
			$accessoryName = $getAccessoryResult['accessoryName'];
			
			$accessoryRevision = $getAccessoryResult['revisionId'];
			if($accessoryRevision == "")
			{
				$accessoryRevision = "N/A";
			}
		}
		
		$pdf->Cell(3,5,"",0,0,'R');
		$pdf->Cell(8,5,++$ai,1,0,'C');
		$pdf->Cell(65,5,$accessoryNumber,1,0,'C');
		$pdf->Cell(65,5,$accessoryName,1,0,'C');
		$pdf->Cell(25,5,$accessoryRevision,1,0,'C');
		$pdf->Cell(25,5,$getChildIdResult['quantity'],1,0,'C');
		$pdf->Ln();
	}
	$ai = 0;
}
// *********************************** End of Accessory ****************************************

// ----------------------------------- Ace Sandoval : Customer Specification -------------------------------
$pdf->SetFont('Arial','',10);
$sql = "SELECT detailId, detailFlag FROM engineering_partstandard WHERE partId = ".$partId;
$partStandardQuery = $db->query($sql);
if($partStandardQuery->num_rows > 0)
{
	$pdf->Ln();
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(3,5,"",0,0,'R');	
	$pdf->Cell(188,5,"Note",1,0,'C');
	$pdf->Ln();

	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(3,5,"",0,0,'R');
	$pdf->Cell(8,5,"#",1,0,'C');
	$pdf->Cell(50,5,"Specification Number",1,0,'C');
	$pdf->Cell(130,5,"Details",1,0,'C');	
	$pdf->Ln();
	
	while($partStandardQueryResult = $partStandardQuery->fetch_array())
	{		
		$specificationDetailNumber = $speficationNumber = $speficiationDetail = '';
		$sql = "SELECT specificationId, detailNumber, detailText FROM engineering_specificationdetail WHERE detailId = ".$partStandardQueryResult['detailId']." ";
		$specificationDetailQuery = $db->query($sql);
		if($specificationDetailQuery->num_rows > 0)
		{		
			$specificationDetailQueryResult = $specificationDetailQuery->fetch_array();
			$specificationDetailNumber = $specificationDetailQueryResult['detailNumber'];
			$speficiationDetail = $specificationDetailQueryResult['detailText'];
			$accessoryRevision = $specificationDetailQueryResult['revisionId'];
			
			$sql = "SELECT specificationNumber FROM engineering_specifications WHERE specificationId = ".$specificationDetailQueryResult['specificationId']." ";
			$specificationQuery = $db->query($sql);
			if($specificationQuery->num_rows > 0)
			{
				$specificationQueryResult = $specificationQuery->fetch_array();
				$speficationNumber = $specificationQueryResult['specificationNumber'];
			}
		
		
		}
		
		$pdf->Cell(3,5,"",0,0,'R');
		$pdf->Cell(8,5,"1",1,0,'C');
		
		$pdf->Cell(50,5,$speficationNumber.' - '.$specificationDetailNumber,1,'L');	
		
		if($partStandardQueryResult['detailFlag']==0)
		{
			// 全体
			$pdf->Cell(15,5,"All",1,0,'C');
		}
		else
		{
			// 部分指定
			$pdf->Cell(15,5,"Point",1,0,'C');
		}
				
		$pdf->MultiCell(115,5,$speficiationDetail,1,'L');		
				
	}	
}
// --------------------------------------------------------------------------------

// --------------------------------- Other Remarks -------------------------------
// ----------------------------------- Ace Sandoval : Customer Specification -------------------------------
$pdf->SetFont('Arial','B',11);
$sql = "SELECT patternId, remarks, remarksFlag FROM cadcam_partprocessnote WHERE processCode = 0 AND partId = ".$partId;
$partStandardQuery = $db->query($sql);
if($partStandardQuery->num_rows > 0)
{
	while($partStandardQueryResult = $partStandardQuery->fetch_array())
	{			
		$pdf->Cell(3,5,"",0,0,'R');
		
		$pdf->Cell(58,5,$partStandardQueryResult['patternId'],1,'L');	
					
		if($partStandardQueryResult['remarksFlag']==0)
		{
			// 全体
			$pdf->Cell(15,5,"All",1,0,'C');
		}
		else
		{
			// 部分指定
			$pdf->Cell(15,5,"Point",1,0,'C');
		}
				
		$pdf->MultiCell(115,5,$partStandardQueryResult['remarks'],1,'L');
				
	}	
}

$pdf->Output();

?>
