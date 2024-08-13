<?php
require "postgresql2jsonPDO.class.php";
require 'connectdb_wms.php';
$today       = date("Y-m-d");
$beginbudget = date("Y-m-d", mktime(0, 0, 0, 9, 30, date("Y") - 1));
if (isset($_GET['fiscalyear'])) {
    if ($_GET['fiscalyear'] === '0') {
        $fiscalyear = date('Y', mktime(0, 0, 0, 3 + date('m')));

    } else {
        $fiscalyear = $_GET['fiscalyear'];
    }

} else {
    $fiscalyear = date('Y', mktime(0, 0, 0, 3 + date('m'))); 
}

function dateswap($datadate) {
    $datearray = explode("-", $datadate);
    if (strlen($datadate) > 3) {
        $meyear   = $datearray[0] + 543;
        $datadate = $meyear;
    }
    return $datadate;
}
$THfiscalyear = dateswap($fiscalyear);

$resultArray   = array();
$arrCol        = array();
$arrCol2        = array();
$resultArray02 = array();

$months = array
    (
    1  => array("month" => 'january',
        "thmonth"           => 'มกราคม',
        "year"              => $fiscalyear,
        "thyear"            => $THfiscalyear),
    2  => array("month" => 'february',
        "thmonth"           => 'กุมภาพันธ์',
        "year"              => $fiscalyear,
        "thyear"            => $THfiscalyear),
    3  => array("month" => 'march',
        "thmonth"           => 'มีนาคม',
        "year"              => $fiscalyear,
        "thyear"            => $THfiscalyear),
    4  => array("month" => 'april',
        "thmonth"           => 'เมษายน',
        "year"              => $fiscalyear,
        "thyear"            => $THfiscalyear),
    5  => array("month" => 'may',
        "thmonth"           => 'พฤษภาคม',
        "year"              => $fiscalyear,
        "thyear"            => $THfiscalyear),
    6  => array("month" => 'june',
        "thmonth"           => 'มิถุนายน',
        "year"              => $fiscalyear,
        "thyear"            => $THfiscalyear),
    7  => array("month" => 'july',
        "thmonth"           => 'กรกฎาคม',
        "year"              => $fiscalyear,
        "thyear"            => $THfiscalyear),
    8  => array("month" => 'august',
        "thmonth"           => 'สิงหาคม',
        "year"              => $fiscalyear,
        "thyear"            => $THfiscalyear),
    9  => array("month" => 'september',
        "thmonth"           => 'กันยายน',
        "year"              => $fiscalyear,
        "thyear"            => $THfiscalyear),
    10 => array("month" => 'October',
        "thmonth"           => 'ตุลาคม',
        "year"              => $fiscalyear - 1,
        "thyear"            => $THfiscalyear - 1),
    11 => array("month" => 'November',
        "thmonth"           => 'พฤศจิกายน',
        "year"              => $fiscalyear - 1,
        "thyear"            => $THfiscalyear - 1),
    12 => array("month" => 'December',
        "thmonth"           => 'ธันวาคม',
        "year"              => $fiscalyear - 1,
        "thyear"            => $THfiscalyear - 1));

foreach ($months as $num => $name) {

    $arrCol2['reportmonth'] = $name['year'] . "-" . $num;

    $arrCol2['onlymonth']       = $name['month'];
    $arrCol2['onlymonthnumber'] = $num;
    $arrCol2['onlyyear']        = $name['year'];
    $arrCol2['thyear']          = $name['thyear'];
    $arrCol2['thmonth']         = $name['thmonth'];

    array_push($resultArray02, $arrCol2);
}

$beginbudget     = date("Y-m-d", mktime(0, 0, 0, $resultArray02[9]['onlymonthnumber'], 1, $resultArray02[9]['onlyyear']));
$endbudget       = date("Y-m-d", mktime(0, 0, 0, $resultArray02[8]['onlymonthnumber'], 30, $resultArray02[8]['onlyyear']));
$showbeginbudget = date("F Y", mktime(0, 0, 0, $resultArray02[9]['onlymonthnumber'], 1, $resultArray02[9]['onlyyear']));
$showendbudget   = date("F Y", mktime(0, 0, 0, $resultArray02[8]['onlymonthnumber'], 30, $resultArray02[8]['onlyyear']));

$THshowbeginbudget = $resultArray02[9]['thmonth'] . " " . $resultArray02[9]['thyear'];
$THshowendbudget   = $resultArray02[8]['thmonth'] . " " . $resultArray02[8]['thyear'];



$arrCol['THshowbeginbudget'] = $THshowbeginbudget;
$arrCol['THshowendbudget']   = $THshowendbudget;
$arrCol['ENshowbeginbudget'] = $showbeginbudget;
$arrCol['ENshowendbudget']   = $showendbudget;
$arrCol['ENshowendbudget']   = $showendbudget;
$arrCol['THfiscalyear']   = $THfiscalyear;


array_push($resultArray, $arrCol);

pg_close($conn);

echo json_encode($resultArray);
