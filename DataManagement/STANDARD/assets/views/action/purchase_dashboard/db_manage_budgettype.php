<?php

require '../dbconnect/connectdb_wms.php';

$resultArray = array();

if (isset($_GET['tidproject_budget'])) {


    for ($b = 1; $b <= 3; $b++) {
        
        $query = "SELECT * FROM project_budgettype 
        LEFT JOIN project_budget on project_budget.idproject_budget = project_budgettype.project_budget_idproject_budget 
        LEFT JOIN budget_type on budget_type.idbudget_type = project_budgettype.budget_type_idbudget_type
        WHERE idproject_budget =" . $_GET['tidproject_budget'] . " AND idbudget_type=" . $b;

        $stmt = $PDOconn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();
       
        if ($num == 0) {

            $query2  = "INSERT INTO project_budgettype (project_budget_idproject_budget,  budget_type_idbudget_type) VALUES (?, ?)";
            $params2 = array($_GET['tidproject_budget'], $b);
            $stmt2   = $PDOconn->prepare($query2);
            $stmt2->execute($params2);

        }
    }

    $arrCol['success'] = 'yes';
    array_push($resultArray, $arrCol);
    pg_close($conn);

    echo json_encode($resultArray);
}
exit;
