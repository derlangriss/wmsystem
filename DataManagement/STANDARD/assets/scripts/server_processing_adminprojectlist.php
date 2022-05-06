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
$table = "project_budget 
          left join project on project.idproject = project_budget.project_idproject
          left join fiscalyear on fiscalyear.idfiscalyear = project_budget.fiscalyear_idfiscalyear
          left join section on section.idsection = project.section_idsection
          left join department on department.iddepartment = section.department_iddepartment
          left join login_user on login_user.idlogin_user = project.login_user_idlogin_user
";

// Table's primary key
$primaryKey = 'idproject';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array(
        'as'        => 'as1',
        'tb'        => 'project',
        'db'        => 'idproject',
        'dt'        => 'DT_RowId',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_' . $d;
        },

    ),
    array('as' => 'as2', 'tb' => 'department', 'db' => 'department', 'dt' => 0),
    array('as' => 'as3', 'tb' => 'section', 'db' => 'section', 'dt' => 1),
    array('as' => 'as4', 'tb' => 'project', 'db' => 'project', 'dt' => 2),
    array('as' => 'as5', 'tb' => 'project_budget', 'db' => 'budget', 'dt' => 3),
    array('as' => 'as6', 'tb' => 'project_budget', 'db' => 'progress', 'dt' => 4),
    array('as' => 'as7', 'tb' => 'project', 'db' => 'idproject', 'dt' => 5),
    array('as' => 'as8', 'tb' => 'project_budget', 'db' => 'approval', 'dt' => 6)


);

// SQL server connection information
$sql_details = array(
    'user' => 'rdsystem',
    'pass' => 'qsbg1234',
    'db'   => 'wmsystem',
    'host' => 'localhost',
);

$whereadd = "fiscalyear =".$_POST['fiscalyear'];



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require 'ssp.class.oneTblnumber.php';

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $whereadd)
);
