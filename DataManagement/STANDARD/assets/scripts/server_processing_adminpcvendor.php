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
$table = "vendor
";

// Table's primary key
$primaryKey = 'idvendor';

// indexes
$columns = array(
    array(
        'as' => 'as1',
        'tb' => 'vendor',
        'db' => 'idvendor',
        'dt' => 'DT_RowId',
        /**
        'formatter' => function ($d, $row) {
        // Technically a DOM id cannot start with an integer, so we prefix
        // a string. This can also be useful if you have multiple tables
        // to ensure that the id is unique with a different prefix
        return 'row_' . $d;
        },**/

    ),
    array('as' => 'as2', 'tb' => 'vendor', 'db' => 'idvendor', 'dt' => 0),
    array('as' => 'as3', 'tb' => 'vendor', 'db' => 'vendor', 'dt' => 1),
    array('as' => 'as4', 'tb' => 'vendor', 'db' => 'vendor_tel', 'dt' => 2),
    array('as' => 'as5', 'tb' => 'vendor', 'db' => 'vendor_email', 'dt' => 3),
    array('as' => 'as6', 'tb' => 'vendor', 'db' => 'vendor_contact', 'dt' => 4),
    array('as' => 'as7', 'tb' => 'vendor', 'db' => 'vendor_note', 'dt' => 5)

);

// SQL server connection information
$sql_details = array(
    'user' => 'rdsystem',
    'pass' => 'qsbg1234',
    'db'   => 'wmsystem',
    'host' => 'localhost',
);


$whereadd = "";


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require 'ssp.class.oneTblnumber.php';

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $whereadd)
);
