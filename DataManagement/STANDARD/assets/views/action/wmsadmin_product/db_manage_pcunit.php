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

        $unit = $obj->unit;
        $unit_desc = $obj->unit_desc;
        $unit_note = $obj->unit_note;

        $chkunit = preg_replace('/\s+/', '', $unit);

      

        if ($unit !== '') {

            $query  = "INSERT INTO unit (unit,unit_desc,unit_note)
            SELECT '" . $unit . "','" . $unit_desc . "','" . $unit_note ."'
            WHERE
                 NOT EXISTS (
                     SELECT 1 from unit
                     WHERE unit ILIKE '" .$chkunit. "'
             )
             RETURNING idunit";


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

       
        $idunit = $obj->idunit;
        $unit = $obj->unit;
        $unit_desc = $obj->unit_desc;
        $unit_note = $obj->unit_note;

        $chkunit = preg_replace('/\s+/', '', $unit);

        if ($unit !== '') {

          
            
            $query  = "UPDATE unit SET unit = '".$unit."',
            unit_desc = '".$unit_desc."',
            unit_note ='".$unit_note."'
            WHERE
                 NOT EXISTS (
                     SELECT 1 from unit
                     WHERE REPLACE(unit, ' ', '') ILIKE '" .$chkunit. "' AND idunit <> ".$idunit."
             ) and idunit =".$idunit."
             RETURNING idunit";


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

    if ($psubtype == 'Punit') {
        $idpunit = $obj->tidpunit;

        $query = "DELETE FROM punit WHERE idpunit = " . $idpunit;
        $stmt = $PDOconn->prepare($query);

        $stmt->execute();
    }
    if ($psubtype == 'PFeature') {
        $idpfeature = $obj->tidpfeature;

        $query = "DELETE FROM pfeature WHERE idpfeature = " . $idpfeature;
        $stmt = $PDOconn->prepare($query);

        $stmt->execute();
    }
    if ($psubtype == 'PUnit') {
        $idpunit = $obj->tidpunit;

        $query = "DELETE FROM punit WHERE idpunit = " . $idpunit;
        $stmt = $PDOconn->prepare($query);

        $stmt->execute();
    }
    if ($psubtype == 'PBrand') {
        $idpbrand = $obj->tidpbrand;

        $query = "DELETE FROM pbrand WHERE idpbrand = " . $idpbrand;
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