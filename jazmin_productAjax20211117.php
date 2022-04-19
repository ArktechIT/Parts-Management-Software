<meta charset="UTF-8">
<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	// $path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	set_include_path($path);
	include('PHP Modules/mysqliConnection.php');
	include('PHP Modules/gerald_functions.php');
	include("PHP Modules/anthony_wholeNumber.php");
	include('PHP Modules/anthony_retrieveText.php');
	include('PHP Modules/rose_prodfunctions.php');
	ini_set("display_errors", "on");
	
	//~ $requestData = $_REQUEST;
	//~ $sqlFilter = $requestData['sqlFilter'];
	
	$requestData= $_REQUEST;
	$sqlData = isset($requestData['sqlData']) ? $requestData['sqlData'] : '';
	$totalRecords = isset($requestData['totalRecords']) ? $requestData['totalRecords'] : '';	
	$showPrice = isset($requestData['showPrice']) ? $requestData['showPrice'] : '';	
	
	$exportFlag = (isset($_POST['exportFlag'])) ? $_POST['exportFlag'] : '';
	
	$totalData = $totalRecords;
	$totalFiltered = $totalRecords;
	
	if($exportFlag!='')
	{
		$filename = "PARTS MASTER (".date('ymdHis').").xls";
		header('Content-type: application/ms-excel');
		header('Content-Disposition: attachment; filename='.$filename);
		
		?>
		<table class='table table-bordered table-condensed table-striped' id="mainTableId" border=1>
			<thead class='w3-indigo thead' style='text-transform:uppercase;'>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L843');?></th>
<!--
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L52');?></th>
-->
				<th class='w3-center' style='vertical-align:middle;'><?php echo "partId";?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L24');?></th>                    
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L28');?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L226');?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L3446');?></th>
				<!-- <th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L396');?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L397');?></th> -->
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L30');?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L111');?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L184');?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo "price";?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L398');?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L399');?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L76');?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L75');?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L74');?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L72');?></th>				
				<th class='w3-center <?php echo $colorFirstPO; ?> firstPOdateClass '  data-id='firstPOdate' style='cursor:pointer; vertical-align:middle;'><?php echo $sortFirstPOClass; ?><?php echo displayText('L4162');?></th>
				<th class='w3-center <?php echo $colorLastPO; ?> lastPOdateClass' data-id='lastPOdate' style='cursor:pointer; vertical-align:middle;'><?php echo $sortLastPOClass; ?><?php echo displayText('L4163');?></th>
				<th class='w3-center <?php echo $colorCountPO; ?> poCountClass' data-id='poCount' style='cursor:pointer; vertical-align:middle;'><?php echo $sortCountPOClass; ?><?php echo displayText('L4164');?></th>
				<th class='w3-center <?php echo $colorQuantityPO; ?> poQuantityClass' data-id='poQuantity' style='cursor:pointer; vertical-align:middle;'><?php echo $sortQuantityPOClass; ?><?php echo displayText('L1003');?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L188');?></th><!--view-->
