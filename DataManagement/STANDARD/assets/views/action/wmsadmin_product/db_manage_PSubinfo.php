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

        $psubtype    = $obj->tpsubtype;

        if ($psubtype == 'PSize') {
            $idproduct = $obj->tidproduct;
            $idsize = $obj->tidsize;
            $size = $obj->tsize;
            $size_desc = $obj->tsize_desc;

            $query  = "INSERT INTO psize (size_idsize,product_idproduct)
            SELECT " . $idsize . "," . $idproduct . " 
            WHERE
                 NOT EXISTS (
                     SELECT idsize,idproduct from psize
                                             LEFT JOIN size on size.idsize = psize.size_idsize 
                                             LEFT JOIN product on product.idproduct = psize.product_idproduct
                                             WHERE idsize = " . $idsize . " AND idproduct = " . $idproduct . "
             )
             RETURNING idpsize";
            $stmt   = $PDOconn->prepare($query);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($result);

            $arr = array(
                'idpsize' => $idpsize,
                'idsize'   => $idsize,
                'size'   => $size,
                'size_desc' => $size_desc
            );
        }
        if ($psubtype == 'PFeature') {
            $idproduct = $obj->tidproduct;
            $idfeature = $obj->tidfeature;
            $feature = $obj->tfeature;
            $feature_desc = $obj->tfeature_desc;

            $query  = "INSERT INTO pfeature (feature_idfeature,product_idproduct)
            SELECT " . $idfeature . "," . $idproduct . " 
            WHERE
                 NOT EXISTS (
                     SELECT idfeature,idproduct from pfeature
                                             LEFT JOIN feature on feature.idfeature = pfeature.feature_idfeature 
                                             LEFT JOIN product on product.idproduct = pfeature.product_idproduct
                                             WHERE idfeature = " . $idfeature . " AND idproduct = " . $idproduct . "
             )
             RETURNING idpfeature";
            $stmt   = $PDOconn->prepare($query);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($result);

            $arr = array(
                'idpfeature' => $idpfeature,
                'idfeature'   => $idfeature,
                'feature'   => $feature,
                'feature_desc' => $feature_desc
            );
        }
        if ($psubtype == 'PBrand') {
            $idproduct = $obj->tidproduct;
            $idbrand = $obj->tidbrand;
            $brand = $obj->tbrand;
            $brand_desc = $obj->tbrand_desc;

            $query  = "INSERT INTO pbrand (brand_idbrand,product_idproduct)
            SELECT " . $idbrand . "," . $idproduct . " 
            WHERE
                 NOT EXISTS (
                     SELECT idbrand,idproduct from pbrand
                                             LEFT JOIN brand on brand.idbrand = pbrand.brand_idbrand 
                                             LEFT JOIN product on product.idproduct = pbrand.product_idproduct
                                             WHERE idbrand = " . $idbrand . " AND idproduct = " . $idproduct . "
             )
             RETURNING idpbrand";
            $stmt   = $PDOconn->prepare($query);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($result);

            $arr = array(
                'idpbrand' => $idpbrand,
                'idbrand'   => $idbrand,
                'brand'   => $brand,
                'brand_desc' => $brand_desc
            );
        }
        if ($psubtype == 'PUnit') {
            $idproduct = $obj->tidproduct;
            $idunit = $obj->tidunit;
            $unit = $obj->tunit;
            $unit_desc = $obj->tunit_desc;

            $query  = "INSERT INTO punit (unit_idunit,product_idproduct)
            SELECT " . $idunit . "," . $idproduct . " 
            WHERE
                 NOT EXISTS (
                     SELECT idunit,idproduct from punit
                                             LEFT JOIN unit on unit.idunit = punit.unit_idunit 
                                             LEFT JOIN product on product.idproduct = punit.product_idproduct
                                             WHERE idunit = " . $idunit . " AND idproduct = " . $idproduct . "
             )
             RETURNING idpunit";
            $stmt   = $PDOconn->prepare($query);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($result);

            $arr = array(
                'idpunit' => $idpunit,
                'idunit'   => $idunit,
                'unit'   => $unit,
                'unit_desc' => $unit_desc
            );
        }
        if ($psubtype == 'PVendor') {
            $idproduct = $obj->tidproduct;
            $idvendor = $obj->tidvendor;
            $vendor = $obj->tvendor;
            $vendor_desc = $obj->tvendor_desc;

            $query  = "INSERT INTO pvendor (vendor_idvendor,product_idproduct)
            SELECT " . $idvendor . "," . $idproduct . " 
            WHERE
                 NOT EXISTS (
                     SELECT idvendor,idproduct from pvendor
                                             LEFT JOIN vendor on vendor.idvendor = pvendor.vendor_idvendor 
                                             LEFT JOIN product on product.idproduct = pvendor.product_idproduct
                                             WHERE idvendor = " . $idvendor . " AND idproduct = " . $idproduct . "
             )
             RETURNING idpvendor";
            $stmt   = $PDOconn->prepare($query);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($result);

            $arr = array(
                'idpvendor' => $idpvendor,
                'idvendor'   => $idvendor,
                'vendor'   => $vendor,
                'vendor_desc' => $vendor_desc
            );
        }
        array_push($resultArray, $arr);
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





