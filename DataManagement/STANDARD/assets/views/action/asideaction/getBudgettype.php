<?php

require '../connectdb_wms.php';
$resultArray = array();
$arrCol      = array();

if (isset($_GET['tidbudget_type'])) {

    $query = "SELECT * FROM expense_type 
    left join budget_type on budget_type.idbudget_type = expense_type.budget_type_idbudget_type
    
    WHERE idbudget_type =" . $_GET['tidbudget_type']." ORDER BY idexpense_type ASC ";

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
