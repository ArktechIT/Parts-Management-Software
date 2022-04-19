<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	// $path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	set_include_path($path);
	include('PHP Modules/mysqliConnection.php');
	include('PHP Modules/anthony_retrieveText.php');
	ini_set("display_errors","on");
	
	$groupNo = $_POST['groupNo'];
	$sqlFilter = $_POST['sqlFilter'];
	$queryLimit = 50;
	$queryPosition = ($groupNo * $queryLimit);

	$count = $queryPosition;
//------------------------------------- Yannie 04 - 12 - 18 --------------------------------------------------------------------------
	if(isset($_GET['type']) AND $_GET['type'] == 'export')
	{
		$filename = $_SESSION['user'].' '.date('Y-m-d-h-i-s').'.xls';
		header('Content-Type: text/xls; charset=utf-8');
		header('Content-Disposition: attachment; filename='.$filename);
		echo " <table>
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
				</tr>
			</thead>";
			
		$sqlFilter = $_GET['sqlFilter'];
		$sql = "SELECT partId, partNumber, partName, revisionId, customerId, materialSpecId, x, y, partSheet, sheetNumber FROM cadcam_parts ".$sqlFilter." ORDER BY partId DESC ";
		
	}
	else
	{
		$sql = "SELECT partId, partNumber, partName, revisionId, customerId, materialSpecId, x, y, partSheet, sheetNumber FROM cadcam_parts ".$sqlFilter." ORDER BY partId DESC LIMIT ".$queryPosition.", ".$queryLimit;
	}
