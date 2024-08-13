<?php

require '../dbconnect/connectdb_wms.php';
$resultArray = array();
$arrCol      = array();

$query = "SELECT * FROM material_type ";
    
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


/*
if (isset($_GET['idexpense_type'])) {
   $query = "SELECT * FROM material_cost ";
    $query .= "LEFT JOIN expense_type ON expense_type.idexpense_type = material_cost.expense_type_idexpense_type ";
    $query .= "LEFT JOIN material_type ON material_type.idmaterial_type = material_cost.material_type_idmaterial_type ";
    $query .= "WHERE idexpense_type = ".$_GET['idexpense_type'] ;
    
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
*/