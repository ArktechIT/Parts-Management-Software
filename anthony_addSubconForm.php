<?php
include("../Common Data/PHP Modules/mysqliConnection.php");
include('../Common Data/PHP Modules/anthony_retrieveText.php');
?>

<center>
<form action = "anthony_addSubconFormSQL.php?partId=<?php echo $_GET['partId']; ?>&patternId=<?php echo $_GET['patternId']; ?>" method = "POST">
    <div class='container-fluid'>
        <div class='row'>
            <div class='col-md-12'>
                <label><?php echo strtoupper(displayText('L3541'));// Add Subcon Form?></label>
                <table border = 1>
                    <tr>
                        <td style='width:200px;'>
                            <label><?php echo displayText('L1369');//Subcon Order?>:</label>
                        </td>
                        <td>
                            <input class='form-control input-sm' style='width:200px;' type = 'number' name = 'subconOrder' min='1' value='1' required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for = "subconProcess"><?php echo displayText('L407');//Subcon Process?>:</label>
                        </td>
                        <td>
                            <select class='form-control input-sm' name = "subconProcess" style = '' required>
                            <option value=''>Select Subcon Process</option>
                            <?php											
                                //~ $sql = "SELECT processCode, processName FROM cadcam_process WHERE processCode NOT IN (SELECT processCode FROM cadcam_subconlist WHERE partId = ".$_GET['partId'].") AND processSection = 10 ORDER BY processName ASC ";
                                //~ $getProcess = $db->query($sql);
                                //~ while($getProcessResult = $getProcess->fetch_array()){
                                    //~ echo "<option value = '".$getProcessResult['processCode']."'>".$getProcessResult['processName']."</option>";
                                //~ }
                                //$sql = "SELECT treatmentId, treatmentName FROM engineering_treatment WHERE treatmentId NOT IN (SELECT processCode FROM cadcam_subconlist WHERE partId = ".$_GET['partId'].") ORDER BY treatmentName ASC ";
                                //requested by maam mers 2017-08-03 deactivate 325 then change all 325 to 353
                                $sql = "SELECT treatmentId, treatmentName FROM engineering_treatment WHERE treatmentId NOT IN (SELECT processCode FROM cadcam_subconlist WHERE partId = ".$_GET['partId'].") and status=0 ORDER BY treatmentName ASC ";
                                $queryTreatment = $db->query($sql);
                                if($queryTreatment->num_rows > 0)
                                {
                                    while($resultTreatment = $queryTreatment->fetch_array())
                                    {
                                        $treatmentId = $resultTreatment['treatmentId'];
                                        $treatmentName = $resultTreatment['treatmentName'];
                                        echo "<option value = '".$treatmentId."'>".$treatmentName."</option>";
                                    }
                                }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><?php echo displayText('L101');//Value?>:</label>
                        </td>
                        <td>
                            <input class='form-control input-sm' type = 'text' name = 'value' required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for = "subconProcess"><?php echo displayText('L91');//Subcon?> :</label>
                        </td>
                        <td>
                        <?php
                            $select = "<select class='form-control input-sm' name='subconId' required>";
                            $select .= "<option value=''>Select Subcon</option>";
                            $sql = "SELECT subconId, subconAlias FROM purchasing_subcon WHERE status = 0 ORDER BY subconAlias";
                            $querySubcon = $db->query($sql);
                            if($querySubcon AND $querySubcon->num_rows > 0)
                            {
                                while($resultSubcon = $querySubcon->fetch_assoc())
                                {
                                    $subconId = $resultSubcon['subconId'];
                                    $subconAlias = $resultSubcon['subconAlias'];
                                    
                                    $select .= "<option value='".$subconId."'>".$subconAlias."</option>";
                                }
                            }
                            $select .= "</select>";	
                            echo $select;
                        ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <div class='row'>
            <div class='col-md-12'>
                <div id="submitButton">
                    <input type ="submit" name = "submit" value = "<?php echo displayText('B4');//Add?>" class="btn btn-primary btn-sm">
                </div>
            </div>
        </div>
    </div>
</form>
</center>
