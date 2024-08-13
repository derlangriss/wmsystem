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

        $brand = $obj->brand;
        $brand_desc = $obj->brand_desc;
        $brand_note = $obj->brand_note;

        $chkbrand = preg_replace('/\s+/', '', $brand);

      

        if ($brand !== '') {

            $query  = "INSERT INTO brand (brand,brand_desc,brand_note)
            SELECT '" . $brand . "','" . $brand_desc . "','" . $brand_note ."'
            WHERE
                 NOT EXISTS (
                     SELECT 1 from brand
                     WHERE REPLACE(brand, ' ', '') ILIKE '" .$chkbrand. "'
             )
             RETURNING idbrand";


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

       
        $idbrand = $obj->idbrand;
        $brand = $obj->brand;
        $brand_desc = $obj->brand_desc;
        $brand_note = $obj->brand_note;

        $chkbrand = preg_replace('/\s+/', '', $brand);

        if ($brand !== '') {

          
            
            $query  = "UPDATE brand SET brand = '".$brand."',
            brand_desc = '".$brand_desc."',
            brand_note ='".$brand_note."'
            WHERE
                 NOT EXISTS (
                     SELECT 1 from brand
                     WHERE REPLACE(brand, ' ', '') ILIKE '" .$chkbrand. "' AND idbrand <> ".$idbrand."
             ) and idbrand =".$idbrand."
             RETURNING idbrand";


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

    if ($psubtype == 'Pbrand') {
        $idpbrand = $obj->tidpbrand;

        $query = "DELETE FROM pbrand WHERE idpbrand = " . $idpbrand;
        $stmt = $PDOconn->prepare($query);

        $stmt->execute();
    }
    if ($psubtype == 'PFeature') {
        $idpfeature = $obj->tidpfeature;

        $query = "DELETE FROM pfeature WHERE idpfeature = " . $idpfeature;
        $stmt = $PDOconn->prepare($query);

        $stmt->execute();
    }
    if ($psubtype == 'Pbrand') {
        $idpbrand = $obj->tidpbrand;

        $query = "DELETE FROM pbrand WHERE idpbrand = " . $idpbrand;
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