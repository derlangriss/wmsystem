<?php

require '../dbconnect/connectdb_wms.php';

$resultArray = array();

if (isset($_GET['idproject_budget'])) {

    $query = "WITH budgetcost AS (
        SELECT budget FROM project_budget
        WHERE idproject_budget = " . $_GET['idproject_budget'] . "
     ), sumexpensecost AS (
        select COALESCE(sum(expense_cost),0) includeexpense from project_details
        left join project_budgettype on project_budgettype.idproject_budgettype = project_details.project_budgettype_idproject_budgettype
        left join project_budget on project_budget.idproject_budget = project_budgettype.project_budget_idproject_budget
        where idproject_budget = " . $_GET['idproject_budget'] . "
     )
        SELECT (SELECT includeexpense FROM sumexpensecost),(SELECT budget FROM budgetcost)-(SELECT includeexpense FROM sumexpensecost) AS balancebudget";

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
