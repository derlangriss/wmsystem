<?php
if( ! ini_get('date.timezone') )
{
    date_default_timezone_set('GMT');
}
require('_header.php');
require('collnolib.php');

	$resultArray = array();
        $sql= "SELECT SUM(numberofitemstoprint) AS totallabel FROM labelprintqueue";
        $res = pg_query($sql);
        $row=pg_fetch_array($res);
        extract($row);

        $arr = array('totallabel' => $totallabel);
        array_push($resultArray,$arr);
        echo json_encode($resultArray);		
    
    
?>
