<?php
require '../dbconnect/connectdb_wms.php';
$resultArray = array();
$arrCol      = array();

function convertToObject($array)
{
    $object = new stdClass();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $value = convertToObject($value);
        }
        $object->$key = $value;
    }
    return $object;
}
$r         = $_POST["datapost"];
$obj       = convertToObject($r);
$action    = $obj->action;

if ($action == 'INSERT' || $action == 'UPDATE') {

    if ($action == 'INSERT') {

        $feature = $obj->feature;
        $feature_desc = $obj->feature_desc;
        $feature_note = $obj->feature_note;

        $chkfeature = preg_replace('/\s+/', '', $feature);


        if ($feature !== '') {

            $query  = "INSERT INTO feature (feature,feature_desc,feature_note)
            SELECT '" . $feature . "','" . $feature_desc . "','" . $feature_note ."'
            WHERE
                 NOT EXISTS (
                     SELECT 1 from feature
                     WHERE REPLACE(feature, ' ', '') ILIKE '" .$chkfeature. "'
             )
            RETURNING idfeature";


            $stmt   = $PDOconn->prepare($query);
            $stmt->execute();

            $count = $stmt->rowCount();

            if ($count == '0') {
                $arrCol['success'] = 0;
            } else {
                $arrCol['success'] = 1;
            }
        } else  $arrCol['success'] = 0;


        array_push($resultArray, $arrCol);
        pg_close($conn);
        echo json_encode($resultArray);
    }
    if ($action == 'UPDATE') {

       
        $idfeature = $obj->idfeature;
        $feature = $obj->feature;
        $feature_desc = $obj->feature_desc;
        $feature_note = $obj->feature_note;

        $chkfeature = preg_replace('/\s+/', '', $feature);

        if ($feature !== '') {

          
            
            $query  = "UPDATE feature SET feature = '".$feature."',
            feature_desc = '".$feature_desc."',
            feature_note ='".$feature_note."'
            WHERE
                 NOT EXISTS (
                     SELECT 1 from feature
                     WHERE REPLACE(feature, ' ', '') ILIKE '" .$chkfeature. "' AND idfeature <> ".$idfeature."
             ) and idfeature =".$idfeature."
             RETURNING idfeature";


            $stmt   = $PDOconn->prepare($query);
            $stmt->execute();

            $count = $stmt->rowCount();

            if ($count == '0') {
                $arrCol['success'] = 0;
            } else {
                $arrCol['success'] = 1;
            }
        } else  $arrCol['success'] = 0;


        array_push($resultArray, $arrCol);
        pg_close($conn);
        echo json_encode($resultArray);
    }
}

if ($action == 'DELETE') {

    $psubtype    = $obj->tpsubtype;

    if ($psubtype == 'Pfeature') {
        $idpfeature = $obj->tidpfeature;

        $query = "DELETE FROM pfeature WHERE idpfeature = " . $idpfeature;
        $stmt = $PDOconn->prepare($query);

        $stmt->execute();
    }
    if ($psubtype == 'PFeature') {
        $idpfeature = $obj->tidpfeature;

        $query = "DELETE FROM pfeature WHERE idpfeature = " . $idpfeature;
        $stmt = $PDOconn->prepare($query);

        $stmt->execute();
    }
    if ($psubtype == 'Pfeature') {
        $idpfeature = $obj->tidpfeature;

        $query = "DELETE FROM pfeature WHERE idpfeature = " . $idpfeature;
        $stmt = $PDOconn->prepare($query);

        $stmt->execute();
    }
    if ($psubtype == 'Pfeature') {
        $idpfeature = $obj->tidpfeature;

        $query = "DELETE FROM pfeature WHERE idpfeature = " . $idpfeature;
        $stmt = $PDOconn->prepare($query);

        $stmt->execute();
    }
    if ($psubtype == 'PVendor') {
        $idpvendor = $obj->tidpvendor;

        $query = "DELETE FROM pvendor WHERE idpvendor = " . $idpvendor;
        $stmt = $PDOconn->prepare($query);

        $stmt->execute();
    }

    $num = $stmt->rowCount();
    if ($num == 0) {
        $arrCol['success'] = 0;
    } else {
        $arrCol['success'] = 1;
    }
    array_push($resultArray, $arrCol);
    pg_close($conn);

    echo json_encode($resultArray);
}