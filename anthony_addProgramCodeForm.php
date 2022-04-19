<!-- Code Modified by Gerald 2015-06-05 -->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
	<link rel = 'stylesheet' type = 'text/css' href = '../Common Data/anthony.css'>
</head>
<body>
<form action = 'anthony_addProgramCodeFormSQL.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>' method = 'POST'>
<table>
	<tr>
		<td style = 'text-align: right;'>X:</td>
		<td><input type = 'text' name = 'x' style = 'width: 70px;' value = '15' required></td>
		<td></td>
		<td style = 'text-align: right;'>Y:</td>
		<td><input type = 'text' name = 'y' style = 'width: 70px;' value = '65' required></td>
	</tr>
	
	<tr>
		<td style = 'text-align: right;'>I:</td>
		<td><input type = 'text' name = 'i' style = 'width: 70px;' value = '17' required></td>
		<td></td>
		<td style = 'text-align: right;'>J:</td>
		<td><input type = 'text' name = 'j' style = 'width: 70px;' value = '17' required></td>
	</tr>
	
	<tr>
		<td style = 'text-align: right;'>G00:</td>
		<td><input type = 'checkbox' name = 'gZero'></td>
		<td></td>
		<td style = 'text-align: right;'>Produce Quantity:</td>
		<td><input type = 'number' name = 'produceQuantity' min='1' value='1'></td>
	</tr>
	<tr>
		<td colspan='5'><center><textarea rows = '25' cols = '40' name = 'programCode' id='txtAreaProgramCode'></textarea></center></td>
	</tr>
	<tr>
		<td colspan='5'><center><input type = 'submit' name = 'submit' value = 'Save' class = 'anthony_submit'></center></td>
	</tr>
</table>
</form>
</body>
</html>
