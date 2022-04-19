<?php 
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);    
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");

$partId = isset($_POST['partId']) ? $_POST['partId']:'';

$sql="SELECT partId,partsComment FROM cadcam_parts WHERE partId = '".$partId."'";
$queryPartsComment = $db->query($sql);
$resultPartsComment = $queryPartsComment->fetch_assoc();
$idPart = $resultPartsComment['partId'];

$partComment = isset($_POST['partsCom']) ? $_POST['partsCom'] : '' ;

if(isset($_POST['partIdHidden']))
{
    $idPartsCom = $_POST['partIdHidden'];
}


if(isset($_POST['btnUpdate']))
{

    $sql2="UPDATE cadcam_parts SET partsComment ='".$_POST['partsCom']."' WHERE partId = '".$idPartsCom."' ";
    $queryPartsComment = $db->query($sql2);
    header('Location:anthony_editProduct.php?partId='.$idPartsCom .'&src=process&patternId=0');
    exit(0);
    


}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parts Comment</title>
    
</head>
<body>
    <form action="jhon_updatePartsComment.php" method="post" id="formPartsComment"></form>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <input type="hidden" name="partIdHidden" id="" value="<?php echo $partId; ?>" form="formPartsComment">
                    <label>Parts Comment</label>
                    <textarea name="partsCom" id="" cols="62" rows="10" style ="resize:none;" form="formPartsComment"><?php echo $resultPartsComment['partsComment']; ?></textarea>
                </div>
                <div class="col-2 text-center">
                    <button type="submit" name="btnUpdate" class="w3-btn w3-round w3-indigo" form="formPartsComment">Update</button>
                </div>

            </div>
            
        </div>

</body>
</html>