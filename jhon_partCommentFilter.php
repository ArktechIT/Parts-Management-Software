<?php 
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);    
include('Templates/mysqliConnection.php');
ini_set("display_errors", "on");

$partComment = isset($_POST['partComments'])? $_POST['partComments']:'';


$sqlFilterArray = array();


if($partComment!='')	$sqlFilterArray[] = "partsComment LIKE '%".$partComment."%'";



$orderBy = "ORDER BY commentId DESC";
$sqlFilter = "WHERE commentId > 0";
if(count($sqlFilterArray) > 0)
{
    $sqlFilter .= " AND ".implode(" AND ",$sqlFilterArray );
}


$sql = "SELECT partsComment FROM cadcam_partsComment";
$queryPComment = $db->query($sql);
$resultPComment = $queryPComment->fetch_assoc();
$sqlData=$sql;
$totalRecords=$queryPComment->num_rows;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>

    <div class="container-fluid">
        <form action="jhon_partsMasterCommentList.php" method="post">
        <div class="row">
            <div class="col-12">
                <label for="">Part Comment:</label>
                <input type="text" list="partComments" id="partComment" name="partComments" class="form-control" aria-describedby="passwordHelpInline" value="<?php echo $partComment;?>">
                    <datalist id="partComments">
                            <?php
                               
                                $sql= "SELECT DISTINCT partsComment FROM cadcam_partsComment  ".$sqlFilter." ORDER BY partsComment";

                                $queryComment = $db->query($sql) or die ($db->error);

                                while($resultComment = $queryComment->fetch_assoc())
                                { 
                                    ?>
                                    <option value="<?php echo $resultComment['partsComment'];?>"><?php echo $resultComment['partsComment'];?></option>
                                
                                    <?php 
                                } 
                            ?>
    
                    </datalist>
            </div>
            
        </div>

        
        <div class="row">
            <div class="col-12 text-center">
                <button type="submit" class="btn w3-indigo" style="margin-top:20px;width:120px;" name="btnSearch" id="btnSearch">Search</button>
            </div>
            
        </div>
        </form>
    </div>
</body>
</html>