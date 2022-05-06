<?php

require '../connectdb_wms.php';
$resultArray = array();
$arrCol      = array();
$resultArrayEx = array();
$arrColEx      = array();

if (isset($_GET['tproject_details'])) {

    $queryEx = "SELECT expense_type,expense_cost,budget_type from project_details
                LEFT JOIN project_budgettype on project_budgettype.idproject_budgettype = project_details.project_budgettype_idproject_budgettype
                LEFT JOIN expense_type on expense_type.idexpense_type  = project_details.expense_type_idexpense_type 
                LEFT JOIN budget_type on budget_type.idbudget_type = project_budgettype.budget_type_idbudget_type
                WHERE idproject_details =" . $_GET['tproject_details'];

    $stmtEx = $PDOconn->prepare($queryEx);
    $stmtEx->execute();

    $numEx = $stmtEx->rowCount();
    if ($numEx != 0) {

        $intNumFieldEx = $stmtEx->columnCount();
        while ($resultEx = $stmtEx->fetch(PDO::FETCH_ASSOC)) {

            for ($i = 0; $i < $intNumFieldEx; $i++) {
                $colEx     = $stmtEx->getColumnMeta($i);
                $columnsEx = $colEx['name'];

                $arrColEx[$columnsEx] = $resultEx[$columnsEx];
            }

            $query =   "SELECT m,month,expect_cost,idproject_details,expense_cost,idproject_details_expect,month_th from generate_series(1,12) m 
                        LEFT JOIN (SELECT EXTRACT(MONTH FROM expect_date) AS month,expect_cost,project_details_idproject_details,idproject_details_expect from project_details_expect LEFT JOIN project_details on project_details.idproject_details = project_details_expect.project_details_idproject_details
                        WHERE idproject_details = " . $_GET['tproject_details'] . ") b on b.month = m.m
                        LEFT JOIN monthlist on monthlist.idmonth = m.m 
                        LEFT JOIN project_details on project_details.idproject_details = b.project_details_idproject_details 
                        LEFT JOIN project_budgettype on project_budgettype.idproject_budgettype = project_details.project_budgettype_idproject_budgettype
                        LEFT JOIN expense_type on expense_type.idexpense_type  = project_details.expense_type_idexpense_type 
                        ORDER BY m asc";

            $stmt = $PDOconn->prepare($query);
            $stmt->execute();

            $num = $stmt->rowCount();

            if ($num != 0) {

                $intNumField = $stmt->columnCount();
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    for ($j = 0; $j < $intNumField; $j++) {
                        $col     = $stmt->getColumnMeta($j);
                        $columns = $col['name'];

                        $arrCol[$columns] = $result[$columns];
                    }

                    array_push($resultArray, $arrCol);
                }
                $arrColEx['expense_month'] = $resultArray;
            } else {
                $arrCol['success'] = 'no';
                array_push($resultArray, $arrCol);
            }
            array_push($resultArrayEx, $arrColEx);
        }
    } else {
        $arrColEx['success'] = 'ssssssssssssssssss';
        array_push($resultArrayEx, $arrColEx);
    }

    pg_close($conn);

    echo json_encode($resultArrayEx);
} else {
}
