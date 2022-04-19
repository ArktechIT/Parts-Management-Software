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
</style>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
</head>
<body>
<form name="inputForm" action = "anthony_checkPartNumberRevisionFormChecking.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>" method = "POST">
	<div>
		<label for = "partNumber">Part Number:</label><input type = "text" name = "partNumber">
	</div><br>
	
	<div>
		<label for = "partName">Part Name:</label><input type = "text" name = "partName">
	</div><br>
	
	<div>
		<label for = "revision">Revision Id:</label><input type = "text" name = "revision">
	</div><br>
	
	<center>
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
				<div id="submitButton">
					<input type ="submit" name = "clone" value = "Clone" class="art-button">
				</div>
			</span>
		</div>
	</center>		
</form>										  
</body>
</html>
