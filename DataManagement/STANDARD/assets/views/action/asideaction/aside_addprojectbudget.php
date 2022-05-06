<?php
require '../dbconnect/connectdb_wms.php';

function convertToObject($array)
{
    $object = new stdClass();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $value = convertToObject($value);
        }
        $object->$key = $value;
    }
    return $object;
}
$r         = $_POST["datapost"];
$resdata = array();
$obj       = convertToObject($r);
$action    = $obj->action;

if ($action == 'INSERT' || $action == 'UPDATE') {

    $idproject        = $obj->idproject;
    $budget_cost          = $obj->budget_cost;
    $fiscalyear          = $obj->fiscalyear;
    $tarraydecode = $r['investigators'];




    if ($action == 'INSERT') {

        $query = "SELECT * FROM project_budget 
                  left join project on project.idproject = project_budget.project_idproject
                  left join fiscalyear on fiscalyear.idfiscalyear = project_budget.fiscalyear_idfiscalyear
                  WHERE idproject =" . $idproject . " AND fiscalyear =" . $fiscalyear;

        $stmt = $PDOconn->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num != 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($result);

            if ($probudget_trash == true) {
                $query2  = "UPDATE project_budget SET ";
                $query2 .= "budget = " . ($budget_cost != '' ? $budget_cost : 0) . " ";
                $query2 .= ",probudget_trash = 'false' ";
                $query2 .= "WHERE idproject_budget   = '" . $idproject_budget . "' ";
                $stmt2 = $PDOconn->prepare($query2);
                $stmt2->execute();
                $arr = array('success' => '1', 'action' => 'UPDATEROW', 'idproject_budget' => $idproject_budget, 'trash_state' => $probudget_trash, 'idproject' => $idproject);
            } else {
                $arr = array('success' => '1', 'action' => 'EXISTROW', 'idproject_budget' => $idproject_budget, 'trash_state' => $probudget_trash, 'idproject' => $idproject);
            }
        } else {

            $queryYear = "SELECT * FROM fiscalyear WHERE fiscalyear = " . $fiscalyear;
            $stmtYear = $PDOconn->prepare($queryYear);
            $stmtYear->execute();
            $numYear = $stmtYear->rowCount();

            if ($numYear != 0) {
                $resultYear = $stmtYear->fetch(PDO::FETCH_ASSOC);
                extract($resultYear);

                

                $query2  = "INSERT INTO project_budget (budget, project_idproject, fiscalyear_idfiscalyear) VALUES (?, ?, ?) RETURNING idproject_budget";
                $params2 = array($budget_cost, $idproject, $idfiscalyear);
                $stmt2   = $PDOconn->prepare($query2);
                $stmt2->execute($params2);

                $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                extract($result2); 

                foreach ($tarraydecode as $row) {
                    $query4  = "INSERT INTO userpro_auth (login_user_iduser, project_budget_idproject_budget) VALUES (?,?) RETURNING iduserpro_auth";
                    $params4 = array($row['iduser'], $idproject_budget);
                    $stmt4   = $PDOconn->prepare($query4);
                    $stmt4->execute($params4);
                }



                $arr = array('success' => '1', 'action' => 'INSERTROW', 'idproject_budget' => $idproject_budget, 'idproject' => $idproject);
            } else {




                function dateswap($datadate)
                {
                    $datearray = explode("-", $datadate);
                    if (strlen($datadate) > 3) {
                        $meyear   = $datearray[0] + 543;
                        $datadate = $meyear;
                    }
                    return $datadate;
                }

                $THfiscalyear    = dateswap($fiscalyear);



                $query2  = "INSERT INTO fiscalyear (fiscalyear, fiscalyear_th) VALUES (?, ?) RETURNING idfiscalyear";
                $params2 = array($fiscalyear, $THfiscalyear);
                $stmt2   = $PDOconn->prepare($query2);
                $stmt2->execute($params2);

                $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                extract($result2);

                $query3  = "INSERT INTO project_budget (budget, project_idproject, fiscalyear_idfiscalyear) VALUES (?, ?, ?) RETURNING idproject_budget";
                $params3 = array($budget_cost, $idproject, $idfiscalyear);
                $stmt3   = $PDOconn->prepare($query3);
                $stmt3->execute($params3);

                $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
                extract($result3);

                foreach ($tarraydecode as $row) {
                    $query4  = "INSERT INTO userpro_auth (login_user_iduser, project_budget_idproject_budget) VALUES (?,?) RETURNING iduserpro_auth";
                    $params4 = array($row['iduser'], $idproject_budget);
                    $stmt4   = $PDOconn->prepare($query4);
                    $stmt4->execute($params4);
                }

                $arr = array('success' => '1', 'action' => 'INSERTROW', 'idproject_budget' => $idproject_budget, 'idproject' => $idproject);
            }
        }

        array_push($resdata, $arr);
        echo json_encode($resdata);
    }
    if ($action == 'UPDATE') {
    }
}
if ($action == 'DELETE' || $action == 'DELETEALL') {
    $idproject_details  = $obj->idproject_details;

    if ($action == 'DELETE') {
        $query2  = "UPDATE project_details SET ";
        $query2 .= "pdetails_trash = 'true' ";
        $query2 .= "WHERE idproject_details   = '" . $idproject_details . "' ";
        $stmt2 = $PDOconn->prepare($query2);
        $stmt2->execute();
    }

    $arr = array('success' => '1', 'action' => 'DELETEROW', 'idproject_details' => $idproject_details);
}

/*
array_push($resdata, $arr);
echo json_encode($resdata);
*/