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
$table = "(select idproduct,product,p_confirm,array_to_string(array_agg(distinct size),', ') AS sizelist,
array_to_string(array_agg(distinct unit),', ') AS unitlist,
array_to_string(array_agg(distinct feature),', ') AS featurelist,
count(DISTINCT idproduct_items) AS countlist from product
left join psize on psize.product_idproduct = product.idproduct
left join size on size.idsize = psize.size_idsize
left join punit on punit.product_idproduct = product.idproduct
left join unit on unit.idunit = punit.unit_idunit
left join pfeature on pfeature.product_idproduct = product.idproduct
left join feature on feature.idfeature = pfeature.feature_idfeature
left join product_items on product_items.product_idproduct = product.idproduct
group by idproduct) b1
";

// Table's primary key
$primaryKey = 'idproduct';

// indexes
$columns = array(
    array(
        'as' => 'as1',
        'tb' => 'b1',
        'db' => 'idproduct',
        'dt' => 'DT_RowId',
        /**
        'formatter' => function ($d, $row) {
        // Technically a DOM id cannot start with an integer, so we prefix
        // a string. This can also be useful if you have multiple tables
        // to ensure that the id is unique with a different prefix
        return 'row_' . $d;
        },**/

    ),
    array('as' => 'as2', 'tb' => 'b1', 'db' => 'idproduct', 'dt' => 0),
    array('as' => 'as3', 'tb' => 'b1', 'db' => 'product', 'dt' => 1),
    array('as' => 'as4', 'tb' => 'b1', 'db' => 'sizelist', 'dt' => 2),
    array('as' => 'as5', 'tb' => 'b1', 'db' => 'featurelist', 'dt' => 3),
    array('as' => 'as6', 'tb' => 'b1', 'db' => 'unitlist', 'dt' => 4),
    array('as' => 'as7', 'tb' => 'b1', 'db' => 'countlist', 'dt' => 5)

);

// SQL server connection information
$sql_details = array(
    'user' => 'rdsystem',
    'pass' => 'qsbg1234',
    'db'   => 'wmsystem',
    'host' => 'localhost',
);

if (isset($_POST) && count($_POST)) {
    $tconfirm = $_POST['tconfirm'];

    $whereadd = "p_confirm = " . $tconfirm;

}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require 'ssp.class.oneTblnumber.php';

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $whereadd)
);
