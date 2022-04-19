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

$partIdGet = isset($_GET['partId']) ? $_GET['partId'] : $_POST['partId'];
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
		?>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<center><h3><b>Parts Alert Details</b></h3></center>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
				<form id="formSubmit" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>"></form>
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
							<td><select type="combobox" name="alertStatus" form="formSubmit" class="form-control input-xs">
									<option value="0">Alert</option>
									<option value="1">Fixed</option>
								</select>
							</td>
						</tr>
					</table>
					<center><input type="submit" name="submit" value="Submit" form="formSubmit" class="w3-btn w3-green w3-ripple"></center>
					<input type="hidden" name="partId" value="<?php echo $partIdGet; ?>" form="formSubmit">
				</div>
			</div>
		</div>
	</body>
</html>
<?php
if(isset($_POST['submit']))
{
	$sql = "UPDATE engineering_partsdetailsalert SET status=".$_POST['alertStatus'].", fixedDate = now(), employeeFixed = '".$_SESSION['userID']."' WHERE partId =".$_POST['partId'];
	$queryUpdate = $db->query($sql);

	header("location: raymond_partsAlertList.php?partId=".$_POST['partId']);
}
?>
<script type="text/javascript" src="../Common Data/Libraries/Javascript/Tiny Box/tinybox.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/Tiny Box/stylebox.css" />
