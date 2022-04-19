<?php
SESSION_START();
include("../Common Data/PHP Modules/mysqliConnection.php");

    $partId = $_GET['partId'];
    $standardSpecId = $_POST['standardSpecId'];
    $count = 0;
    foreach($standardSpecId as $value)
    {
        // $standardSpecIdVal = $standardSpecId[$count];
        $standardSpecIdVal = $value;
        echo "<br>".$sql = "INSERT INTO engineering_partprocessstandard (partId, processCode, specificationId) VALUES (".$partId.", ".$_GET['processCode'].", '".$standardSpecIdVal."')";
        $insertQuery = $db->query($sql);
        $count++;
    }
    // exit(0);

header("Location:anthony_editProduct.php?partId=".$_GET['partId']."&src=process&patternId=".$_GET['patternId']."");
?>