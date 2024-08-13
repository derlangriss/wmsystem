<?php
require '../dbconnect/connectdb_wms.php';

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
$resultArray = array();
$arrCol      = array();
$obj       = convertToObject($r);
$action    = $obj->action;

if ($action == 'INSERT' || $action == 'UPDATE') {

    if ($action == 'INSERT') {
        $product    = $obj->product;
        $idmaterial_cost    = $obj->idmaterial_cost;
        $p_image    = $obj->p_image;
        $idptype    = $obj->idptype;
        $idlogin_user = $obj->idlogin_user;

        $image_name = basename($p_image);

        $chkproduct = preg_replace('/\s+/', '', $product);

        $query  = "INSERT INTO product (product,material_cost_idmaterial_cost,p_image,ptype_idptype,login_user_idlogin_user)
            SELECT '" . $product . "','" . $idmaterial_cost . "','" . $image_name . "','" . $idptype . "','" . $idlogin_user . "'
            WHERE
                 NOT EXISTS (
                     SELECT 1 from product
                     WHERE product ILIKE '" . $chkproduct . "'
             )
             RETURNING idproduct";

        $stmt   = $PDOconn->prepare($query);
        $stmt->execute();

        $count = $stmt->rowCount();



        if ($count == '0') {
            $arrCol['success'] = 0;
        } else {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($result);
            $arrCol['success'] = 1;
            $arrCol['idproduct'] = $idproduct;
        }

        array_push($resultArray, $arrCol);
        pg_close($conn);
        echo json_encode($resultArray);
    }
    if ($action == 'UPDATE') {
    }
}
if ($action == 'DELETE' || $action == 'DELETEALL') {
}
