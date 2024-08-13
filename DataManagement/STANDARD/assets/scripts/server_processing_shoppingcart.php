<?php
date_default_timezone_set('America/New_York');
date("H:i:s");
session_start();
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = "product_compound
left join product on product.idproduct = product_compound.product_idproduct
left join catagory_type on catagory_type.idcatagory_type = product.catagory_type_idcatagory_type
left join catagory on catagory.idcatagory = catagory_type.catagory_idcatagory
left join supplies_type on supplies_type.idsupplies_type = catagory_type.supplies_type_idsupplies_type
left join product_size on product_size.idproduct_size = product_compound.product_size_idproduct_size
left join size on size.idsize = product_size.size_idsize
left join product_unit on product_unit.idproduct_unit = product_compound.product_unit_idproduct_unit
left join unit on unit.idunit = product_unit.unit_idunit
left join product_supplier on product_supplier.idproduct_supplier = product_compound.product_supplier_idproduct_supplier
left join supplier on supplier.idsupplier = product_supplier.supplier_idsupplier
left join product_brand on product_brand.idproduct_brand = product_compound.product_brand_idproduct_brand
left join brand on brand.idbrand = product_brand.brand_idbrand
left join product_feature on product_feature.idproduct_feature = product_compound.product_feature_idproduct_feature
left join feature on feature.idfeature = product_feature.feature_idfeature
";

// Table's primary key
$primaryKey = 'idproduct_compound';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array(
        'as' => 'as1',
        'tb' => 'product_compound',
        'db' => 'idproduct_compound',
        'dt' => 'DT_RowId',
        /**
        'formatter' => function ($d, $row) {
        // Technically a DOM id cannot start with an integer, so we prefix
        // a string. This can also be useful if you have multiple tables
        // to ensure that the id is unique with a different prefix
        return 'row_' . $d;
        },**/

    ),
    array('as' => 'as2', 'tb' => 'product', 'db' => 'idproduct', 'dt' => 0),
    array('as' => 'as3', 'tb' => 'catagory', 'db' => 'catagory', 'dt' => 1),
    array('as' => 'as4', 'tb' => 'supplies_type', 'db' => 'supplies_type', 'dt' => 2),
    array('as' => 'as5', 'tb' => 'product', 'db' => 'product', 'dt' => 3),
    array('as' => 'as6', 'tb' => 'size', 'db' => 'size', 'dt' => 4),
    array('as' => 'as7', 'tb' => 'feature', 'db' => 'feature', 'dt' => 5),
    array('as' => 'as8', 'tb' => 'brand', 'db' => 'brand', 'dt' => 6),
    array('as' => 'as9', 'tb' => 'unit', 'db' => 'unit', 'dt' => 7),
    array('as' => 'as10', 'tb' => 'supplier', 'db' => 'supplier', 'dt' => 8),
    array('as' => 'as11', 'tb' => 'product_compound', 'db' => 'price', 'dt' => 9),
    array('as' => 'as12', 'tb' => 'brand', 'db' => 'idbrand', 'dt' => 10),
    array('as' => 'as13', 'tb' => 'unit', 'db' => 'idunit', 'dt' => 11),
    array('as' => 'as14', 'tb' => 'supplier', 'db' => 'idsupplier', 'dt' => 12),
    array('as' => 'as15', 'tb' => 'catagory', 'db' => 'idcatagory', 'dt' => 13),
    array(
        'as'        => 'as16',
        'tb'        => 'product_compound',
        'db'        => 'idproduct_compound',
        'dt'        => 14,
        'formatter' => function ($d, $row) {

            $key = array_search($d, $_SESSION["strProductID"]);
            /*$_SESSION["strQty"][$key]*/

            return $_SESSION["strQty"][$key];
        },

    ),
    array(
        'as'        => 'as17',
        'tb'        => 'product_compound',
        'db'        => 'idproduct_compound',
        'dt'        => 15,
        'formatter' => function ($d, $row) {

            $key = array_search($d, $_SESSION["strProductID"]);
            /*$_SESSION["strQty"][$key]*/

            return $_SESSION["stravgPrice"][$key];
        },

    ),
    array(
        'as'        => 'as18',
        'tb'        => 'product_compound',
        'db'        => 'idproduct_compound',
        'dt'        => 16,
        'formatter' => function ($d, $row) {

            $countarrdt = count($_SESSION['strProductID']);
            $result = $countarrdt + 2;

            return $result;
        },

    ),

    /*
array('as' => 'as2', 'tb' => 'product', 'db' => 'idproduct', 'dt' => 0),
array('as' => 'as3', 'tb' => 'catagory', 'db' => 'catagory', 'dt' => 1),
array('as' => 'as4', 'tb' => 'supplies_type', 'db' => 'supplies_type', 'dt' => 2),
array('as' => 'as5', 'tb' => 'product', 'db' => 'product', 'dt' => 3),
array('as' => 'as6', 'tb' => 'size', 'db' => 'size', 'dt' => 4),
array('as' => 'as7', 'tb' => 'feature', 'db' => 'feature', 'dt' => 5),
array('as' => 'as8', 'tb' => 'brand', 'db' => 'brand', 'dt' => 6),
array('as' => 'as9', 'tb' => 'unit', 'db' => 'unit', 'dt' => 7),
array('as' => 'as10', 'tb' => 'supplier', 'db' => 'supplier', 'dt' => 8),
array('as' => 'as11', 'tb' => 'product_compound', 'db' => 'price', 'dt' => 9),
array('as' => 'as12', 'tb' => 'brand', 'db' => 'idbrand', 'dt' => 10),
array('as' => 'as13', 'tb' => 'unit', 'db' => 'idunit', 'dt' => 11),
array('as' => 'as14', 'tb' => 'supplier', 'db' => 'idsupplier', 'dt' => 12),
 */

);

// SQL server connection information
$sql_details = array(
    'user' => 'rdsystem',
    'pass' => 'qsbg1234',
    'db'   => 'RDsystem',
    'host' => 'localhost',
);

$whereadd = "";

if (!isset($_SESSION["intLine"])) {

    $whereadd .= "idproduct_compound = 0";

} else {

    for ($i = 0; $i <= (int) $_SESSION["intLine"]; $i++) {

        /*   print_r($arraypush);*/

        if ($_SESSION["strProductID"][$i] != "") {

            if ($i == 0) {
                $whereadd .= "idproduct_compound =" . $_SESSION["strProductID"][$i];
            } else {

                $whereadd .= " OR idproduct_compound =" . $_SESSION["strProductID"][$i];
            }
        }
    }

}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require 'ssp.class.oneTblnumber.php';

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $whereadd)
);
