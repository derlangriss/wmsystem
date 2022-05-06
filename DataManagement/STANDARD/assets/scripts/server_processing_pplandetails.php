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
function dateswap($datadate)
{
    $datearray = explode("-", $datadate);
    if (strlen($datadate) > 3) {
        $meyear   = $datearray[0] + 543;
        $datadate = $meyear;
    }
    return $datadate;
}

// DB table to use
$table = "generate_series(1,12) as mcreated_id 
            LEFT JOIN (SELECT EXTRACT(MONTH FROM expect_date) AS month,expect_cost,idproject_details,idproject_details_expect from project_details_expect LEFT JOIN project_details on project_details.idproject_details = project_details_expect.project_details_idproject_details
            WHERE idproject_details = " . $_POST['idproject_details'] . " ORDER BY month asc) b on b.month = mcreated_id.mcreated_id 
            LEFT JOIN monthlist ON monthlist.idmonth = mcreated_id.mcreated_id 
            ";

// Table's primary key
$primaryKey = 'mcreated_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array(
        'as'        => 'as1',
        'tb'        => 'mcreated_id',
        'db'        => 'mcreated_id',
        'dt'        => 'DT_RowId',
        'formatter' => function ($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return $d;
        },

    ),
    array(
        'as' => 'as2',
        'tb' => 'monthlist',
        'db' => 'idmonth',
        'dt' => 0,
        'formatter' => function ($d, $row) {

            if (isset($_POST['fiscalyear'])) {
                $fiscalyear = $_POST['fiscalyear'];
                $THfiscalyear    = dateswap($fiscalyear);

                $months          = array(
                    1  => array(
                        "shortenm" => 'jan.',
                        "month"    => 'january',
                        "thmonth"  => 'มกราคม',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    2  => array(
                        "shortenm" => 'feb.',
                        "month"    => 'february',
                        "thmonth"  => 'กุมภาพันธ์',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    3  => array(
                        "shortenm" => 'mar.',
                        "month"    => 'march',
                        "thmonth"  => 'มีนาคม',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    4  => array(
                        "shortenm" => 'apr.',
                        "month"    => 'april',
                        "thmonth"  => 'เมษายน',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    5  => array(
                        "shortenm" => 'may.',
                        "month"    => 'may',
                        "thmonth"  => 'พฤษภาคม',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    6  => array(
                        "shortenm" => 'jun.',
                        "month"    => 'june',
                        "thmonth"  => 'มิถุนายน',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    7  => array(
                        "shortenm" => 'jul.',
                        "month"    => 'july',
                        "thmonth"  => 'กรกฎาคม',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    8  => array(
                        "shortenm" => 'aug.',
                        "month"    => 'august',
                        "thmonth"  => 'สิงหาคม',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    9  => array(
                        "shortenm" => 'sep.',
                        "month"    => 'september',
                        "thmonth"  => 'กันยายน',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    10 => array(
                        "shortenm" => 'oct.',
                        "month"    => 'October',
                        "thmonth"  => 'ตุลาคม',
                        "year"     => $fiscalyear - 1,
                        "thyear"   => $THfiscalyear - 1
                    ),
                    11 => array(
                        "shortenm" => 'nov.',
                        "month"    => 'November',
                        "thmonth"  => 'พฤศจิกายน',
                        "year"     => $fiscalyear - 1,
                        "thyear"   => $THfiscalyear - 1
                    ),
                    12 => array(
                        "shortenm" => 'dec.',
                        "month"    => 'December',
                        "thmonth"  => 'ธันวาคม',
                        "year"     => $fiscalyear - 1,
                        "thyear"   => $THfiscalyear - 1
                    ),
                );

                foreach ($months as $num => $name) {
                    if ($d == $num) {
                        $date_th = $name['thmonth'] . "-" . $name['thyear'];
                        return $date_th;
                    }
                }
            }
        }
    ),
    array(
        'as' => 'as3',
        'tb' => 'monthlist',
        'db' => 'idmonth',
        'dt' => 1,
        'formatter' => function ($d, $row) {

            if (isset($_POST['fiscalyear'])) {
                $fiscalyear = $_POST['fiscalyear'];
                $THfiscalyear    = dateswap($fiscalyear);

                $months          = array(
                    1  => array(
                        "shortenm" => 'jan.',
                        "month"    => 'january',
                        "thmonth"  => 'มกราคม',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    2  => array(
                        "shortenm" => 'feb.',
                        "month"    => 'february',
                        "thmonth"  => 'กุมภาพันธ์',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    3  => array(
                        "shortenm" => 'mar.',
                        "month"    => 'march',
                        "thmonth"  => 'มีนาคม',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    4  => array(
                        "shortenm" => 'apr.',
                        "month"    => 'april',
                        "thmonth"  => 'เมษายน',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    5  => array(
                        "shortenm" => 'may.',
                        "month"    => 'may',
                        "thmonth"  => 'พฤษภาคม',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    6  => array(
                        "shortenm" => 'jun.',
                        "month"    => 'june',
                        "thmonth"  => 'มิถุนายน',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    7  => array(
                        "shortenm" => 'jul.',
                        "month"    => 'july',
                        "thmonth"  => 'กรกฎาคม',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    8  => array(
                        "shortenm" => 'aug.',
                        "month"    => 'august',
                        "thmonth"  => 'สิงหาคม',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    9  => array(
                        "shortenm" => 'sep.',
                        "month"    => 'september',
                        "thmonth"  => 'กันยายน',
                        "year"     => $fiscalyear,
                        "thyear"   => $THfiscalyear
                    ),
                    10 => array(
                        "shortenm" => 'oct.',
                        "month"    => 'October',
                        "thmonth"  => 'ตุลาคม',
                        "year"     => $fiscalyear - 1,
                        "thyear"   => $THfiscalyear - 1
                    ),
                    11 => array(
                        "shortenm" => 'nov.',
                        "month"    => 'November',
                        "thmonth"  => 'พฤศจิกายน',
                        "year"     => $fiscalyear - 1,
                        "thyear"   => $THfiscalyear - 1
                    ),
                    12 => array(
                        "shortenm" => 'dec.',
                        "month"    => 'December',
                        "thmonth"  => 'ธันวาคม',
                        "year"     => $fiscalyear - 1,
                        "thyear"   => $THfiscalyear - 1
                    ),
                );

                foreach ($months as $num => $name) {
                    if ($d == $num) {
                        $countbegin = $name['year'] . "-" . $num . "-" . "1";
                        return $countbegin;
                    }
                }
            }
        }
    ),
    array('as' => 'as4', 'tb' => 'b', 'db' => 'expect_cost', 'dt' => 2, 'formatter' => function ($d, $row) {
        if ($d == null) {
            return 0;
        } else {
            return $d;
        }
    }),
    array('as' => 'as5', 'tb' => 'b', 'db' => 'idproject_details_expect', 'dt' => 3),
    array('as' => 'as6', 'tb' => 'b', 'db' => 'idproject_details', 'dt' => 4)

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
