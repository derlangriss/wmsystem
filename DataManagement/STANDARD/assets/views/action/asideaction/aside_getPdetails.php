<?php

require '../dbconnect/connectdb_wms.php';

$resultArray = array();

if (isset($_GET['tPdetails'])) {

    if ($_GET['tPdetails'] == 'psize') {
        $query = "SELECT * FROM size 
                  WHERE firstname_th ILIKE '%" . $_GET['sInvestigator'] . "%' ORDER BY firstname_th ASC LIMIT 5";
    }
    if ($_GET['tPdetails'] == 'pfeature') {
        $query = "SELECT * FROM feature 
                  WHERE firstname_th ILIKE '%" . $_GET['sInvestigator'] . "%' ORDER BY firstname_th ASC LIMIT 5";
    }
    if ($_GET['tPdetails'] == 'pbrand') {
        $query = "SELECT * FROM brand 
                  WHERE firstname_th ILIKE '%" . $_GET['sInvestigator'] . "%' ORDER BY firstname_th ASC LIMIT 5";
    }
    if ($_GET['tPdetails'] == 'pvendor') {
        $query = "SELECT * FROM vendor 
                  WHERE firstname_th ILIKE '%" . $_GET['sInvestigator'] . "%' ORDER BY firstname_th ASC LIMIT 5";
    }
    if ($_GET['tPdetails'] == 'punit') {
        $query = "SELECT * FROM unit 
                  WHERE firstname_th ILIKE '%" . $_GET['sInvestigator'] . "%' ORDER BY firstname_th ASC LIMIT 5";
    }
}

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
