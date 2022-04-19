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
		<?php
			$sql = "SELECT partNumber, partName FROM cadcam_parts WHERE partId = ".$partIdGet;
			$query = $db->query($sql);
			$result = $query->fetch_assoc();

			$partNumber = $result['partNumber'];
			$partName = $result['partName'];

			
			$sql = "SELECT alertDate, fixedDate, employeeFixed, status FROM engineering_partsdetailsalert WHERE partId = ".$partIdGet;
			$queryDetailsAlert = $db->query($sql);
			$resultDetailsAlert = $queryDetailsAlert->fetch_assoc();

			$status = $resultDetailsAlert['status'];
			$alertDate = $resultDetailsAlert['alertDate'];
			$fixedDate = $resultDetailsAlert['fixedDate'];
			$employeeFixed = $resultDetailsAlert['employeeFixed'];

			$sql = "SELECT employeeId FROM system_users WHERE userName = '".$employeeFixed."'";
			$queryEmployeeId = $db->query($sql);
			$resultEmployeeId = $queryEmployeeId->fetch_assoc();
			$employeeId = $resultEmployeeId['employeeId'];
			
			$sql = "SELECT firstName, surName FROM hr_employee WHERE employeeId = ".$employeeId."";
			$queryEmployeeFixed = $db->query($sql);
			$resultEmployeeFixed = $queryEmployeeFixed->fetch_assoc();

			$firstName = $resultEmployeeFixed['firstName'];
			$surName = $resultEmployeeFixed['surName'];

			$employeeFixed = $surName.", ".$firstName;

			$alertDate = date('M d, Y h:i:s A', strtotime($alertDate));
			$fixedDate = date('M d, Y h:i:s A', strtotime($fixedDate));

			$status = ($status == 0) ? 'Alert' : 'Fixed';
		?>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<center><h3><b>Parts Alert Details</b></h3></center>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-4">
					<table class="table table-bordered table-condensed">
						<tr>
							<td>Part Number : </td>							
							<td><?php echo $partNumber; ?></td>							
						</tr>
						<tr>
							<td>Part Name : </td>							
							<td><?php echo $partName; ?></td>							
						</tr>
						<tr>
							<td>Status : </td>							
							<td><?php echo $status; ?></td>							
						</tr>
						<tr>
							<td>Alert Date : </td>							
							<td><?php echo $alertDate; ?></td>							
						</tr>
						<tr>
							<td>Fixed Date : </td>							
							<td><?php echo $fixedDate; ?></td>							
						</tr>
						<tr>
							<td>By : </td>							
							<td><?php echo $employeeFixed; ?></td>							
						</tr>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript" src="../Common Data/Libraries/Javascript/Tiny Box/tinybox.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/Tiny Box/stylebox.css" />