<!--
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L188');?></th>
-->
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L77');?></th><!--drawing-->
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L636');?></th><!--comment-->
				<th class='w3-center' style='vertical-align:middle;'><?php echo "MatTreatment";?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo "ItemTreatment";?></th>
				<th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L73');?></th>
				<!-- <th class='w3-center' style='vertical-align:middle;'><?php echo displayText('L1346');?></th> -->
			</thead>
			<tbody class='tbody'>
		<?php
	}		
	
	//~ $num=0;
			
	//~ $sql = "SELECT * FROM cadcam_parts ".$sqlFilter."";
	//~ $query = $db->query($sql);
	//~ $totalData = $query->num_rows;
	//~ $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.	
	//~ $sql.=" LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	//~ $data = array();
	//~ $partsQuery = $db->query($sql);
	//~ $counter = $requestData['start'];
	
	$data = array();
	$sql = $sqlData;
	if($exportFlag=='') $sql.=" LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$counter = $requestData['start'];
	$partsQuery = $db->query($sql);	
	if($partsQuery->num_rows > 0)
	{
		//~ $tableContent = "<tr><td colspan='14'>".$sqlMain."</td></tr>";
		while($partsQueryResult = $partsQuery->fetch_array())
		{
			$sqlCustomer = "SELECT customerId, customerName FROM sales_customer WHERE customerId = ".$partsQueryResult['customerId']."";
			$customerQuery = $db->query($sqlCustomer);
			$customerQueryResult = $customerQuery->fetch_assoc();
			
			$price = "0.0000";
			$sql = "SELECT price FROM sales_pricelist WHERE arkPartId = ".$partsQueryResult['partId'];
			$queryPrice = $db->query($sql);
			if($queryPrice AND $queryPrice->num_rows > 0)
			{
				$resultPrice = $queryPrice->fetch_assoc();
				$price = $resultPrice['price'];
			}

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
			
			// $nameLength = strlen($partsQueryResult['partName']);
			// if($nameLength > 50)
			// {
			// 	$partName = trim(substr($partsQueryResult['partName'],0,49))."...";
			// }
			// else
			// {
			// 	$partName = trim($partsQueryResult['partName']);
			// }
        
        	$partName = trim($partsQueryResult['partName']);

			$fileDrawing = $folder = $color = $url = $drawing = '';
			$path = '../../Document Management System/Arktech Folder/ARK_'.$partsQueryResult['partId'].'.pdf';
			$path1 = '../../Document Management System/Master Folder/MAIN_'.$partsQueryResult['partId'].'.pdf';
			if(file_exists($path))
			{
				$fileDrawing = 'Arktech Folder/ARK_'.$partsQueryResult['partId'].'.pdf';
				$folder = 'Arktech';
				//~ $url = "openTinyBox('800','580','','','/".v."/20 Document Management System/gerald_fileViewer.php?file=".$fileDrawing."')";
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
				$url = "window.open('/".v."/20 Document Management System/raymond_drawingViewer.php?partId=".$partsQueryResult['partId']."&dwg=2','cc','left=50,screenX=700,screenY=20,resizable,scrollbars,status,width=1000,height=700')";
				$drawing = "<img onclick=\" ".$url." \" src='../Common Data/Templates/images/drawingIcon.png' width='20' height='20' alt='VIEW' title='Drawing' >";
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
			/*
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
			}*/
			
				$partId=trim($partsQueryResult['partId']);
				$customer=$customerQueryResult['customerName'].$KCOwebsite;
				$partNumber=trim($partsQueryResult['partNumber']);
				$revisionId=$partsQueryResult['revisionId'];
				$sheet="<a onclick=\"TINY.box.show({url: 'raymond_editSheetNumber&partSheet.php?sheetNumber=".$partsQueryResult['sheetNumber']."&partSheet=".$partsQueryResult['partSheet']."&partId=".$partsQueryResult['partId']."', width: 'auto' , height: 'auto', opacity: 10 ,topsplit: 6 ,top: 150,animate:false ,close:true})\" style='text-decoration:none; color:black;'>".$partsQueryResult['sheetNumber']."</a>";
				$partSheet="<a onclick=\"TINY.box.show({url: 'raymond_editSheetNumber&partSheet.php?sheetNumber=".$partsQueryResult['sheetNumber']."&partSheet=".$partsQueryResult['partSheet']."&partId=".$partsQueryResult['partId']."', width: 'auto' , height: 'auto', opacity: 10 ,topsplit: 6 ,top: 150,animate:false ,close:true})\" style='text-decoration:none; color:black;'>".$partsQueryResult['partSheet']."</a>";

				$x=$partsQueryResult['x'];
				$y=$partsQueryResult['y'];
				$editProduct="<a href='anthony_editProduct.php?partId=".$partsQueryResult['partId']."&src=process&patternId=0'> <img src='../Common Data/Templates/images/view1.png' width='20' height='20' alt='VIEW' title='VIEW'></a>";
				$checkFAI="<a onclick=\"window.open('rose_checkFAI.php?partId=".$partsQueryResult['partId']."', 'pp2','left=50,screenX=800,screenY=20,resizable,scrollbars,status,width=300,height=500'); return false;\">".$FAIview."</a>";
			
				$sql = "SELECT partNote FROM cadcam_parts WHERE partId = ".$partId;
			$queryPartNote = $db->query($sql);
			if($queryPartNote AND $queryPartNote->num_rows > 0)
			{
				$resultPartNote = $queryPartNote->fetch_assoc();
				$partNote = $resultPartNote['partNote'];
			}
			
			$bgColor = '';
			$sql = "SELECT sheetWorksId FROM engineering_sheetworksdata WHERE partId = ".$partId." LIMIT 1";
			$querySheetWorksData = $db->query($sql);
			if($querySheetWorksData AND $querySheetWorksData->num_rows > 0)
			{
				$bgColor = 'yellowgreen';
			}

			$partsDataLink = "<a href='anthony_editProduct2.php?partId=".$partsQueryResult['partId']."&src=process&patternId=0'> <img src='../Common Data/Templates/images/view1.png' width='20' height='20' alt='VIEW' title='VIEW'></a>";

			$sheetWorksButton = '';
			$sql = "SELECT batchId, keyType, keyId FROM engineering_sheetworksdatanew WHERE partId = ".$partId." AND status = 1 ORDER BY sheetWorksId DESC LIMIT 1";
			$querySheetWorksDataNew = $db->query($sql);
			if($querySheetWorksDataNew AND $querySheetWorksDataNew->num_rows > 0)
			{
				$resultSheetWorksDataNew = $querySheetWorksDataNew->fetch_assoc();
				$batchId = $resultSheetWorksDataNew['batchId'];
				$keyType = $resultSheetWorksDataNew['keyType'];
				$keyId = $resultSheetWorksDataNew['keyId'];
				
				$sheetWorksButton="<img onclick=\" window.open('gerald_viewSheetWorksMSAccessData.php?keyType=".$keyType."&batchId=".$batchId."&keyId=".$keyId."','View MS Access Data','left=200,screenX=400,screenY=150,resizable,scrollbars,status,width=1000,height=650'); return false; \" src='../Common Data/Templates/images/view1.png' width='20' height='20' alt='VIEW' title='VIEW MS Access Data'>";
			}
				$treatmentId=$partsQueryResult['treatmentId'];
				$statofMatPrime="";				
				if($treatmentId!="")
				{	
					$sqlT = "SELECT treatmentName FROM cadcam_treatmentprocess where treatmentId =".$treatmentId." limit 1";
					$queryT = $db->query($sqlT);
					if($queryT AND $queryT->num_rows > 0)
					{
						$resultT = $queryT->fetch_assoc();
						$statofMatPrime = $resultT['treatmentName'];
					}					
				}
				
				list ($subconId,$subconprocessCode,$subconprocessName,$subconAlias)=partidSubcon($partId) ;
				$treatmentValue=""; if(count($subconprocessName)>0){ for($s=0;$s<count($subconprocessName);$s++){ if($treatmentValue==""){$treatmentValue="(".$subconAlias[$s].") ".$subconprocessName[$s];} else{$treatmentValue=$treatmentValue."`_`(".$subconAlias[$s].") ".$subconprocessName[$s]."";}}}
				
				$statofPrime="";
					$sqlT2 = "SELECT processCode FROM cadcam_partprocess where partId =".$partId." and processCode IN(195,331,335,153,218)";
					$queryT2 = $db->query($sqlT2);
					if($queryT2 AND $queryT2->num_rows > 0)
					{
						while($resultT2 = $queryT2->fetch_assoc())
						{
							if($resultT2['processCode']==153)
							{
								$statofPrime = $statofPrime."Paint";
							}
							else if($resultT2['processCode']==218)
							{
								$statofPrime = $statofPrime."Paint";
							}
							else
							{
								$statofPrime = $statofPrime."Prime";
							}
						}
						$treatmentValue=$treatmentValue."".$statofPrime;
					}		
				
				
				
				
				
				
				
			
			

			if($exportFlag=='')
			{
				// if(strlen($partName) >= 10) $partName = substr($partName, 0, 10)."..";
				$nestedData = array();
				$nestedData[] =	$partId;
				$nestedData[] = ++$counter;
				//~ $nestedData[] =	"<span style='background-color:".$bgColor.";'>".$partId."</span>";
				$nestedData[] = $customer;
				$nestedData[] = "<span style='background-color:".$bgColor.";' title='".$partId."'>".$partNumber."</span>";
				$nestedData[] = $revisionId;
				$nestedData[] = $partNote;
				// $nestedData[] = $sheet;
				// $nestedData[] = $partSheet;
				$nestedData[] = $partName;
				$nestedData[] = $metalType;
				$nestedData[] = $metalThickness;
				$nestedData[] = $x;
				$nestedData[] = $y;
				$nestedData[] = $partsQueryResult['itemHeight'];
				$nestedData[] = $partsQueryResult['itemWidth'];
				$nestedData[] = $partsQueryResult['itemLength'];
				$nestedData[] = $partsQueryResult['itemWeight'];
				if($showPrice == 1)
				{
					$nestedData[] = $price;
				}
				$nestedData[] = $partsQueryResult['firstPODate'];
				$nestedData[] = $partsQueryResult['lastPODate'];
				$nestedData[] = $partsQueryResult['orderCount'];
				$nestedData[] = $partsQueryResult['totalQuantity'];
				$nestedData[] = $editProduct;
				//~ $nestedData[] = $partsDataLink;
				$nestedData[] = $drawing.$sheetWorksButton;
				$nestedData[] = $partsQueryResult['partsComment'];
				// $nestedData[] = $checkFAI;
				$data[] = $nestedData;
			}
			else
			{
				echo "
					<tr>
						<td>".++$counter."</td>
						<td>".$partId."</td>
						<td>".$customer."</td>
						<td>".$partNumber."</td>
						<td>".$revisionId."</td>
						<td>".$partNote."</td>
						<td>".$partName."</td>
						<td>".$metalType."</td>
						<td>".$metalThickness."</td>
						<td>".$price."</td>
						<td>".$x."</td>
						<td>".$y."</td>
						<td>".$partsQueryResult['itemHeight']."</td>
						<td>".$partsQueryResult['itemWidth']."</td>
						<td>".$partsQueryResult['itemLength']."</td>
						<td>".$partsQueryResult['itemWeight']."</td>
						<td>".$partsQueryResult['firstPODate']."</td>
						<td>".$partsQueryResult['lastPODate']."</td>
						<td>".$partsQueryResult['orderCount']."</td>
						<td>".$partsQueryResult['totalQuantity']."</td>
						<td></td>
						<td></td>					
						<td>".$partsQueryResult['partsComment']."</td>
						<td>".$statofMatPrime."</td>
						<td>".$treatmentValue."</td>
						<td>".$partsQueryResult['itemArea']."</td>
					</tr>
				";				
			}
		}
	}
	
	if($exportFlag=='')
	{	
		$json_data = array(
					"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
					"recordsTotal"    => intval( $totalData ),  // total number of records
					"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
					"data"            => $data   // total data array
					);

		echo json_encode($json_data);  // send data as json format	
	}
	else
	{
		echo "</tbody></table>";
	}
?>
