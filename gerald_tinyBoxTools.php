<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	// $path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	$javascriptLib = "/Common Data/Libraries/Javascript/";
	$templates = "/Common Data/Templates/";
	set_include_path($path);	
	include('PHP Modules/mysqliConnection.php');
	include('PHP Modules/anthony_retrieveText.php');
	ini_set("display_errors", "on");
	
	if($_GET['type']=='viewTools')
	{ 
		$partId = $_POST['partId'];
		$processCode = $_POST['processCode'];
		$toolIdArray = array();
		echo "<div id='tableDiv'>";
		$sql = "SELECT `listId`, `toolId` FROM `cadcam_processtoolsdetails` WHERE `partId` = ".$partId." AND `processCode` = ".$processCode."";
		$queryToolDetails = $db->query($sql);
		if($queryToolDetails->num_rows > 0)
		{
			echo "
				<table border='1' style='width:300px;'>
					<tr>
						<td style='width:5%;'></td>
						<td >".displayText('L370')."</td>						
						<td style='width:2%;'></td>
					</tr>
			";
			$count = 0;
			while($resultToolDetails = $queryToolDetails->fetch_array())
			{
				$listId = $resultToolDetails['listId'];
				$toolId = $resultToolDetails['toolId'];
				
				$toolIdArray[] = $toolId;
				
				$toolName = '';
				$sql = "SELECT toolName FROM cadcam_processtools WHERE toolId = ".$toolId." LIMIT 1";
				$queryToolName = $db->query($sql);
				if($queryToolName->num_rows > 0)
				{
					$resultToolName = $queryToolName->fetch_array();
					$toolName = $resultToolName['toolName'];
				}
				echo "
					<tr>
						<td>".++$count."</td>
						<td>".$toolName."</td>						
						<td ><a href='gerald_processToolSql.php?type=deleteTool&listId=".$listId."&partId=".$partId."'><img src='".$templates."images/close1.png' height='15' title='Delete'></a></td>
					</tr>
				";
			}
			echo "</table>";
		}
		?>
		<form action='gerald_processToolSql.php?type=addTool' method='post' id='addToolForm'></form>
		<input type='hidden' name='partId' value='<?php echo $partId;?>' form='addToolForm'>
		<input type='hidden' name='processCode' value='<?php echo $processCode;?>' form='addToolForm'>
		<table border='1'>
			<tr>
				<td><?php echo displayText('L370');?></td>
				<td>
					<select name='toolId' required form='addToolForm'>
						<option value=""></option>
						<?php
							if(count($toolIdArray) > 0)
							{
								$sql = "SELECT toolId, toolName FROM cadcam_processtools WHERE toolId NOT IN(".implode(",",$toolIdArray).") ORDER BY toolName";
							}
							else
							{
								$sql = "SELECT toolId, toolName FROM cadcam_processtools WHERE toolId ORDER BY toolName";
							}
							$queryTools = $db->query($sql);
							if($queryTools->num_rows > 0)
							{
								while($resultTools = $queryTools->fetch_array())
								{
									?><option value='<?php echo $resultTools['toolId'];?>'><?php echo $resultTools['toolName'];?></option><?php
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2'><center><input type='submit' value='ADD' form='addToolForm'></center></td>
			</tr>
		</table>
		</div>
		<?php
	}
	/*
	else if($_GET['type']=='editTool')
	{
		$listId = $_POST['listId'];
		
		$partId = $processCode = '';
		$sql = "SELECT partId, processCode FROM cadcam_processtoolsdetails WHERE listId = ".$listId." LIMIT 1";
		$queryToolDetails = $db->query($sql);
		if($queryToolDetails->num_rows > 0)
		{
			$resultToolDetails = $queryToolDetails->fetch_array();
			{
				$partId = $resultToolDetails['partId'];
				$processCode = $resultToolDetails['processCode'];
			}
		}
		
		$toolIdArray = array();
		$sql = "SELECT toolId FROM cadcam_processtoolsdetails WHERE partId = ".$partId." AND processCode = ".$processCode."";
		$queryToolDetails = $db->query($sql);
		if($queryToolDetails->num_rows > 0)
		{
			while($resultToolDetails = $queryToolDetails->fetch_array())
			{
				$toolIdArray[] = $resultToolDetails['toolId'];
			}
		}
		
		?>
		<form action='gerald_processToolSql.php?type=editTool' method='post' id='editToolForm'></form>
		<input type='hidden' name='listId' value='<?php echo $listId;?>' form='editToolForm'>
		<input type='hidden' name='toolId' value='<?php echo $toolId;?>' form='editToolForm'>
		<input type='hidden' name='partId' value='<?php echo $partId;?>' form='editToolForm'>
		<table border='1'>
			<tr>
				<td>Tool Name :</td>
				<td>
					<select name='toolId' required form='editToolForm'>
						<option value=""></option>
						<?php
							$sql = "SELECT toolId, toolName FROM cadcam_processtools WHERE toolId NOT IN(".implode(",",$toolIdArray).")";
							$queryTools = $db->query($sql);
							if($queryTools->num_rows > 0)
							{
								while($resultTools = $queryTools->fetch_array())
								{
									$selected = ($toolId==$resultTools['toolId']) ? 'selected' : '' ;
									?><option value='<?php echo $resultTools['toolId'];?>' <?php echo $selected;?>><?php echo $resultTools['toolName'];?></option><?php
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2'><center><input type='submit' value='EDIT' form='editToolForm'></center></td>
			</tr>
		</table>
		</div>
		<?php
	}
	*/
?>
