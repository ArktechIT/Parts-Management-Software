<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	set_include_path($path);
	include('PHP Modules/mysqliConnection.php');
	include('PHP Modules/anthony_wholeNumber.php');
	include('PHP Modules/anthony_retrieveText.php');
	include('PHP Modules/gerald_functions.php');
	include('PHP Modules/gerald_sheetWorksFunction.php');
	ini_set("display_errors", "on");
	
	function getSheetWorksColumnTable($table)
	{
		include('PHP Modules/mysqliConnection.php');
		
		$columnNameArray = array();
		$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'arktechdatabase' AND TABLE_NAME = '".$table."'";
		$queryColumns = $db->query($sql);
		if($queryColumns AND $queryColumns->num_rows > 0)
		{
			while($resultColumns = $queryColumns->fetch_assoc())
			{
				if($resultColumns['COLUMN_NAME']=='listId' OR $resultColumns['COLUMN_NAME']=='batchId') continue;
				
				$columnNameArray[] = $resultColumns['COLUMN_NAME'];
			}
		}
		
		return $columnNameArray;
	}
	
	function getSheetWorksTable($column)
	{
		include('PHP Modules/mysqliConnection.php');
		
		$tableNameArray = array();
		$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'arktechdatabase' AND TABLE_NAME LIKE 'sheetworks_%' AND COLUMN_NAME = '".$column."'";
		$queryTables = $db->query($sql);
		if($queryTables AND $queryTables->num_rows > 0)
		{
			while($resultTables = $queryTables->fetch_assoc())
			{
				$tableNameArray[] = $resultTables['TABLE_NAME'];
			}
		}
		
		return $tableNameArray;
	}
	
	function getSheetWorksPrimaryKey()
	{
		include('PHP Modules/mysqliConnection.php');
		
		$primaryKeyArray = array();
		
		$sql = "SHOW tables FROM arktechdatabase LIKE 'sheetworks_%';";
		$queryArktechdatabase = $db->query($sql);
		while($resultArktechdatabase = $queryArktechdatabase->fetch_row())
		{   
			$table = $resultArktechdatabase[0];
			
			$sheetworksTable = $table;
			
			$numericDataTypes = array('tinyint','smallint','mediumint','int','bigint','decimal','float','double','double','tinyint','bigint');
			
			$sql = "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'arktechdatabase' AND TABLE_NAME = '".$table."'";
			$queryColumns = $db->query($sql);
			if($queryColumns AND $queryColumns->num_rows > 0)
			{
				while($resultColumns = $queryColumns->fetch_assoc())
				{
					if($resultColumns['COLUMN_NAME']=='listId' OR $resultColumns['COLUMN_NAME']=='batchId') continue;
					
					if(strstr($resultColumns['COLUMN_NAME'],'ID')!==FALSE)
					{
						$primaryKeyArray[$table] = $resultColumns['COLUMN_NAME'];
						
						break;
					}
				}
			}
		}	
		
		return $primaryKeyArray;	
	}
	
	function getSheetWorksPrimaryKeyValues($batchId,$columnKeyValuesArray,$columnKey)
	{
		include('PHP Modules/mysqliConnection.php');
		
		$tableNameArray = getSheetWorksTable($columnKey);
		
		$columnKeyArray = array_keys($columnKeyValuesArray);
		
		if(count($tableNameArray) > 0)
		{
			foreach($tableNameArray as $tableName)
			{
				$columnNameArray = getSheetWorksColumnTable($tableName);
				
				$tempColumnKeyArray = array_intersect($columnKeyArray, $columnNameArray);
				
				$sql = "SELECT ".implode(",",$tempColumnKeyArray)." FROM `".$tableName."` WHERE batchId = ".$batchId." AND ".$columnKey." IN('".implode("','",$columnKeyValuesArray[$columnKey])."')";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						foreach($tempColumnKeyArray as $column)
						{
							$columnKeyValuesArray[$column][] = $result[$column];
						}
					}
				}
				
				//~ if(count($tempColumnKeyArray) > 0)
				//~ {
					//~ foreach($tempColumnKeyArray as $tempColumnKey)
					//~ {
						//~ getSheetWorksPrimaryKeyValues($batchId,$columnKeyValuesArray,$tempColumnKey);
					//~ }
				//~ }
			}
		}
	}
	
	function displaySheetWorksTable($batchId,$columnKeyValuesArray)
	{
		include('PHP Modules/mysqliConnection.php');
		
		$sql = "SHOW tables FROM arktechdatabase LIKE 'sheetworks_%';";
		$queryArktechdatabase = $db->query($sql);
		while($resultArktechdatabase = $queryArktechdatabase->fetch_row())
		{   
			$table = $resultArktechdatabase[0];
			
			$sheetworksTable = $table;
			
			$columnNameArray = $numericFieldsArray = array();
			$sql = "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'arktechdatabase' AND TABLE_NAME = '".$table."'";
			$queryColumns = $db->query($sql);
			if($queryColumns AND $queryColumns->num_rows > 0)
			{
				while($resultColumns = $queryColumns->fetch_assoc())
				{
					if($resultColumns['COLUMN_NAME']=='listId' OR $resultColumns['COLUMN_NAME']=='batchId') continue;
					
					$columnNameArray[] = $resultColumns['COLUMN_NAME'];
				}
			}	
			
			$columnKey = $columnNameArray[0];
			
			echo "
				<hr><h2>".$sheetworksTable."</h2>
				<table border='1'>
					<tr>
						<th>".implode("</th><th>",$columnNameArray)."</th>
					</tr>
			";			
				
			$sql = "SELECT ".implode(",",$columnNameArray)." FROM `".$sheetworksTable."` WHERE batchId = ".$batchId." AND ".$columnKey." IN('".implode("','",$columnKeyValuesArray[$columnKey])."')";
			$querySheetworks = $db->query($sql);
			if($querySheetworks AND $querySheetworks->num_rows > 0)
			{
				while($resultSheetworks = $querySheetworks->fetch_assoc())
				{
					echo "<tr>";
					foreach($columnNameArray as $column)
					{
						echo "<td>".$resultSheetworks[$column]."</td>";
					}
					echo "</tr>";
				}
			}
			echo "</table>";
			
		}		
	}
	
	function displayTable($batchId,&$columnKeyValuesArray,$columnKey,&$tableNameDataArray)
	{
		include('PHP Modules/mysqliConnection.php');
		
		$tableNameArray = getSheetWorksTable($columnKey);
		
		$columnKeyArray = array_keys($columnKeyValuesArray);
		
		if(count($tableNameArray) > 0)
		{
			foreach($tableNameArray as $tableName)
			{
				$columnNameArray = getSheetWorksColumnTable($tableName);
				
				$newFlag = 0;
				
				$tempColumnKeyArray = array_intersect($columnKeyArray, $columnNameArray);
				
				//~ $newFlag = 0;
				//~ if(!in_array($columnNameArray[0],$columnKeyArray))
				//~ {
					//~ $columnKeyArray[] = $columnNameArray[0];
					//~ $columnKeyValuesArray[$columnNameArray[0]] = array();
					//~ $newFlag = 1;
				//~ }
				
				if(!in_array($tableName,$tableNameDataArray))
				{
					$tableNameDataArray[] = $tableName;
				}
				else
				{
					continue;
				}
				
				echo "
					<hr><h2>".$tableName."</h2>
					<table border='1'>
						<tr>
							<th>".implode("</th><th>",$columnNameArray)."</th>
						</tr>
				";	
				$sql = "SELECT ".implode(",",$columnNameArray)." FROM `".$tableName."` WHERE batchId = ".$batchId." AND ".$columnKey." IN('".implode("','",$columnKeyValuesArray[$columnKey])."')";
				$query = $db->query($sql);
				if($query AND $query->num_rows > 0)
				{
					while($result = $query->fetch_assoc())
					{
						echo "<tr>";
						foreach($columnNameArray as $column)
						{
							echo "<td>".$result[$column]."</td>";
							
							if(in_array($column,$columnKeyArray))
							{
								$columnKeyValuesArray[$column][] = $result[$column];
							}
							
							//~ if($newFlag == 1 AND $columnNameArray[0]==$column)
							//~ {
								//~ $columnKeyValuesArray[$columnNameArray[0]][] = $result[$column];
							//~ }
						}
						echo "</tr>";
					}
				}
				echo "</table>";
				
				//~ if($newFlag==1)
				//~ {
					//~ displayTable($batchId,$columnKeyValuesArray,$columnNameArray[0],$tableNameDataArray);
				//~ }
				
				if(count($tempColumnKeyArray) > 0)
				{
					foreach($tempColumnKeyArray as $tempColumnKey)
					{
						displayTable($batchId,$columnKeyValuesArray,$tempColumnKey,$tableNameDataArray);
					}
				}
			}
		}		
	}
	
	//~ $batchId = 20201016124515;
	//~ $PartID = 98;
	$keyType = $_GET['keyType'];
	$batchId = $_GET['batchId'];
	$keyId = $_GET['keyId'];
	$partId = (isset($_GET['partId'])) ? $_GET['partId'] : '';
	
	$primaryKeyArray = getSheetWorksPrimaryKey();
	
	$columnKeyValuesArray = array();
	foreach($primaryKeyArray as $table => $primaryKey)
	{
		//~ echo "<br>".++$count." ".$table." => ".$primaryKey;
		$columnKeyValuesArray[$primaryKey] = array();
	}
	
	if($partId!='')
	{
		$sql = "SELECT batchId, keyType, keyId FROM engineering_sheetworksdatanew WHERE partLevel = 1 AND partId = ".$partId." ORDER BY sheetWorksId DESC LIMIT 1";
		$querySheetWorksDataNew = $db->query($sql);
		if($querySheetWorksDataNew AND $querySheetWorksDataNew->num_rows > 0)
		{
			$resultSheetWorksDataNew = $querySheetWorksDataNew->fetch_assoc();
			$batchId = $resultSheetWorksDataNew['batchId'];
			$keyType = $resultSheetWorksDataNew['keyType'];
			$keyId = $resultSheetWorksDataNew['keyId'];
		}
	}
	
	if($keyType==0)
	{
		$PartID = $keyId;
		
		$columnKeyValuesArray['PartID'][] = $PartID;
		$tableNameDataArray = array();
		displayTable($batchId,$columnKeyValuesArray,'PartID',$tableNameDataArray);
		//~ getSheetWorksPrimaryKeyValues($batchId,$columnKeyValuesArray,'PartID',$tableNameDataArray);
		//~ displaySheetWorksTable($batchId,$columnKeyValuesArray);
	}
	else if($keyType==1)
	{
		$ProductID = $keyId;
		
		$columnKeyValuesArray['ProductID'][] = $ProductID;
		$tableNameDataArray = array();
		displayTable($batchId,$columnKeyValuesArray,'ProductID',$tableNameDataArray);	
		//~ getSheetWorksPrimaryKeyValues($batchId,$columnKeyValuesArray,'ProductID',$tableNameDataArray);	
		//~ displaySheetWorksTable($batchId,$columnKeyValuesArray);		
	}
?>