//--------------------------------------------------- Yannie ----------------------------------------------------------------------------------

	$sqlMain = $sql;
	$partsQuery = $db->query($sql);
	if($partsQuery->num_rows > 0)
	{
		//~ $tableContent = "<tr><td colspan='14'>".$sqlMain."</td></tr>";
		while($partsQueryResult = $partsQuery->fetch_array())
		{
			$sql = "SELECT customerId, customerName FROM sales_customer WHERE customerId = ".$partsQueryResult['customerId']." LIMIT 1";
			$customerQuery = $db->query($sql);
			$customerQueryResult = $customerQuery->fetch_array();
			
			$sql = "SELECT listId FROM system_kcocheckdetails WHERE oldPartId = ".trim($partsQueryResult['partId'])."";
			$CheckKCO = $db->query($sql);
			if($CheckKCO->num_rows > 0){					
				$CheckKCOResult = $CheckKCO->fetch_array();
				$listId = $CheckKCOResult['listId']; 
			}
			else
			{
				$listId = 0;
			}
			
			$metalType = '-'; $metalThickness = '-';
			$sql = "SELECT materialTypeId, metalThickness, metalLength, metalWidth FROM cadcam_materialspecs WHERE materialSpecId = ".$partsQueryResult['materialSpecId']."";
			$materialSpecs = $db->query($sql);
			if($materialSpecs->num_rows > 0){					
				$materialSpecsResult = $materialSpecs->fetch_array();
				$materialTypeId = $materialSpecsResult['materialTypeId']; 
				$metalThickness = $materialSpecsResult['metalThickness']; 
				
				$sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$materialTypeId." LIMIT 1";
				$queryMaterialType = $db->query($sql);
				if($queryMaterialType AND $queryMaterialType->num_rows > 0)
				{
					$resultMaterialType = $queryMaterialType->fetch_assoc();
					$metalType = $resultMaterialType['materialType'];
				}
			}
			
			$nameLength = strlen($partsQueryResult['partName']);
			if($nameLength > 50)
			{
				$partName = trim(substr($partsQueryResult['partName'],0,49))."...";
			}
			else
			{
				$partName = trim($partsQueryResult['partName']);
			}

			$fileDrawing = $folder = $color = $url = $drawing = '';
			$path = '../../Document Management System/Arktech Folder/ARK_'.$partsQueryResult['partId'].'.pdf';
			$path1 = '../../Document Management System/Master Folder/MAIN_'.$partsQueryResult['partId'].'.pdf';
			if(file_exists($path))
			{
				$fileDrawing = 'Arktech Folder/ARK_'.$partsQueryResult['partId'].'.pdf';
				$folder = 'Arktech';
				//~ $url = "openTinyBox('800','580','','','/20 Document Management System/gerald_fileViewer.php?file=".$fileDrawing."')";
				$url = "openModalBox('','','/".v."/20 Document Management System/gerald_fileViewer.php?file=".$fileDrawing."')";
				//~ $url = "openModalBox('/".v."/20 Document Management System/gerald_fileViewer.php?file=".$fileDrawing."')";
			}
			else if(file_exists($path1))
			{
				$fileDrawing = 'Master Folder/MAIN_'.$partsQueryResult['partId'].'.pdf';
				$folder = 'Main';
				//~ $url = "openTinyBox('800','580','','','/".v."/20 Document Management System/gerald_fileViewer.php?file=".$fileDrawing."')";
				$url = "openModalBox('','','/".v."/20 Document Management System/gerald_fileViewer.php?file=".$fileDrawing."')";
				//~ $url = "openModalBox('/".v."/20 Document Management System/gerald_fileViewer.php?file=".$fileDrawing."')";
			}
			
			if($url!='')
			{
				$drawing = "<img onclick=\" ".$url." \" src='../Common Data/Templates/images/drawingIcon.png' width='20' height='20' alt='VIEW' title='".$folder." Drawing' >";
			}
			
			//rosemie
			if($partsQueryResult['customerId']==45 or $partsQueryResult['customerId']==49)
			{
				if(trim($partsQueryResult['partNumber'])[0]=='P')
				{
					$partSearch = substr(trim($partsQueryResult['partNumber']),0, 10);
					$sht = substr(trim($partsQueryResult['partNumber']), -4);	
				}
				else
				{
					$partSearch = substr(trim($partsQueryResult['partNumber']),0, 12);
					//$sht = substr(trim($partsQueryResult['partNumber']), -2);
					$sht = "";
				}
			$KCOwebsite="<a onclick=\" window.open ('https://210.172.85.77/dwginf30/dwg/dwg.asp?page=1&type=NBR&dwg=".$partSearch."&post=&sht=*".$sht."&uid=3DUP009&uadrs=3Dnagano@arktech.co.jp', 'kcoWindow', config='left=130,top=80,height=500,width=700, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, directories=no, status=no'); \" height='25' title='VIEW KCO'> - KCOview -</a>";
			//https://210.172.85.77/dwginf30/dwg/dwg.asp?page=1&type=NBR&dwg=PPLA000000&post=&sht=*&uid=3DUP009&uadrs=3Dnagano@arktech.co.jp
			}
			else
			{
			$KCOwebsite="";
			}
			//
			//rosemie
			$roseFlag=0;
			$lotNumber = "";
			$FAIview = "";
			$poId = "";
			//$lotNumber = "SELECT lotNumber FROM qc_fai2 WHERE lotNumber IN (select lotNumber from ppic_lotlist where partId =".$partsQueryResult['partId']." and identifier=1 order by lotNumber)";
			$sql = "SELECT lotNumber FROM qc_fai2 WHERE lotNumber IN (select lotNumber from ppic_lotlist where partId =".$partsQueryResult['partId']." and identifier=1 order by lotNumber)";
			$queryFAI = $db->query($sql);
			if($queryFAI AND $queryFAI->num_rows > 0)
			{				
				while($resultFAI = $queryFAI->fetch_assoc() and $roseFlag==0)
				{
				$lotNumber = $resultFAI['lotNumber'];
				$FAIview = "FAI";
				
					$sql = "SELECT poId from ppic_lotlist where lotNumber like '".$lotNumber."' and identifier=1";
					$queryFAI2 = $db->query($sql);
					if($queryFAI2 AND $queryFAI2->num_rows > 0)
					{				
						while($resultFAI2 = $queryFAI2->fetch_assoc() and $roseFlag==0)
						{
						$poId = $resultFAI2['poId'];
						}
					}
				$roseFlag=1;
				//break;
				}
			}

			
			
				$tableContent .= "
			
				<tr>
					<td align = 'center'>".++$count."</td>
					<td align = 'center'>".trim($partsQueryResult['partId'])."</td>
					<td>".$customerQueryResult['customerName'].$KCOwebsite."</td>
					<td>".trim($partsQueryResult['partNumber'])."</td>
					<td>".$partsQueryResult['revisionId']."</td>
					<td onclick=\"TINY.box.show({url: 'raymond_editSheetNumber&partSheet.php?sheetNumber=".$partsQueryResult['sheetNumber']."&partSheet=".$partsQueryResult['partSheet']."&partId=".$partsQueryResult['partId']."', width: 'auto' , height: 'auto', opacity: 10 ,topsplit: 6 ,top: 150,animate:false ,close:true})\" style='text-decoration:none; color:black;'>".$partsQueryResult['sheetNumber']."</td>
					<td onclick=\"TINY.box.show({url: 'raymond_editSheetNumber&partSheet.php?sheetNumber=".$partsQueryResult['sheetNumber']."&partSheet=".$partsQueryResult['partSheet']."&partId=".$partsQueryResult['partId']."', width: 'auto' , height: 'auto', opacity: 10 ,topsplit: 6 ,top: 150,animate:false ,close:true})\" style='text-decoration:none; color:black;'>".$partsQueryResult['partSheet']."</td>
					<td>".$partName."</td>
					<td>".$metalType."</td>
					<td>".$metalThickness."</td>
					<td>".$partsQueryResult['x']."</td>
					<td>".$partsQueryResult['y']."</td>
					<td align = 'center'><a href='anthony_editProduct.php?partId=".$partsQueryResult['partId']."&src=process&patternId=0'> <img src='../Common Data/Templates/images/view1.png' width='20' height='20' alt='VIEW' title='VIEW'></a></td>
					<td align='center' >".$drawing."</td>
					<td onclick=\"window.open('rose_checkFAI.php?partId=".$partsQueryResult['partId']."', 'pp2','left=50,screenX=800,screenY=20,resizable,scrollbars,status,width=300,height=500'); return false;\">".$FAIview."</td>
					</tr>
					";
					//<td onclick=\"window.open('../prs/james_faiJamcoAllViewNewSignature.php?poId=".$poId."&cocONLY=1&LOT=".$lotNumber."', 'pp','left=50,screenX=700,screenY=20,resizable,scrollbars,status,width=700,height=500'); return false;\">".$FAIview."</td>
					
			
		}
		echo $tableContent;
	}
					
?>
