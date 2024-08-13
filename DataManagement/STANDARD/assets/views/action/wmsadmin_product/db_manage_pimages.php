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
$resultArray = array();
$arrCol      = array();
$obj       = convertToObject($r);
$action    = $obj->action;

if ($action == 'INSERT' || $action == 'UPDATE') {

    /* $idproject        = $obj->idproject;
    $budget_cost          = $obj->budget_cost;

    echo $idproject." ".$budget_cost;*/




    if ($action == 'INSERT') {

        $query = "SELECT idproject_details,pdetails_trash FROM project_details 
                  left join expense_type on expense_type.idexpense_type = project_details.expense_type_idexpense_type
                  left join project_budgettype on project_budgettype.idproject_budgettype = project_details.project_budgettype_idproject_budgettype
                  left join budget_type on budget_type.idbudget_type = project_budgettype.budget_type_idbudget_type
                  WHERE idproject_budgettype =" . $idproject_budgettype . " AND idexpense_type =" . $idexpense_type . " ORDER BY idexpense_type ASC ";

        $stmt = $PDOconn->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num != 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($result);

            if ($pdetails_trash == false) {
                $query2  = "UPDATE project_details SET ";
                $query2 .= "expense_cost = " . ($texpense_cost != '' ? $texpense_cost : 0) . " ";
                $query2 .= ",balance = " . ($texpense_cost != '' ? $texpense_cost : 0) . " ";
                $query2 .= "WHERE idproject_details   = '" . $idproject_details . "' ";
            }

            if ($pdetails_trash == true) {
                $query2  = "UPDATE project_details SET ";
                $query2 .= "expense_cost = " . ($texpense_cost != '' ? $texpense_cost : 0) . " ";
                $query2 .= ",balance = " . ($texpense_cost != '' ? $texpense_cost : 0) . " ";
                $query2 .= ",pdetails_trash = 'false' ";
                $query2 .= "WHERE idproject_details   = '" . $idproject_details . "' ";
            }

            $stmt2 = $PDOconn->prepare($query2);
            $stmt2->execute();
            $arr = array('success' => '1', 'action' => 'UPDATEROW', 'idproject_details' => $idproject_details, 'trash_state' => $pdetails_trash, 'idbudget_type' => $idbudget_type);
        } else {

            $query2  = "INSERT INTO project_details (expense_type_idexpense_type, expense_cost, project_budgettype_idproject_budgettype,balance) VALUES (?, ?, ?, ?) RETURNING idproject_details";
            $params2 = array($idexpense_type, $texpense_cost, $idproject_budgettype, $texpense_cost);
            $stmt2   = $PDOconn->prepare($query2);
            $stmt2->execute($params2);

            $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            extract($result2);

            $arr = array('success' => '1', 'action' => 'INSERTROW', 'idproject_details' => $idproject_details, 'idbudget_type' => $idbudget_type);
        }
    }
    if ($action == 'UPDATE') {

        $idproduct    = $obj->idproduct;
        $idexpense_type    = $obj->idexpense_type;
        $idmaterial_type    = $obj->idmaterial_type;

        $query = "WITH selectmaterial_cost AS (
            SELECT idmaterial_cost FROM material_cost
            LEFT JOIN expense_type on expense_type.idexpense_type = material_cost.expense_type_idexpense_type
            LEFT JOIN material_type on material_type.idmaterial_type = material_cost.material_type_idmaterial_type
            WHERE idmaterial_type = " . $idmaterial_type . " AND idexpense_type = " . $idexpense_type . "
         )
            UPDATE product SET material_cost_idmaterial_cost = (SELECT idmaterial_cost FROM selectmaterial_cost)
			WHERE idproduct = " . $idproduct;

        $stmt = $PDOconn->prepare($query);
        $stmt->execute();
        $count = $stmt->rowCount();

        if ($count == '0') {
            $arrCol['success'] = 0;
        } else {
            $arrCol['success'] = 1;
        }
        array_push($resultArray, $arrCol);
        pg_close($conn);
        echo json_encode($resultArray);
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