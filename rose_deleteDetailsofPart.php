<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel='' type='text/css' href='../Common Data/anthony.css'>
<?php
SESSION_START();
include('../Common Data/PHP Modules/mysqliConnection.php');

//echo "<form action='anthony_cloneToThisPart.php?partId=".$_GET['partId']."&patternId=".$_GET['patternId']."' method='POST'>";
///* 
$sql = "UPDATE cadcam_parts SET quantityPerSheet = 0, 
										materialSpecId = 0,
										materialSpecDetail = '',
										partDrawing = '',
										PVC = 0,
										x = '0',
										y = '0',
										y = '0',
										itemWeight = '0',
										itemArea = '0',
										itemLength = '0',
										itemWidth = '0',
										itemHeight = '0',
										treatmentId = 0
			WHERE partId = ".$_GET['partId']." ";
$update = $db->query($sql);		
$sql = "DELETE FROM engineering_alternatematerial WHERE partId = ".$_GET['partId']." ";
	$delete = $db->query($sql);	
$sql = "DELETE FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." ";
	$delete = $db->query($sql);
$sql = "DELETE FROM cadcam_processtoolsdetails WHERE partId = ".$_GET['partId']." ";
	$delete = $db->query($sql);	
$sql = "DELETE FROM cadcam_subparts WHERE parentId = ".$_GET['partId']." ";
	$delete = $db->query($sql);	
$sql = "DELETE FROM cadcam_subparts WHERE childId = ".$_GET['partId']." and identifier=1";
	$delete = $db->query($sql);	
$sql = "DELETE FROM cadcam_subconlist WHERE partId = ".$_GET['partId']." ";
	$delete = $db->query($sql);
$sql = "DELETE FROM cadcam_partprocessnote WHERE partId = ".$_GET['partId'];
	$delete = $db->query($sql);	
 //*/
// echo "anthony_editProduct.php?partId=".$_GET['editproduct']."&src=process&patternId=".$_GET['patternId'];
header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
?>
