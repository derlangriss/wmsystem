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

        $ptype = $obj->ptype;
        $ptype_note = $obj->ptype_note;
        $idexpense_type = $obj->idexpense_type;
        $idmaterial_type = $obj->idmaterial_type;

     
        if ($ptype !== '') {
          

            $query = "WITH selectmaterial_cost AS (
                SELECT idmaterial_cost FROM material_cost
                LEFT JOIN expense_type on expense_type.idexpense_type = material_cost.expense_type_idexpense_type
                LEFT JOIN material_type on material_type.idmaterial_type = material_cost.material_type_idmaterial_type
                WHERE idmaterial_type = " . $idmaterial_type . " AND idexpense_type = " . $idexpense_type . "
             )
                INSERT INTO ptype (ptype,material_cost_idmaterial_cost,ptype_note)
                VALUES ('" . $ptype . "', (SELECT idmaterial_cost FROM selectmaterial_cost),'".$ptype_note."') 
                WHERE
                    NOT EXISTS (
                        SELECT 1 from ptype
                        WHERE REPLACE(ptype, ' ', '') ILIKE '" .$chkptype. "' 
                        ) 
                RETURNING idptype ";


            $stmt   = $PDOconn->prepare($query);
            $stmt->execute();

            $count = $stmt->rowCount();

            if ($count == '0') {
                $arrCol['success'] = 0;
            } else {
                $arrCol['success'] = 1;
            }
        } else  $arrCol['success'] = 555555;


        array_push($resultArray, $arrCol);
        pg_close($conn);
        echo json_encode($resultArray);
    }
    if ($action == 'UPDATE') {

      

        $idptype = $obj->idptype;
        $ptype = $obj->ptype;
        $ptype_note = $obj->ptype_note;
        $idexpense_type = $obj->idexpense_type;
        $idmaterial_type = $obj->idmaterial_type;

        $chkptype = preg_replace('/\s+/', '', $ptype);
     
        if ($ptype !== '') {

            $query = "WITH selectmaterial_cost AS (
                SELECT idmaterial_cost FROM material_cost
                LEFT JOIN expense_type on expense_type.idexpense_type = material_cost.expense_type_idexpense_type
                LEFT JOIN material_type on material_type.idmaterial_type = material_cost.material_type_idmaterial_type
                WHERE idmaterial_type = " . $idmaterial_type . " AND idexpense_type = " . $idexpense_type . "
             )";
            $query  .= " UPDATE ptype SET ptype = '".$ptype."',
            ptype_note = '".$ptype_note."',
            material_cost_idmaterial_cost = (SELECT idmaterial_cost FROM selectmaterial_cost)
            WHERE
                 NOT EXISTS (
                     SELECT 1 from ptype
                     WHERE REPLACE(ptype, ' ', '') ILIKE '" .$chkptype. "' AND idptype <> ".$idptype."
             ) and idptype =".$idptype."
             RETURNING idptype";


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