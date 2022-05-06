<?php

require '../connectdb_wms.php';
$resultArray = array();
$arrCol      = array();

if (isset($_GET['idproduct'])) {

    $strSQL = "SELECT * FROM product ";
    $strSQL .= "LEFT JOIN material_cost on material_cost.idmaterial_cost = product.material_cost_idmaterial_cost ";
    $strSQL .= "LEFT JOIN material_type on material_type.idmaterial_type = material_cost.material_type_idmaterial_type ";
    $strSQL .= "LEFT JOIN expense_type on expense_type.idexpense_type = material_cost.expense_type_idexpense_type ";
    $strSQL .= "WHERE idproduct =" . $_GET['idproduct'];

    $stmt = $PDOconn->prepare($strSQL);
    $stmt->execute();

    $num = $stmt->rowCount();
    if ($num != 0) {

        $intNumField = $stmt->columnCount();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

            for ($i = 0; $i < $intNumField; $i++) {
                $col     = $stmt->getColumnMeta($i);
                $columns = $col['name'];

                $arrCol[$columns] = $result[$columns];
            }
            array_push($resultArray, $arrCol);
        }
    } else {
        $arrCol['success'] = 'no';
        array_push($resultArray, $arrCol);
    }
    pg_close($conn);

    echo json_encode($resultArray);
} else {
}
/*
<?php

require '../dbconnect/connectdb_wms.php';
$tidproduct = $_GET['idproduct'];

$query = "SELECT * FROM product ";
$query .= "WHERE idproduct =" . $tidproduct;

$stmt = $PDOconn->prepare($query);
$stmt->execute();

if ($stmt->execute()) {
    $result = $stmt->fetchAll();

*/

