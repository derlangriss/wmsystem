<?php
require 'connectdb_herbarium.php';
$today       = date("Y-m-d");
$beginbudget = date("Y-m-d", mktime(0, 0, 0, 9, 30, date("Y") - 1));

if (isset($_GET['year_id'])) {
    if ($_GET['year_id'] === '0') {
        $fiscalyear = date('Y', mktime(0, 0, 0, 3 + date('m')));
    } else {
        $fiscalyear = $_GET['year_id'];
    }
} else {
    $fiscalyear = date('Y', mktime(0, 0, 0, 3 + date('m'))); /* $_POST['treport_year'];*/
}

function dateswap($datadate)
{
    $datearray = explode("-", $datadate);
    if (strlen($datadate) > 3) {
        $meyear   = $datearray[0] + 543;
        $datadate = $meyear;
    }
    return $datadate;
}

$THfiscalyear    = dateswap($fiscalyear);
$museumstorage   = 2;
$specimens_trash = 1;
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

$resultArray = array();
$arrCol      = array();
foreach ($months as $num => $name) {

    $strSQL03 = "SELECT SUM(package_qty) AS sumspecinyear FROM receivespec_details ";
    $strSQL03 .= "LEFT JOIN receivespec on receivespec.idreceivespec = receivespec_details.receivespec_idreceivespec ";
    $strSQL03 .= "where EXTRACT(YEAR FROM date_receive) = " . $fiscalyear;
    $objQuery03 = pg_query($strSQL03);

    $row03 = pg_fetch_array($objQuery03);
    extract($row03);
    if ($num == 9) {
        $before     = $num - 1;
        $countbegin = $name['year'] . "-" . $before . "-" . "25";
        $countend   = $name['year'] . "-" . $num . "-" . "30";
    } else
    if ($num == 10) {
        $countbegin = $name['year'] . "-" . $num . "-" . "1";
        $countend   = $name['year'] . "-" . $num . "-" . "25";
    } else
    if ($num == 1) {
        $before     = 12;
        $yearbefore = $name['year'] - 1;
        $countbegin = $yearbefore . "-" . $before . "-" . "25";
        $countend   = $name['year'] . "-" . $num . "-" . "25";
    } else {
        $before     = $num - 1;
        $countbegin = $name['year'] . "-" . $before . "-" . "25";
        $countend   = $name['year'] . "-" . $num . "-" . "25";
    }

    $strSQLex01 = "SELECT COALESCE (SUM(package_qty),0) AS sumspecinmonth FROM receivespec_details ";
    $strSQLex01 .= "LEFT JOIN receivespec on receivespec.idreceivespec = receivespec_details.receivespec_idreceivespec ";
    $strSQLex01 .= "WHERE date_receive > '" . $countbegin . "' ";
    $strSQLex01 .= "AND date_receive <= '" . $countend . "' ";
    $objQueryex01 = pg_query($strSQLex01);
    $obResultex01 = pg_fetch_array($objQueryex01);
    extract($obResultex01);

    $strSQLex02 = "SELECT COALESCE (count(idqbgcollection),0) AS sumspecdb FROM qbgcollection ";
    $strSQLex02 .= "LEFT JOIN logbook_details on logbook_details.idlogbook_details = qbgcollection.logbook_details_idlogbook_details ";
    $strSQLex02 .= "LEFT JOIN receivespec_details on receivespec_details.idreceivespec_details = logbook_details.receivespec_details_idreceivespec_details ";
    $strSQLex02 .= "LEFT JOIN receivespec on receivespec.idreceivespec = receivespec_details.receivespec_idreceivespec ";
    $strSQLex02 .= "WHERE date_receive > '" . $countbegin . "' ";
    $strSQLex02 .= "AND date_receive <= '" . $countend . "' ";
    $objQueryex02 = pg_query($strSQLex02);
    $obResultex02 = pg_fetch_array($objQueryex02);
    extract($obResultex02);

    $strSQLex03 = "SELECT COALESCE (count(idqbgcollection),0) AS sumherbarium FROM qbgcollection ";
    $strSQLex03 .= "LEFT JOIN logbook_details on logbook_details.idlogbook_details = qbgcollection.logbook_details_idlogbook_details ";
    $strSQLex03 .= "LEFT JOIN receivespec_details on receivespec_details.idreceivespec_details = logbook_details.receivespec_details_idreceivespec_details ";
    $strSQLex03 .= "LEFT JOIN receivespec on receivespec.idreceivespec = receivespec_details.receivespec_idreceivespec ";
    $strSQLex03 .= "LEFT JOIN speclocate on speclocate.idspeclocate = qbgcollection.speclocate_idspeclocate ";
    $strSQLex03 .= "WHERE date_receive > '" . $countbegin . "' ";
    $strSQLex03 .= "AND date_receive <= '" . $countend . "' ";
    $strSQLex03 .= "AND idspeclocate = 3";
    $objQueryex03 = pg_query($strSQLex03);
    $obResultex03 = pg_fetch_array($objQueryex03);
    extract($obResultex03);

    $arrCol['reportmonth'] = $num;

    $arrCol['reportyear'] = $name['year'];
    $arrCol['shortenm']   = $name['shortenm'];
    $arrCol['fullmonth']  = $name['month'];

    $arrCol['sumspecinmonth'] = $sumspecinmonth;
    $arrCol['sumspecdb']      = $sumspecdb;
    $arrCol['sumherbarium']   = $sumherbarium;

    $arrCol['thfullmonth'] = $name['thmonth'];
    $arrCol['thfullyear']  = $name['thyear'];

    $arrCol['fiscalyear'] = $THfiscalyear;

    array_push($resultArray, $arrCol);
}
pg_close($conn);

echo json_encode($resultArray);
