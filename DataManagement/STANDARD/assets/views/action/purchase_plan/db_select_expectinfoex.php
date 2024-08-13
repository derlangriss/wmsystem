<?php

require '../dbconnect/connectdb_wms.php';

$resultArray = array();

if (isset($_GET['idproject_details'])) {

    $query = "WITH expensecost AS (
        SELECT expense_cost,expense_type
        FROM project_details
        LEFT JOIN expense_type on expense_type.idexpense_type = project_details.expense_type_idexpense_type
        WHERE idproject_details = " . $_GET['idproject_details'] . "
     ), sumexcost AS (
        SELECT sum(expect_cost) as includeexpect FROM project_details_expect
        LEFT JOIN project_details on project_details.idproject_details = project_details_expect.project_details_idproject_details
        WHERE idproject_details  = " . $_GET['idproject_details'] . "
     )
        SELECT (select expense_type from expensecost),COALESCE((select expense_cost from expensecost),0) as expense_cost,(select COALESCE(includeexpect,0) as includeexpect from sumexcost) ,COALESCE((select expense_cost from expensecost)-(select includeexpect from sumexcost),0) AS balancecost";


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
} else {
}

exit;
