<?php
date_default_timezone_set('America/New_York');
date("H:i:s");
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
$table = "product_warehouse
          left join (select product_warehouse_idproduct_warehouse ,sum(disburse_qty) as suminit from product_disburse 
          where disburse_type_iddisburse_type = 1
          group by product_warehouse_idproduct_warehouse) a on a.product_warehouse_idproduct_warehouse =  product_warehouse.idproduct_warehouse
          left join (select product_warehouse_idproduct_warehouse ,sum(disburse_qty) as sumpurchase from product_disburse 
          where disburse_type_iddisburse_type = 2
          group by product_warehouse_idproduct_warehouse) b on b.product_warehouse_idproduct_warehouse =  product_warehouse.idproduct_warehouse
          left join (select product_warehouse_idproduct_warehouse ,sum(disburse_qty) as sumborrow from product_disburse 
          where disburse_type_iddisburse_type = 3
          group by product_warehouse_idproduct_warehouse) c on c.product_warehouse_idproduct_warehouse =  product_warehouse.idproduct_warehouse
          left join (select product_warehouse_idproduct_warehouse ,sum(disburse_qty) as sumprorate from product_disburse 
          where disburse_type_iddisburse_type = 4
          group by product_warehouse_idproduct_warehouse) d on d.product_warehouse_idproduct_warehouse =  product_warehouse.idproduct_warehouse
          left join product_document on product_document.idproduct_document = product_disburse.product_document_idproduct_document
          left join product_compound on product_compound.idproduct_compound = product_warehouse.product_compound_idproduct_compound
          left join project_list on project_list.idproject_list = product_warehouse.project_list_idproject
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
$primaryKey = 'idproduct_warehouse';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array(
        'as'        => 'as1',
        'tb'        => 'product_warehouse',
        'db'        => 'idproduct_warehouse',
        'dt'        => 'DT_RowId',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_' . $d;
        },

    ),
    array('as' => 'as2', 'tb' => 'catagory', 'db' => 'catagory', 'dt' => 0),
    array('as' => 'as3', 'tb' => 'supplies_type', 'db' => 'supplies_type', 'dt' => 1),
    array('as' => 'as4', 'tb' => 'product', 'db' => 'product', 'dt' => 2),
    array('as' => 'as5', 'tb' => 'feature', 'db' => 'feature', 'dt' =>3),
    array('as' => 'as6', 'tb' => 'brand', 'db' => 'brand', 'dt' => 4),
    array('as' => 'as7', 'tb' => 'unit', 'db' => 'unit', 'dt' => 5),
    array(
        'as'        => 'as8',
        'tb'        => 'feature',
        'db'        => 'feature',
        'dt'        => 6,
        'formatter' => function ($d, $row) {

            if ($d == "NA") {
                return false;
            } else {
                return $d;
            }

        },

    ),
    array('as' => 'as9', 'tb' => 'a', 'db' => 'suminit', 'dt' => 7),
    array('as' => 'as10', 'tb' => 'b', 'db' => 'sumpurchase', 'dt' => 8),
    array('as' => 'as11', 'tb' => 'c', 'db' => 'sumborrow', 'dt' => 9),
    array('as' => 'as12', 'tb' => 'd', 'db' => 'sumprorate', 'dt' => 10),
    array('as' => 'as13', 'tb' => 'product_warehouse', 'db' => 'resqty', 'dt' => 11),
    array('as' => 'as14', 'tb' => 'product_document', 'db' => 'd_unit_price', 'dt' => 12)

);

// SQL server connection information
$sql_details = array(
    'user' => 'rdsystem',
    'pass' => 'qsbg1234',
    'db'   => 'RDsystem',
    'host' => 'localhost',
);

$whereadd = "login_user_iduser = ".$_POST['iduser'];



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require 'ssp.class.oneTblnumber.php';

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $whereadd)
);
