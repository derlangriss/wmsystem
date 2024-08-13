<?php
require '../dbconnect/connectdb_wms.php';
$resultArray = array();
$arrCol      = array();

if (!empty($_GET["PSubInfo"])) {

    if ($_GET["PSubInfo"] == 'Pgroup') {
        if ($_GET['sPgroup'] !== '') {
            $query = "SELECT idptype,ptype,ptype_note,material_type,expense_type,idmaterial_cost FROM ptype";
            $query .= " LEFT JOIN material_cost on material_cost.idmaterial_cost = ptype.material_cost_idmaterial_cost";
            $query .= " LEFT JOIN expense_type on expense_type.idexpense_type = material_cost.expense_type_idexpense_type";
            $query .= " LEFT JOIN material_type on material_type.idmaterial_type = material_cost.material_type_idmaterial_type";
            $query .= " WHERE ptype ILIKE '%" . $_GET['sPgroup'] . "%' AND idptype <> 0 ORDER BY idptype ASC LIMIT 5";
        } else {
            exit;
        }

        $resultArray = array();
        $stmt        = $PDOconn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num == 0) {
            $rowarr = array(
                'idptype' => 0,
                'ptype'   => 'ไม่พบรายการ',
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

    if ($_GET["PSubInfo"] == 'PSize') {
        if ($_GET['sPSize'] !== '') {
            $query = "SELECT idsize,size,size_desc FROM size";
            $query .= " WHERE size ILIKE '%" . $_GET['sPSize'] . "%' AND idsize <> 0 ORDER BY idsize ASC LIMIT 5";
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

    if ($_GET["PSubInfo"] == 'PFeature') {

        if ($_GET['sPFeature'] !== '') {
            $query = "SELECT idfeature,feature,feature_desc FROM feature";
            $query .= " WHERE feature ILIKE '%" . $_GET['sPFeature'] . "%' AND idfeature <> 0 ORDER BY idfeature ASC LIMIT 5";
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

    if ($_GET["PSubInfo"] == 'PBrand') {

        if ($_GET['sPBrand'] !== '') {
            $query = "SELECT idbrand,brand,brand_desc FROM brand";
            $query .= " WHERE brand ILIKE '%" . $_GET['sPBrand'] . "%' AND idbrand <> 0 ORDER BY idbrand ASC LIMIT 5";
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

    if ($_GET["PSubInfo"] == 'PUnit') {

        if ($_GET['sPUnit'] !== '') {
            $query = "SELECT idunit,unit,unit_desc FROM unit";
            $query .= " WHERE unit ILIKE '%" . $_GET['sPUnit'] . "%' AND idunit <> 0 ORDER BY idunit ASC LIMIT 5";
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

    if ($_GET["PSubInfo"] == 'PVendor') {

        if ($_GET['sPVendor'] !== '') {
            $query = "SELECT idvendor,vendor,vendor_desc FROM vendor";
            $query .= " WHERE vendor ILIKE '%" . $_GET['sPVendor'] . "%' AND idvendor <> 0 ORDER BY idvendor ASC LIMIT 5";
        } else {
            exit;
        }

        $resultArray = array();
        $stmt        = $PDOconn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num == 0) {
            $rowarr = array(
                'idvendor' => 0,
                'vendor'   => 'ไม่พบรายการ',
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

    

    

}

echo json_encode($resultArray);
