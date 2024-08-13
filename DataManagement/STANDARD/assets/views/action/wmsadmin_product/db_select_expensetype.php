<?php
require '../dbconnect/connectdb_wms.php';
$resultArray = array();
$arrCol      = array();

$query = "SELECT * FROM expense_type 
    left join budget_type on budget_type.idbudget_type = expense_type.budget_type_idbudget_type
    WHERE idbudget_type = 3 ";

$stmt = $PDOconn->prepare($query);
$stmt->execute();

$num = $stmt->rowCount();
if ($num != 0) {

    $intNumField = $stmt->columnCount();
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

        for ($i = 0; $i < $intNumField; $i++) {
            $col     = $stmt->getColumnMeta($i);
            $columns = $col['name'];

            $arrCol[$columns] = $result[$columns];
        }
        array_push($resultArray, $arrCol);
    }
} else {
    $arrCol['success'] = 'no';
    array_push($resultArray, $arrCol);
}
pg_close($conn);

echo json_encode($resultArray);
