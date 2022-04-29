<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/gerald_functions.php');
include('PHP Modules/anthony_retrieveText.php');
include("PHP Modules/anthony_wholeNumber.php");
include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");
$ctrl = new PMSDatabase;
$tpl = new PMSTemplates;

$sqlData = isset($_POST['sqlData']) ? $_POST['sqlData'] : "";
$lastValue = (isset($_POST['lastValue'])) ? $_POST['lastValue'] : '';
$showOpenPOCheckData = (isset($_POST['showOpenPOCheckData'])) ? $_POST['showOpenPOCheckData'] : '';

$_POST = json_decode(str_replace("'",'"',$_POST['filterDataPost']),true);
$_GET = json_decode(str_replace("'",'"',$_POST['filterDataGet']),true);

$customerName = (isset($_POST['customerName'])) ? $_POST['customerName'] : '';
$customerId = (isset($_POST['customerId'])) ? $_POST['customerId'] : '';
$partNumber = (isset($_POST['partNumber'])) ? $_POST['partNumber'] : '';
$partName = (isset($_POST['partName'])) ? $_POST['partName'] : '';
$partx = (isset($_POST['partx'])) ? $_POST['partx'] : '';
$party = (isset($_POST['party'])) ? $_POST['party'] : '';
$partl = (isset($_POST['partl'])) ? $_POST['partl'] : '';
$partw = (isset($_POST['partw'])) ? $_POST['partw'] : '';
$parth = (isset($_POST['parth'])) ? $_POST['parth'] : '';
$statusPart = (isset($_POST['statusPart'])) ? $_POST['statusPart'] : '';
$materialType = (isset($_POST['materialType'])) ? $_POST['materialType'] : '';
$metalThickness = (isset($_POST['metalThickness'])) ? $_POST['metalThickness'] : '';
$sheetWorksFlag = (isset($_POST['sheetWorksFlag'])) ? $_POST['sheetWorksFlag'] : '';
$firstPODate = (isset($_POST['firstPODate'])) ? $_POST['firstPODate'] : '';
$lastPODate = (isset($_POST['lastPODate'])) ? $_POST['lastPODate'] : '';
$processCode = (isset($_POST['process'])) ? $_POST['process'] : [];
$processGroup = (isset($_POST['processGroup'])) ? $_POST['processGroup'] : '';
$partTypeFlag = (isset($_POST['partTypeFlag'])) ? $_POST['partTypeFlag'] : '';
$xOperator = (isset($_POST['xOperator'])) ? $_POST['xOperator'] : '';
$yOperator = (isset($_POST['yOperator'])) ? $_POST['yOperator'] : '';
$lengthOperator = (isset($_POST['lengthOperator'])) ? $_POST['lengthOperator'] : '';
$widthOperator = (isset($_POST['widthOperator'])) ? $_POST['widthOperator'] : '';
$heightOperator = (isset($_POST['heightOperator'])) ? $_POST['heightOperator'] : '';
$showPrice = (isset($_POST['showPrice'])) ? $_POST['showPrice'] : '';
$partsComment = (isset($_POST['partsComment'])) ? $_POST['partsComment'] : '';

$selectedXA = ($xOperator == ">=") ? "selected" : "";
$selectedXB = ($xOperator == "<=") ? "selected" : "";

$selectedYA = ($yOperator == ">=") ? "selected" : "";
$selectedYB = ($yOperator == "<=") ? "selected" : "";

$selectedLengthA = ($lengthOperator == ">=") ? "selected" : "";
$selectedLengthB = ($lengthOperator == "<=") ? "selected" : "";

$selectedWidthA = ($widthOperator == ">=") ? "selected" : "";
$selectedWidthB = ($widthOperator == "<=") ? "selected" : "";

$selectedHeightA = ($heightOperator == ">=") ? "selected" : "";
$selectedHeightB = ($heightOperator == "<=") ? "selected" : "";

$sheetWorksFlagChecked = ($sheetWorksFlag==1) ? "checked" : "";
$showPriceDataChecked = ($showPrice==1) ? "checked" : "";

