<?php 
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);    
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");

if(isset($_POST['partComment']))
{
    $partComment = $_POST['partComment'];
}
if(isset($_POST['btnAdd']))
{
    $sql = "INSERT INTO cadcam_partsComment (partsComment) VALUES ('".$partComment."')";
    $queryAddComment = $db->query($sql);

    header('Location:jhon_partsMasterCommentList.php');

    exit(0);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <form action="jhon_partCommentAdd.php" method="post">
            <div class="row">
                <div class="col-12">
                    <label>Part Comment</label>
                    <textarea type="text" name="partComment" id="partComment" cols="56" rows="10" style="resize:none;"></textarea>
                </div>
                <div class="col-auto text-center">
                    <button type="submit" name="btnAdd" class="w3-btn w3-round w3-indigo">Submit</button>
                </div>
            </div>
        </form>
    </div>  
</body>
</html>