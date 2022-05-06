<?php
require "postgresql2jsonPDO.class.php";
require 'connectdb_wms.php';

$query = "SELECT * FROM section 
          ORDER BY idsection ASC ";

$stmt = $PDOconn->prepare($query);
$stmt->execute();

$num = $stmt->rowCount();

$json = new postgresql2jsonPDO;
$data = $json->getJSON($stmt, $num);
echo $data;

