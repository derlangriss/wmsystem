<?php
require '../dbconnect/connectdb_wms.php';
$resultArray = array();
$arrCol      = array();

$idpimages = $_GET["query"];

if (isset($idpimages)) {

    $strSQL = "UPDATE pimages SET ";
    $strSQL .= "pimages_trash = 'false'";
    $strSQL .= "WHERE idpimages = '" . $idpimages . "' ";
    $stmt = $PDOconn->prepare($strSQL);
    $stmt->execute();
    $num = $stmt->rowCount();
    if ($num == 0) {
        $arrCol['success'] = 0;
    } else {
        $arrCol['success'] = 1;
    }
    array_push($resultArray, $arrCol);

}

pg_close($conn);

echo json_encode($resultArray);