$itemxFilter = isset($_POST['itemxFilter']) ? $_POST['itemxFilter'] : '>=';
$itemxFromFilter = isset($_POST['itemxFromFilter']) ? $_POST['itemxFromFilter'] : '';
$itemxToFilter = isset($_POST['itemxToFilter']) ? $_POST['itemxToFilter'] : '';
$itemyFilter = isset($_POST['itemyFilter']) ? $_POST['itemyFilter'] : '>=';
$itemyFromFilter = isset($_POST['itemyFromFilter']) ? $_POST['itemyFromFilter'] : '';
$itemyToFilter = isset($_POST['itemyToFilter']) ? $_POST['itemyToFilter'] : '';
$itemWeightFilter = isset($_POST['itemWeightFilter']) ? $_POST['itemWeightFilter'] : '>=';
$itemWeightFromFilter = isset($_POST['itemWeightFromFilter']) ? $_POST['itemWeightFromFilter'] : '';
$itemWeightToFilter = isset($_POST['itemWeightToFilter']) ? $_POST['itemWeightToFilter'] : '';
$itemLengthFilter = isset($_POST['itemLengthFilter']) ? $_POST['itemLengthFilter'] : '>=';
$itemLengthFromFilter = isset($_POST['itemLengthFromFilter']) ? $_POST['itemLengthFromFilter'] : '';
$itemLengthToFilter = isset($_POST['itemLengthToFilter']) ? $_POST['itemLengthToFilter'] : '';
$itemWidthFilter = isset($_POST['itemWidthFilter']) ? $_POST['itemWidthFilter'] : '>=';
$itemWidthFromFilter = isset($_POST['itemWidthFromFilter']) ? $_POST['itemWidthFromFilter'] : '';
$itemWidthToFilter = isset($_POST['itemWidthToFilter']) ? $_POST['itemWidthToFilter'] : '';
$itemHeightFilter = isset($_POST['itemHeightFilter']) ? $_POST['itemHeightFilter'] : '>=';
$itemHeightFromFilter = isset($_POST['itemHeightFromFilter']) ? $_POST['itemHeightFromFilter'] : '';
$itemHeightToFilter = isset($_POST['itemHeightToFilter']) ? $_POST['itemHeightToFilter'] : '';

$customerIdArray = $partNameArray = $partNumberArray = $materialSpecIdArray = $statusArray = $partIdArray = $partsCommentArray = [ ];
$sql = $sqlData;
$partsData = $ctrl->setSQLQuery($sql)->getRecords();
foreach ($partsData as $key) 
{
    $partIdArray[] = $key['partId'];
    $customerIdArray[] = $key['customerId'];
    $partNameArray[] = $key['partName'];
    $partNumberArray[] = $key['partNumber'];
    $materialSpecIdArray[] = $key['materialSpecId'];
    $statusArray[] = $key['status'];
    $partsCommentArray[] = $key['partsComment'];
}

$sql = "SELECT DISTINCT processCode FROM cadcam_partprocess WHERE partId IN (".implode(", ",$partIdArray).")";
$queryProcess = $db->query($sql);
if($queryProcess AND $queryProcess->num_rows >0)
{
    while($resultProcess = $queryProcess->fetch_assoc())
    {
        $processCodeArray[] = $resultProcess['processCode'];
    }
}

$processNames = $groupNames = "";
if($processCodeArray != NULL)
{
    $processSectionArray = Array();
    $sql = "SELECT processCode, processName, processSection FROM cadcam_process WHERE processCode IN (".implode(", ",$processCodeArray).") ORDER BY processName";
    $queryName = $db->query($sql);
    if($queryName AND $queryName->num_rows > 0)
    {
        while($resultName = $queryName->fetch_assoc())
        {
            $processCodeVal = $resultName['processCode'];
            $processSectionArray[] = $resultName['processSection'];
            $processName = $resultName['processName'];

            // $selected = ($processCode == $processCodeVal) ? 'selected' : '';
            $selected = (count($processCode) > 0 AND in_array($processName,$processCode)) ? 'selected' : '';
            $processNames .= "<option value='".$processName."' ".$selected.">".$processName."</option>";
        }
    }

    if($processSectionArray != NULL)
    {
        $processSectionArray = array_unique($processSectionArray);
        $sql = "SELECT sectionId, sectionName FROM ppic_section WHERE sectionId IN (".implode(", ",$processSectionArray).") ORDER BY sectionName";
        $querySection = $db->query($sql);
        if($querySection AND $querySection->num_rows > 0)
        {
            while ($resultSection = $querySection->fetch_assoc()) 
            {
                $sectionId = $resultSection['sectionId'];
                $sectionName = $resultSection['sectionName'];
                
                $selected = ($processGroup == $sectionId) ? 'selected' : '';
                $groupNames .= "<option value='".$sectionId."' ".$selected.">".$sectionName."</option>";
            }
        }
    }
}

$tpl->setDataValue("B5");
$tpl->setAttribute("form","formFilter");
$searchBtn = $tpl->createButton();