/*
$strSQL = "SELECT * FROM product ";
$strSQL .= "LEFT JOIN catagory_type on catagory_type.idcatagory_type = product.catagory_type_idcatagory_type ";
$strSQL .= "LEFT JOIN supplies_type on supplies_type.idsupplies_type = catagory_type.supplies_type_idsupplies_type ";
$strSQL .= "LEFT JOIN catagory on catagory.idcatagory = catagory_type.catagory_idcatagory ";
$strSQL .= "WHERE idproduct =" . $tproductid;

$objQuery    = pg_query($strSQL);
$intNumField = pg_num_fields($objQuery);
$resultArray = array();
while ($obResult = pg_fetch_array($objQuery)) {
    $arrCol = array();
    extract($obResult);
    for ($i = 0; $i < $intNumField; $i++) {
        if (pg_field_name($objQuery, $i) == 'product_images') {
            $arrCol[pg_field_name($objQuery, $i)] = "assets/images/product/" . $obResult[$i];
        } else {
            $arrCol[pg_field_name($objQuery, $i)] = $obResult[$i];
        }
    }
    $strSQL02 = "select idsupplier,supplier,idproduct_supplier from product  ";
    $strSQL02 .= "left join product_supplier on product_supplier.product_idproduct = product.idproduct ";
    $strSQL02 .= "left join supplier on product_supplier.supplier_idsupplier = supplier.idsupplier ";
    $strSQL02 .= "WHERE idproduct =" . $tproductid;
    $objQuery02    = pg_query($strSQL02);
    $resultArray02 = array();
    $intNumField02 = pg_num_fields($objQuery02);
    $q             = 1;

    $arrCol02 = array();
    $check02  = pg_num_rows($objQuery02);
    if ($check02 == 0) {
        $arrCol02['idsize']         = '';
        $arrCol02['size']           = '';
        $arrCol02['idproduct_size'] = '';
        array_push($resultArray02, $arrCol02);

        $arrCol['size'] = $resultArray02;
    } else {

        while ($obResult02 = pg_fetch_array($objQuery02)) {

            for ($i = 0; $i < $intNumField02; $i++) {
                $arrCol02[pg_field_name($objQuery02, $i)] = $obResult02[$i];
            }
            $arrCol02['queue'] = $q;
            array_push($resultArray02, $arrCol02);

            $arrCol['supplier'] = $resultArray02;

            $q++;

        }
    }

    $strSQL03 = "select idsize,size,idproduct_size from size ";
    $strSQL03 .= "left join product_size on product_size.size_idsize = size.idsize ";
    $strSQL03 .= "left join product on product.idproduct = product_size.product_idproduct ";
    $strSQL03 .= "WHERE idproduct =" . $tproductid;
    $objQuery03    = pg_query($strSQL03);
    $resultArray03 = array();
    $intNumField03 = pg_num_fields($objQuery03);

    $arrCol03 = array();
    $check03  = pg_num_rows($objQuery03);
    if ($check03 == 0) {
        $arrCol03['idsize']         = '';
        $arrCol03['size']           = '';
        $arrCol03['idproduct_size'] = '';
        array_push($resultArray03, $arrCol03);

        $arrCol['size'] = $resultArray03;
    } else {
        while ($obResult03 = pg_fetch_array($objQuery03)) {

            for ($i = 0; $i < $intNumField03; $i++) {
                $arrCol03[pg_field_name($objQuery03, $i)] = $obResult03[$i];
            }

            array_push($resultArray03, $arrCol03);

            $arrCol['size'] = $resultArray03;

        }

    }

    $strSQL04 = "select idfeature,feature,idproduct_feature from feature ";
    $strSQL04 .= "left join product_feature on product_feature.feature_idfeature = feature.idfeature ";
    $strSQL04 .= "left join product on product.idproduct = product_feature.product_idproduct ";
    $strSQL04 .= "WHERE idproduct =" . $tproductid;
    $objQuery04    = pg_query($strSQL04);
    $resultArray04 = array();
    $intNumField04 = pg_num_fields($objQuery04);
    $check04       = pg_num_rows($objQuery04);
    $arrCol04      = array();
    if ($check04 == 0) {

        $arrCol04['idfeature']         = '';
        $arrCol04['feature']           = '';
        $arrCol04['idproduct_feature'] = '';
        array_push($resultArray04, $arrCol04);

        $arrCol['feature'] = $resultArray03;
    } else {

        while ($obResult04 = pg_fetch_array($objQuery04)) {

            for ($i = 0; $i < $intNumField04; $i++) {
                $arrCol04[pg_field_name($objQuery04, $i)] = $obResult04[$i];
            }

            array_push($resultArray04, $arrCol04);

            $arrCol['feature'] = $resultArray04;

        }
    }

    $strSQL05 = "select idproduct_unit,idunit,unit,idsubunit,subunit,qtyperunit from product_unit ";
    $strSQL05 .= "left join product on product.idproduct = product_unit.product_idproduct ";
    $strSQL05 .= "left join unit on unit.idunit = product_unit.unit_idunit ";
    $strSQL05 .= "left join subunit on subunit.idsubunit = product_unit.subunit_idsubunit ";
    $strSQL05 .= "WHERE idproduct =" . $tproductid;
    $objQuery05    = pg_query($strSQL05);
    $resultArray05 = array();
    $intNumField05 = pg_num_fields($objQuery05);
    $arrCol05      = array();
    $check05       = pg_num_rows($objQuery05);
    if ($check03 == 0) {
        $arrCol05['idunit']         = '';
        $arrCol05['unit']           = '';
        $arrCol05['subunit']        = '';
        $arrCol05['idsubunit']      = '';
        $arrCol05['idproduct_unit'] = '';
        $arrCol05['qtyperunit']     = '';
        array_push($resultArray05, $arrCol05);

        $arrCol['unit'] = $resultArray05;
    } else {

        while ($obResult05 = pg_fetch_array($objQuery05)) {

            for ($i = 0; $i < $intNumField05; $i++) {
                $arrCol05[pg_field_name($objQuery05, $i)] = $obResult05[$i];
            }

            array_push($resultArray05, $arrCol05);

            $arrCol['unit'] = $resultArray05;

        }
    }

    $strSQL06 = "select idbrand,brand,idproduct_brand from brand ";
    $strSQL06 .= "left join product_brand on product_brand.brand_idbrand = brand.idbrand ";
    $strSQL06 .= "left join product on product.idproduct = product_brand.product_idproduct ";
    $strSQL06 .= "WHERE idproduct =" . $tproductid;
    $objQuery06    = pg_query($strSQL06);
    $resultArray06 = array();
    $intNumField06 = pg_num_fields($objQuery06);

    $arrCol06 = array();
    $check06  = pg_num_rows($objQuery06);
    if ($check06 == 0) {
        $arrCol06['idbrand']         = '';
        $arrCol06['brand']           = '';
        $arrCol06['idproduct_brand'] = '';
        array_push($resultArray06, $arrCol06);

        $arrCol['brand'] = $resultArray06;
    } else {

        while ($obResult06 = pg_fetch_array($objQuery06)) {

            for ($i = 0; $i < $intNumField06; $i++) {
                $arrCol06[pg_field_name($objQuery06, $i)] = $obResult06[$i];
            }

            array_push($resultArray06, $arrCol06);

            $arrCol['brand'] = $resultArray06;

        }
    }

    $strSQL07 = "select idproduct_image,image_name from product_images ";
    $strSQL07 .= "left join product on product.idproduct = product_images.product_idproduct ";
    $strSQL07 .= "WHERE idproduct =" . $tproductid;
    $objQuery07    = pg_query($strSQL07);
    $resultArray07 = array();
    $intNumField07 = pg_num_fields($objQuery07);

    $arrCol07 = array();
    $check07  = pg_num_rows($objQuery07);
    if ($check07 == 0) {
        $arrCol07['idproduct_image'] = '';
        $arrCol07['image_name']      = '';
        array_push($resultArray07, $arrCol07);
        $arrCol['brand'] = $resultArray07;
    } else {

        while ($obResult07 = pg_fetch_array($objQuery07)) {

            for ($i = 0; $i < $intNumField07; $i++) {
                if (pg_field_name($objQuery07, $i) == 'image_name') {
                    $arrCol07[pg_field_name($objQuery07, $i)] = "assets/images/product/" . $obResult07[$i];
                } else {
                    $arrCol07[pg_field_name($objQuery07, $i)] = $obResult07[$i];
                }
            }

            array_push($resultArray07, $arrCol07);

            $arrCol['imageall'] = $resultArray07;

        }
    }

    array_push($resultArray, $arrCol);
}
pg_close($conn);

echo json_encode($resultArray);
*/