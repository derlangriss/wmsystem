<?php

require '../dbconnect/connectdb_wms.php';

if (isset($_GET['idproduct'])) {
    $resultArray = array();
    $query       = "SELECT * FROM pimages
              left join product on product.idproduct = pimages.product_idproduct
              WHERE idproduct ='" . $_GET["idproduct"] . "' AND pimages_trash = 'true' ORDER BY  idpimages ASC";

    $stmt = $PDOconn->prepare($query);
    $stmt->execute();

    $num = $stmt->rowCount();
    if ($num == 0) {

        exit;

    } else {
        $intNumField = $stmt->columnCount();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

            for ($i = 0; $i < $intNumField; $i++) {
                $col     = $stmt->getColumnMeta($i);
                $columns = $col['name'];

                $arrCol[$columns] = $result[$columns];
            }
            $arrCol['success'] = 1;
            array_push($resultArray, $arrCol);

        }

    }

    echo json_encode($resultArray);

}
