<?php 
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);    
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");

$idNumber = $_SESSION['idNumber'];

$partIds = (isset($_POST['partId'])?$_POST['partId']:'');

if(isset($_POST['partIdHidden']))
{
    $idPartsCom = $_POST['partIdHidden'];
    $partIdExplode = explode(' ',$idPartsCom);

}

if(isset($_POST['btnSubmit']))
{

        foreach($partIdExplode as $partId)
        {
                $sql2="UPDATE cadcam_parts SET partsComment ='".$_POST['partsCom']."' WHERE partId = '".$partId."'";
                $queryPartsComment = $db->query($sql2);
                header('Location:jazmin_product.php?softwareIdentifier=masterView');
            
        }
    

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
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>

<style>
 #snackbar{
  visibility: hidden;
  min-width: 250px;
  margin-left: -125px;
  background-color: #333;
  color: #fff;
  text-align: center;
  border-radius: 2px;
  padding: 16px;
  position: fixed;
  z-index: 1;
  left: 50%;
  bottom: 30px;
  font-size: 17px;
}

#snackbar.show{
  visibility: visible;
  -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
  animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

.masterList{
  cursor:pointer;
}

@-webkit-keyframes fadein {
  from {bottom: 0; opacity: 0;} 
  to {bottom: 30px; opacity: 1;}
}

@keyframes fadein {
  from {bottom: 0; opacity: 0;}
  to {bottom: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
  from {bottom: 30px; opacity: 1;} 
  to {bottom: 0; opacity: 0;}
}

@keyframes fadeout {
  from {bottom: 30px; opacity: 1;}
  to {bottom: 0; opacity: 0;}
}
</style>
    
</head>
<body>
    <form action="jhon_addPartsComment.php" method="post" id="formPartsComment"></form>
        <div class="container-fluid">
            <div class="row" >
                <div class="col-12" style="float:right;">
                        <?php 
                            if($idNumber == '0001' OR $idNumber == '0939' OR $idNumber == '0346' OR $idNumber == '0280' OR $idNumber == '0276' OR $idNumber == '0063')
                            {
                                echo '<a name="btnPMaster" class="masterList" onclick="myPartMaster()">Master List >></a>';
                            }

                        ?>
                </div>
            </div>
            <div class="row">
                
                <div class="col-12">
                    <input type="hidden" name="partIdHidden" id="" value="<?php echo $partIds; ?>" form="formPartsComment">
                    <label>Parts Comment</label>
                    <select name="partsCom" id="partsCom" class="form-control" form="formPartsComment">
                        <option></option>
                        <?php 
                        
                            $sql = "SELECT partsComment FROM cadcam_partsComment";
                            $queryPC = $db->query($sql);
                            while($resulPC = $queryPC->fetch_assoc())
                            {
                                echo "<option value='".$resulPC['commentId']."'>".$resulPC['partsComment']."</option>";
                            }
                        
                        ?>
                    </select>

                </div>
                
               

            </div>
            <div class="row" style="margin-top:20px;">
                    <div class="col-2 text-center">
                        <button type="submit" name="btnSubmit" class="w3-btn w3-round w3-indigo" form="formPartsComment">Submit</button>
                    </div>
                </div>
            
        </div>

</body>
</html>
<script>
function myPartMaster() 
{

  const myWindow = window.open("http://192.168.254.163/V4/2-C%20Parts%20Management%20Software/jhon_partsMasterCommentList.php");
 
}
</script>