echo "<div class='row'>";
    echo "<div class='col-md-2'>";
        echo "<label>".displayText('L24', 'utf8', 0, 0, 1)."</label>";
        echo "<select form='formFilter' id='customerId' class='w3-input w3-border' name='customerId[]' multiple='multiple'>";
            echo "<option></option>";
            $customerIdArray = array_unique($customerIdArray);
            $sql = "SELECT customerId, customerName FROM sales_customer WHERE customerId IN (".implode(", ",$customerIdArray).") ORDER BY customerName";
            $customerData = $ctrl->setSQLQuery($sql)->getRecords();
            foreach ($customerData as $key) 
            {
                $customerIdVal = $key['customerId'];
                $customerNameVal = $key['customerName'];

                $selected = (in_array($customerIdVal,$customerId)) ? "selected" : "";
                echo "<option ".$selected." value='".$customerIdVal."'>".$customerNameVal."</option>";
            }
        echo "</select>";
    echo "</div>";
    echo "<div class='col-md-2'>";
        echo "<label>".displayText('L30', 'utf8', 0, 0, 1)."</label>";
        echo "<input form='formFilter' list='partName' name='partName' class='w3-input w3-border w3-pale-red' value='".$partName."'>";
        echo "<datalist id = 'partName'>";
            $partNameArray = array_unique($partNameArray);
            sort($partNameArray);
            foreach ($partNameArray as $key) 
            {
                echo "<option>".$key."</option>";
            }
        echo "</datalist>";
    echo "</div>";
    echo "<div class='col-md-2'>";
        echo "<label>".displayText('L28', 'utf8', 0, 0, 1)."</label>";
        echo "<input form='formFilter' list='partNumber' name='partNumber' class='w3-input w3-border w3-pale-red' value='".$partNumber."'>";
        echo "<datalist id = 'partNumber'>";
            $partNumberArray = array_unique($partNumberArray);
            sort($partNumberArray);
            foreach ($partNumberArray as $key) 
            {
                echo "<option>".$key."</option>";
            }
        echo "</datalist>";
    echo "</div>";
    // echo "<div class='col-md-2'>";
    //     echo "<label>".displayText('L70', 'utf8', 0, 0, 1)."</label>";
    //     echo "<div class='w3-right'>";
    //         echo "<select form='formFilter' class='w3-pale-green' name='xOperator'>";
    //             echo "<option value='='></option>";
    //             echo "<option ".$selectedXA.">>=</option>";
    //             echo "<option ".$selectedXB."><=</option>";
    //         echo "</select>";
    //     echo "</div>";
    //     echo "<input form='formFilter' type='number' name='partx' class='w3-input w3-border' value='".$partx."'>";
    // echo "</div>";
    // echo "<div class='col-md-2'>";
    //     echo "<label>".displayText('L71', 'utf8', 0, 0, 1)."</label>";
    //     echo "<div class='w3-right'>";
    //         echo "<select form='formFilter' class='w3-pale-green' name='yOperator'>";
    //             echo "<option value='='></option>";
    //             echo "<option ".$selectedYA.">>=</option>";
    //             echo "<option ".$selectedYB."><=</option>";
    //         echo "</select>";
    //     echo "</div>";
    //     echo "<input form='formFilter' type='number' name='party' class='w3-input w3-border' value='".$party."'>";
    // echo "</div>";
    echo "<div class='col-md-2'>";
        echo "<label>".displayText('L111', 'utf8', 0, 0, 1)."</label>";
        echo "<select form='formFilter' id='partTypeFlag' name='partTypeFlag[]'  multiple='multiple'>";
            for ($i=0; $i <=2 ; $i++) 
            { 
                $selected = (in_array($i,$partTypeFlag)) ? 'selected' : '';
                if($i == 0) $valueCaption = "ASSY";
                if($i == 1) $valueCaption = "SUBPART";
                if($i == 2) $valueCaption = "SINGLE";

                echo "<option value=".$i." ".$selected.">".$valueCaption."</option>";
            }
        echo "</select>";
    echo "</div>";
