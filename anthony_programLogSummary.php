<?php
include('../Common Data/PHP Modules/mysqliConnection.php');
include('../Common Data/PHP Modules/anthony_retrieveText.php');
ini_set("display_errors", "on");
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
    <title>Arktech Philippines Incorporated</title>
    <link rel = 'stylesheet' type = 'text/css' href = '../Common Data/anthony.css'>

<style>
	#tempId
	{
		border:1px solid yellow;
	}
</style>
</head>
<div class="" style='overflow-x:scroll;'><!-- style='overflow-x:scroll;' -->
<h2 class="art-postheader"><center><?php echo displayText('L81');?></center></h2>
		<form action='anthony_programLogSummary.php?partId=<?php echo $_GET['partId']; ?>' method='POST'>
		From: <input type = "date" name = "from" value = "<?php if(isset($_POST['date'])){ echo $_POST['from']; } ?>">
		To: <input type = "date" name = "to" value = "<?php if(isset($_POST['date'])){ echo $_POST['to']; } ?>">
		<input type = "submit" name = "date" value = "Date Range" class = "anthony_submit">
	<center>	
	<div style="border:2px solid;border-radius:25px;display:inline-block;width:auto;padding: 10px;">
		
		<table id="table10" class="mytable" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>Program Code</th>
					<th><?php echo displayText('L292');?></th>                    
					<th>IP</th>
					<th><?php echo displayText('L577');?></th>
					<th><?php echo displayText('L242');?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$sql = "SELECT programId FROM engineering_programs WHERE partId = ".$_GET['partId']." ";
			$getProgramID = $db->query($sql);
			if($getProgramID->num_rows > 0)
			{
				while($getProgramIDResult = $getProgramID->fetch_array())
				{				
					if(isset($_POST['date']))
					{
						$sql = "SELECT logId, programCode, date, IP, user, remarks FROM engineering_programslog WHERE programId = ".$getProgramIDResult['programId']." AND DATE_FORMAT(date, '%Y-%m-%d') BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' ";
					}
					else
					{
						$sql = "SELECT logId, programCode, date, IP, user, remarks FROM engineering_programslog WHERE programId = ".$getProgramIDResult['programId']." ORDER BY date DESC ";
					}
					$getProgramsLog = $db->query($sql);
					if($getProgramsLog->num_rows > 0)
					{
						while($getProgramsLogResult = $getProgramsLog->fetch_array())
						{
							echo "<tr>";
								if(strlen($getProgramsLogResult['programCode']) > 73)
								{ ?>
									<td><a onclick="TINY.box.show({url:'anthony_programsLogTinyBox.php?logId=<?php echo $getProgramsLogResult['logId']; ?>',width:340,height:395,opacity:10,topsplit:6,animate:false,close:true})"><?php echo substr($getProgramsLogResult['programCode'],0,73).'...'; ?></a></td>
								<?php
								}
								else
								{ ?>
									<td><a onclick="TINY.box.show({url:'anthony_programsLogTinyBox.php?logId=<?php echo $getProgramsLogResult['logId']; ?>',width:340,height:395,opacity:10,topsplit:6,animate:false,close:true})"><?php echo $getProgramsLogResult['programCode']; ?></a></td>
								<?php
								}
								
								echo "<td>".$getProgramsLogResult['date']."</td>";
								echo "<td>".$getProgramsLogResult['IP']."</td>";
								echo "<td>".$getProgramsLogResult['user']."</td>";
								echo "<td>".$getProgramsLogResult['remarks']."</td>";
							echo "</tr>";
						}
					}					
				}
			}
			else
			{
				echo "<tr>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
				echo "</tr>";
				
				echo "<tr>";
					echo "<td colspan = '5' align = 'center'>No Program Log/History</td>";
				echo "</tr>";
			}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
		</form>
	</div>
	</center>
</div>
<!-- -------------------------------------------TABLE FILTER JQUERY----------------------------------------------------------><!-- -------------------------------------------TABLE FILTER JQUERY---------------------------------------------------------->
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/Table Filter/style2.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="../Common Data/Libraries/Javascript/Table Filter/filtergrid3.css" />
<style>.scrollableTable tbody tr:hover{background: linear-gradient(#DAE2EB, #C1CFDD);}</style>
<style type="text/css">/* Sortable tables */ table.mytable a.sortheader {background-color:#eee;color:#666666;font-weight: bold;text-decoration: none;display: block;}table.mytable span.sortarrow {color: black;text-decoration: none;}</style>
<style type="text/css" media="screen">div#navmenu li a#lnk02{color:#333; font-weight:bold; border-top:2px solid #ff9900;background:#fff;}</style>
<style type="text/css">.scrollableTable tbody {display: block;height:450px;width: 1230px;overflow-y:scroll;}.scrollableTable thead {display: block;width: 1230px;}.scrollableTable tfoot {display: block;width: 1230px;}</style>
<script src="../Common Data/Libraries/Javascript/Table Filter/tablefilter_all_min.js" language="javascript" type="text/javascript"></script>
<script type="text/javascript" src="../Common Data/Libraries/Javascript/Table Filter/sorttable.js"></script>
<script src="../Common Data/Libraries/Javascript/Table Filter/scroll3.js"></script>
<script type="text/javascript">var sizeColWidths = function() {$('#table10 td, #table10 th').css('width', 'auto');$('#table10').removeClass('scrollableTable');$('#table10 tbody').css('width', 'auto');var i=0, colWidth=new Array();$('#table10 th').each(function() {colWidth[i++] = $(this).outerWidth();});$('#table10 tr').each(function() {var i=0;$('th, td', this).each(function() {$(this).css('width', colWidth[i++] + 'px');})});$('#table10').addClass('scrollableTable');$('#table10 tbody').css('width', ($('#table10 thead').width() + 20) +'px');};$(document).ready(function() {sizeColWidths()});$(window).resize(function() {sizeColWidths()});</script>
<script language="javascript" type="text/javascript">
	var totRowIndex = tf_Tag(tf_Id('table10'),"tr").length;
    var table10_Props = {                            
							filters_row_index: 1,
							alternate_rows: true,
							rows_counter: true,
							showRowHeader: true,
                            loader: true,
                            loader_text: "Filtering data...",
							col_number_format: [null,null,null,null,null],
                            
                            rows_always_visible: [totRowIndex],
                            col_0: 'none',col_1: 'none',col_2: 'none',col_3: 'none',col_4: 'none',
                            refresh_filters: true
                        };
    var tf10 = setFilterGrid( "table10",table10_Props );
</script>
<!-- -----------------------------------START SMALL BOX------------------------------------------------------------- -->
<script type="text/javascript" src="../Common Data/Libraries/Javascript/Tiny Box/tinybox.js"></script>
<link rel="stylesheet" href="../Common Data/Libraries/Javascript/Tiny Box/stylebox.css" />
<script type="text/javascript">
function openJS(){alert('loaded')}
function closeJS(){alert('closed')}
</script>   
<!-- -----------------------------------END SMALL BOX----------------------------------------------------------------> 
