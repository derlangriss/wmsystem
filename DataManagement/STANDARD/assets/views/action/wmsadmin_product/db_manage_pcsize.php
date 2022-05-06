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

        $size = $obj->size;
        $size_desc = $obj->size_desc;
        $size_note = $obj->size_note;

        $chksize = preg_replace('/\s+/', '', $size);

      

        if ($size !== '') {

            $query  = "INSERT INTO size (size,size_desc,size_note)
            SELECT '" . $size . "','" . $size_desc . "','" . $size_note ."'
            WHERE
                 NOT EXISTS (
                     SELECT 1 from size
                     WHERE size ILIKE '" .$chksize. "'
             )
             RETURNING idsize";


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

       
        $idsize = $obj->idsize;
        $size = $obj->size;
        $size_desc = $obj->size_desc;
        $size_note = $obj->size_note;

        $chksize = preg_replace('/\s+/', '', $size);

        if ($size !== '') {

          
            
            $query  = "UPDATE size SET size = '".$size."',
            size_desc = '".$size_desc."',
            size_note ='".$size_note."'
            WHERE
                 NOT EXISTS (
                     SELECT 1 from size
                     WHERE REPLACE(size, ' ', '') ILIKE '" .$chksize. "' AND idsize <> ".$idsize."
             ) and idsize =".$idsize."
             RETURNING idsize";


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

    if ($psubtype == 'PSize') {
        $idpsize = $obj->tidpsize;

        $query = "DELETE FROM psize WHERE idpsize = " . $idpsize;
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