echo "</div>";
echo "<div class='row w3-padding-top'>";
    // echo "<div class='col-md-2'>";
    //     echo "<label>".displayText('L74', 'utf8', 0, 0, 1)."</label>";
    //     echo "<div class='w3-right'>";
    //         echo "<select form='formFilter' class='w3-pale-green' name='lengthOperator'>";
    //             echo "<option value='='></option>";
    //             echo "<option ".$selectedLengthA.">>=</option>";
    //             echo "<option ".$selectedLengthB."><=</option>";
    //         echo "</select>";
    //     echo "</div>";
    //     echo "<input form='formFilter' type='number' name='partl' class='w3-input w3-border' value='".$partl."'>";
    // echo "</div>";
    // echo "<div class='col-md-2'>";
    //     echo "<label>".displayText('L75', 'utf8', 0, 0, 1)."</label>";
    //     echo "<div class='w3-right'>";
    //         echo "<select form='formFilter' class='w3-pale-green' name='widthOperator'>";
    //             echo "<option value='='></option>";
    //             echo "<option ".$selectedWidthA.">>=</option>";
    //             echo "<option ".$selectedWidthB."><=</option>";
    //         echo "</select>";
    //     echo "</div>";
    //     echo "<input form='formFilter' type='number' name='partw' class='w3-input w3-border' value='".$partw."'>";
    // echo "</div>";
    // echo "<div class='col-md-2'>";
    //     echo "<label>".displayText('L76', 'utf8', 0, 0, 1)."</label>";
    //     echo "<div class='w3-right'>";
    //         echo "<select form='formFilter' class='w3-pale-green' name='heightOperator'>";
    //             echo "<option value='='></option>";
    //             echo "<option ".$selectedHeightA.">>=</option>";
    //             echo "<option ".$selectedHeightB."><=</option>";
    //         echo "</select>";
    //     echo "</div>";
    //     echo "<input form='formFilter' type='number' name='parth' class='w3-input w3-border' value='".$parth."'>";
    // echo "</div>";
    echo "<div class='col-md-2'>";
        echo "<label>".displayText('L566', 'utf8', 0, 0, 1)."</label>";
        echo "<select form='formFilter' id='materialType' name='materialType[]'  multiple='multiple'>";
            $materialSpecIdArray = array_unique($materialSpecIdArray);
            $sql = "SELECT DISTINCT materialType FROM engineering_materialtype WHERE materialType != '' AND materialTypeId IN(SELECT materialTypeId FROM cadcam_materialspecs WHERE materialSpecId IN(".implode(",",$materialSpecIdArray).")) ORDER BY materialType";
            $materialTypeData = $ctrl->setSQLQuery($sql)->getRecords();
            foreach ($materialTypeData as $key) 
            {
                $materialTypeData = $key['materialType'];

                $selectedMaterial = (in_array($materialTypeData,$materialType)) ? 'selected' : '';

                echo "<option ".$selectedMaterial.">".$materialTypeData."</option>";
            }
        echo "</select>";
    echo "</div>";
    echo "<div class='col-md-2'>";
        echo "<label>".displayText('L184', 'utf8', 0, 0, 1)."</label>";
        echo "<select form='formFilter' id='metalThickness' class='w3-input w3-border' name='metalThickness'>";
            echo "<option value=''></option>";
            $sql = "SELECT DISTINCT metalThickness FROM cadcam_materialspecs WHERE materialSpecId IN(".implode(",",$materialSpecIdArray).") ORDER BY metalThickness";
            $materialThicknessData = $ctrl->setSQLQuery($sql)->getRecords();
            foreach ($materialThicknessData as $key) 
            {
                $metalThicknessData = $key['metalThickness'];
                $selected = ($metalThicknessData == $metalThickness) ? "selected" : "";
                echo "<option ".$selected.">".$metalThicknessData."</option>";
            }
        echo "</select>";
    echo "</div>";
    echo "<div class='col-md-2'>";
        echo "<label>".displayText('L172', 'utf8', 0, 0, 1)."</label>";
        echo "<select form='formFilter' id='statusPart' name='statusPart[]' multiple='multiple'>";
            $statusArray = array_unique($statusArray);
            foreach ($statusArray as $key) 
            {
                if($key==0)
                {
                    $valueCaption = "Active";
                }
                else if($key==1)
                {
                    $valueCaption = "Inactive";
                }
                else if($key==2)
                {
                    $valueCaption = "Pending";
                }
                else if($key==3)
                {
                    $key = "For Check Rev";
                }

                $selectedStatus = (in_array($key,$statusArray)) ? 'selected' : '';
                echo "<option ".$selectedStatus." value='".$key."'>".$valueCaption."</option>";
            }
        echo "</select>";
    echo "</div>";