/*
if (!empty($_GET["PSubInfo"])) {

    if ($_GET["PSubInfo"] == 'Psize') {
        if ($_GET['sPsize'] !== '') {
            $query = "SELECT idsize,size FROM size";
            $query .= " WHERE size ILIKE '%" . $_GET['sPsize'] . "%' AND idsize <> 0 ORDER BY idsize ASC LIMIT 5";
        } else {
            exit;
        }

        $resultArray = array();
        $stmt        = $PDOconn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num == 0) {
            $rowarr = array(
                'idsize' => 0,
                'size'   => 'ไม่พบรายการ',
            );
            array_push($resultArray, $rowarr);

        } else {
            $intNumField = $stmt->columnCount();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

                for ($i = 0; $i < $intNumField; $i++) {
                    $col     = $stmt->getColumnMeta($i);
                    $columns = $col['name'];

                    $arrCol[$columns] = $result[$columns];
                }
                array_push($resultArray, $arrCol);

            }

        }

    }

    if ($_GET["PSubInfo"] == 'Pfeature') {

        if ($_GET['sPfeature'] !== '') {
            $query = "SELECT idfeature,feature FROM feature";
            $query .= " WHERE feature ILIKE '%" . $_GET['sPfeature'] . "%' AND idfeature <> 0 ORDER BY idfeature ASC LIMIT 5";
        } else {
            exit;
        }

        $resultArray = array();
        $stmt        = $PDOconn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num == 0) {
            $rowarr = array(
                'idfeature' => 0,
                'feature'   => 'ไม่พบรายการ',
            );
            array_push($resultArray, $rowarr);

        } else {
            $intNumField = $stmt->columnCount();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

                for ($i = 0; $i < $intNumField; $i++) {
                    $col     = $stmt->getColumnMeta($i);
                    $columns = $col['name'];

                    $arrCol[$columns] = $result[$columns];
                }
                array_push($resultArray, $arrCol);

            }

        }

    }

    if ($_GET["PSubInfo"] == 'Punit') {

        if ($_GET['sPunit'] !== '') {
            $query = "SELECT idunit,unit FROM unit";
            $query .= " WHERE unit ILIKE '%" . $_GET['sPunit'] . "%' AND idunit <> 0 ORDER BY idunit ASC LIMIT 5";
        } else {
            exit;
        }

        $resultArray = array();
        $stmt        = $PDOconn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num == 0) {
            $rowarr = array(
                'idunit' => 0,
                'unit'   => 'ไม่พบรายการ',
            );
            array_push($resultArray, $rowarr);

        } else {
            $intNumField = $stmt->columnCount();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

                for ($i = 0; $i < $intNumField; $i++) {
                    $col     = $stmt->getColumnMeta($i);
                    $columns = $col['name'];

                    $arrCol[$columns] = $result[$columns];
                }
                array_push($resultArray, $arrCol);

            }

        }

    }

    if ($_GET["PSubInfo"] == 'Psubunit') {

        if ($_GET['sPsubunit'] !== '') {
            $query = "SELECT idsubunit,subunit FROM subunit";
            $query .= " WHERE subunit ILIKE '%" . $_GET['sPsubunit'] . "%' AND idsubunit <> 0 ORDER BY idsubunit ASC LIMIT 5";
        } else {
            exit;
        }

        $resultArray = array();
        $stmt        = $PDOconn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num == 0) {
            $rowarr = array(
                'idsubunit' => 0,
                'subunit'   => 'ไม่พบรายการ',
            );
            array_push($resultArray, $rowarr);

        } else {
            $intNumField = $stmt->columnCount();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

                for ($i = 0; $i < $intNumField; $i++) {
                    $col     = $stmt->getColumnMeta($i);
                    $columns = $col['name'];

                    $arrCol[$columns] = $result[$columns];
                }
                array_push($resultArray, $arrCol);

            }

        }

    }

    if ($_GET["PSubInfo"] == 'Pbrand') {

        if ($_GET['sPbrand'] !== '') {
            $query = "SELECT idbrand,brand FROM brand";
            $query .= " WHERE brand ILIKE '%" . $_GET['sPbrand'] . "%' AND idbrand <> 0 ORDER BY idbrand ASC LIMIT 5";
        } else {
            exit;
        }

        $resultArray = array();
        $stmt        = $PDOconn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num == 0) {
            $rowarr = array(
                'idbrand' => 0,
                'brand'   => 'ไม่พบรายการ',
            );
            array_push($resultArray, $rowarr);

        } else {
            $intNumField = $stmt->columnCount();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

                for ($i = 0; $i < $intNumField; $i++) {
                    $col     = $stmt->getColumnMeta($i);
                    $columns = $col['name'];

                    $arrCol[$columns] = $result[$columns];
                }
                array_push($resultArray, $arrCol);

            }

        }

    }

    if ($_GET["PSubInfo"] == 'Psupplier') {

        if ($_GET['sPsupplier'] !== '') {
            $query = "SELECT idsupplier,supplier FROM supplier";
            $query .= " WHERE supplier ILIKE '%" . $_GET['sPsupplier'] . "%' AND idsupplier <> 0 ORDER BY idsupplier ASC LIMIT 5";
        } else {
            exit;
        }

        $resultArray = array();
        $stmt        = $PDOconn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num == 0) {
            $rowarr = array(
                'idsupplier' => 0,
                'supplier'   => 'ไม่พบรายการ',
            );
            array_push($resultArray, $rowarr);

        } else {
            $intNumField = $stmt->columnCount();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

                for ($i = 0; $i < $intNumField; $i++) {
                    $col     = $stmt->getColumnMeta($i);
                    $columns = $col['name'];

                    $arrCol[$columns] = $result[$columns];
                }
                array_push($resultArray, $arrCol);

            }

        }

    }

}

echo json_encode($resultArray);
*/