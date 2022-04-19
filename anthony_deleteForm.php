<?php
include("../Common Data/PHP Modules/anthony_retrieveText.php");
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
</head>
<body>
<form action = 'anthony_delete.php?partId=<?php echo $_GET['partId']; ?>&count=<?php echo $_GET['count']; ?>&patternId=<?php echo $_GET['patternId']; ?>' method = 'POST'>
	<center>
		<label><?php echo displayText('L1411');?></label><br>
		<textarea rows = "5" cols = "30" name = "userRemarks" placeholder = "Enter User Remarks" required></textarea>
		
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "submit" value = "Delete" class="art-button">
			</div>
			</span>
			</div>
	</center>		
</form>										  
</body>
</html>