echo "</div>";
echo "<div class='row w3-padding-top'>";
    echo "<div class='col-md-2'>";
        echo "<label>".displayText('L4162', 'utf8', 0, 0, 1)."</label>";
        echo "<input id='firstPODate' name='firstPODate' class='w3-input w3-border' value='".$firstPODate."' form='formFilter'>";
    echo "</div>";
    echo "<div class='col-md-2'>";
        echo "<label>".displayText('L4163', 'utf8', 0, 0, 1)."</label>";
        echo "<input id='lastPODate' name='lastPODate' class='w3-input w3-border' value='".$lastPODate."' form='formFilter'>";
    echo "</div>";
    echo "<div class='col-md-2'>";
        echo "<label>".displayText('L59', 'utf8', 0, 0, 1)."</label>";
        echo "<select form='formFilter' id='process' class='w3-input w3-border' name='process[]' multiple='multiple'>";
        // echo "<option value=''></option>";
            echo $processNames;
        echo "</select>";
    echo "</div>";
    echo "<div class='col-md-2'>";
        echo "<label>".displayText('L61', 'utf8', 0, 0, 1)."</label>";
        echo "<select form='formFilter' id='processGroup' class='w3-input w3-border' name='processGroup'>";
        echo "<option value=''></option>";
            echo $groupNames;
        echo "</select>";
    echo "</div>";
    echo "<div class='col-md-2'>";
        echo "<label>".displayText('L636', 'utf8', 0, 0, 1)."</label>";
        echo "<input form='formFilter' list='partsComment' name='partsComment' class='w3-input w3-border w3-pale-red' value='".$partsComment."'>";
        echo "<datalist id = 'partsComment'>";
            $partsCommentArray = array_unique($partsCommentArray);
            sort($partsCommentArray);
            foreach ($partsCommentArray as $key) 
            {
                echo "<option>".$key."</option>";
            }
        echo "</datalist>";
    echo "</div>";
