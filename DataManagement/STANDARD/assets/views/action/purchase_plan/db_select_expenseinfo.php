<?php

require '../connectdb_wms.php';
$resultArray = array();
$arrCol      = array();
$resultArrayEx = array();
$arrColEx      = array();

if (isset($_GET['tidproject_details'])) {

    $query = "WITH expensecost AS (
                SELECT expense_cost
                FROM project_details
	            WHERE idproject_details = " . $_GET['tidproject_details'] . "
             ), sumexcost AS (
                SELECT COALESCE(sum(expect_cost),0) as includeexpect FROM project_details_expect
		        LEFT JOIN project_details on project_details.idproject_details = project_details_expect.project_details_idproject_details
                WHERE idproject_details  = " . $_GET['tidproject_details'] . "
             ), budgetcost AS (
                SELECT budget
                FROM project_details
                LEFT JOIN project_budgettype on project_budgettype.idproject_budgettype = project_details.project_budgettype_idproject_budgettype
                LEFT JOIN project_budget on project_budget.idproject_budget = project_budgettype.project_budget_idproject_budget
                WHERE idproject_details = " . $_GET['tidproject_details'] . "
             )
                SELECT (select budget from budgetcost)-(select includeexpect from sumexcost) AS balancecost";

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
}
