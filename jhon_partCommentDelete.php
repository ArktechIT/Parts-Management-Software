<?php 
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);    
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");

$commentId = $_POST['commentId'];

$sql ="SELECT partsComment FROM cadcam_partsComment WHERE commentId='".$commentId."'";
$queryEditComment = $db->query($sql);
$resultComment = $queryEditComment->fetch_assoc();

$partComment = $resultComment['partsComment'];

// if(isset($_POST['partComment']))
// {
//     $partComment = $_POST['partComment'];
// }
if(isset($_POST['idCom']))
{
    $comId = $_POST['idCom'];
}
if(isset($_POST['btnDelete']))
{
    $sql = "DELETE FROM cadcam_partsComment WHERE commentId = '".$comId."'";
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
        <form action="jhon_partCommentDelete.php" method="post">
            <div class="row">
                <div class="col-12">
                    <input type="hidden" name="idCom" id="idCom" value="<?php echo $commentId; ?>">
                    <p class="text-center" style="font-size:30px;"><b>Are you sure you want to delete this comment?</b></p>
                    
                </div>
                <div class="col-auto text-center">
                    <button type="submit" name="btnDelete" class="w3-btn w3-round w3-indigo">Delete</button>
                </div>
            </div>
        </form>
    </div>  
</body>
</html>