echo "</div>";
if($_SESSION['idNumber']==true)
{
    echo "<div class='row w3-padding-top'>";
        echo "<div class='col-md-12'>";
            echo "<ul class='list-inline'>";
                echo "<li>";
                    echo "<label class='w3-tiny'>".(displayText("L74", "utf8", 0, 0, 1))."</label>"; // Length
                    echo "<select class='w3-input w3-border' id='itemLengthFilter' name='itemLengthFilter' form='formFilter'>";
                        $selectTotalA = ($itemLengthFilter == "RANGE") ? "selected" : "";
                        $selectTotalB = ($itemLengthFilter == ">=") ? "selected" : "";
                        $selectTotalC = ($itemLengthFilter == "<=") ? "selected" : "";
                        echo "<option ".$selectTotalA." value='RANGE'>".displayText("L558", "utf8", 0, 1, 1)."</option>";
                        echo "<option ".$selectTotalB." value='>='>>=</option>";
                        echo "<option ".$selectTotalC." value='<='><=</option>";
                    echo "</select>";
                echo "</li>";
                echo "<li>";
                    echo "<input type='number' value='".$itemLengthFromFilter."' step='any' min=0 class='w3-input w3-border w3-pale-yellow' name='itemLengthFromFilter' form='formFilter'>";
                echo "</li>";
                $showitemLength = ($itemLengthFilter == "RANGE") ? "" : "display:none;";
                echo "<li id='showitemLength' style='".$showitemLength."'>";
                    echo "<input type='number' value='".$itemLengthToFilter."' step='any' min=0 class='w3-input w3-border w3-pale-yellow' name='itemLengthToFilter' form='formFilter'>";
                echo "</li>";
                echo "<li>";
                    echo "<label class='w3-tiny'>".(displayText("L75", "utf8", 0, 0, 1))."</label>"; // Width
                    echo "<select class='w3-input w3-border' id='itemWidthFilter' name='itemWidthFilter' form='formFilter'>";
                        $selectTotalA = ($itemWidthFilter == "RANGE") ? "selected" : "";
                        $selectTotalB = ($itemWidthFilter == ">=") ? "selected" : "";
                        $selectTotalC = ($itemWidthFilter == "<=") ? "selected" : "";
                        echo "<option ".$selectTotalA." value='RANGE'>".displayText("L558", "utf8", 0, 1, 1)."</option>";
                        echo "<option ".$selectTotalB." value='>='>>=</option>";
                        echo "<option ".$selectTotalC." value='<='><=</option>";
                    echo "</select>";
                echo "</li>";
                echo "<li>";
                    echo "<input type='number' value='".$itemWidthFromFilter."' step='any' min=0 class='w3-input w3-border w3-pale-yellow' name='itemWidthFromFilter' form='formFilter'>";
                echo "</li>";
                $showitemWidth = ($itemWidthFilter == "RANGE") ? "" : "display:none;";
                echo "<li id='showitemWidth' style='".$showitemWidth."'>";
                    echo "<input type='number' value='".$itemWidthToFilter."' step='any' min=0 class='w3-input w3-border w3-pale-yellow' name='itemWidthToFilter' form='formFilter'>";
                echo "</li>";
                echo "<li>";
                    echo "<label class='w3-tiny'>".(displayText("L76", "utf8", 0, 0, 1))."</label>"; // Height
                    echo "<select class='w3-input w3-border' id='itemHeightFilter' name='itemHeightFilter' form='formFilter'>";
                        $selectTotalA = ($itemHeightFilter == "RANGE") ? "selected" : "";
                        $selectTotalB = ($itemHeightFilter == ">=") ? "selected" : "";
                        $selectTotalC = ($itemHeightFilter == "<=") ? "selected" : "";
                        echo "<option ".$selectTotalA." value='RANGE'>".displayText("L558", "utf8", 0, 1, 1)."</option>";
                        echo "<option ".$selectTotalB." value='>='>>=</option>";
                        echo "<option ".$selectTotalC." value='<='><=</option>";
                    echo "</select>";
                echo "</li>";
                echo "<li>";
                    echo "<input type='number' value='".$itemHeightFromFilter."' step='any' min=0 class='w3-input w3-border w3-pale-yellow' name='itemHeightFromFilter' form='formFilter'>";
                echo "</li>";
                $showitemHeight = ($itemHeightFilter == "RANGE") ? "" : "display:none;";
                echo "<li id='showitemHeight' style='".$showitemHeight."'>";
                    echo "<input type='number' value='".$itemHeightToFilter."' step='any' min=0 class='w3-input w3-border w3-pale-yellow' name='itemHeightToFilter' form='formFilter'>";
                echo "</li>";
            echo "</ul>";                
        echo "</div>";
    echo "</div>";
}
echo "<div class='row w3-padding-top'>";
    echo "<div class='col-md-12'>";
        echo "<ul class='list-inline'>";
            echo "<li>";
                echo "<label class='w3-tiny'>".(displayText("L70", "utf8", 0, 0, 1))."</label>"; // Item X
                echo "<select class='w3-input w3-border' id='itemxFilter' name='itemxFilter' form='formFilter'>";
                    $selectTotalA = ($itemxFilter == "RANGE") ? "selected" : "";
                    $selectTotalB = ($itemxFilter == ">=") ? "selected" : "";
                    $selectTotalC = ($itemxFilter == "<=") ? "selected" : "";
                    echo "<option ".$selectTotalA." value='RANGE'>".displayText("L558", "utf8", 0, 1, 1)."</option>";
                    echo "<option ".$selectTotalB." value='>='>>=</option>";
                    echo "<option ".$selectTotalC." value='<='><=</option>";
                echo "</select>";
            echo "</li>";
            echo "<li>";
                echo "<input type='number' value='".$itemxFromFilter."' step='any' min=0 class='w3-input w3-border w3-pale-yellow' name='itemxFromFilter' form='formFilter'>";
            echo "</li>";
            $showitemx = ($itemxFilter == "RANGE") ? "" : "display:none;";
            echo "<li id='showitemx' style='".$showitemx."'>";
                echo "<input type='number' value='".$itemxToFilter."' step='any' min=0 class='w3-input w3-border w3-pale-yellow' name='itemxToFilter' form='formFilter'>";
            echo "</li>";
            echo "<li>";
                echo "<label class='w3-tiny'>".(displayText("L71", "utf8", 0, 0, 1))."</label>"; // Item Y
                echo "<select class='w3-input w3-border' id='itemyFilter' name='itemyFilter' form='formFilter'>";
                    $selectTotalA = ($itemyFilter == "RANGE") ? "selected" : "";
                    $selectTotalB = ($itemyFilter == ">=") ? "selected" : "";
                    $selectTotalC = ($itemyFilter == "<=") ? "selected" : "";
                    echo "<option ".$selectTotalA." value='RANGE'>".displayText("L558", "utf8", 0, 1, 1)."</option>";
                    echo "<option ".$selectTotalB." value='>='>>=</option>";
                    echo "<option ".$selectTotalC." value='<='><=</option>";
                echo "</select>";
            echo "</li>";
            echo "<li>";
                echo "<input type='number' value='".$itemyFromFilter."' step='any' min=0 class='w3-input w3-border w3-pale-yellow' name='itemyFromFilter' form='formFilter'>";
            echo "</li>";
            $showitemy = ($itemyFilter == "RANGE") ? "" : "display:none;";
            echo "<li id='showitemy' style='".$showitemy."'>";
                echo "<input type='number' value='".$itemyToFilter."' step='any' min=0 class='w3-input w3-border w3-pale-yellow' name='itemyToFilter' form='formFilter'>";
            echo "</li>";
            echo "<li>";
                echo "<label class='w3-tiny'>".(displayText("L72", "utf8", 0, 0, 1))."</label>"; // Item Weight
                echo "<select class='w3-input w3-border' id='itemWeightFilter' name='itemWeightFilter' form='formFilter'>";
                    $selectTotalA = ($itemWeightFilter == "RANGE") ? "selected" : "";
                    $selectTotalB = ($itemWeightFilter == ">=") ? "selected" : "";
                    $selectTotalC = ($itemWeightFilter == "<=") ? "selected" : "";
                    echo "<option ".$selectTotalA." value='RANGE'>".displayText("L558", "utf8", 0, 1, 1)."</option>";
                    echo "<option ".$selectTotalB." value='>='>>=</option>";
                    echo "<option ".$selectTotalC." value='<='><=</option>";
                echo "</select>";
            echo "</li>";
            echo "<li>";
                echo "<input type='number' value='".$itemWeightFromFilter."' step='any' min=0 class='w3-input w3-border w3-pale-yellow' name='itemWeightFromFilter' form='formFilter'>";
            echo "</li>";
            $showitemWeight = ($itemWeightFilter == "RANGE") ? "" : "display:none;";
            echo "<li id='showitemWeight' style='".$showitemWeight."'>";
                echo "<input type='number' value='".$itemWeightToFilter."' step='any' min=0 class='w3-input w3-border w3-pale-yellow' name='itemWeightToFilter' form='formFilter'>";
            echo "</li>";
        echo "</ul>";                
    echo "</div>";
