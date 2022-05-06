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

        $vendor = $obj->vendor;
        $vendor_tel = $obj->vendor_tel;
        $vendor_email = $obj->vendor_email;
        $vendor_contact = $obj->vendor_contact;
        $vendor_note = $obj->vendor_note;

        $chkvendor = preg_replace('/\s+/', '', $vendor);

     if ($vendor !== '') {

            $query  = "INSERT INTO vendor (vendor,vendor_tel,vendor_email,vendor_contact,vendor_note)
            SELECT '" . $vendor . "','" . $vendor_tel . "','" . $vendor_email ."','" . $vendor_contact ."','" . $vendor_note ."'
            WHERE
                 NOT EXISTS (
                     SELECT 1 from vendor
                     WHERE vendor ILIKE '" .$chkvendor. "'
             )
             RETURNING idvendor";


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

       
        $idvendor = $obj->idvendor;
        $vendor = $obj->vendor;
        $vendor_tel = $obj->vendor_tel;
        $vendor_email = $obj->vendor_email;
        $vendor_contact = $obj->vendor_contact;
        $vendor_note = $obj->vendor_note;

        $chkvendor = preg_replace('/\s+/', '', $vendor);

        if ($vendor !== '') {

          
            
            $query  = "UPDATE vendor SET vendor = '".$vendor."',
            vendor_tel = '".$vendor_tel."',
            vendor_email ='".$vendor_email."',
            vendor_contact ='".$vendor_contact."',
            vendor_note ='".$vendor_note."'
            WHERE
                 NOT EXISTS (
                     SELECT 1 from vendor
                     WHERE REPLACE(vendor, ' ', '') ILIKE '" .$chkvendor. "' AND idvendor <> ".$idvendor."
             ) and idvendor =".$idvendor."
             RETURNING idvendor";


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

    if ($psubtype == 'Pvendor') {
        $idpvendor = $obj->tidpvendor;

        $query = "DELETE FROM pvendor WHERE idpvendor = " . $idpvendor;
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