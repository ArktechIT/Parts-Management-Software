<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');

if(isset($_POST['add']))
{
	$sql = "INSERT INTO cadcam_subparts (parentId, childId, quantity, identifier) VALUES (".$_GET['partId'].", ".$_POST['suppliesName'].", ".$_POST['quantity'].", 8) ";
	$insert = $db->query($sql);
	
	header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=supplies&patternId=".$_GET['patternId']."");
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
<form action = "anthony_addSuppliesForm.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>" method = "POST">
	<div>
		<label for = "accesory"><?php echo displayText('L246');//Item Name?>:</label>
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
					
					echo "<option value = '".$itemId."'>".$itemName." [ ".$itemDescription." ]</option>";
				}
			}
		?> </select>
	</div><br>	
		
	<div>
		<label for = "quantity"><?php echo displayText('L31');//Quantity?>:</label><input type = "number" name = "quantity" min = "1">
	</div><br>
	
	<center>
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "add" value = "<?php echo displayText('B4');//Add?>" class="art-button">
			</div>
			</span>
			</div>
	</center>		
</form>										  
</body>
</html>