echo "</div>";
echo "<div class='row w3-padding-top'>";
    echo "<div class='col-md-12 w3-center'>";
        echo "<input type='hidden' name='lastValue' value='".$lastValue."' form='formFilter'>";
        echo "&emsp;<span class='w3-padding w3-round w3-sand'><b>SHOW PRICE</b>&emsp;<input ".$showPriceDataChecked." type='checkbox' name='showPrice' value='1' style='position:relative; top:3px;' form='formFilter'></span>";
        echo "&emsp;<span class='w3-padding w3-round w3-sand'><b>SHOW OPEN PO</b>&emsp;<input ".$showOpenPOCheckData." type='checkbox' name='showOpenPO' value='1' style='position:relative; top:3px;' form='formFilter'></span>";
        echo "&emsp;<span class='w3-padding w3-round w3-sand'><b>SHEETWORKS</b>&emsp;<input ".$sheetWorksFlagChecked." type='checkbox' name='sheetWorksFlag' value='1' style='position:relative; top:3px;' form='formFilter'></span>";
    echo "</div>";
echo "</div>";
echo "<div class='w3-padding-top'></div>";
echo "<div class='w3-padding-top'></div>";
echo "<div class='row'>";
    echo "<div class='col-md-12 w3-center'>";
        echo $searchBtn;
    echo "</div>";
