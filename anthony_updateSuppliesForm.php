<?php
include("../Common Data/PHP Modules/mysqliConnection.php");

if(isset($_GET['src']) AND $_GET['src'] == 'delete')
{
	$sql = "DELETE FROM cadcam_subparts WHERE subpartId = ".$_GET['subpartId']." ";
	$delete = $db->query($sql);
	
	header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=supplies&patternId=".$_GET['patternId']."");
}

if(isset($_POST['update']))
{
	$sql = "UPDATE cadcam_subparts SET childId = ".$_POST['suppliesName'].", quantity = ".$_POST['quantity']." WHERE subpartId = ".$_GET['subpartId']." ";
	$update = $db->query($sql);
	
	header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=supplies&patternId=".$_GET['patternId']."");
}

$childId = $quantity = 0;
$sql = "SELECT childId, quantity FROM cadcam_subparts WHERE subpartId = ".$_GET['subpartId'];
$getQuantity = $db->query($sql);
if($getQuantity->num_rows > 0)
{
	$getQuantityResult = $getQuantity->fetch_array();
	$childId = $getQuantityResult['childId'];
	$quantity = $getQuantityResult['quantity'];
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
	<style>
	div {
		line-height: 15px;
	}

	label
	{
		float: left;
		width: 8em;
		margin-right: 1em;
		text-align: right;
	}
	
	select
	{
		width: 50%;
	}
	</style>
</head>
<body>
<form action = "anthony_updateSuppliesForm.php?partId=<?php echo $_GET['partId']; ?>&subpartId=<?php echo $_GET['subpartId']; ?>&patternId=<?php echo $_GET['patternId']; ?>" method = "POST">
	<div>
		<label for = "accesory">Item Name:</label>
		<select name = "suppliesName"> <?php
				echo "<option value = '0'></option>";
			$itemId = $itemName = $itemDescription = '';
			$sql = "SELECT itemId, itemName, itemDescription FROM purchasing_items ORDER BY itemName";
			$getItems = $db->query($sql);
			if($getItems->num_rows > 0)
			{
				while($getItemsResult = $getItems->fetch_array())
				{
					$itemId = $getItemsResult['itemId'];
					$itemName = $getItemsResult['itemName'];
					$itemDescription = $getItemsResult['itemDescription'];
					
					echo "<option value = '".$itemId."'"; if($itemId == $childId){ echo 'selected'; } echo ">".$itemName." [ ".$itemDescription." ]</option>";
				}
			}
		?> </select>
	</div><br>	
		
	<div>
		<label for = "quantity">Quantity:</label><input type = "number" name = "quantity" min = "1" value = "<?php echo $quantity; ?>">
	</div><br>
	
	<center>
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "update" value = "Update" class="art-button">
			</div>
			</span>
			</div>
	</center>		
</form>										  
</body>
</html>
