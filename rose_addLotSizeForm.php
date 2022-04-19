<?php
include("../Common Data/PHP Modules/mysqliConnection.php");


?>
<style>
div {
	line-height: 15px;
}

label
{
	float: left;
	width: 9em;
	margin-right: 1em;
	text-align: right;
}
</style>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
</head>
<body>
<form action = "rose_addLotSizeFormSQL.php?partId=<?php echo $_GET['partId']; ?>" method = "POST">
<div>

		<label for = "remarks">Lot Size:</label>
		<input type ="number" name = "lotSize" value = "<?php echo $_GET['lotSize']; ?>" class="art-button">
		<input type ="hidden" name = "oldlotSize" value = "<?php echo $_GET['lotSize']; ?>">
		<input type ="hidden" name = "partId2" value = "<?php echo $_GET['partId']; ?>">
	</div><br>
	
	<center>
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "submit" value = "Add" class="art-button">
			</div>
			</span>
			</div>
	</center>		
</form>										  
</body>
</html>