echo "</div>";
?>
<style>
	.birthdate {!important;color: #FF0000 !important;background-image :none !important;}
	.regularHoliday {background-color : #FF5353 !important;background-image :none !important;}
	.specialHoliday {background-color : #80FF80 !important;background-image :none !important;}
	.companyHoliday {background-color : #FF8533 !important;background-image :none !important;}
	.companyEvent {background-color : #FFDB4D !important;background-image :none !important;}
	.specialNon {background-color : #9999FF !important;background-image :none !important;}
	.appointmentDay {background-color : #66FFFF!important;background-image :none !important;}
	.sunday {background-color : #FFDDDD !important;background-image :none !important;}	
</style>
<script>
$(document).ready(function(){
    $('#statusPart, #partTypeFlag, #materialType, #process, #customerId').multiselect({
        maxHeight               : 300,
        includeSelectAllOption  : true,
        buttonClass             :'w3-input w3-border w3-pale-yellow',
        buttonWidth             : '100%',
        nonSelectedText         : 'Select',
        numberDisplayed         : 1,
        onSelectAll             : function(event) {
            event.preventDefault();
        },
        onDeselectAll           : function(event) {
            event.preventDefault();
        }
    });

    $("#itemxFilter").change(function(){
        var val = $(this).val();

        if(val != "RANGE")
        {
            $("#showitemx").hide();
        }
        else
        {
            $("#showitemx").show();
        }
    });

    $("#itemyFilter").change(function(){
        var val = $(this).val();

        if(val != "RANGE")
        {
            $("#showitemy").hide();
        }
        else
        {
            $("#showitemy").show();
        }
    });    
    
    $("#itemWeightFilter").change(function(){
        var val = $(this).val();

        if(val != "RANGE")
        {
            $("#showitemWeight").hide();
        }
        else
        {
            $("#showitemWeight").show();
        }
    });    
    $("#itemLengthFilter").change(function(){
        var val = $(this).val();

        if(val != "RANGE")
        {
            $("#showitemLength").hide();
        }
        else
        {
            $("#showitemLength").show();
        }
    });    
    $("#itemWidthFilter").change(function(){
        var val = $(this).val();

        if(val != "RANGE")
        {
            $("#showitemWidth").hide();
        }
        else
        {
            $("#showitemWidth").show();
        }
    });    
    $("#itemHeightFilter").change(function(){
        var val = $(this).val();

        if(val != "RANGE")
        {
            $("#showitemHeight").hide();
        }
        else
        {
            $("#showitemHeight").show();
        }
    });    

    $.ajax({
        url         : '/<?php echo v;?>/1-3 Received Order List V2/v5/gerald_holidayAjax.php',
        type        : 'post',
        dataType    : 'json',
        data        : { },
        success     : function(data){
            //Regular Holidays
            var regular = [];
            var l = data.regular.length;
            for(i=0;i<l;i++)
            {
                var thisDate = moment(data.regular[i].hDate);
                regular[i] = thisDate.format("MM-DD-YYYY");
            }
            
            //Special Holidays
            var special = [];
            var l = data.special.length;
            for(i=0;i<l;i++)
            {
                var thisDate = moment(data.special[i].hDate);
                special[i] = thisDate.format("MM-DD-YYYY");
            }
            
            //Company Holidays
            var comH = [];
            var l = data.companyH.length;
            for(i=0;i<l;i++)
            {
                var thisDate = moment(data.companyH[i].hDate);
                comH[i] = thisDate.format("MM-DD-YYYY");
            }
            
            //Company Events
            var comE = [];
            var l = data.companyE.length;
            for(i=0;i<l;i++)
            {
                var thisDate = moment(data.companyE[i].hDate);
                comE[i] = thisDate.format("MM-DD-YYYY");
            }
            
            //Special Non Working
            var specialNW = [];
            var l = data.specialNW.length;
            for(i=0;i<l;i++)
            {
                var thisDate = moment(data.specialNW[i].hDate);
                specialNW[i] = thisDate.format("MM-DD-YYYY");
            }
            
            var dateRangePickerParams = {
                beforeShowDay: function(time){
                    var thisDate = moment(time);
                    var asd = thisDate.format("MM-DD-YYYY");
                    
                    var valid = true;
                    var _class = '';
                    var _tooltip = '';
                    
                    var backgroundColor = "";
                    if($.inArray(asd,regular) != -1)
                    {
                        _class = 'regularHoliday';
                        _tooltip = 'Regular Holiday';
                    }
                    else if($.inArray(asd,special) != -1)
                    {
                        _class = 'specialHoliday';
                        _tooltip = 'Special Holiday';
                    }
                    else if($.inArray(asd,comH) != -1)
                    {
                        _class = 'companyHoliday';
                        _tooltip = 'Company Holiday';
                    }
                    else if($.inArray(asd,comE) != -1)
                    {
                        _class = 'companyEvent';
                        _tooltip = 'Company Event';
                    }
                    else if($.inArray(asd,specialNW) != -1)
                    {
                        _class = 'specialNon';
                        _tooltip = 'Special Non-working';
                    }
                    else if(thisDate.format('dddd') == 'Sunday')
                    {
                        _class = 'sunday';
                        _tooltip = 'Sunday';
                    }
                    
                    return [valid,_class,_tooltip];
                },
                language: '<?php echo ($_SESSION['language']==2) ? 'ja' : 'en';?>',
                showCustomValues: true,
                customValueLabel: ' ',
                customValues:[
                    {
                        name: 'CLEAR',
                        value: ''
                    }
                ],
                showShortcuts: true,
                shortcuts : null,
                customShortcuts:
                [
                    {
                        name: '1 week',
                        dates : function()
                        {
                            var start = moment().toDate();
                            var end = moment().add(1,'weeks').toDate();
                            return [start,end];
                        }
                    },
                    {
                        name: '2 weeks',
                        dates : function()
                        {
                            var start = moment().toDate();
                            var end = moment().add(2,'weeks').toDate();
                            return [start,end];
                        }
                    },
                    {
                        name: '3 weeks',
                        dates : function()
                        {
                            var start = moment().toDate();
                            var end = moment().add(3,'weeks').toDate();
                            return [start,end];
                        }
                    },
                    {
                        name: '1 month',
                        dates : function()
                        {
                            var start = moment().toDate();
                            var end = moment().add(3,'month').toDate();
                            return [start,end];
                        }
                    }
                ],					
                autoClose: true,
                getValue: function() {
                    return this.value;
                },
                setValue: function(s){
                    this.value = s;
                }					
            };
            
            var dateRangePickerParamsData = $.extend({}, { startDate : '', endDate: '' }, dateRangePickerParams);
            
            $('#firstPODate').dateRangePicker(dateRangePickerParamsData);
            $('#lastPODate').dateRangePicker(dateRangePickerParamsData);
            
            $("span.custom-value a").click(function(){
                if($(this).text() == 'CLEAR')
                {
                    $("#formFilter").submit();
                }
            });
        }
    });
});
</script>