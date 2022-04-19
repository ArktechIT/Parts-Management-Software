<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
// $path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
$javascriptLib = "/".v."/Common Data/Libraries/Javascript/";
$templates = "/".v."/Common Data/Libraries/Javascript/";
set_include_path($path);    
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_wholeNumber.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
ini_set("display_errors", "on");

$partIdGet = isset($_GET['partId']) ? $_GET['partId'] : '';
$partNumberGet = isset($_GET['partNumber']) ? $_GET['partNumber'] : '';
$revisionIdGet = isset($_GET['revisionId']) ? $_GET['revisionId'] : '';

if($revisionIdGet != "")
{
	$addQuery = " AND revisionId LIKE '".$revisionIdGet."'";
}
?>
<html>
	<head>
	  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  	<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/css/bootstrap-theme.css">
		<script type="text/javascript" src="../Common Data/Libraries/Javascript/Jquery Charts/jquery-1.11.1.min.js"></script>
		<script src="../Common Data/Templates/Bootstrap/js/bootstrap.min.js"></script>
		<script src="../Common Data/Templates/Bootstrap/bootstrap-combobox-master/js/bootstrap-combobox.js"></script>
		<link rel="stylesheet" type="text/css" href="../Common Data/Libraries/Javascript/sweetAlert2/dist/sweetalert2.css">
		<script src="../Common Data/Libraries/Javascript/sweetAlert2/dist/sweetalert2.min.js"></script>
		<link rel="stylesheet" href="../Common Data/Templates/Bootstrap/w3css/w3.css">
	</head>
	<style type="text/css">
		label, th,td
		{
			font-size: 12px;
		}
		.input-xs 
		{
			height: 22px;
			width: 150px;
			padding: 2px 5px;
			font-size: 12px;
			line-height: 1.5;
			border-radius: 3px;
		}

		.input-group-xs>.form-control,
		.input-group-xs>.input-group-addon,
		.input-group-xs>.input-group-btn>.btn 
		{
			height: 22px;
			padding: 1px 5px;
			font-size: 12px;
			line-height: 1.5;
		}
	</style>
	<title></title>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<center><h4><b>Lot Affected</b></h4></center>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-4">
					<table class="table table-bordered table-condensed">
						<tr class="w3-red">
							<th class="text-center"><?php echo displayText('L28');?></th>							
							<th class="text-center">Revison</th>							
						</tr>
						<tr>	
							<td class="text-center"><?php echo $partNumberGet; ?></td>							
							<td class="text-center"><?php echo $revisionIdGet; ?></td>							
						</tr>
						<tr>
							<th class="text-center w3-blue" colspan="2">Lot Number(s)</th>	
						</tr>
						<?php
							$i = 1; 
							$sql = "SELECT DISTINCT lotNumber FROM view_workschedule WHERE partNumber = '".$partNumberGet."' ".$addQuery."";
							$queryLotNumber = $db->query($sql);
							if ($queryLotNumber AND $queryLotNumber->num_rows > 0)
							{
								while ($resultLotNumber = $queryLotNumber->fetch_assoc())
								{
									$lotNumber = $resultLotNumber['lotNumber'];
									echo "<tr>";
										echo "<td><b>".$i++.".</b> <a onclick=\"window.open('../16 Lot Details Management Software/ace_lotDetails.php?submitButton=SUBMIT&inputLot=".$lotNumber."');\">".$lotNumber."</td>";
									echo "</tr>";
								}
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript" src="../Common Data/Libraries/Javascript/Tiny Box/tinybox.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/Tiny Box/stylebox.css" />
