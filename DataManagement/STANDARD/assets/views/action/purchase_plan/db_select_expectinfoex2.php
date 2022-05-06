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
     ), budgetcost AS (
        SELECT budget,idproject_budgettype
        FROM project_details
        LEFT JOIN project_budgettype on project_budgettype.idproject_budgettype = project_details.project_budgettype_idproject_budgettype
		LEFT JOIN project_budget on project_budget.idproject_budget = project_budgettype.project_budget_idproject_budget
        WHERE idproject_details = " . $_GET['idproject_details'] . "
     ), sumwholeexpense AS (
        SELECT SUM(expense_cost) as includeexepense FROM project_details
        left join project_budgettype on project_budgettype.idproject_budgettype = project_details.project_budgettype_idproject_budgettype
        left join expense_type on expense_type.idexpense_type = project_details.expense_type_idexpense_type
        where idproject_budgettype = (SELECT idproject_budgettype FROM budgetcost)
     )
        SELECT (select budget from budgetcost),(select expense_type from expensecost),COALESCE((select expense_cost from expensecost),0) as expense_cost,(select COALESCE(includeexpect,0) as includeexpect from sumexcost) ,COALESCE((select budget from budgetcost)-(select includeexepense from sumwholeexpense),0) AS balancecost";


    $stmt = $PDOconn->prepare($query);
    $stmt->execute();

    $num = $stmt->rowCount();
    if ($num != 0) {

        $intNumField = $stmt->columnCount();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            


            for ($i = 0; $i < $intNumField; $i++) {
                /* $col     = $stmt->getColumnMeta($i);
                $columns = $col['name'];

                if ($arrCol[$columns] == 'expense_cost') {
                    $stmt3 = $PDOconn->prepare($query3);
                    $stmt3->execute();
                    $result = $stmt3->fetch(PDO::FETCH_ASSOC);
                    extract($result);
                    if ($result[$columns] != $expense_cost) {
                        $stmt2 = $PDOconn->prepare($query2);
                        $stmt2->execute();
                    }
                }
                $arrCol[$columns] = $result[$columns];*/
                $col     = $stmt->getColumnMeta($i);
                $columns = $col['name'];

                if ($columns == 'includeexpect') {
                    $query3  = "SELECT expense_cost FROM project_details ";
                    $query3 .= "WHERE idproject_details = " . $_GET['idproject_details'];
                    $stmt3 = $PDOconn->prepare($query3);
                    $stmt3->execute();
                    $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
                    extract($result3);
                  
                    if ($result[$columns] != $expense_cost) {
                        $query2  = "UPDATE project_details ";
                        $query2 .= "SET expense_cost = ".$result[$columns];
                        $query2 .= "WHERE idproject_details = " . $_GET['idproject_details'];
                        $stmt2 = $PDOconn->prepare($query2);
                        $stmt2->execute();
                    }
                }
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
