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
$resultdata = array();
$obj       = convertToObject($r);
$action    = $obj->taction;

if ($action == 'INSERT' || $action == 'UPDATE') {

  
    $tidproject_budget = $obj->tidproject_budget;
    $texpect_date = $obj->texpect_date;
    $tidproject_details = $obj->tidproject_details;
    $texpect_cost  = $obj->texpect_cost;
    $tidproject_details_expect = $obj->tidproject_details_expect;
    

    $queryCHECK = "WITH budgetcost AS (
        SELECT budget FROM project_budget
        WHERE idproject_budget = " . $tidproject_budget . "
     ), sumexpensecost AS (
        select sum(expense_cost) includeexpense from project_details
        left join project_budgettype on project_budgettype.idproject_budgettype = project_details.project_budgettype_idproject_budgettype
        left join project_budget on project_budget.idproject_budget = project_budgettype.project_budget_idproject_budget
        where idproject_budget = " . $tidproject_budget . "
     )
     SELECT (SELECT budget FROM budgetcost)-(SELECT includeexpense FROM sumexpensecost) AS balancebudget";

    $stmt = $PDOconn->prepare($queryCHECK);
    $stmt->execute();

    $num = $stmt->rowCount();

    if ($num != 0) {

        $intNumField = $stmt->columnCount();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($result);

        if ($balancebudget !== 0) {

            if ($tidproject_details_expect !== '') {
                $strSQL =  "do $$  ";
                $strSQL .= "begin ";
                $strSQL .= "UPDATE project_details_expect SET  ";
                $strSQL .= "expect_cost = " . ($texpect_cost != '' ? $texpect_cost : 0) . " ";
                $strSQL .= "WHERE idproject_details_expect = " . $tidproject_details_expect . "; ";
                $strSQL .= "UPDATE project_details SET expense_cost = (SELECT SUM(expect_cost) AS sumexepectcost FROM project_details_expect ";
                $strSQL .= "LEFT JOIN project_details on project_details.idproject_details = project_details_expect.project_details_idproject_details ";
                $strSQL .= "WHERE idproject_details = " . $tidproject_details . ") ";
                $strSQL .= "WHERE idproject_details = " . $tidproject_details . "; ";
                $strSQL .= "end ";
                $strSQL .= "$$; ";
                $stmt = $PDOconn->prepare($strSQL);
                $stmt->execute();
            } else {

                $strSQL =  "do $$  ";
                $strSQL .= "begin ";
                $strSQL .= "INSERT INTO project_details_expect ( ";
                $strSQL .= "expect_date, project_details_idproject_details, expect_cost) ";
                $strSQL .= "VALUES ('" . $texpect_date . "','" . $tidproject_details . "','" . $texpect_cost . "'); ";
                $strSQL .= "UPDATE project_details SET expense_cost = (SELECT SUM(expect_cost) AS sumexepectcost FROM project_details_expect ";
                $strSQL .= "LEFT JOIN project_details on project_details.idproject_details = project_details_expect.project_details_idproject_details ";
                $strSQL .= "WHERE idproject_details = " . $tidproject_details . ") ";
                $strSQL .= "WHERE idproject_details = " . $tidproject_details . "; ";
                $strSQL .= "end ";
                $strSQL .= "$$; ";
                $stmt  = $PDOconn->prepare($strSQL);
                $stmt->execute();
            }
            $arr = array('success' => '1', 'action' => 'INSERT');
        } else {
            $arr = array('success' => '0', 'action' => 'INSERT');
        }
    }
}
if ($action == 'DELETE') {


    $tidproject_details = $obj->tidproject_details;

    $tidproject_details_expect = $obj->tidproject_details_expect;


    $strSQL =  "do $$  ";
    $strSQL .= "begin ";
    $strSQL .= "UPDATE project_details_expect SET  ";
    $strSQL .= "expect_cost = 0 ";
    $strSQL .= "WHERE idproject_details_expect = " . $tidproject_details_expect . "; ";
    $strSQL .= "UPDATE project_details SET expense_cost = (SELECT SUM(expect_cost) AS sumexepectcost FROM project_details_expect ";
    $strSQL .= "LEFT JOIN project_details on project_details.idproject_details = project_details_expect.project_details_idproject_details ";
    $strSQL .= "WHERE idproject_details = " . $tidproject_details . ") ";
    $strSQL .= "WHERE idproject_details = " . $tidproject_details . "; ";
    $strSQL .= "end ";
    $strSQL .= "$$; ";
    $stmt = $PDOconn->prepare($strSQL);
    $stmt->execute();
    $arr = array('success' => '1', 'action' => 'DELETE');
}
array_push($resultdata, $arr);
echo json_encode($resultdata);
