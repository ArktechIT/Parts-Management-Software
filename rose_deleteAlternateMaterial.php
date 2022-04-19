<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

$userID = $_SESSION['userID'];
$userIP = $_SERVER['REMOTE_ADDR'];
$delete= isset($_GET['delete']) ? $_GET['delete']: "";
if($delete!="")
{
$mats= isset($_GET['mats']) ? $_GET['mats']: "";
$materialSpecId= isset($_GET['materialSpecId']) ? $_GET['materialSpecId']: "";
	$sql = "DELETE FROM engineering_alternatematerial where listId=".$delete;
	 $insert = $db->query($sql);	
	$sql = "INSERT INTO system_partlog (partId, date, query, field, oldValue, newValue, ip, user, details) VALUES (".$_GET['partId'].", now(), 3, 34, '".$mats."', '', '".$userIP."', '".$userID."', 'Delete Alternate Material ".$materialSpecId."') ";
	$insert = $db->query($sql);
	
	// header("Location: anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
	<link rel = 'stylesheet' type = 'text/css' href = '../Common Data/anthony.css'>
</head>
<body>
<form action = 'rose_deleteAlternateMaterial.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>' method = 'POST'>
<?php
//;cadcam_materialspecs;
$r=1;
echo "<table>";
$sql2 = "SELECT a.materialSpecId, a.listId, c.materialType, b.metalThickness FROM engineering_alternatematerial as a
	INNER JOIN cadcam_materialspecs as b on a.materialSpecId=b.materialSpecId
	INNER JOIN engineering_materialtype as c on b.materialTypeId=c.materialTypeId
	WHERE a.partId = ".$_GET['partId']." ";
	$getmats = $db->query($sql2);
	while($getmatsResult = $getmats->fetch_array())
	{
		$listId = $getmatsResult['listId'];			
		$materialSpecId = $getmatsResult['materialSpecId'];			
		$materialType = $getmatsResult['materialType'];		
		$metalThickness = $getmatsResult['metalThickness'];	
		echo "<tr>";
		echo "<td>".$r."</td>";
		echo "<td>".$materialType."</td>";
		echo "<td>".$metalThickness."</td>";
		?>
		<td>
		<a href="#" onclick= "javascript:TINY.box.show({url:'rose_deleteAlternateMaterial.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>&delete=<?php echo $listId; ?>&materialSpecId=<?php echo $materialSpecId; ?>&mats=<?php echo $materialType." ".$metalThickness; ?>',width:800,height:300,opacity:10,topsplit:6,animate:false,close:true})">
		<img src = '../Common Data/Templates/images/trash1.png' width = '20' height = '20' align = 'right'>
		</a>
		</td>
		<?php
		
		echo "</tr>";
		$r++;
	}
echo "</table>";	
	?>

<br><br>
<center>
<!--
<input type = 'submit' value = 'Add' class = 'anthony_submit' name = 'add'>
-->
</center>
</form>
</body>
</html>
