<?php
require '../dbconnect/connectdb_wms.php';

$resultArray = array();


if (isset($_GET['tverified']) && isset($_GET['tidproject_budget'])) {


    $query = "WITH budgetcost AS (
        SELECT budget FROM project_budget
        WHERE idproject_budget = " . $_GET['tidproject_budget'] . "
     ), sumexpensecost AS (
        select COALESCE(sum(expense_cost),0) includeexpense from project_details
        left join project_budgettype on project_budgettype.idproject_budgettype = project_details.project_budgettype_idproject_budgettype
        left join project_budget on project_budget.idproject_budget = project_budgettype.project_budget_idproject_budget
        where idproject_budget = " . $_GET['tidproject_budget'] . "
     )
     SELECT (SELECT budget FROM budgetcost),(SELECT budget FROM budgetcost)-(SELECT includeexpense FROM sumexpensecost) AS balancebudget";

    $stmt = $PDOconn->prepare($query);
    $stmt->execute();

    $num = $stmt->rowCount();

    if ($num != 0) {

        $intNumField = $stmt->columnCount();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($result);

        if ($budget !== 0) {
            if ($balancebudget == 0) {
                $query2  = "UPDATE project_budget SET ";
                $query2 .= "approval = '" . $_GET['tverified'] . "' ";
                $query2 .= "WHERE idproject_budget = " . $_GET['tidproject_budget'];
                $stmt2 = $PDOconn->prepare($query2);
                $stmt2->execute();
                $arrCol['success'] = 1;
            } else {
                $arrCol['success'] = 0;
            }
        }else{
            $arrCol['success'] = 0;
        }
        
        array_push($resultArray, $arrCol);
    } else {
        $arrCol['msg'] = 'no';
        array_push($resultArray, $arrCol);
    }
    pg_close($conn);

    echo json_encode($resultArray);
}
exit;
