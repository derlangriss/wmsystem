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
$table = "feature
";

// Table's primary key
$primaryKey = 'idfeature';

// indexes
$columns = array(
    array(
        'as' => 'as1',
        'tb' => 'feature',
        'db' => 'idfeature',
        'dt' => 'DT_RowId',
        /**
        'formatter' => function ($d, $row) {
        // Technically a DOM id cannot start with an integer, so we prefix
        // a string. This can also be useful if you have multiple tables
        // to ensure that the id is unique with a different prefix
        return 'row_' . $d;
        },**/

    ),
    array('as' => 'as2', 'tb' => 'feature', 'db' => 'idfeature', 'dt' => 0),
    array('as' => 'as3', 'tb' => 'feature', 'db' => 'feature', 'dt' => 1),
    array('as' => 'as4', 'tb' => 'feature', 'db' => 'feature_desc', 'dt' => 2),
    array('as' => 'as5', 'tb' => 'feature', 'db' => 'feature_note', 'dt' => 3)

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
