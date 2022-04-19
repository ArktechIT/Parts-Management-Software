<?php
include('../Common Data/PHP Modules/mysqliConnection.php');
ini_set("display_errors", "on");

$sql = "SELECT programCode FROM engineering_programslog WHERE logId = ".$_GET['logId']." ";
$getProgramsLog = $db->query($sql);
$getProgramsLogResult = $getProgramsLog->fetch_array();
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
	<link rel = "stylesheet" type = "text/css" href = "../Common Data/anthony.css">
</head>
<body>
<form>
	<textarea rows = "26" cols = "40" readonly><?php echo $getProgramsLogResult['programCode']; ?></textarea>
</form>
</body>
</html>
