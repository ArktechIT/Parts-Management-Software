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

$addQuery = array();
$partIdGet = isset($_GET['partId']) ? $_GET['partId'] : '';

if($partIdGet != '')
{
	$addQuery[] = ' AND partId = '.$partIdGet;
}

$mergeQuery = implode(' AND ', $addQuery);

$showFixed = isset($_POST['showFixed']) ? $_POST['showFixed'] : '0';
$selectedFixed = '';
if($showFixed == 1)
{
	$showFixed = '0,1';
	$selectedFixed = 'checked';
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
	<?php
		$sql = "SELECT partId FROM engineering_partsdetailsalert WHERE status IN (".$showFixed.") ".$mergeQuery."";
		$queryCount = $db->query($sql);
		$count = $queryCount->num_rows;
	?>
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

		.tbody 
		{
			display:block;
			<?php
				if($count > 10)
				{
			?>
				height:300px;
			<?php
				}
				else
				{
			?>
				height:;
			<?php
				}
			?>
			overflow:auto;
		}
		thead, tbody tr 
		{
			display:table;
			width:100%;
			table-layout:fixed;
		}
		thead {
			<?php
				if($count > 10)
				{
			?>
				width: calc( 100% - 1.2em)
			<?php
				}
				else
				{
			?>
				width: calc( 100%)
			<?php
				}
			?>
		}
		body
		{
			background-image: url("../images/Bottom_texture.jpg");
		}
	</style>
	<title>Parts Alert List</title>
	<body>
	<form id="filterForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>"></form>
	<br>
	<br>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<a onclick="location.href='../dashboard.php'" class="w3-btn w3-blue w3-small"><span class="glyphicon glyphicon-home"></span> <?php echo displayText('L1');?></a>
					<a onclick="history.back()" class="w3-btn w3-green w3-small"><span class="glyphicon glyphicon-backward"></span> <?php echo displayText('L1724');?></a>
					<a onclick="location.reload()" class="w3-btn w3-red w3-large pull-right	"><span class="glyphicon glyphicon-refresh"></span></a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<center><h3><b>Parts Alert List</b></h3></center>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="pull-left">
						<p></p>
						<label><?php echo displayText('L41')?> : <?php echo $count; ?></label>
					</div>
				</div>
				<div class="col-md-6">
					<div class="pull-right">
						<input <?php echo $selectedFixed; ?> type="checkbox" class="w3-check" name="showFixed" id="showFixed" value="1" onchange="this.form.submit();" form="filterForm">
						<label style="font-size: 13px;"><?php echo displayText('L937')?></label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default w3-card-4">
						<table class="table table-bordered table-condensed">
							<thead class="w3-black">
								<th class="text-center w3-text-sand" width='250px'><?php echo displayText('L28')?></th>
								<th class="text-center w3-text-sand"><?php echo displayText('L30')?></th>
								<th class="text-center w3-text-sand" width='80px'><?php echo displayText('L1934')?></th>
								<th class="text-center w3-text-sand" width='100px'><?php echo displayText('L172')?></th>
								<th class="text-center w3-text-sand" width='150px'><?php echo displayText('L1120')?></th>
								<th class="text-center w3-text-sand" width='150px'><?php echo displayText('L939')?></th>
							</thead>
							<tbody class="tbody">
								<?php 
									$sql = "SELECT partId, status FROM engineering_partsdetailsalert WHERE status IN (".$showFixed.") ".$mergeQuery."";
									$queryList = $db->query($sql);
									if($queryList AND $queryList->num_rows > 0)
									{
										while ($resultList = $queryList->fetch_assoc()) 
										{
											$action = '';
											$sql = "SELECT partNumber, partName, revisionId FROM cadcam_parts WHERE partId = ".$resultList['partId'];
											$queryCadcamParts = $db->query($sql);
											if($queryCadcamParts AND $queryCadcamParts->num_rows > 0)
											{
												$resultCadcamParts = $queryCadcamParts->fetch_assoc();
												$partNumber = $resultCadcamParts['partNumber'];
												$partName = $resultCadcamParts['partName'];
												$revision = $resultCadcamParts['revisionId'];

												$status = ($resultList['status'] == 0) ? '<span class="w3-text-pink"><b>Alert</b></span>' : '<span class="w3-text-green"><b>Fixed</b></span>';

												if($status == '<span class="w3-text-pink"><b>Alert</b></span>')
												{
													$action = "<a onclick=\"TINY.box.show({url:'raymond_updateAlertStatus.php?partId=".$resultList['partId']."',width:420,height:'auto',opacity:10,topsplit:6,animate:false,close:true})\" class='w3-btn w3-red w3-ripple w3-tiny w3-card-2'>KEY</a>";
												}
												else
												{
													$action = "<a onclick=\"TINY.box.show({url:'raymond_viewAlertStatus.php?partId=".$resultList['partId']."',width:420,height:'auto',opacity:10,topsplit:6,animate:false,close:true})\" class='w3-btn w3-blue w3-ripple w3-tiny w3-card-2'>View</a>";
												}

												$affectedLot = "<a onclick=\"TINY.box.show({url:'raymond_viewAffectedLot.php?partId=".$resultList['partId']."&partNumber=".$partNumber."&revisionId=".$revision."',width:420,height:'auto',opacity:10,topsplit:6,animate:false,close:true})\" class='w3-btn w3-blue w3-ripple w3-tiny w3-card-2'>View</a>";

												echo "<tr>";
													echo "<td width='250px'>".$partNumber."</td>";
													echo "<td>".$partName."</td>";
													echo "<td class='text-center' width='80px'>".$revision."</td>";
													echo "<td class='text-center' width='100px'>".$status."</td>";
													echo "<td class='text-center' width='150px'>".$action."</td>";
													echo "<td class='text-center' width='150px'>".$affectedLot."</td>";
												echo "</tr>";

											}
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript" src="../Common Data/Libraries/Javascript/Tiny Box/tinybox.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/Tiny Box/stylebox.css" />
