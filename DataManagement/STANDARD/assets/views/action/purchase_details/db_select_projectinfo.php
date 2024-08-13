<?php
if (!ini_get('date.timezone')) {
    date_default_timezone_set('GMT');
}
require '../dbconnect/connectdb_wms.php';

$resultArray = array();
$resultArrayLayer2 = array();
$resultArrayLayer3 = array();
$resultArrayLayer4 = array();
$resultArrayLayer5 = array();




if (isset($_GET['idproject_budget'])) {

    $query = "SELECT * FROM project_budget 
    left join project on project.idproject = project_budget.project_idproject
    left join fiscalyear on fiscalyear.idfiscalyear = project_budget.fiscalyear_idfiscalyear
    left join section on section.idsection = project.section_idsection
    WHERE idproject_budget  = '" . $_GET["idproject_budget"] . "'";

    $stmt = $PDOconn->prepare($query);
    $stmt->execute();

    $num = $stmt->rowCount();
    $intNumField = $stmt->columnCount();

    if ($num != 0) {

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            for ($i = 0; $i < $intNumField; $i++) {
                $col     = $stmt->getColumnMeta($i);
                $columns = $col['name'];

                $arrCol[$columns] = $result[$columns];
            }

            $queryLayer2 = "SELECT idproject_budgettype,budget_cost,budget_type,idbudget_type FROM project_budgettype
                            left join project_budget on project_budget.idproject_budget = project_budgettype.project_budget_idproject_budget
                            left join budget_type on budget_type.idbudget_type = project_budgettype.budget_type_idbudget_type
                            left join project on project.idproject = project_budget.project_idproject
                            WHERE idproject_budget  = '" . $_GET["idproject_budget"] . "'";

            $stmtLayer2 = $PDOconn->prepare($queryLayer2);

            $stmtLayer2->execute();
            $numLayer2 = $stmtLayer2->rowCount();
            if ($numLayer2 != 0) {

                for ($b = 1; $b <= 3; $b++) {

                    $queryLayer2 = "SELECT idproject_budgettype,budget_cost,budget_type,idbudget_type FROM project_budgettype
                            left join project_budget on project_budget.idproject_budget = project_budgettype.project_budget_idproject_budget
                            left join budget_type on budget_type.idbudget_type = project_budgettype.budget_type_idbudget_type
                            left join project on project.idproject = project_budget.project_idproject
                            WHERE idproject_budget  = '" . $_GET["idproject_budget"] . "' AND idbudget_type =" . $b;

                    $stmtLayer2 = $PDOconn->prepare($queryLayer2);

                    $stmtLayer2->execute();
                    $numLayer2 = $stmtLayer2->rowCount();

                    if ($numLayer2 == 0) {
                        $query2  = "INSERT INTO project_budgettype (project_budget_idproject_budget,  budget_type_idbudget_type) VALUES (?, ?) RETURNING idproject_budgettype";
                        $params2 = array($_GET['idproject_budget'], $b);
                        $stmt2   = $PDOconn->prepare($query2);
                        $stmt2->execute($params2);

                        $resultLayer2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                        extract($resultLayer2);

                        if ($b == 1) {
                            $budget_type = "หมวดค่าตอบแทน";
                        }
                        if ($b == 2) {
                            $budget_type = "หมวดค่าใช้สอย";
                        }
                        if ($b == 3) {
                            $budget_type = "หมวดค่าวัสดุ";
                        }

                        $arr = array(
                            'expenselist' => [],
                            'idproject_budgettype' => $idproject_budgettype,
                            'budget_type' => $budget_type,
                            'budget_cost' => 0,
                            "idbudget_type" => $b
                        );

                        array_push($resultArrayLayer2, $arr);
                    } else {
                        
                        $intNumFieldLayer2 = $stmtLayer2->columnCount();
                        while ($resultLayer2 = $stmtLayer2->fetch(PDO::FETCH_ASSOC)) {
                            extract($resultLayer2);
                            for ($j = 0; $j < $intNumFieldLayer2; $j++) {

                                $colLayer2    = $stmtLayer2->getColumnMeta($j);
                                $columnsLayer2 = $colLayer2['name'];

                                if ($columnsLayer2 == 'idbudget_type') {

                                    $queryLayer3 = "SELECT idproject_details,idbudget_type,expense_type,expense_cost  FROM project_details
                                left join project_budgettype on project_budgettype.idproject_budgettype = project_details.project_budgettype_idproject_budgettype
                                left join budget_type on budget_type.idbudget_type = project_budgettype.budget_type_idbudget_type
                                left join project_budget on project_budget.idproject_budget = project_budgettype.project_budget_idproject_budget
                                left join expense_type on expense_type.idexpense_type = project_details.expense_type_idexpense_type
                                left join project on project.idproject = project_budget.project_idproject
                                WHERE idproject_budget  = '" . $_GET["idproject_budget"] . "' AND idbudget_type = '" . $resultLayer2[$columnsLayer2] . "' AND pdetails_trash = 'false' ";
                                    $stmtLayer3 = $PDOconn->prepare($queryLayer3);
                                    $stmtLayer3->execute();
                                    $arrColLayer3 = array();
                                    $intNumFieldLayer3 = $stmtLayer3->columnCount();


                                    if ($resultLayer2[$columnsLayer2] == 1) {

                                        while ($resultLayer3 = $stmtLayer3->fetch(PDO::FETCH_ASSOC)) {

                                            for ($k = 0; $k < $intNumFieldLayer3; $k++) {
                                                $colLayer3    = $stmtLayer3->getColumnMeta($k);
                                                $columnsLayer3 = $colLayer3['name'];

                                                $arrColLayer3[$columnsLayer3] = $resultLayer3[$columnsLayer3];
                                            }
                                            array_push($resultArrayLayer3, $arrColLayer3);
                                        }
                                        $arrColLayer2['expenselist'] = $resultArrayLayer3;
                                    }
                                    if ($resultLayer2[$columnsLayer2] == 2) {

                                        while ($resultLayer3 = $stmtLayer3->fetch(PDO::FETCH_ASSOC)) {

                                            for ($k = 0; $k < $intNumFieldLayer3; $k++) {
                                                $colLayer3    = $stmtLayer3->getColumnMeta($k);
                                                $columnsLayer3 = $colLayer3['name'];

                                                $arrColLayer3[$columnsLayer3] = $resultLayer3[$columnsLayer3];
                                            }
                                            array_push($resultArrayLayer4, $arrColLayer3);
                                        }
                                        $arrColLayer2['expenselist'] = $resultArrayLayer4;
                                    }
                                    if ($resultLayer2[$columnsLayer2] == 3) {

                                        while ($resultLayer3 = $stmtLayer3->fetch(PDO::FETCH_ASSOC)) {

                                            for ($k = 0; $k < $intNumFieldLayer3; $k++) {
                                                $colLayer3    = $stmtLayer3->getColumnMeta($k);
                                                $columnsLayer3 = $colLayer3['name'];

                                                $arrColLayer3[$columnsLayer3] = $resultLayer3[$columnsLayer3];
                                            }
                                            array_push($resultArrayLayer5, $arrColLayer3);
                                        }
                                        $arrColLayer2['expenselist'] = $resultArrayLayer5;
                                    }
                                }

                                $arrColLayer2[$columnsLayer2] = $resultLayer2[$columnsLayer2];
                            }
                            array_push($resultArrayLayer2, $arrColLayer2);
                        }
                    }
                }






                $arrCol['budgetlist'] = $resultArrayLayer2;
            } else {

                for ($b = 1; $b <= 3; $b++) {

                    $query2  = "INSERT INTO project_budgettype (project_budget_idproject_budget,  budget_type_idbudget_type) VALUES (?, ?) RETURNING idproject_budgettype";
                    $params2 = array($_GET['idproject_budget'], $b);
                    $stmt2   = $PDOconn->prepare($query2);
                    $stmt2->execute($params2);

                    $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                    extract($result2);

                    if ($b == 1) {
                        $budget_type = "หมวดค่าตอบแทน";
                    }
                    if ($b == 2) {
                        $budget_type = "หมวดค่าใช้สอย";
                    }
                    if ($b == 3) {
                        $budget_type = "หมวดค่าวัสดุ";
                    }

                    $arr = array(
                        'expenselist' => [],
                        'idproject_budgettype' => $idproject_budgettype,
                        'budget_type' => $budget_type,
                        'budget_cost' => 0,
                        "idbudget_type" => $b
                    );

                    array_push($resultArrayLayer2, $arr);
                }
                $arrCol['budgetlist'] = $resultArrayLayer2;
            }

            array_push($resultArray, $arrCol);
        }
    } else {
        $arrCol = array(
            'success' => 0,
            'msg' => 'No Found Record'
        );
        array_push($resultArray, $arrCol);
    }

    pg_close($conn);
    echo json_encode($resultArray);
}

exit;
