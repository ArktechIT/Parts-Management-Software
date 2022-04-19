<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');
?>
<style>
div {
	line-height: 15px;
}

label
{
	float: left;
	width: 6em;
	margin-right: 1em;
	text-align: right;
}
</style>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo displayText('L1430');//Automated Subpart Input Form ?></title>
</head>
<body>
<?php
// ---------------------------------------- First Form ---------------------------------------------------- 
if(!isset($_POST['action']))
{
?>
<form action = "ace_addAutomatedSubpartsForm.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>" method = "POST">
	<center>
		<h2><?php echo displayText('L1430');//Automated Subpart Input Form ?></h2>		
		
		<div>
			<?php echo displayText('L1431');//Number of Subpart ?> <input type = "number" name = "quantity" min = "1">
		</div>
		<br>
		
		<input type="hidden" name="action" value="generate">
				
		<div>
			<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<div id="submitButton">
				<input type ="submit" name = "submit" value = "<?php echo displayText('B4');//Add ?>" class="art-button">
			</div>
			</span>
		</div>
		
	</center>		
</form>
<?php
}
// -------------------------------------- End of First Form ------------------------------------------------
// ------------------------------------------ Second Form --------------------------------------------------
else
{
?>
	<form action = "ace_insertSql.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>" method = "POST">
		<center>
			<h2><?php echo displayText('L1430');//Automated Subpart Input Form ?></h2>
			
			<table border = 1>
				<tr>
					<td><center><?php echo displayText('L28');//Part Number ?></center></td>
					<td><center><?php echo displayText('L1934');//Revision ?></center></td>
					<td><center><?php echo displayText('L31');//Quantity ?></center></td>
					<td><center><?php echo displayText('L59');//Process ?></center></td>
				</tr>
				
				<?php
				
				//~ if($_SESSION['idNumber']=='0346')
				//~ {
					$selectProcess = "<select name = 'processCode[]' id = 'process' required>
						<option value=''>Select Process</option>";
						
							$sql = "SELECT processCode FROM cadcam_partprocess WHERE partId = ".$_GET['partId']." AND patternId = ".$_GET['patternId']." ORDER BY processOrder";
							$queryPartProcess = $db->query($sql);
							if($queryPartProcess AND $queryPartProcess->num_rows > 0)
							{
								while($resultPartProcess = $queryPartProcess->fetch_assoc())
								{
									$processCode = $resultPartProcess['processCode'];
									
									$processName = '';
									$sql = "SELECT processName FROM cadcam_process WHERE processCode = ".$processCode." LIMIT 1";
									$queryProcess = $db->query($sql);
									if($queryProcess AND $queryProcess->num_rows > 0)
									{
										$resultProcess = $queryProcess->fetch_assoc();
										$processName = $resultProcess['processName'];
									}
									
									$selectProcess .= "<option value='".$processCode."'>".$processName."</option>";
								}
							}
						
					$selectProcess .= "</select>";
				//~ }
				
				$sql = "SELECT partNumber, revisionId, customerId FROM cadcam_parts WHERE partId = ".$_GET['partId'];
				$partNumberQuery = $db->query($sql);
				$partNumberQueryResult = $partNumberQuery->fetch_array();
				echo "<input type='hidden' name='customerId' value='".$partNumberQueryResult['customerId']."'>";
				for($i=0;$i<$_POST['quantity'];$i++)
				{
				$partNumber =  $partNumberQueryResult['partNumber']."-".($i+1);
					echo "<tr>";
						echo "<td>";
							echo "<input type='text' name='partNumber[]' value='".$partNumber."'>";
						echo "</td>";
						echo "<td>";
							echo "<input type='text' name='revisionId[]' value='".$partNumberQueryResult['revisionId']."' required>";
						echo "</td>";
						echo "<td>";
							echo "<input type='number' name='quantity[]' value='' min='1' required>";
						echo "</td>";
						echo "<td>";
							echo $selectProcess;
						echo "</td>";
					echo "</tr>";
				}
				?>
			</table>
		
			<br>		
			<div>
				<span class="art-button-wrapper">
				<span class="art-button-l"></span>
				<span class="art-button-r"></span>
				<div id="submitButton">
					<input type ="submit" name = "submit" value = "<?php echo displayText('L1052');//Save ?>" class="art-button">
				</div>
				</span>
			</div>
		</center>		
	</form>
<?php
}
?>
<!-- ------------------------------------- End of Second Form ----------------------------------------------- -->

</body>
</html>
