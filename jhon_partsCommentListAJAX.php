<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);    
include('Templates/mysqliConnection.php');
// include('PHP Modules/anthony_wholeNumber.php');
// include('PHP Modules/anthony_retrieveText.php');
// include('PHP Modules/gerald_functions.php');
// include('PHP Modules/rose_prodfunctions.php');
ini_set("display_errors", "on");


   
$requestData= $_REQUEST;
$sqlData = isset($requestData['sqlData']) ? $requestData['sqlData'] : "";
//$exportExcelData = isset($requestData['exportExcelData']) ? $requestData['exportExcelData'] : "";
$totalRecords = (isset($requestData['totalRecords'])) ? $requestData['totalRecords'] : 0;
$totalFiltered = $totalRecords;
$totalData = $totalFiltered;

$data = array();

$sql= $sqlData;
$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
$counter = $requestData['start'];
$queryCommentList = $db->query($sql) or die ($db->error);
// $row = $employee->fetch_assoc();
$num=0;
    
 
if($queryCommentList AND $queryCommentList->num_rows > 0)
{
    while($resultCommentList = $queryCommentList->fetch_assoc())
    {  
        $commentId = $resultCommentList['commentId'];
        $partComment = $resultCommentList['partsComment'];
        
        
       
        $button ="<button class='w3-sm-btn w3-round w3-indigo' name='edit' onclick=\"modalEditForm(".$commentId.")\" title='Edit'><i class='fa fa-edit'></i></button> <button class='w3-sm-btn w3-round w3-red' name='delete' onclick=\"modalDeleteForm(".$commentId.")\" title='Delete'><i class='fa fa-trash'></i></button>";
     
        $nestedData = Array ();

        $nestedData[] = $num+=1;
        $nestedData[] = $partComment;
        $nestedData[] = $button;
       
      

        $data[] = $nestedData;
    }
}
   
    

$json_data = array(
    "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
    "recordsTotal"    => intval( $totalData ),  // total number of records
    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
?>