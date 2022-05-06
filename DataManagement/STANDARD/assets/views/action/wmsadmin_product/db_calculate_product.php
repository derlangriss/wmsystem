<?php
require '../dbconnect/connectdb_wms.php';

$resultArray = array();

$query = "WITH countconfirm as (";
$query .="SELECT count(*) as countconfirm FROM product ";
$query .="where p_confirm = true ";
$query .="), countwaiting as ( ";
$query .="SELECT count(*) as countwaiting FROM product ";
$query .="where p_confirm = false ";
$query .=") SELECT (SELECT countconfirm from countconfirm) , (SELECT countwaiting from countwaiting)";

$stmt = $PDOconn->prepare($query);
$stmt->execute();

$num = $stmt->rowCount();
if ($num == 0) {


} else {
    $intNumField = $stmt->columnCount();
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

        for ($i = 0; $i < $intNumField; $i++) {
            $col     = $stmt->getColumnMeta($i);
            $columns = $col['name'];

            $arrCol[$columns] = $result[$columns];
        }
        $arrCol['success'] = 1;

        array_push($resultArray, $arrCol);

    }

}

echo json_encode($resultArray);