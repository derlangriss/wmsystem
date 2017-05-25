<?php
if( ! ini_get('date.timezone') )
{
    date_default_timezone_set('GMT');
}
require('_header.php');
require('collnolib.php');
if ( isset($_GET['specid']) )
    {
	$strSQL = "select * from specimens left join collection on specimens.collection_idcollection = collection.idcollection where specimens.collection_idcollection   = '".$_GET["specid"]."' order by specimen_number desc limit 1 ";
	
	
	
	
	
	
	
	
	
	}else{

$strSQL= "select * from collection
left join specimens on 
(collection.idcollection = specimens.collection_idcollection)
left join collectionmethods on idcollectionmethods=collectionmethods_idcollectionmethods
left join amphurs on idamphurs=amphurs_idamphurs
left join province on idprovince=province_idprovince
left join collectors on idcollectors=collectors_idcollectors








order by coll_year desc, coll_number desc ,specimen_number desc limit 1";

    }
    
 
	$objQuery = pg_query($strSQL);
	$intNumField = pg_num_fields($objQuery);
	$resultArray = array();
	while($obResult = pg_fetch_array($objQuery))
	{
		$arrCol = array();
		for($i=0;$i<$intNumField;$i++)
		{
			$arrCol[pg_field_name($objQuery,$i)] = $obResult[$i];
		}
		array_push($resultArray,$arrCol);
	}
	
	pg_close($conn);
	
	echo json_encode($resultArray);	   
    


    
    
?>
