<?php

require '../connectdb_wms.php';
$resultArray = array();
$arrCol      = array();

if (isset($_GET['tidproject_budget'])) {

    $query = "WITH project_de AS (
        SELECT * FROM project_budget 
        LEFT JOIN project on project.idproject = project_budget.project_idproject
        WHERE idproject_budget = ".$_GET['tidproject_budget']."
     ), project_ba AS (
        SELECT COALESCE(sum(expense_cost),0) as probalance FROM project_details
        left join project_budgettype on project_budgettype.idproject_budgettype = project_details.project_budgettype_idproject_budgettype
        left join project_budget on project_budget.idproject_budget = project_budgettype.project_budget_idproject_budget
        WHERE idproject_budget  = ".$_GET['tidproject_budget']." AND pdetails_trash = false
     )
        SELECT (select budget from project_de)-(select probalance from project_ba) AS projectbalance";

    /*$query = "SELECT * FROM project_budget 
    left join project on project.idproject = project_budget.project_idproject
    WHERE idproject_budget =" . $_GET['tidproject_budget'];*/

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
}else{

  
}