<?php
include('../Common Data/PHP Modules/mysqliConnection.php');
require('../Common Data/Libraries/PHP/FPDF/fpdf.php');
require('../Common Data/Libraries/PHP/FPDI/fpdi.php');
ini_set('display_errors','on');


// ------------------------------------------------ Customized Class ------------------------------------------------------------
class PDF extends FPDI
{
	// ----------------------------------------------- Page Group Function ---------------------------------------------------------
	var $NewPageGroup;   // variable indicating whether a new group was requested
	var $PageGroups;     // variable containing the number of pages of the groups
	var $CurrPageGroup;  // variable containing the alias of the current page group

	// create a new page group; call this before calling AddPage()
	function StartPageGroup()
	{
			$this->NewPageGroup = true;
	}

	// current page in the group
	function GroupPageNo()
	{
			return $this->PageGroups[$this->CurrPageGroup];
	}

	// alias of the current page group -- will be replaced by the total number of pages in this group
	function PageGroupAlias()
	{
			return $this->CurrPageGroup;
	}

	function _beginpage($orientation, $format)
	{
			parent::_beginpage($orientation, $format);
			if($this->NewPageGroup)
			{
					// start a new group
					$n = sizeof($this->PageGroups)+1;
					$alias = "{nb$n}";
					$this->PageGroups[$alias] = 1;
					$this->CurrPageGroup = $alias;
					$this->NewPageGroup = false;
			}
			elseif($this->CurrPageGroup)
					$this->PageGroups[$this->CurrPageGroup]++;
	}

	function _putpages()
	{
			$nb = $this->page;
			if (!empty($this->PageGroups))
			{
					// do page number replacement
					foreach ($this->PageGroups as $k => $v)
					{
							for ($n = 1; $n <= $nb; $n++)
							{
									$this->pages[$n] = str_replace($k, $v, $this->pages[$n]);
							}
					}
			}
			parent::_putpages();
	}
	
	function getLastPageNumber()
	{
	return $this->PageNo();
	}
	
	function setLastPageNumber($last)
	{
	$this->last = $last;
	}
	
	function setLotNumber($lotNumber)
	{
	$this->lotNumber = $lotNumber;
	}
	
	function getLotNumber() 
	{
	return $this->lotNumber;
	}
	
	function getLast()
	{
	return $this->last();
	}	
	// ------------------------------------ End of Page Group Function ----------------------------------------------

	var $parentId="";
	var $last;
	
	
}
// --------------------------------------------------------- End of Class ----------------------------------------------------

// ---------------------------------------------- COC DOCUMENTS ----------------------------------------------

$pdf = new PDF('L','mm','A4');
//$pdf->AddPage();
$pdf->SetTopMargin(5);
$pdf->SetLeftMargin(5);
$pdf->SetFont('Arial','',10);
$limit = 10;		

		
		// ---------------------------------- Ace Sandoval ----------------------------------------------------------		
		// ----------------------------------- Merge Documents ----------------------------------------------------------		
		// ---------------------------------------------------------- Attach Arktech Drawing --------------------------------------------------------
		

if((file_exists("../../Document Management System/Arktech Folder/ARK_".$_GET['partId'].".pdf") > 0))
{	
	$pdf->StartPageGroup();		
	$lastPage=$pdf->getlastpageNumber();
	$pdf->setlastpageNumber(($lastPage-1));	
	//$pdf->setSourceFile("../../Document Management System/Master Folder/MAIN_".$partIdQueryResult['partId'].".pdf");  	
	$pdf->setSourceFile("../../Document Management System/Arktech Folder/ARK_".$_GET['partId'].".pdf");  	
	$tplIdx = $pdf->importPage(1);		
	$pageLayout = $pdf->getTemplateSize($tplIdx);
	$pdf->addPage($pageLayout['h'] > $pageLayout['w'] ? 'P' : 'L');
	$pdf->useTemplate($tplIdx);		
}

if((file_exists("../../Document Management System/Master Folder/MAIN_".$_GET['partId'].".pdf") > 0))
{	
	$pdf->StartPageGroup();		
	$lastPage=$pdf->getlastpageNumber();
	$pdf->setlastpageNumber(($lastPage-1));	
	//$pdf->setSourceFile("../../Document Management System/Master Folder/MAIN_".$partIdQueryResult['partId'].".pdf");  	
	$pdf->setSourceFile("../../Document Management System/Master Folder/MAIN_".$_GET['partId'].".pdf");  	
	$tplIdx = $pdf->importPage(1);		
	$pageLayout = $pdf->getTemplateSize($tplIdx);
	$pdf->addPage($pageLayout['h'] > $pageLayout['w'] ? 'P' : 'L');
	$pdf->useTemplate($tplIdx);		
}
$pdf->Output();

?>
