<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
</head>
<body>
<?php
include('../Common Data/PHP Modules/anthony_retrieveText.php');
	if(isset($_GET['count']))
	{
		?>
		<form action = 'anthony_deleteProcessDetailFormSQL.php?partId=<?php echo $_GET['partId']; ?>&processOrder=<?php echo $_GET['processOrder']; ?>&count=<?php echo $_GET['count']; ?>&patternId=<?php echo $_GET['patternId']; ?>' method = 'POST'>
		<?php
	}
	else if(isset($_GET['subProcessListId']))
	{
		?>
		<form action = 'anthony_deleteProcessDetailFormSQL.php?partId=<?php echo $_GET['partId']; ?>&processOrder=<?php echo $_GET['processOrder']; ?>&subProcessListId=<?php echo $_GET['subProcessListId']; ?>&patternId=<?php echo $_GET['patternId']; ?>' method = 'POST'>
		<?php
	}
	else if(isset($_POST['subProcessListIdArray']))
	{
		$subProcessListIdArray = explode(",",$_POST['subProcessListIdArray']);
		?>
		<form action = 'anthony_deleteProcessDetailFormSQL.php?partId=<?php echo $_GET['partId']; ?>&processOrder=<?php echo $_GET['processOrder']; ?>&patternId=<?php echo $_GET['patternId']; ?>' method = 'POST'>
		<?php
		
		if(count($subProcessListIdArray) > 0)
		{
			foreach($subProcessListIdArray as $subProcessListId)
			{
				echo "<input type='hidden' name='subProcessListIdArray[]' value='".$subProcessListId."'>";
			}
		}
	}
?>
	<center>
		<label><?php echo displayText('L1411');//User Remark ?></label><br>
		<textarea rows = "5" cols = "30" name = "userRemarks" placeholder = "Enter User Remarks" required></textarea>
		
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "submit" value = "<?php echo displayText('L609');//Delete ?>" class="art-button">
			</div>
			</span>
			</div>
	</center>		
</form>										  
</body>
</html>
